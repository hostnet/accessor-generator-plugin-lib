<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Package\RootPackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Hostnet\Component\AccessorGenerator\Annotation\Generate;
use Hostnet\Component\AccessorGenerator\Generator\CodeGenerator;
use Hostnet\Component\AccessorGenerator\Generator\CodeGeneratorInterface;
use Hostnet\Component\AccessorGenerator\Generator\Exception\ReferencedClassNotFoundException;
use Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Symfony\Component\Finder\Finder;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Plugin that will generate accessor methods in traits and puts them
 * in a Generated folder and namespace relative for the file the trait
 * is created for.
 *
 * The generated trait should be included in the class manually after
 * generation.
 *
 * The plugin will only generate traits for classes in the application and
 * classes in composer-packages that directly require this package.
 *
 * For more information on usage please see README.md
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    public const NAME = 'hostnet/accessor-generator-plugin-lib';

    /**
     * @var Composer
     */
    private $composer;

    /**
     * @var IOInterface
     */
    private $io;

    /**
     * @var CodeGenerator
     */
    private $generator;

    /**
     * @var array
     */
    private $metadata;

    /**
     * Initialize the annotation registry with composer as auto loader. Create
     * a CodeGenerator if none was provided.
     *
     * @param CodeGeneratorInterface $generator
     * @throws \InvalidArgumentException
     * @throws LoaderError
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function __construct(CodeGeneratorInterface $generator = null)
    {
        AnnotationRegistry::registerLoader('class_exists');

        if ($generator) {
            $this->generator = $generator;
        } else {
            $this->generator = new CodeGenerator();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::PRE_AUTOLOAD_DUMP  => ['onPreAutoloadDump', 20],
            ScriptEvents::POST_AUTOLOAD_DUMP => ['onPostAutoloadDump', 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->composer = $composer;
        $this->io       = $io;
    }

    /**
     * Gets called on the PRE_AUTOLOAD_DUMP event
     *
     * Generate Traits for every package that requires
     * this plugin and has php files with the @Generate
     * annotation set on at least one property.
     *
     * @throws \DomainException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function onPreAutoloadDump(): void
    {
        $local_repository = $this->composer->getRepositoryManager()->getLocalRepository();
        $packages         = $local_repository->getPackages();
        $packages[]       = $this->composer->getPackage();

        $extra = $this->composer->getPackage()->getExtra();
        isset($extra['accessor-generator']) && $this->generator->setEncryptionAliases($extra['accessor-generator']);

        foreach ($packages as $package) {
            /** @var $package PackageInterface */
            if (!array_key_exists(self::NAME, $package->getRequires())) {
                continue;
            }

            $this->generateTraitForPackage($package);
        }
    }

    public function onPostAutoloadDump(): void
    {
        $local_repository = $this->composer->getRepositoryManager()->getLocalRepository();
        $packages         = $local_repository->getPackages();
        $packages[]       = $this->composer->getPackage();

        foreach ($packages as $package) {
            /** @var $package PackageInterface */
            if (! array_key_exists(self::NAME, $package->getRequires())) {
                continue;
            }

            foreach ($this->getFilesAndReflectionClassesFromPackage($package) as $filename => $reflection_class) {
                try {
                    $generated_enum_classes = $this->generator->writeEnumeratorAccessorsForClass($reflection_class);
                } catch (ReferencedClassNotFoundException $e) {
                    if ($this->io->isVerbose()) {
                        $this->io->write("    - " . $e->getMessage());
                    }
                    continue;
                }

                if ($this->io->isVeryVerbose()) {
                    foreach ($generated_enum_classes as $generated_enum_class) {
                        $this->io->write("    - Generated enumerator accessor for <info>$generated_enum_class</info>");
                    }
                }
            }
        }
    }

    /**
     * Generate traits on disk for the given package.
     * Will only do so when the package actually has
     * the @Generate annotation set on at least one
     * property.
     *
     * @throws \DomainException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \OutOfBoundsException
     * @throws \RuntimeException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     *
     * @param PackageInterface $package
     */
    private function generateTraitForPackage(PackageInterface $package): void
    {
        if ($this->io->isVerbose()) {
            $this->io->write('Generating metadata for <info>' . $package->getPrettyName() . '</info>');
        }

        foreach ($this->getFilesAndReflectionClassesFromPackage($package) as $filename => $reflection_class) {
            if (!$this->generator->writeTraitForClass($reflection_class) || !$this->io->isVeryVerbose()) {
                continue;
            }

            $this->io->write("  - generated trait for <info>$filename</info>");
        }

        // At the end of generating the Traits for each package we need to write the KeyRegistry classes
        // which hold the encryption key paths.
        $this->generator->writeKeyRegistriesForPackage();
    }

    /**
     * Returns a key-value array of ReflectionClass instances for all the file names found in the given package.
     * This method returns a cached instance when executed more than once for the same package.
     *
     * @param PackageInterface $package
     * @return mixed
     * @throws Reflection\Exception\FileException
     */
    private function getFilesAndReflectionClassesFromPackage(PackageInterface $package)
    {
        $cache_id = $package->getName();

        if (isset($this->metadata[$cache_id])) {
            return $this->metadata[$cache_id];
        }

        $this->metadata[$cache_id] = [];
        foreach ($this->getFilesForPackage($package) as $filename) {
            $filename = (string) $filename;
            if (isset($this->metadata[$cache_id][$filename])) {
                continue;
            }

            $this->metadata[$cache_id][$filename] = new ReflectionClass($filename);
        }

        return $this->metadata[$package->getName()];
    }

    /**
     * Find all the PHP files within a package.
     *
     * Excludes
     *  - all files in VCS directories
     *  - all files in vendor folders
     *  - all files in Generated folders
     *  - all hidden files
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     *
     * @param PackageInterface $package
     * @return \Iterator|\SplFileInfo[]
     */
    private function getFilesForPackage(PackageInterface $package)
    {
        if ($package instanceof RootPackageInterface) {
            $path = '.';
        } else {
            $path = $this->composer->getInstallationManager()->getInstallPath($package);
        }

        $path  .= '/src';
        $finder = new Finder();

        return $finder
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->exclude(['Generated'])
            ->name('*.php')
            ->in($path)
            ->getIterator();
    }
}

<?php
namespace Hostnet\Component\AccessorGenerator;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Package\RootPackageInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\ScriptEvents;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Hostnet\Component\AccessorGenerator\Generator\CodeGenerator;
use Hostnet\Component\AccessorGenerator\Generator\CodeGeneratorInterface;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Symfony\Component\Finder\Finder;

/**
 * Plugin that will generate accessor methods in traits put
 * in a Generated folder and namespace relative for the file
 * the trait is created for.
 *
 * The trait is included by hand in the file using the accessor-
 * generation.
 *
 * The plugin will ONLY generate traits for packages that directly
 * require the package.
 *
 * For more information on usage please see README.md
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{

    const NAME = 'hostnet/accessor-generator-plugin-lib';

    private $composer;
    private $io;
    private $generator;

    /**
     * Initialize the annotation registry with composer
     * as autoloader. Create a CodeGemerator if now was
     * provided.
     *
     * @param CodeGeneratorInterface $generator
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
     * @see \Composer\Plugin\PluginInterface::activate()
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io       = $io;
    }

    /**
     * @see Composer\EventDispatcher\EventSubscriberInterface::getSubscribedEvents
     * @return int|string[][]
     */
    public static function getSubscribedEvents()
    {
        return [
            ScriptEvents::PRE_AUTOLOAD_DUMP => ['onPreAutoloadDump', 20 ],
        ];
    }

    /**
     * Gets called on the POST_AUTOLOAD_DUMP event
     *
     * Generate Traits for every package that requires
     * this plugin and has php files with the @Generate
     * annotation set on at least one property.
     */
    public function onPreAutoloadDump()
    {
        $local_repository = $this->composer->getRepositoryManager()->getLocalRepository();
        $packages         = $local_repository->getPackages();
        $packages[]       = $this->composer->getPackage();
        foreach ($packages as $package) {
            /* @var $package PackageInterface */
            if (array_key_exists(self::NAME, $package->getRequires())) {
                $this->generateTraitsForPackage($package);
            }
        }
    }

    /**
     * Generate traits on disk for the given package.
     * Will only do so when the package actually has
     * the @Generate annotation set on at least one
     * property.
     *
     * @param PackageInterface $package
     */
    private function generateTraitsForPackage(PackageInterface $package)
    {
        if ($this->io->isVerbose()) {
            $this->io->write('Generating accessors for <info>' . $package->getPrettyName() .'</info>');
        }

        foreach ($this->getFilesForPackage($package) as $filename) {
            $class = new ReflectionClass($filename);
            if ($this->generator->writeTraitForClass($class) && $this->io->isVeryVerbose()) {
                $this->io->write("  - generated acessors for <info>$filename</info>");
            }
        }
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
     * @param PackageInterface $package
     * @return Iterator
     */
    private function getFilesForPackage(PackageInterface $package)
    {
        if ($package instanceof RootPackageInterface) {
            $path = '.';
        } else {
            $path = $this->composer->getInstallationManager()->getInstallPath($package);
        }

        $finder = new Finder();

        return $finder
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->exclude(['vendor', 'Generated'])
            ->name('*.php')
            ->in($path)
            ->getIterator();
    }
}

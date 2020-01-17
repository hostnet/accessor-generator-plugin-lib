<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\Annotation\Enumerator;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformation;
use Hostnet\Component\AccessorGenerator\Generator\Exception\ReferencedClassNotFoundException;
use Hostnet\Component\AccessorGenerator\Generator\Exception\TypeUnknownException;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Generator\CodeGenerator
 */
class CodeGeneratorTest extends TestCase
{
    private $generator;

    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public static function setUpBeforeClass(): void
    {
        $fs = new Filesystem();
        $fs->remove(__DIR__ . '/fixtures/Generated');
    }

    /**
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @throws \DomainException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \OutOfBoundsException
     */
    public function testWriteTraitForClass(): void
    {
        $finder = new Finder();
        $files  = $finder
            ->name('*.php')
            ->exclude('expected')
            ->exclude('Generated')
            ->in(__DIR__ . '/fixtures/')
            ->getIterator();

        $generator = $this->getGenerator();
        foreach ($files as $file) {
            // Read class information;
            $class = new ReflectionClass($file->getRealPath());

            // Generate the accessor methods trait.
            $generator->writeTraitForClass($class);
            $generator->writeEnumeratorAccessorsForClass($class);
        }

        // Generate the KeyRegistry class(es).
        $generator->writeKeyRegistriesForPackage();

        $this->compareExpectedToGeneratedFiles();
        $this->compareExpectedToGeneratedFiles(true);
    }

    private function getGenerator(): CodeGenerator
    {
        if ($this->generator === null) {
            $this->generator = new CodeGenerator();
        }

        return $this->generator;
    }

    private function compareExpectedToGeneratedFiles($inverse = false): void
    {
        $paths          = ['/expected', '/Generated'];
        $paths          = $inverse ? array_reverse($paths) : $paths;
        $finder         = new Finder();
        $expected_files = $finder->name('*.php')->in(__DIR__ . '/fixtures' . $paths[0])->getIterator();

        foreach ($expected_files as $expected_file) {
            // Get the mirrored file.
            $actual_file_path = str_replace($paths[0], $paths[1], $expected_file->getPathname());
            $actual_contents  = file_get_contents($actual_file_path);

            // Remove system, time and user dependend header
            $pattern           = '#^// Generated at 20[\d]{2}-[\d]{2}-[\d]{2} [\d]{2}:[\d]{2}:[\d]{2} by .*$#m';
            $expected_contents = preg_replace($pattern, '// HEADER', $expected_file->getContents(), 1);
            $actual_contents   = preg_replace($pattern, '// HEADER', $actual_contents, 1);

            // Assert we're not comparing the same file.
            self::assertNotEquals($expected_file->getPathname(), $actual_file_path);
            // Assert the contents is the expected contents.
            self::assertEquals(
                $expected_contents,
                $actual_contents,
                'Generated result does not match for file ' . $expected_file
            );
        }
    }

    public function testGenerateAccessorsTypeUnknown(): void
    {
        $info = new PropertyInformation(new ReflectionProperty('phpunit'));
        $info->setIsGenerator(true); // Default for all @Generate properties.

        $this->expectException(TypeUnknownException::class);

        $this->getGenerator()->generateAccessors($info);
    }

    public function testGenerateEnumeratorClassNotFound(): void
    {
        $enumerator        = new Enumerator();
        $enumerator->value = "\\This\\Does\\Not\\Exist";

        $class = new ReflectionClass(__FILE__);
        $info  = new PropertyInformation(new ReflectionProperty('my_prop', null, null, null, $class));

        $this->expectException(ReferencedClassNotFoundException::class);
        $this->expectExceptionMessage(
            '"CodeGeneratorTest" was not generated because the enum class "\This\Does\Not\Exist"'
        );

        $this->getGenerator()->generateEnumeratorAccessors($enumerator, $info);
    }
}

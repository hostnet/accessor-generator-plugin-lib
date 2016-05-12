<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformation;
use Hostnet\Component\AccessorGenerator\Generator\Exception\TypeUnknownException;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @covers Hostnet\Component\AccessorGenerator\Generator\CodeGenerator
 */
class CodeGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $generator;

    /**
     * {@inheritDoc}
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public static function setUpBeforeClass()
    {
        $fs = new Filesystem();
        $fs->remove(__DIR__ . '/fixtures/Generated');
    }

    public function writeTraitForClassProvider()
    {
        $provider = [];
        $finder   = new Finder();
        $files    = $finder->name('*.php')
                           ->exclude('expected')
                           ->exclude('Generated')
                           ->in(__DIR__ . '/fixtures/')
                           ->getIterator();

        foreach ($files as $file) {
            $provider[] = [$file];
        }

        return $provider;
    }

    /**
     * @dataProvider writeTraitForClassProvider
     * @param $filename
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @throws \DomainException
     * @throws \Hostnet\Component\AccessorGenerator\Generator\Exception\TypeUnknownException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \OutOfBoundsException
     */
    public function testWriteTraitForClass($filename)
    {
        // Read class information;
        $class = new ReflectionClass($filename);

        // Generate the accessor methods trait.
        $this->getGenerator()->writeTraitForClass($class);


        // Get file names
        $actual   = dirname($filename) . '/Generated/' . basename($filename, '.php') . 'MethodsTrait.php';
        $expected = dirname($filename) . '/expected/' . basename($filename, '.php') . 'MethodsTrait.php';

        // Get file contents
        $expected_contents = file_exists($expected) ? file_get_contents($expected) : '';
        $actual_contents   = file_exists($actual) ? file_get_contents($actual) : '';

        // Remove system, time and user depended header
        $pattern         = '#^// Generated at 20[\d]{2}-[\d]{2}-[\d]{2} [\d]{2}:[\d]{2}:[\d]{2} by .*$#m';
        $actual_contents = preg_replace($pattern, '// HEADER', $actual_contents, 1);

        // Test if contents is the expected contents.
        self::assertEquals($expected_contents, $actual_contents);
    }

    private function getGenerator()
    {
        if ($this->generator === null) {
            $this->generator = new CodeGenerator();
        }

        return $this->generator;
    }

    /**
     * @expectedException \Hostnet\Component\AccessorGenerator\Generator\Exception\TypeUnknownException
     * @throws TypeUnknownException
     */
    public function testGenerateAccessorsTypeUnknown()
    {
        $this->getGenerator()->generateAccessors(new PropertyInformation(new ReflectionProperty('phpunit')));
    }
}

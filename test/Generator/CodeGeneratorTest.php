<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformation;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class CodeGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $generator;

    public function writeTraitForClassProvider()
    {
        $provider = [];
        $finder   = new Finder();
        $files    =  $finder->name('*.php')
                    ->exclude('expected')
                    ->exclude('Generated')
                    ->in(__DIR__ . '/fixtures/')
                    ->getIterator();

        foreach ($files as $file) {
            $provider[] = [$file];
        }

        return $provider;
    }

    public static function setUpBeforeClass()
    {
        $fs = new Filesystem();
        $fs->remove(__DIR__ . '/fixtures/Generated');
    }

    /**
     * @dataProvider writeTraitForClassProvider
     */
    public function testWriteTraitForClass($filename)
    {
        // Read class information;
        $class = new ReflectionClass($filename);

        // Generate the accessor methods trait.
        $this->getGenerator()->writeTraitForClass($class);


        // Get file names
        $actual   = dirname($filename) . '/Generated/' . basename($filename, '.php') . 'MethodsTrait.php' ;
        $expected = dirname($filename) . '/expected/' . basename($filename, '.php') . 'MethodsTrait.php' ;

        // Get file contents
        $expected_contents = file_exists($expected) ? file_get_contents($expected) : '';
        $actual_contents   = file_exists($actual)   ? file_get_contents($actual)   : '';

        // Remove system, time and user dependend header
        $pattern         = '#^// Generated at 20[0-9]{2}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2} by .*$#m';
        $actual_contents = preg_replace($pattern, '// HEADER', $actual_contents, 1);

        // Test if contents is the expected contents.
        $this->assertEquals($expected_contents, $actual_contents);
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
     */
    public function testGenerateAccessorsTypeUnkown()
    {
        $this->getGenerator()->generateAccessors(new PropertyInformation(new ReflectionProperty('phpunit')));
    }
}

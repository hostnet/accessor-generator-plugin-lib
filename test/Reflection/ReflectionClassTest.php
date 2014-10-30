<?php
namespace Hostnet\Component\AccessorGenerator\Reflection;

class ReflectionClassTest extends \PHPUnit_Framework_TestCase
{

    public function fileProvider()
    {
        return [
            [
                'tokens.php',
                'FooBar',
                'Hostnet\Component\AccessorGenerator\Reflection',
                ['E' => 'Exception', 'ORM' => 'Doctrine\ORM\Mapping'],
                [
                    'public $foo = \'99\';',
                    'protected $baz = 0x88;',
                    'private $bar = 10;',
                    'private $foz;',
                ]
            ],
            [
                'abstract.php',
                'Boom',
                'Test',
                ['ORM' => 'Doctrine\ORM\Mapping'],
                [
                    '/**'                                                    . PHP_EOL .
                    ' * Waarom dit dan?'                                     . PHP_EOL .
                    ' *'                                                     . PHP_EOL .
                    ' * en dit dan?'                                         . PHP_EOL .
                    ' * @version bluh'                                       . PHP_EOL .
                    ' * @ORM\Column(name="stam", length=100, type="string")' . PHP_EOL .
                    ' */'                                                    . PHP_EOL .
                    'private $stam;'
                ]
            ],
            [
                'static.php',
                'Boom',
                '',
                ['ORM' => 'Doctrine\ORM\Mapping', 'AG' => 'Hostnet\Component\AccessorGenerator\Annotation'],
                [
                    '/**'                                                    . PHP_EOL .
                    ' * Waarom dit dan?'                                     . PHP_EOL .
                    ' * @version bluh'                                       . PHP_EOL .
                    ' * @ORM\Column(name="stam", length=100, type="string")' . PHP_EOL .
                    ' */'                                                    . PHP_EOL .
                    'private static $stam;'
                ]
            ],
            [
                'trait.php',
                'Tak',
                '',
                ['Exception'],
                [
                    'private $blad;',
                    'private $naald = 123412341234;',
                    'private $nog_een_naald = "blasdfasdfhi";',
                    "/**\n *\n * @var unknown\n */\nprivate \$en_nog_een_naald = 'bluhadatie';",
                    'private $weer_een_naald = 0x87;',
                    'private $en_weer_een_naald = 0.12;',
                    'private $meer_naalden = "	HOI";',
                    'private $nog_steeds_meer_naalden = "	HOI";',
                    'private $nog_steeds_veel_meer_naalden = \'	HOI\' . "\n" . \'	$hoi\';'
                ]
            ],
            [
                'use_trait_extend_class.php',
                'Groot',
                'Hostnet\Component\AccessorGenerator\Annotation',
                [],
                [
                    'private $klein;',
                ]
            ],
            [
            'modifiers.php',
                'Boom',
                '',
                [],
                [
                    'private $stam;',
                    'protected $blad;',
                    'public $tak;',
                    'private static $twijg;',
                    'protected static $rank;',
                    'public static $naald;',
                    'private static $eikel;',
                    'protected static $beukenoot;',
                    'public static $kastanje;',
                    'private static $wortel;',
                    'protected static $denneappel;',
                    'public static $piek;',
                    'private $bast;',
                    'private $schors;',
                    'public $kruin;',
                    'public $nerf;',
                    'private $riet;',
                ]
            ],
        ];
    }

    /**
     * Test ReflectionClass::getName()
     *      ReflectionClass::getUseStatements()
     *      ReflectionClass::getName()
     *      ReflectionClass::getFileName()
     *      ReflectionClass::getNamespace()
     *
     * @dataProvider fileProvider
     */
    public function testGetters($filename, $name, $namespace, array $imports, array $properties)
    {
        $class = new ReflectionClass(__DIR__ . '/fixtures/' . $filename);

        $this->assertEquals($name, $class->getName());
        $this->assertEquals(__DIR__ . '/fixtures/' . $filename, $class->getFilename());
        $this->assertEquals($namespace, $class->getNamespace());
        $this->assertEquals($imports, $class->getUseStatements());

        $reflected_properties = $class->getProperties();
        array_walk($reflected_properties, function (&$item) {
            $item = $item->__toString();
        });

        $this->assertEquals($properties, $reflected_properties);
    }

    /**
     * @expectedException Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @expectedExceptionMessage readable
     */
    public function testFileExceptionReadable()
    {
        new ReflectionClass('/etc/shadow');
    }

    /**
     * @expectedException        Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @expectedExceptionMessage exist
     */
    public function testFileExceptionExist()
    {
        new ReflectionClass(__DIR__ . '/fixtures/doesnotexist.php');
    }

    /**
     * @expectedException Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException
     */
    public function testClassNotFoundException()
    {
        $class = new ReflectionClass(__DIR__ . '/fixtures/noclass.php');
        $this->assertEquals('Error', $class->getNamespace());
        $class->getName();
    }

    /**
     * @expectedException Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException
     */
    public function testBroken()
    {
        $class = new ReflectionClass(__DIR__ . '/fixtures/broken.php');
        $this->assertEquals([ 'ORM' => 'Doctrine\ORM\Mapping'], $class->getUseStatements());
        $this->assertEquals([], $class->getUseStatements());
    }

    public function testCache()
    {
        $class = new ReflectionClass(__DIR__ . '/fixtures/abstract.php');
        $this->assertEquals(['ORM' => 'Doctrine\ORM\Mapping'], $class->getUseStatements());
        $this->assertEquals(['ORM' => 'Doctrine\ORM\Mapping'], $class->getUseStatements());
    }
}

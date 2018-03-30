<?php
namespace Hostnet\Component\AccessorGenerator\Reflection;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass
 */
class ReflectionClassTest extends TestCase
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
                ],
            ],
            [
                'abstract.php',
                'Boom',
                'Test',
                ['ORM' => 'Doctrine\ORM\Mapping'],
                [
                    '/**' . PHP_EOL .
                    ' * Waarom dit dan?' . PHP_EOL .
                    ' *' . PHP_EOL .
                    ' * en dit dan?' . PHP_EOL .
                    ' * @version bluh' . PHP_EOL .
                    ' * @ORM\Column(name="stam", length=100, type="string")' . PHP_EOL .
                    ' */' . PHP_EOL .
                    'private $stam;',
                ],
            ],
            [
                'static.php',
                'Boom',
                '',
                ['ORM' => 'Doctrine\ORM\Mapping', 'AG' => 'Hostnet\Component\AccessorGenerator\Annotation'],
                [
                    '/**' . PHP_EOL .
                    ' * Waarom dit dan?' . PHP_EOL .
                    ' * @version bluh' . PHP_EOL .
                    ' * @ORM\Column(name="stam", length=100, type="string")' . PHP_EOL .
                    ' */' . PHP_EOL .
                    'private static $stam;',
                ],
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
                    'private $nog_steeds_veel_meer_naalden = \'	HOI\' . "\n" . \'	$hoi\';',
                    'private $denneboom;',
                ],
            ],
            [
                'use_trait_extend_class.php',
                'Groot',
                'Hostnet\Component\AccessorGenerator\Annotation',
                [],
                [
                    'private $klein;',
                ],
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
                ],
            ],
            [
                'nullable.php',
                'Nullable',
                'Hostnet\Component\AccessorGenerator\Annotation',
                [],
                [
                    'private $nullable = null;',
                ],
            ],
            [
                'array.php',
                'DefaultArrays',
                'Hostnet\Component\AccessorGenerator\Annotation',
                [],
                [
                    'private $a = [\'string\'];',
                    'private $b = [\'string\'];',
                    'private $c = [0 => \'string\'];',
                    'private $d = [0 => \'string\', 1 => 2];',
                    'private $e = [0 => \'string\', 1 => 2, \'three\' => 3];',
                    'private $f = [0 => \'string\', 1 => \'2\', \'three\' => 3];',
                    'private $g = [0 => \'string\', [\'1\' => [\'2\']], \'three\' => 3];',
                    'private $h = [0 => \'string\', [\'1\' => [(\'2\')]], \'three\' => 3];',
                ],
            ],
            [
                'const.php',
                'Constant',
                'Hostnet\Component\AccessorGenerator\Annotation',
                [],
                [
                    'private $constant = self::class;',
                    'private $color = COLOR;',
                    'private $color = self::COLOR;',
                    'private $color = An\Other\Place::class;',
                    'private $color = \An\Other\Place::class;',
                ],
            ],
            [
                'use_function.php',
                'UseFunction',
                'ThisNamespace',
                [
                    'const ThisNamespace\HELLO',
                    'kaboom' => 'function ThisNamespace\destory',
                    'HALLO'  => 'const ThisNamespace\HELLO',
                    'function sprintf',
                ],
                [],
            ],
            [
                'use_const.php',
                'UseConst',
                'ThisNamespace',
                [],
                [],
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
     * @param $filename
     * @param $name
     * @param $namespace
     * @param array $imports
     * @param array $properties
     * @throws Exception\ClassDefinitionNotFoundException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @throws \OutOfBoundsException
     */
    public function testGetters($filename, $name, $namespace, array $imports, array $properties)
    {
        $class = new ReflectionClass(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $filename);

        self::assertEquals($name, $class->getName());
        self::assertEquals(
            __DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . $filename,
            $class->getFilename()
        );
        self::assertEquals($namespace, $class->getNamespace());
        self::assertEquals($imports, $class->getUseStatements());
        self::assertEquals($namespace . '\\' . $name, $class->getFullyQualifiedClassName());
        $reflected_properties = $class->getProperties();
        array_walk(
            $reflected_properties,
            function (&$item) {
                $item = (string)$item;
            }
        );

        self::assertEquals($properties, $reflected_properties);
    }

    /**
     * @expectedException \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @expectedExceptionMessage readable
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     */
    public function testFileExceptionReadable()
    {
        new ReflectionClass('/etc/shadow');
    }

    /**
     * @expectedException \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @expectedExceptionMessage exist
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     */
    public function testFileExceptionExist()
    {
        new ReflectionClass(__DIR__ . '/fixtures/does_not_exist.php');
    }

    /**
     * @expectedException \Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @throws \OutOfBoundsException
     */
    public function testClassNotFoundException()
    {
        $class = new ReflectionClass(__DIR__ . '/fixtures/no_class.php');
        self::assertEquals('ThisNamespace', $class->getNamespace());
        $class->getName();
    }

    /**
     * @expectedException \Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @throws \OutOfBoundsException
     */
    public function testEmptyFileClassNotFoundException()
    {
        $class = new ReflectionClass(__DIR__ . '/fixtures/empty.php');
        self::assertEquals('', $class->getNamespace());
        $class->getName();
    }

    /**
     * @expectedException \Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException
     * @throws \Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException
     * @throws \OutOfBoundsException
     */
    public function testBroken()
    {
        $class = new ReflectionClass(__DIR__ . '/fixtures/broken.php');
        self::assertEquals(['ORM' => 'Doctrine\ORM\Mapping'], $class->getUseStatements());
        self::assertEquals([], $class->getUseStatements());
    }

    public function testCache()
    {
        $class = new ReflectionClass(__DIR__ . '/fixtures/abstract.php');
        self::assertEquals(['ORM' => 'Doctrine\ORM\Mapping'], $class->getUseStatements());
        self::assertEquals(['ORM' => 'Doctrine\ORM\Mapping'], $class->getUseStatements());
    }
}

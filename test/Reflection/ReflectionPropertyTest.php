<?php
namespace Hostnet\Component\AccessorGenerator\Reflection;

/**
 * @covers Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class ReflectionPropertyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->simple = new ReflectionProperty('simple');

        $this->public_static = new ReflectionProperty(
            'public_static',
            ReflectionProperty::IS_STATIC | ReflectionProperty::IS_PUBLIC
        );

        $this->complex = new ReflectionProperty(
            'complex',
            ReflectionProperty::IS_PROTECTED,
            '\'default\'',
            'DOCS',
            new ReflectionClass(__DIR__ . '/fixtures/no_class.php')
        );

        $this->empty_doc = new ReflectionProperty(
            'empty_doc',
            ReflectionProperty::IS_PRIVATE,
            null,
            ''
        );
    }

    /**
     * @expectedException DomainException
     */
    public function testModifiersDomainNone()
    {
        new ReflectionProperty('foo', 0);
    }

    /**
     * @expectedException DomainException
     */
    public function testModifiersDomainTwo()
    {
        new ReflectionProperty('foo', \ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidType()
    {
        new ReflectionProperty('foo', 'bar');
    }

    public function testGetName()
    {
        self::assertEquals('simple', $this->simple->getName());
        self::assertEquals('public_static', $this->public_static->getName());
        self::assertEquals('complex', $this->complex->getName());
    }

    public function testGetClass()
    {
        self::assertNull($this->simple->getClass());
    }

    public function testGetDocComment()
    {
        self::assertNull($this->simple->getDocComment());

    }

    public function testGetDefault()
    {
        self::assertNull($this->simple->getDefault());
        self::assertEquals('', $this->empty_doc->getDefault());
    }

    public function testModifiers()
    {
        self::assertTrue($this->simple->isPrivate());
        self::assertFalse($this->simple->isProtected());
        self::assertFalse($this->simple->isPublic());
        self::assertFalse($this->simple->isStatic());

        self::assertFalse($this->public_static->isPrivate());
        self::assertFalse($this->public_static->isProtected());
        self::assertTrue($this->public_static->isPublic());
        self::assertTrue($this->public_static->isStatic());

        self::assertFalse($this->complex->isPrivate());
        self::assertTrue($this->complex->isProtected());
        self::assertFalse($this->complex->isPublic());
        self::assertFalse($this->complex->isStatic());

        self::assertTrue($this->empty_doc->isPrivate());
        self::assertFalse($this->empty_doc->isProtected());
        self::assertFalse($this->empty_doc->isPublic());
        self::assertFalse($this->empty_doc->isStatic());
    }

    public function testToString()
    {
        self::assertEquals('private $simple;', $this->simple->__toString());
        self::assertEquals('public static $public_static;', $this->public_static->__toString());
        self::assertEquals("DOCS\nprotected \$complex = 'default';", $this->complex->__toString());
    }
}

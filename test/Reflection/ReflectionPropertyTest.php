<?php
namespace Hostnet\Component\AccessorGenerator\Reflection;

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
            new ReflectionClass(__DIR__ . '/fixtures/noclass.php')
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
        $this->assertEquals('simple', $this->simple->getName());
        $this->assertEquals('public_static', $this->public_static->getName());
        $this->assertEquals('complex', $this->complex->getName());
    }

    public function testGetClass()
    {
        $this->assertNull($this->simple->getClass());
    }

    public function testGetDocComment()
    {
        $this->assertNull($this->simple->getDocComment());

    }

    public function testGetDefault()
    {
        $this->assertNull($this->simple->getDefault());
        $this->assertEquals('', $this->empty_doc->getDefault());
    }

    public function testModifiers()
    {
        $this->assertTrue($this->simple->isPrivate());
        $this->assertFalse($this->simple->isProtected());
        $this->assertFalse($this->simple->isPublic());
        $this->assertFalse($this->simple->isStatic());

        $this->assertFalse($this->public_static->isPrivate());
        $this->assertFalse($this->public_static->isProtected());
        $this->assertTrue($this->public_static->isPublic());
        $this->assertTrue($this->public_static->isStatic());

        $this->assertFalse($this->complex->isPrivate());
        $this->assertTrue($this->complex->isProtected());
        $this->assertFalse($this->complex->isPublic());
        $this->assertFalse($this->complex->isStatic());

        $this->assertTrue($this->empty_doc->isPrivate());
        $this->assertFalse($this->empty_doc->isProtected());
        $this->assertFalse($this->empty_doc->isPublic());
        $this->assertFalse($this->empty_doc->isStatic());
    }

    public function testToString()
    {
        $this->assertEquals('private $simple;', $this->simple->__toString());
        $this->assertEquals('public static $public_static;', $this->public_static->__toString());
        $this->assertEquals("DOCS\nprotected \$complex = 'default';", $this->complex->__toString());
    }
}

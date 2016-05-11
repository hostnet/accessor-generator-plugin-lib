<?php
namespace Hostnet\Component\AccessorGenerator\Reflection;

/**
 * @covers Hostnet\Component\AccessorGenerator\Reflection\Metadata
 */
class MetadataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Metadata
     */
    private $metadata;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->metadata = new Metadata();
    }

    /**
     * @throws Exception\ClassNotFoundException
     */
    public function testReflectionClass()
    {
        $reflection_class_prophecy = $this->prophesize(ReflectionClass::class);

        $reflection_class_prophecy->getFullyQualifiedClassName()->willReturn('name');

        $reflection_class = $reflection_class_prophecy->reveal();

        $this->metadata->addReflectionClass($reflection_class);
        self::assertSame($reflection_class, $this->metadata->getReflectionClassByName('name'));
        self::assertTrue($this->metadata->hasReflectionClassByName('name'));
        self::assertFalse($this->metadata->hasReflectionClassByName('not_there'));
        self::assertSame(['name' => $reflection_class], $this->metadata->getReflectionClasses()->toArray());
    }

    /**
     * @expectedException \Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassNotFoundException
     * @throws Exception\ClassNotFoundException
     */
    public function testGetReflectionClassByName()
    {
        $this->metadata->getReflectionClassByName('not_there');
    }
}

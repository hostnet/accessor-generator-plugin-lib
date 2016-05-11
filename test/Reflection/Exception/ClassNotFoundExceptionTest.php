<?php
namespace Hostnet\Component\AccessorGenerator\Reflection\Exception;

/**
 * @covers Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassNotFoundException
 */
class ClassNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var ClassNotFoundException
     */
    private $class_not_found_exception;

    public function testConstruct()
    {
        $e = new ClassNotFoundException('aClass');
        self::assertContains('aClass', $e->getMessage());
    }
}

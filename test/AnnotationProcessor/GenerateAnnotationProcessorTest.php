<?php
namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Hostnet\Component\AccessorGenerator\Annotation\Generate;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;

/**
 * @covers Hostnet\Component\AccessorGenerator\GenerateAnnotationProcessor
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class GenerateAnnotationProcessorTest extends \PHPUnit_Framework_TestCase
{
    // Some constatns for better reading of the
    // parameters parsed into function.
    const GET       = true;
    const NO_GET    = false;
    const SET       = true;
    const NO_SET    = false;
    const ADD       = true;
    const NO_ADD    = false;
    const REMOVE    = true;
    const NO_REMOVE = false;

    /**
     * Generate TestCases for the parsing
     * of the @Generate annotation.
     *
     * @return Generate|bool[][]
     */
    public function processAnnotationProvider()
    {
        $all           = new Generate();
        $no_get        = new Generate();
        $no_is         = new Generate();
        $no_set        = new Generate();
        $no_add        = new Generate();
        $no_remove     = new Generate();
        $no_collection = new Generate();
        $nothing       = new Generate();

        $no_is->is         = false;
        $no_get->get       = false;
        $no_set->set       = false;
        $no_add->add       = false;
        $no_remove->remove = false;

        $no_collection->add    = false;
        $no_collection->remove = false;

        $nothing->get    = false;
        $nothing->is     = false;
        $nothing->set    = false;
        $nothing->add    = false;
        $nothing->remove = false;

        return [
            [$all,           self::GET   , self::SET,    self::ADD,    self::REMOVE   ],
            [$no_get,        self::NO_GET, self::SET,    self::ADD,    self::REMOVE   ],
            [$no_is,         self::NO_GET, self::SET,    self::ADD,    self::REMOVE   ],
            [$no_set,        self::GET,    self::NO_SET, self::NO_ADD, self::NO_REMOVE],
            [$no_add,        self::GET,    self::SET,    self::NO_ADD, self::REMOVE   ],
            [$no_remove,     self::GET,    self::SET,    self::ADD,    self::NO_REMOVE],
            [$no_collection, self::GET,    self::SET,    self::NO_ADD, self::NO_REMOVE],
            [$nothing,       self::NO_GET, self::NO_SET, self::NO_ADD, self::NO_REMOVE],
        ];
    }

    /**
     * @dataProvider processAnnotationProvider
     * @param object $annotation
     * @param bool $get
     * @param bool $set
     * @param bool $add
     * @param bool $remove
     */
    public function testProcessAnnotation($annotation, $get, $set, $add, $remove)
    {
        // Set up dependencies.
        $property    = new ReflectionProperty('test');
        $information = new PropertyInformation($property);
        $processor   = new GenerateAnnotationProcessor();
        $processor->processAnnotation($annotation, $information);

        // Check if right information was processed.
        $this->assertEquals($get, $information->willGenerateGet());
        $this->assertEquals($set, $information->willGenerateSet());
        $this->assertEquals($add, $information->willGenerateAdd());
        $this->assertEquals($remove, $information->willGenerateRemove());

        // If $set, is false we wil not generate a add method and remove method.
        if ($set == false) {
            $this->assertFalse($information->willGenerateAdd());
            $this->assertFalse($information->willGenerateRemove());
        }
    }
}

<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Doctrine\ORM\Mapping\Column;
use Hostnet\Component\AccessorGenerator\Annotation\Enumerator;
use Hostnet\Component\AccessorGenerator\Annotation\Generate;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\AnnotationProcessor\GenerateAnnotationProcessor
 */
class GenerateAnnotationProcessorTest extends TestCase
{
    // Some constants for better reading of the
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
        $type          = new Generate();
        $encryption    = new Generate();
        $enumerate     = new Generate();

        $column     = new Column();
        $enumerator = new Enumerator();

        $no_is->is         = 'none';
        $no_get->get       = 'none';
        $no_set->set       = 'none';
        $no_add->add       = 'none';
        $no_remove->remove = 'none';

        $no_collection->add    = 'none';
        $no_collection->remove = 'none';

        $nothing->get    = 'none';
        $nothing->is     = 'none';
        $nothing->set    = 'none';
        $nothing->add    = 'none';
        $nothing->remove = 'none';

        $type->get    = 'none';
        $type->is     = 'none';
        $type->set    = 'none';
        $type->add    = 'none';
        $type->remove = 'none';
        $type->type   = \ArrayObject::class;

        $encryption->get              = 'none';
        $encryption->is               = 'none';
        $encryption->set              = 'none';
        $encryption->add              = 'none';
        $encryption->remove           = 'none';
        $encryption->encryption_alias = 'database.table.column';

        $enumerate->enumerators = [$enumerator];
        $enumerator->name       = 'Foo';
        $enumerator->value      = 'SomeClass';

        return [
            [$column,        self::NO_GET, self::NO_SET, self::NO_ADD, self::NO_REMOVE, null,                null],
            [$all,           self::GET   , self::SET,    self::ADD,    self::REMOVE,    null,                null],
            [$no_get,        self::NO_GET, self::SET,    self::ADD,    self::REMOVE,    null,                null],
            [$no_is,         self::NO_GET, self::SET,    self::ADD,    self::REMOVE,    null,                null],
            [$no_set,        self::GET,    self::NO_SET, self::NO_ADD, self::NO_REMOVE, null,                null],
            [$no_add,        self::GET,    self::SET,    self::NO_ADD, self::REMOVE,    null,                null],
            [$no_remove,     self::GET,    self::SET,    self::ADD,    self::NO_REMOVE, null,                null],
            [$no_collection, self::GET,    self::SET,    self::NO_ADD, self::NO_REMOVE, null,                null],
            [$nothing,       self::NO_GET, self::NO_SET, self::NO_ADD, self::NO_REMOVE, null,                null],
            [$type,          self::NO_GET, self::NO_SET, self::NO_ADD, self::NO_REMOVE, \ArrayObject::class, null],
            [$enumerate,     self::NO_GET, self::NO_SET, self::NO_ADD, self::NO_REMOVE, null,                null],
            [
                $encryption,
                self::NO_GET,
                self::NO_SET,
                self::NO_ADD,
                self::NO_REMOVE,
                null,
                'database.table.column',
            ],
        ];
    }

    /**
     * @dataProvider processAnnotationProvider
     * @param mixed $annotation
     * @param bool $get
     * @param bool $set
     * @param bool $add
     * @param bool $remove
     * @param string $type
     * @param string $encryption
     */
    public function testProcessAnnotation($annotation, $get, $set, $add, $remove, $type, $encryption): void
    {
        // Set up dependencies.
        $property    = new ReflectionProperty('test');
        $information = new PropertyInformation($property);
        $processor   = new GenerateAnnotationProcessor();
        $processor->processAnnotation($annotation, $information);

        // Check if right information was processed.
        self::assertSame($get, $information->willGenerateGet());
        self::assertSame($set, $information->willGenerateSet());
        self::assertSame($add, $information->willGenerateAdd());
        self::assertSame($remove, $information->willGenerateRemove());
        self::assertSame($type, $information->getType());
        self::assertSame($encryption, $information->getEncryptionAlias());

        // If $set, is false we wil not generate a add method and remove method.
        if ($set !== false) {
            return;
        }

        self::assertFalse($information->willGenerateAdd());
        self::assertFalse($information->willGenerateRemove());
    }

    public function testEnumeratorVisibilities(): void
    {
        $enumerator        = new Enumerator();
        $enumerator->name  = 'Foo';
        $enumerator->value = 'SomeClass';

        $annotation  = new Generate();
        $annotation2 = new Generate();

        $annotation->enumerators  = [$enumerator];
        $annotation2->enumerators = [$enumerator];
        $annotation2->get         = Generate::VISIBILITY_PUBLIC;

        $property     = new ReflectionProperty('test');
        $property2    = new ReflectionProperty('test2');
        $information  = new PropertyInformation($property);
        $information2 = new PropertyInformation($property2);
        $processor    = new GenerateAnnotationProcessor();

        $processor->processAnnotation($annotation, $information);
        $processor->processAnnotation($annotation2, $information2);

        self::assertTrue($information->willGenerateEnumeratorAccessors());
        self::assertFalse($information->willGenerateGet());
        self::assertFalse($information->willGenerateSet());
        self::assertFalse($information->willGenerateAdd());
        self::assertFalse($information->willGenerateRemove());
    }

    public function testGetProcessableAnnotationNamespace(): void
    {
        self::assertSame(
            'Hostnet\Component\AccessorGenerator\Annotation',
            (new GenerateAnnotationProcessor())->getProcessableAnnotationNamespace()
        );
    }

    public function testEnumeratorOnMissingClass(): void
    {
        $enumerator        = new Enumerator();
        $enumerator->type  = 'ClassDoesNotExist';

        $property     = new ReflectionProperty('test');
        $information  = new PropertyInformation($property);
        $processor    = new GenerateAnnotationProcessor();

        $processor->processAnnotation($enumerator, $information);

        self::assertFalse($information->willGenerateEnumeratorAccessors());
    }
}

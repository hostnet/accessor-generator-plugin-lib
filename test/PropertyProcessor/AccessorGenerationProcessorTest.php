<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\PropertyProcessor;

use Doctrine\ORM\Mapping\Column;
use Hostnet\Component\AccessorGenerator\Attribute\Enumerator;
use Hostnet\Component\AccessorGenerator\Attribute\Generate;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hostnet\Component\AccessorGenerator\PropertyProcessor\AccessorGenerationProcessor
 */
class AccessorGenerationProcessorTest extends TestCase
{
    // Some constants for better reading of the
    // parameters parsed into function.
    private const true GET        = true;
    private const false NO_GET    = false;
    private const true SET        = true;
    private const false NO_SET    = false;
    private const true ADD        = true;
    private const false NO_ADD    = false;
    private const true REMOVE     = true;
    private const false NO_REMOVE = false;

    public function applyProvider(): iterable
    {
        $column     = new Column();
        $enumerator = new Enumerator(value: 'SomeClass', name: 'Foo');

        $all           = new Generate();
        $no_get        = new Generate(get: 'none');
        $no_is         = new Generate(is: 'none');
        $no_set        = new Generate(set: 'none');
        $no_add        = new Generate(add: 'none');
        $no_remove     = new Generate(remove: 'none');
        $no_collection = new Generate(add: 'none', remove: 'none');
        $nothing       = new Generate(get: 'none', is: 'none', set: 'none', add: 'none', remove: 'none');
        $type          = new Generate(
            get: 'none',
            is: 'none',
            set: 'none',
            add: 'none',
            remove: 'none',
            type: \ArrayObject::class
        );
        $encryption    = new Generate(
            get: 'none',
            is: 'none',
            set: 'none',
            add: 'none',
            remove: 'none',
            encryption_alias: 'database.table.column'
        );
        $enumerate     = new Generate(enumerators: [$enumerator]);

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
     * @dataProvider applyProvider
     * @param mixed $annotation
     * @param bool $get
     * @param bool $set
     * @param bool $add
     * @param bool $remove
     * @param string $type
     * @param string $encryption
     */
    public function testApply($annotation, $get, $set, $add, $remove, $type, $encryption): void
    {
        // Set up dependencies.
        $property    = new ReflectionProperty('test');
        $information = new PropertyInformation($property);
        $processor   = new AccessorGenerationProcessor();
        $processor->apply($annotation, $information);

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
        $enumerator  = new Enumerator(value: 'SomeClass', name: 'Foo');
        $annotation  = new Generate(enumerators: [$enumerator]);
        $annotation2 = new Generate(enumerators: [$enumerator], get: Generate::VISIBILITY_PUBLIC);

        $property     = new ReflectionProperty('test');
        $property2    = new ReflectionProperty('test2');
        $information  = new PropertyInformation($property);
        $information2 = new PropertyInformation($property2);
        $processor    = new AccessorGenerationProcessor();

        $processor->apply($annotation, $information);
        $processor->apply($annotation2, $information2);

        self::assertTrue($information->willGenerateEnumeratorAccessors());
        self::assertFalse($information->willGenerateGet());
        self::assertFalse($information->willGenerateSet());
        self::assertFalse($information->willGenerateAdd());
        self::assertFalse($information->willGenerateRemove());
    }

    public function testGetProcessableNamespace(): void
    {
        self::assertSame(
            'Hostnet\Component\AccessorGenerator\Annotation',
            (new AccessorGenerationProcessor())->getProcessableNamespace()
        );
    }
}

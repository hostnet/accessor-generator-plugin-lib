<?php
namespace Hostnet\Component\AccessorGenerator\Annotation;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaults()
    {
        $generate = new Generate();

        // Test default on values and availabillity of
        // the Generate Annotation public fields
        $generate->setDefaultVisibility(Generate::VISIBILITY_PUBLIC);

        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->get);
        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->set);
        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->add);
        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->remove);
        self::assertSame(Generate::VISIBILITY_PUBLIC, $generate->is);
        self::assertTrue($generate->strict);
        self::assertNull($generate->type);
        self::assertNull($generate->encryption_alias);
    }

    public function testTypeAndStrictnessAndEncryptionAlias()
    {
        $generate = new Generate();

        $generate->strict           = false;
        $generate->type             = \stdClass::class;
        $generate->encryption_alias = 'database.table.column';

        self::assertFalse($generate->isStrict());
        self::assertSame(\stdClass::class, $generate->getType());
        self::assertSame('database.table.column', $generate->getEncryptionAlias());
    }

    /**
     * @dataProvider newProvider
     */
    public function testNew($given, $expected)
    {
        $generate = new Generate();

        $generate->get    = $given;
        $generate->set    = $given;
        $generate->add    = $given;
        $generate->remove = $given;
        $generate->is     = $given;

        self::assertSame($expected, $generate->getGet());
        self::assertSame($expected, $generate->getSet());
        self::assertSame($expected, $generate->getAdd());
        self::assertSame($expected, $generate->getRemove());
        self::assertSame($expected, $generate->getIs());
    }

    public function newProvider()
    {
        return [
            [Generate::VISIBILITY_PUBLIC, Generate::VISIBILITY_PUBLIC],
            [Generate::VISIBILITY_PROTECTED, Generate::VISIBILITY_PROTECTED],
            [Generate::VISIBILITY_PRIVATE, Generate::VISIBILITY_PRIVATE],
            [Generate::VISIBILITY_NONE, Generate::VISIBILITY_NONE],
            [true, Generate::VISIBILITY_PUBLIC],
            [false, Generate::VISIBILITY_NONE]
        ];
    }

    /**
     * @dataProvider getMostLimitedVisibilityProvider
     */
    public function testGetMostLimitedVisibility($expected, array $input)
    {
        self::assertSame($expected, Generate::getMostLimitedVisibility(... $input));
    }

    public function getMostLimitedVisibilityProvider()
    {
        return [[
            Generate::VISIBILITY_NONE,
            [Generate::VISIBILITY_PUBLIC, Generate::VISIBILITY_NONE],
        ], [
            Generate::VISIBILITY_PUBLIC,
            [],
        ], [
            Generate::VISIBILITY_PUBLIC,
            [Generate::VISIBILITY_PUBLIC],
        ], [
            Generate::VISIBILITY_PROTECTED,
            [Generate::VISIBILITY_PUBLIC, Generate::VISIBILITY_PROTECTED],
        ], [
            Generate::VISIBILITY_PRIVATE,
            [Generate::VISIBILITY_PUBLIC, Generate::VISIBILITY_PRIVATE],
        ]];
    }
}

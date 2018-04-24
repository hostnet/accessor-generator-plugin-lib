<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Util\Inflector;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Types;
use PHPUnit\Framework\TestCase;

class TypesTest extends TestCase
{
    public function typeProvider()
    {
        $resource = fopen('data://text/plain,', 'r');
        $date     = new \DateTime();
        $array    = [5 => 10, 6 => 11, 7 => [12 , 13]];
        $object   = new \stdClass();

        $values = [
            ['smallint',               0                          ],
            ['smallint',          -32768                          ],
            ['smallint',           32767                          ],
            ['smallint',           32768, \DomainException::class ],
            ['smallint',          -32769, \DomainException::class ],
            ['integer',       2147483647                         ],
            ['integer',      -2147483648                         ],
            ['integer',       2147483648, \DomainException::class ],
            ['integer',      -2147483649, \DomainException::class ],
            ['bigint',       PHP_INT_MAX                          ],
            ['bigint',   -PHP_INT_MAX -1                          ],
            ['bigint',   PHP_INT_MAX + 1,  \DomainException::class],
            ['bigint',  -PHP_INT_MAX - 2,  \DomainException::class],
            ['decimal',         '1000.1'                          ],
            ['float',         1000.10999                          ],
            ['string',          'string'                          ],
            ['text',              'text'                          ],
            ['guid',              'guid'                          ],
            ['blob',           $resource                          ],
            ['boolean',             true                          ],
            ['is_this_boolean',     true                          ],
            ['date',               $date                          ],
            ['datetime',           $date                          ],
            ['array',             $array                          ],
            ['json_array',        $array                          ],
            ['object',            $object                         ],
        ];

        return $values;
    }

    public function getTypeProvider()
    {
        $values = [['id', 0]];

        $class = new \ReflectionClass(Types::class);

        foreach ($class->getProperties() as $property) {
            $values[] = [$property->getName(), null, \BadMethodCallException::class, 1];
        }

        foreach ($class->getProperties() as $property) {
            $values[] = [$property->getName(), null, \LogicException::class];
        }

        return array_merge($this->typeProvider(), $values);
    }

    /**
     * @param string $type
     * @param mixed $value
     * @param string $exception
     * @param mixed $extra_parameter
     * @dataProvider getTypeProvider
     */
    public function testGetType($type, $value, $exception = null, $extra_parameter = null)
    {
        $exception && $this->expectException($exception);

        $types = new Types();

        $property = new \ReflectionProperty($types, $type);
        $property->setAccessible(true);
        $property->setValue($types, $value);

        if ($type === 'boolean') {
            $getter = 'isBoolean';
        } elseif ($type === 'is_this_boolean') {
            $getter = 'isThisBoolean';
        } else {
            $getter = 'get' . Inflector::classify($type);
        }
        if ($extra_parameter) {
            $get = $types->$getter($extra_parameter);
        } else {
            $get = $types->$getter();
        }

        self::assertTrue(
            $value === $get,
            sprintf('%s is not equal in value and type to %s', var_export($value, true), var_export($get, true))
        );
    }

    public function setTypeProvider()
    {
        // When an integer grows bigger than PHP_MAX_INT or smaller than
        // -PHP_MAX_INT - 1 it will be transformed into a float value.
        // This float value lacks the precision of an integer, to detect
        // if this could have happend we determine the range of floats that
        // one can be sure that can not be the results of such a transformation
        // this still leaves room for false positives, but since these numbers
        // are not noramlly used, getting a domain exception instead of invalid
        // argument will still aid the developper locating problems in the code.
        $range_float = 1 << (PHP_INT_SIZE === 8 ? 53 : 24);

        $values = [
            ['smallint', 01                                                    ],
            ['smallint', 0x1                                                   ],
            ['integer',  01                                                    ],
            ['integer',  0x1                                                   ],
            ['integer',  '10',                  \InvalidArgumentException::class],
            ['integer',  1.2,                   \InvalidArgumentException::class],
            ['integer',  1.0,                   \InvalidArgumentException::class],
            ['integer',  '10a',                 \InvalidArgumentException::class],
            ['integer',  '10.1a',               \InvalidArgumentException::class],
            ['integer',  'a10',                 \InvalidArgumentException::class],
            ['integer',  null,                  \InvalidArgumentException::class],
            ['integer',  [],                    \InvalidArgumentException::class],
            ['integer',  new \stdClass(),       \InvalidArgumentException::class],
            ['bigint',   01                                                    ],
            ['bigint',   0x1                                                   ],
            ['bigint',   1.0,                   \InvalidArgumentException::class],
            ['bigint',   $range_float - 1.0,    \InvalidArgumentException::class],
            ['bigint',   (float) ($range_float), \DomainException::class         ],
            ['decimal',  10.10                                                 ],
            ['decimal',  10.100000                                             ],
            ['decimal',  '10.10',               \DomainException::class         ],
            ['decimal',  123456789                                             ],
            ['decimal',  1234567890,            \DomainException::class         ],
            ['decimal',  12345678901.0,         \DomainException::class         ],
            ['decimal',  '12345678901',         \DomainException::class         ],
            ['decimal',  '12345678a901',        \InvalidArgumentException::class],
            ['decimal',  01                                                    ],
            ['decimal',  0x1                                                   ],
            ['string',   str_repeat('a', 256),  \LengthException::class         ],
        ];

        // Try an invalid type and wrong number of parameters
        $class = new \ReflectionClass(Types::class);
        foreach ($class->getProperties() as $property) {
            $setter = 'set' . Inflector::classify($property->getName());
            if (!method_exists(Types::class, $setter)) {
                continue;
            }

            // Too many parameters
            $values[] = [$property->getName(), new \DateTime(), \BadMethodCallException::class, 1];

            // Wrong type gives other Errors since PHP 7
            if (stripos($property->getName(), 'date') === false || PHP_MAJOR_VERSION < 7) {
                $error_class = \InvalidArgumentException::class;
            } else {
                $error_class = \TypeError::class;
            }
            $invalid_type = $property->getName() === 'object' ? null : new \stdClass();
            $values[]     = [$property->getName(), $invalid_type, $error_class];
        }

        return array_merge($this->typeProvider(), $values);
    }

    /**
     * @param string $type
     * @param mixed $value
     * @param string $exception
     * @param mixed $extra_parameter
     * @dataProvider setTypeProvider
     */
    public function testSetType($type, $value, $exception = null, $extra_parameter = null)
    {
        $exception && $this->expectException($exception);
        $types = new Types();

        if ($type === 'boolean') {
            $getter = 'isBoolean';
        } elseif ($type === 'is_this_boolean') {
            $getter = 'isThisBoolean';
        } else {
            $getter = 'get' . Inflector::classify($type);
        }

        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            if ($errno === E_RECOVERABLE_ERROR) {
                throw new \InvalidArgumentException(sprintf('File %s:%d: %s', $errfile, $errline, $errstr));
            }
            return false;
        });

        $setter = 'set' . Inflector::classify($type);

        if ($extra_parameter !== null) {
            $set = $types->$setter($value, false, $extra_parameter);
        } else {
            $set = $types->$setter($value);
            $get = $types->$getter();
        }

        // Check for fluent interface
        self::assertSame($types, $set);

        self::assertEquals($value, $get);
    }

    /**
     * @expectedException DomainException
     */
    public function testSetDecimalTooBig()
    {
        $types = new Types();
        $types->setDecimal('1E+10');
    }

    /**
     * @expectedException DomainException
     */
    public function testSetDecimalTooSmall()
    {
        $types = new Types();
        $types->setDecimal('+10E-2');
    }

    public function testSetDecimal()
    {
        $types = new Types();

        $types->setDecimal('1E+8');
        self::assertEquals('100000000', $types->getDecimal());

        $types->setDecimal('1.0000000005E+8', true);
        self::assertEquals('100000000.1', $types->getDecimal());

        $types->setDecimal('1.0000000004E+8', true);
        self::assertEquals('100000000.0', $types->getDecimal());

        $types->setDecimal('+1.0000000005E+8', true);
        self::assertEquals('100000000.1', $types->getDecimal());

        $types->setDecimal('-1.0000000005E+8', true);
        self::assertEquals('-100000000.1', $types->getDecimal());

        $types->setDecimal('+1E-1');
        self::assertEquals('0.1', $types->getDecimal());

        $types->setDecimal('+1E-2', true);
        self::assertEquals('0.0', $types->getDecimal());

        $types->setDecimal('+5E-2', true);
        self::assertEquals('0.1', $types->getDecimal());

        $types->setDecimal(+5E-20, true);
        self::assertEquals('0.0', $types->getDecimal());
    }
}

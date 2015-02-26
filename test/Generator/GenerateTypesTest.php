<?php
namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityNotFoundException;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\GenerateTypes;

class GenerateTypesTest extends \PHPUnit_Framework_TestCase
{

    public function typeProvider()
    {
        $date   = new \DateTime();
        $array  = [5 => 10, 6 => 11, 7 => [12 , 13]];
        $object = new \stdClass();

        $values = [
            ['integer',       2147483647,                         ],
            ['integer',      -2147483648,                         ],
            ['integer',       2147483648, \DomainException::class ],
            ['integer',      -2147483649, \DomainException::class ],
            ['float',         1000.10999                          ],
            ['string',          'string'                          ],
            ['boolean',             true                          ],
            ['is_this_boolean',     true                          ],
            ['datetime',           $date                          ],
            ['array',             $array                          ],
            ['object',            $object                         ],
        ];

        return $values;
    }

    public function getTypeProvider()
    {
        $values = [];
        $class  = new \ReflectionClass(GenerateTypes::class);

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
        $this->setExpectedException($exception);

        $types = new GenerateTypes();

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

        $this->assertTrue(
            $value === $get,
            sprintf('%s is not equal in value and type to %s', var_export($value, true), var_export($get, true))
        );
    }

    public function setTypeProvider()
    {
        $values = [
            ['integer',  01,                                                    ],
            ['integer',  0x1,                                                   ],
            ['integer',  '10',                  \InvalidArgumentException::class],
            ['integer',  1.2,                   \InvalidArgumentException::class],
            ['integer',  1.0,                   \InvalidArgumentException::class],
            ['integer',  '10a',                 \InvalidArgumentException::class],
            ['integer',  '10.1a',               \InvalidArgumentException::class],
            ['integer',  'a10',                 \InvalidArgumentException::class],
            ['integer',  null,                  \InvalidArgumentException::class],
            ['integer',  [],                    \InvalidArgumentException::class],
            ['integer',  new \stdClass(),       \InvalidArgumentException::class],
        ];

        // Try an invalid type and wrong number of parameters
        $class = new \ReflectionClass(GenerateTypes::class);
        foreach ($class->getProperties() as $property) {
            $setter = 'set' . Inflector::classify($property->getName());
            if (method_exists(GenerateTypes::class, $setter)) {
                $invalid_type = $property->getName() === 'object' ? null : new \stdClass();
                $values[]     = [$property->getName(), new \DateTime(), \BadMethodCallException::class, 1];
                $values[]     = [$property->getName(), $invalid_type, \InvalidArgumentException::class];
            }
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
        $this->setExpectedException($exception);
        $types = new GenerateTypes();

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
        $this->assertSame($types, $set);

        $this->assertEquals($value, $get);
    }
}

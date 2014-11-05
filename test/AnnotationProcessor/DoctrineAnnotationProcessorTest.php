<?php
namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;

/**
 * @covers Hostnet\Component\AccessorGenerator\DoctrineAnnotationProcessor
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class DoctrineAnnotationProcessorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var PropertyInformation
     */
    private $information;

    /**
     * @var DoctrineAnnotationProcessor
     */
    private $processor;

    /**
     * Initialize processor and
     * @see PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        // Set up dependencies.
        $this->information = new PropertyInformation(new ReflectionProperty('test'));
        $this->processor   = new DoctrineAnnotationProcessor();
    }
    /**
     * Generate TestCases for the parsing
     * of the @Column annotation.
     *
     * @return Column|mixed[][]
     */
    public function processColumnAnnotationProvider()
    {
        $property = new ReflectionProperty('test');
        $implicit = new Column();
        $explicit = new Column();

        $implicit_info = new PropertyInformation($property);
        $explicit_info = new PropertyInformation($property);

        $explicit->length = 100;
        $explicit_info->setLength(100);

        $explicit->nullable = 'jumbodumbo';
        $explicit_info->setNullable(true);

        $explicit->precision = 3;
        $explicit_info->setPrecision(3);

        $explicit->scale = 9;
        $explicit_info->setScale(9);

        $explicit->type = 'bigint';
        $explicit_info->setType('integer');
        $explicit_info->setIntegerSize(64);

        $explicit->unique = true;
        $explicit_info->setUnique(true);

        return [
            [$implicit, $implicit_info],
            [$explicit, $explicit_info],
        ];
    }

    /**
     * @dataProvider processColumnAnnotationProvider
     */
    public function testProcessColumnAnnotation(Column $column, PropertyInformationInterface $output)
    {
        // Set up dependencies.
        $this->processor->processAnnotation($column, $this->information);

        // Check if right information was processed.
        $this->assertEquals(
            $output->isUnique(),
            $this->information->isUnique(),
            'Value for unique does not match'
        );
        $this->assertEquals(
            $output->isNullable(),
            $this->information->isNullable(),
            'Value for nullable does not match'
        );
        $this->assertEquals(
            $output->isFixedPointNumber(),
            $this->information->isFixedPointNumber(),
            'Value for fixed point number does not match'
        );
        $this->assertEquals(
            $output->getIntegerSize(),
            $this->information->getIntegerSize(),
            'Value for integer size does not match'
        );
        $this->assertEquals(
            $output->getPrecision(),
            $this->information->getPrecision(),
            'Value for precision does not match'
        );
        $this->assertEquals(
            $output->getScale(),
            $this->information->getScale(),
            'Value for scale does not match'
        );
        $this->assertEquals(
            $output->getLength(),
            $this->information->getLength(),
            'Value for length does not match'
        );
        $this->assertEquals(
            $output->getType(),
            $this->information->getType(),
            'Value for tupe does not match'
        );
    }

    public function processAssociationAnnotationProvider()
    {
        $many_to_many    = new ManyToMany();
        $many_to_one     = new ManyToOne();
        $one_to_many     = new OneToMany();
        $one_to_one      = new OneToOne();
        $generated_value = new GeneratedValue();

        $many_to_many->targetEntity = '\\Employee';
        $many_to_one->targetEntity  = 'Employee';
        $one_to_many->targetEntity  = '\\Employer';
        $one_to_one->targetEntity   = 'Boss';

        return [
            [$many_to_many],
            [$many_to_one],
            [$one_to_many],
            [$one_to_one],
            [$generated_value],
        ];
    }

    /**
     * @dataProvider processAssociationAnnotationProvider
     */
    public function testAssociationAnnotations($annotation)
    {
        // Set up dependencies.
        $this->processor->processAnnotation($annotation, $this->information);

        // These annotation should lead to isCollection is is true
        if ($annotation instanceof ManyToMany || $annotation instanceof ManyToOne) {
            $this->assertTrue($this->information->isCollection());
        } else {
            $this->assertFalse($this->information->isCollection());
        }


        // Check type.
        if ($annotation instanceof ManyToMany
            || $annotation instanceof ManyToOne
            || $annotation instanceof OneToMany
            || $annotation instanceof ManyToMany
        ) {
            // Add \ prefix to type if it was not there
            // in the annotation since we expect our result
            // to always include it.
            $type = $annotation->targetEntity;
            if (isset($type[0]) && $type[0] != '\\') {
                $type = '\\' . $type;
            }
            $this->assertEquals($type, $this->information->getType());
        }

        // Check Generated value disables
        // set method generation.
        if ($annotation instanceof GeneratedValue) {
            $this->assertFalse($this->information->willGenerateSet());
        }
    }

    /**
     * @see http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html
     * @return string[][]:
     */
    public function typeConversionDataProvider()
    {
        return [
            ['smallint',   'integer'],
            ['integer',    'integer'],
            ['decimal',    'float'],
            ['float',      'float'],
            ['string',     'string'],
            ['text',       'string'],
            ['guid',       'string'],
            ['blob',       'resource'],
            ['boolean',    'boolean'],
            ['date',       '\\DateTime'],
            ['time',       '\\DateTime'],
            ['datetime',   '\\DateTime'],
            ['datetimetz', '\\DateTime'],
            ['array',      'array'],
            ['json_array', 'array'],
            ['object',     'object'],

            ['double',     'dobule',   \DomainException::class],
            ['bool',       'bool',     \DomainException::class],
            ['binary',     'resource', \DomainException::class],
            ['int',        'integer',  \DomainException::class],
            ['',           '',         \DomainException::class],
            [null,         null,       \DomainException::class],
            [false,        false,      \DomainException::class],
            [[],           [],         \DomainException::class],
        ];
    }

    /**
     * @dataProvider typeConversionDataProvider
     * @param string $doctrine_type
     * @param string $php_type
     */
    public function testTypeConversion($doctrine_type, $php_type, $exception = null)
    {
        // Set exeception if we except one.
        $this->setExpectedException($exception);

        // Check if we have an assosciation or a scalar db type.
        if (is_string($doctrine_type) && substr($doctrine_type, 0, 1) == '\\') {
            $annotation               = new ManyToOne();
            $annotation->targetEntity = $doctrine_type;
        } else {
            $annotation       = new Column();
            $annotation->type = $doctrine_type;
        }

        $this->processor->processAnnotation($annotation, $this->information);
        $this->assertSame($php_type, $this->information->getType());
    }

    public function testOtherAnnotation()
    {
        $information = clone($this->information);
        $annotation  = new \stdClass();
        $this->processor->processAnnotation($annotation, $this->information);
        $this->assertEquals($information, $this->information);
    }
}

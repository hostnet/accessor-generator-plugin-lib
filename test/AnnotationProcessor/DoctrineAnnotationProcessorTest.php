<?php
namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\Exception\InvalidColumnSettingsException;
use Hostnet\Component\AccessorGenerator\Reflection\Metadata;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionProperty;

/**
 * @covers Hostnet\Component\AccessorGenerator\AnnotationProcessor\DoctrineAnnotationProcessor
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
        $this->processor   = new DoctrineAnnotationProcessor(new Metadata());
    }

    /**
     * Generate TestCases for the parsing
     * of the @Column annotation.
     *
     * @return Column|mixed[][]
     * @throws \RangeException
     * @throws \InvalidArgumentException
     * @throws \DomainException
     */
    public function processColumnAnnotationProvider()
    {
        $property = new ReflectionProperty('test');
        $implicit = new Column();
        $explicit = new Column();
        $faulty   = new Column();

        $implicit_info = new PropertyInformation($property);
        $explicit_info = new PropertyInformation($property);
        $faulty_info   = new PropertyInformation($property);

        $implicit_info->setType($implicit->type);
        $explicit_info->setType($explicit->type);
        $faulty_info->setType($faulty->type);

        $explicit->length = 100;
        $explicit_info->setLength(100);

        $explicit->nullable = 'jumbo_dumbo';
        $explicit_info->setNullable(true);

        $explicit->precision = 3;
        $explicit_info->setPrecision(3);

        $explicit->scale = 9;
        $explicit_info->setScale(9);

        $explicit->type = Type::BIGINT;
        $explicit_info->setType('integer');
        $explicit_info->setIntegerSize(64);

        $explicit->unique = true;
        $explicit_info->setUnique(true);

        $faulty->type = Type::DECIMAL;

        return [
            [$implicit, $implicit_info, null],
            [$explicit, $explicit_info, null],
            [$faulty,   $faulty_info,   InvalidColumnSettingsException::class],
        ];
    }

    /**
     * @dataProvider processColumnAnnotationProvider
     * @param Column $column
     * @param PropertyInformationInterface $output
     * @param $exception
     * @throws \DomainException
     * @throws \Hostnet\Component\AccessorGenerator\AnnotationProcessor\Exception\InvalidColumnSettingsException
     * @throws \InvalidArgumentException
     * @throws \RangeException
     */
    public function testProcessColumnAnnotation(Column $column, PropertyInformationInterface $output, $exception)
    {
        // Set if an explosion is needed.
        $this->expectException($exception);

        // Set up dependencies.
        $this->processor->processAnnotation($column, $this->information);

        // Check if right information was processed.
        self::assertEquals(
            $output->isUnique(),
            $this->information->isUnique(),
            'Value for unique does not match'
        );
        self::assertEquals(
            $output->isNullable(),
            $this->information->isNullable(),
            'Value for nullable does not match'
        );
        self::assertEquals(
            $output->isFixedPointNumber(),
            $this->information->isFixedPointNumber(),
            'Value for fixed point number does not match'
        );
        self::assertEquals(
            $output->getIntegerSize(),
            $this->information->getIntegerSize(),
            'Value for integer size does not match'
        );
        self::assertEquals(
            $output->getPrecision(),
            $this->information->getPrecision(),
            'Value for precision does not match'
        );
        self::assertEquals(
            $output->getScale(),
            $this->information->getScale(),
            'Value for scale does not match'
        );
        self::assertEquals(
            $output->getLength(),
            $this->information->getLength(),
            'Value for length does not match'
        );
        self::assertEquals(
            $output->getType(),
            $this->information->getType(),
            'Value for type does not match'
        );
    }

    public function processAssociationAnnotationProvider()
    {
        $many_to_many    = new ManyToMany();
        $many_to_one     = new ManyToOne();
        $one_to_many     = new OneToMany();
        $one_to_one      = new OneToOne();
        $generated_value = new GeneratedValue();
        $join_column     = new JoinColumn();
        $one_to_one_bi   = new OneToOne();
        $one_to_many_bi  = new OneToMany();

        $many_to_many->targetEntity   = '\\Employee';
        $many_to_one->targetEntity    = 'Employee';
        $one_to_many->targetEntity    = '\\Employer';
        $one_to_one->targetEntity     = 'Boss';
        $join_column->name            = 'id';
        $one_to_one_bi->inversedBy    = 'id';
        $one_to_one_bi->targetEntity  = 'Employee\\Boss';
        $one_to_many_bi->mappedBy     = 'id';
        $one_to_many_bi->targetEntity = 'LunchLady';

        return [
            [$many_to_many],
            [$many_to_one],
            [$one_to_many],
            [$one_to_one],
            [$generated_value],
            [$join_column],
            [$one_to_one_bi],
            [$one_to_many_bi],
        ];
    }

    /**
     * @dataProvider processAssociationAnnotationProvider
     * @param $annotation
     * @throws \DomainException
     * @throws \Hostnet\Component\AccessorGenerator\AnnotationProcessor\Exception\InvalidColumnSettingsException
     * @throws \InvalidArgumentException
     * @throws \RangeException
     */
    public function testAssociationAnnotations($annotation)
    {
        // Set up dependencies.
        $this->processor->processAnnotation($annotation, $this->information);

        // These annotation should lead to isCollection is is true
        if ($annotation instanceof ManyToMany || $annotation instanceof OneToMany) {
            self::assertTrue($this->information->isCollection());
        } else {
            self::assertFalse($this->information->isCollection());
        }

        // Check type.
        if ($annotation instanceof ManyToMany
            || $annotation instanceof ManyToOne
            || $annotation instanceof OneToMany
            || $annotation instanceof ManyToMany
        ) {
            $type = $annotation->targetEntity;
            self::assertEquals($type, $this->information->getType());
        }

        // Check Generated value disables
        // set method generation.
        if ($annotation instanceof GeneratedValue) {
            self::assertFalse($this->information->willGenerateSet(), 'generate');
        }

        // Check for a bidirectional association
        if (property_exists($annotation, 'mappedBy') && $annotation->mappedBy) {
            // Bidirectional, inverse side.
            self::assertEquals($annotation->mappedBy, $this->information->getReferencedProperty());
        } elseif (property_exists($annotation, 'inversedBy') && $annotation->inversedBy) {
            // Bidirectional, owning side.
            self::assertEquals($annotation->inversedBy, $this->information->getReferencedProperty());
        } else {
            // Unidirectional.
            self::assertEmpty($this->information->getReferencedProperty());
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
            ['bigint',     'integer'],
            ['decimal',    'string'],
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
            [DoctrineAnnotationProcessor::ZEROED_DATE_TIME, '\\DateTime'],
            ['array',      'array'],
            ['json_array', 'array'],
            ['object',     'object'],
            ['double',     null,  \DomainException::class],
            ['bool',       null,  \DomainException::class],
            ['binary',     null,  \DomainException::class],
            ['int',        null,  \DomainException::class],
            ['',           null,  \DomainException::class],
            [null,         null,  \InvalidArgumentException::class],
            [false,        false, \InvalidArgumentException::class],
            [[],           [],    \InvalidArgumentException::class],
            [['test'],     [],    \InvalidArgumentException::class],
        ];
    }

    /**
     * @dataProvider typeConversionDataProvider
     * @param string $doctrine_type
     * @param string $php_type
     * @param null $exception
     * @throws \DomainException
     * @throws \Hostnet\Component\AccessorGenerator\AnnotationProcessor\Exception\InvalidColumnSettingsException
     * @throws \InvalidArgumentException
     * @throws \RangeException
     */
    public function testTypeConversion($doctrine_type, $php_type, $exception = null)
    {
        // Set exception if we except one.
        $this->expectException($exception);

        // Check if we have an association or a scalar db type.
        if (is_string($doctrine_type)
            && $doctrine_type
            && (ctype_upper($doctrine_type[0]) || $doctrine_type[0] === '\\')
        ) {
            $annotation               = new ManyToOne();
            $annotation->targetEntity = $doctrine_type;
        } else {
            $annotation        = new Column();
            $annotation->type  = $doctrine_type;
            $annotation->scale = 1;
        }

        $this->processor->processAnnotation($annotation, $this->information);
        self::assertSame($php_type, $this->information->getType());
    }

    public function testOtherAnnotation()
    {
        $information = clone $this->information;
        $annotation  = new \stdClass();
        $this->processor->processAnnotation($annotation, $this->information);
        self::assertEquals($information, $this->information);
    }

    public function testGetProcessableAnnotationNamespace()
    {
        self::assertSame('Doctrine\ORM\Mapping', $this->processor->getProcessableAnnotationNamespace());
    }
}

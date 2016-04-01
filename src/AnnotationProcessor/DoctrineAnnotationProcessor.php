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

/**
 * Process Column, ManyToMany, OneToOne, ManyToOne,
 * OneToMany and GeneratedValue Doctrine ORM annotations
 * and extract the type and relationship information.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class DoctrineAnnotationProcessor implements AnnotationProcessorInterface
{
    const ZEROED_DATE_TIME = 'zeroeddatetime';
    const YAML_ARRAY       = 'yaml_array';

    /**
     * Process annotations of type:
     *  Column,
     *  GeneratedValue,
     *  ManyToMany,
     *  ManyToOne,
     *  OneToMany,
     *  OneToOne.
     *
     * @param  object              $annotation  object of a class annotated with @annotation
     * @param  PropertyInformation $information
     * @return void
     */
    public function processAnnotation($annotation, PropertyInformation $information)
    {
        switch (true) {
            case $annotation instanceof Column:
                // Process scalar value (db-wise) columns.
                $this->processColumn($annotation, $information);
                break;
            case $annotation instanceof JoinColumn:
                // Process scalar value (db-wise) columns.
                $this->processJoinColumn($annotation, $information);
                break;
            case $annotation instanceof GeneratedValue:
                // Generated value columns such as auto_increment
                // should not have a stetter function generated.
                // If the user insists on setting this collumn
                // a setter could be implemented by hand.
                $information->setGenerateSet(false);
                break;
            case $annotation instanceof OneToMany:
            case $annotation instanceof ManyToMany:
                // We are one the owning side (db-wise) of a collection,
                // so we should generate add en remove methods.
                $information->setCollection(true);
                // Intentional fall-through
            case $annotation instanceof OneToOne:
            case $annotation instanceof ManyToOne:
                // All relationships have a target type that can
                // be extracted and used as the column type.
                $type = $this->transformComplexType($annotation->targetEntity);
                $information->setType($type);
                $this->processBidirectional($annotation, $information);
                break;
            default:
                // Do nothing for other types
        }
    }

    /**
     * @see AnnotationProcessorInterface::getProcessableAnnotations()
     */
    public function getProcessableAnnotationNamespace()
    {
        return 'Doctrine\ORM\Mapping';
    }

    /**
     * Return referenced entity if we have a bidirectional
     * doctrine association.
     *
     * @param object $annotation with annotation Annotation
     */
    private function processBidirectional($annotation, PropertyInformation $information)
    {
        // Parse the mappedBy and inversedBy columns, there is no nice interface
        // on then so we have to check for existance of the property.
        if (property_exists($annotation, 'inversedBy') && $annotation->inversedBy) {
            $information->setReferencedProperty($annotation->inversedBy);
        } elseif (property_exists($annotation, 'mappedBy') && $annotation->mappedBy) {
            $information->setReferencedProperty($annotation->mappedBy);
        }

        if ($annotation instanceof ManyToOne || $annotation instanceof ManyToMany) {
            $information->setReferencingCollection(true);
        }

        // Set default value for nullable
        if ($information->isNullable() === null) {
            $information->setNullable((new JoinColumn())->nullable);
        }
    }

    /**
     * Process a Column Annotation, extraxt information
     * about scale and precision for decimal types, length
     * and size of string and integer types, if the column
     * may be null and if it should be a unique value.
     *
     * @param Column $column
     * @param PropertyInformation $information
     */
    protected function processColumn(Column $column, PropertyInformation $information)
    {
        $information->setType($this->transformType($column->type));
        $information->setFixedPointNumber(strtolower($column->type) === Type::DECIMAL);
        $information->setLength($column->length ?: 0);
        $information->setPrecision($column->precision);
        $information->setScale($column->scale);
        $information->setUnique($column->unique);
        $information->setNullable($column->nullable);
        $information->setIntegerSize($this->getIntegerSizeForType($column->type));

        if ($information->isFixedPointNumber()
            && $information->getPrecision() === 0
            && $information->getScale() === 0
        ) {
            throw new InvalidColumnSettingsException(
                sprintf(
                    'Decimal type of "%s::%s" has scale and precision set to 0 or not set at all.'
                    . PHP_EOL . 'Usage: e.g. @ORM\Column(type="decimal", precision=2, scale=4).'
                    . PHP_EOL . '"The precision represents the number of digits that are stored for values,'
                    . PHP_EOL . ' and the scale represents the number of digits that can be stored'
                    . PHP_EOL . ' following the decimal point".',
                    $information->getClass(),
                    $information->getName()
                )
            );
        }
    }

    /**
     * Process a JoinColumn Annotation, extraxt nullable.
     *
     * @param JoinColumn $join_column
     * @param PropertyInformationInterface $information
     */
    private function processJoinColumn(JoinColumn $join_column, PropertyInformation $information)
    {
        $information->setNullable($join_column->nullable);
        $information->setUnique($join_column->unique);
    }

    /**
     * Take the doctrine type and turn it into the corresponding PHP type.
     * Take notion that we differ from the default implementation for bigint
     * values. We treat them as integer (which if fine on a  64bit system) or
     * throw exceptions (in the set methods) if the value is too big for PHP to
     * handle.
     *
     * If no valid transformation is found, the type will not be changed and
     * returned.
     *
     * @see http://php.net/manual/en/language.types.php
     * @see http://php.net/manual/en/function.gettype.php (double vs float)
     * @see http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html
     * @param  string $type
     * @return string Valid PHP type
     */
    private function transformType($type)
    {
        if ($type == Type::BOOLEAN) {
            return 'boolean';
        } elseif ($type == Type::SMALLINT || $type == Type::BIGINT || $type == Type::INTEGER) {
            return 'integer';
        } elseif ($type == Type::FLOAT) {
            return 'float';
        } elseif ($type == Type::TEXT || $type == Type::GUID || $type == Type::STRING || $type == Type::DECIMAL) {
            return 'string';
        } elseif ($type == Type::BLOB /* binary will be added in doctrine 2.5 */) {
            return 'resource';
        } elseif (in_array($type, [Type::DATETIME, Type::DATETIMETZ, Type::DATE, Type::TIME, self::ZEROED_DATE_TIME])) {
            return '\\' . \DateTime::class;
        } elseif (in_array($type, [Type::SIMPLE_ARRAY, Type::JSON_ARRAY, Type::TARRAY, self::YAML_ARRAY])) {
            return 'array';
        } elseif ($type == Type::OBJECT) {
            return 'object';
        } else {
            return $type;
        }
    }

    /**
     * Transform a Doctrine complex type to a valid
     * PHP type reference. Doctrine does not require
     * your class to start with a \ for a fully qual-
     * lified classname. When there is a \ inside
     * Doctrine assumes a fully qualified name. This
     * makes relative references to subnamespaces
     * impossible. When there is no \ in the class name
     * it is assumed to be relative to the current name-
     * space and left as-is.
     *
     * @param string $type
     * @return string
     */
    private function transformComplexType($type)
    {
        if (strpos($type, '\\') > 0) {
            return '\\' . $type;
        } else {
            return $type;
        }
    }

    /**
     * Return the size of an integer type in bits.
     * This value can be used by the set methods to
     * validate that the value sent to the databse
     * will not be too big and chopped off.
     *
     * PHP does scale all int values automatically
     * up when they grow larger and eventually turn
     * them silently into a float.
     *
     * @see http://doctrine-dbal.readthedocs.org/en/latest/reference/types.html
     * @param  string $type
     * @return int
     */
    private function getIntegerSizeForType($type)
    {
        switch ($type) {
            case 'bool':
            case 'boolean':
                return 1;
            case 'smallint':
                return 16;
            case 'bigint':
                return 64;
            case 'int':
            case 'integer':
            default:
                return 32;
        }
    }
}

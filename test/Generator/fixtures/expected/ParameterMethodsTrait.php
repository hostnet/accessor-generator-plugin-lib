<?php
// Generated at 2018-03-09 10:46:20 by hiedema on se18-03-73-40-f6-af

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Enum\EnumeratorCompatibleEntityInterface;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Parameter;

trait ParameterMethodsTrait
{
    /**
     * Gets id
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return int
     */
    public function getId()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getId() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->id === null) {
            throw new \LogicException(sprintf(
                'Property id is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        if ($this->id < -2147483648|| $this->id > 2147483647) {
            throw new \DomainException(
                sprintf(
                    'Parameter id(%s) is too big for the integer domain [%d,%d]',
                    $this->id,
                    -2147483648,
                    2147483647
                )
            );
        }

        return (int) $this->id;
    }

    /**
     * Gets name
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getName()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getName() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->name === null) {
            throw new \LogicException(sprintf(
                'Property name is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->name;
    }

    /**
     * Gets value
     *
     * @throws \BadMethodCallException
     *
     * @return string
     */
    public function getValue()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getValue() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        if ($this->value === null) {
            return null;
        }

        return $this->value;
    }

    /**
     * Sets value
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you do not like
     * this message, specify a default value or use JoinColumn(nullable=false).
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param  string $value
     * @return $this|Parameter
     */
    public function setValue($value = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setValue() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($value === null) {
            $this->value = $value;
            return $this;
        }

        if ($value === null
            || is_scalar($value)
            || is_callable([$value, '__toString'])
        ) {
            $value = (string)$value;
        } else {
            throw new \InvalidArgumentException(
                'Parameter value must be convertible to string.'
            );
        }

        $this->value = $value;

        return $this;
    }
}

<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\ConstantDefault;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Weather;

trait ConstantDefaultMethodsTrait
{
    /**
     * Gets weather
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return int
     */
    public function getWeather(): int
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getWeather() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }
        if ($this->weather === null) {
            throw new \LogicException(sprintf(
                'Property weather is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        if ($this->weather < -2147483648|| $this->weather > 2147483647) {
            throw new \DomainException(
                sprintf(
                    'Parameter weather(%s) is too big for the integer domain [%d,%d]',
                    $this->weather,
                    -2147483648,
                    2147483647
                )
            );
        }

        return (int) $this->weather;
    }

    /**
     * Sets weather
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     * @throws \DomainException if the integer value is outside of the domain on this machine
     *
     * @param int $weather
     *
     * @return $this|ConstantDefault
     */
    public function setWeather($weather = Weather::SUN)
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setWeather() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if (!\is_int($weather)) {
            throw new \InvalidArgumentException(
                'Parameter weather must be integer.'
            );
        }
        if ($weather < -2147483648|| $weather > 2147483647) {
            throw new \DomainException(
                sprintf(
                    'Parameter weather(%s) is too big for the integer domain [%d,%d]',
                    $weather,
                    -2147483648,
                    2147483647
                )
            );
        }

        $this->weather = $weather;

        return $this;
    }
}

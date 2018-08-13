<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Comic as Comic;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Comic\Obelix;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\SubNamespace;
use Hostnet\Component\AccessorGenerator\Plugin;

trait SubNamespaceMethodsTrait
{
    /**
     * Gets asterix
     *
     * @throws \BadMethodCallException
     * @throws \LogicException
     *
     * @return string
     */
    public function getAsterix(): string
    {
        if (\func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getAsterix() has no arguments but %d given.',
                    \func_num_args()
                )
            );
        }
        if ($this->asterix === null) {
            throw new \LogicException(sprintf(
                'Property asterix is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in the code is not set to be nullable ' .
                'and it currently contains a NULL-value in the database.'
            ));
        }

        return $this->asterix;
    }

    /**
     * Sets asterix
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $asterix
     *
     * @return $this|SubNamespace
     */
    public function setAsterix($asterix = Comic\Asterix::class)
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setAsterix() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($asterix === null
            || \is_scalar($asterix)
            || \is_callable([$asterix, '__toString'])
        ) {
            $asterix = (string)$asterix;
        } else {
            throw new \InvalidArgumentException(
                'Parameter asterix must be convertible to string.'
            );
        }

        $this->asterix = $asterix;

        return $this;
    }

    /**
     * Sets super_namespace
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $super_namespace
     *
     * @return $this|SubNamespace
     */
    public function setSuperNamespace($super_namespace = Plugin::NAME)
    {
        if (\func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setSuperNamespace() has one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if ($super_namespace === null
            || \is_scalar($super_namespace)
            || \is_callable([$super_namespace, '__toString'])
        ) {
            $super_namespace = (string)$super_namespace;
        } else {
            throw new \InvalidArgumentException(
                'Parameter super_namespace must be convertible to string.'
            );
        }

        $this->super_namespace = $super_namespace;

        return $this;
    }
}

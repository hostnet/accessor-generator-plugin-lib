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
     * Get asterix
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getAsterix()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getAsterix() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }
        if ($this->asterix === null) {
            throw new \LogicException(sprintf(
                'Property asterix is null, but the column is not nullable, '.
                'make sure your object is initialized in such a way the properties are in '.
                'a valid state, for example by using a proper constructor. If you want to ' .
                'test if an object is new for the database please consult the UnitOfWork.' .
                'It could also be that your column in code is set tot not nullable and in' .
                'and contains null values in the database'
            ));
        }

        return $this->asterix;
    }

    /**
     * Set asterix
     *
     * @param string $asterix
     * @return SubNamespace
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    public function setAsterix($asterix = Comic\Asterix::class)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setAsterix() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($asterix === null
            || is_scalar($asterix)
            || is_callable([$asterix, '__toString'])
        ) {
            $asterix = (string)$asterix;
        } else {
            throw new \InvalidArgumentException(
                'Parameter asterix must be convertable to string.'
            );
        }

        $this->asterix = $asterix;
        return $this;
    }

    /**
     * Set super_namespace
     *
     * @param string $super_namespace
     * @return SubNamespace
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     */
    public function setSuperNamespace($super_namespace = Plugin::NAME)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setSuperNamespace() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($super_namespace === null
            || is_scalar($super_namespace)
            || is_callable([$super_namespace, '__toString'])
        ) {
            $super_namespace = (string)$super_namespace;
        } else {
            throw new \InvalidArgumentException(
                'Parameter super_namespace must be convertable to string.'
            );
        }

        $this->super_namespace = $super_namespace;
        return $this;
    }
}

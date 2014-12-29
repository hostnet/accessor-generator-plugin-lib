<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Address;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\User;

trait UserMethodsTrait
{
    /**
     * Get address
     *
     * @return Address
     * @throws \InvalidArgumentException
     */
    public function getAddress()
    {
        if (func_num_args() > 0) {
            throw new \BadMethodCallException(
                sprintf(
                    'getAddress() has no arguments but %d given.',
                    func_num_args()
                )
            );
        }

        return $this->address;
    }

    /**
     * Set address
     *
     * @param Address $address
     * @return User
     * @throws \BadMethodCallException if the number of arguments is not correct
     */
    public function setAddress(Address $address = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setAddress() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        $this->address = $address;
        return $this;
    }
}

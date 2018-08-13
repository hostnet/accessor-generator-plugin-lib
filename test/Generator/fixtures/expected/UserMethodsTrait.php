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
     * Gets address
     *
     * @throws \BadMethodCallException
     *
     * @return Address|null
     */
    public function getAddress(): ?Address
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
     * Sets address
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     *
     * @param Address $address
     *
     * @return $this|User
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

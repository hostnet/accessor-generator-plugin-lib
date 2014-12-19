<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures as This;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Attribute;

trait AttributeMethodsTrait
{
    /**
     * Set product
     *
     * @param This\Product $product
     * @return Attribute
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \LogicException if the association constraints are violated
     * @access friends with This\Product
     */
    private function setProduct(This\Product $product = null)
    {
        if (func_num_args() != 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setProduct() has one argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($product && ! $product->getAttributes()->contains($this)) {
            throw new \LogicException('Please use Product::addAttribute().');
        } elseif ($product && $this->product) {
            throw new \LogicException('Attribute objects can not be added to more than one This\Product.');
        }

        $this->product = $product;
        return $this;
    }
}

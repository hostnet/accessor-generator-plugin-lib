<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Attribute;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Product;

trait AttributeMethodsTrait
{
    /**
     * Set product
     *
     * Generated a default null value because the doctrine column is nullable.
     * Still require an explicit argument to set the column. If you want to get
     * rid of this message, please specify a default value or specify
     * @JoinColumn(nullable=false).
     *
     * @param Product $product
     * @return Attribute
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \LogicException if the association constraints are violated
     * @access friends with Product
     */
    private function setProduct(Product $product = null)
    {
        if (func_num_args() > 1) {
            throw new \BadMethodCallException(
                sprintf(
                    'setProduct() has one optional argument but %d given.',
                    func_num_args()
                )
            );
        }

        if ($product && ! $product->getAttributes()->contains($this)) {
            throw new \LogicException('Please use Product::addAttribute().');
        } elseif ($product && $this->product) {
            throw new \LogicException('Attribute objects can not be added to more than one Product.');
        }

        $this->product = $product;
        return $this;
    }
}

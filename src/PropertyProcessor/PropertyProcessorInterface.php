<?php
/**
 * @copyright 2026-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\PropertyProcessor;

/**
 * Contract for processors that extract metadata from a single native attribute instance
 * and store it in a PropertyInformation instance.
 */
interface PropertyProcessorInterface
{
    /**
     * Apply the metadata from $attribute (an instantiated native attribute) to $information.
     */
    public function apply(object $attribute, PropertyInformation $information): void;
}

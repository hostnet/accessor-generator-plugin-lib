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
     * Apply the metadata from $annotation (an instantiated native attribute) to $information.
     *
     * @param object              $annotation  instantiated attribute object
     * @param PropertyInformation $information accumulator for this property's metadata
     */
    public function apply($annotation, PropertyInformation $information): void;
}

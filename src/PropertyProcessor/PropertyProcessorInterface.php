<?php
/**
 * @copyright 2026-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\PropertyProcessor;

/**
 * Contract for processors that extract metadata from a single annotation or attribute object
 * and store it in a PropertyInformation instance.
 */
interface PropertyProcessorInterface
{
    /**
     * Apply the metadata from $annotation (a docblock annotation or native attribute instance)
     * to $information.
     *
     * @param object              $annotation  instantiated annotation or attribute object
     * @param PropertyInformation $information accumulator for this property's metadata
     */
    public function apply($annotation, PropertyInformation $information): void;

    /**
     * Returns the namespace this processor handles, used to filter imports before
     * passing them to Doctrine's DocParser.
     */
    public function getProcessableNamespace(): string;
}

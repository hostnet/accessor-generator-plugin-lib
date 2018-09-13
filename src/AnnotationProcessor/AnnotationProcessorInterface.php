<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

/**
 * Interface for Annotation Processors.
 *
 * An annotation processor gets the annotation class as $annotation and a
 * PropertyInformation object to store the knowledge extracted from the
 * annotation.
 */
interface AnnotationProcessorInterface
{
    /**
     * processAnnotations
     *
     * @param object              $annotation  class annotated with @annotation
     * @param PropertyInformation $information location to store new
     *                                          information about the property
     * @return void
     */
    public function processAnnotation($annotation, PropertyInformation $information): void;

    /**
     * Get the namespace of the annotations that will be parsed by this
     * annotation parser.
     *
     * This value can be used to feed it into a doc parser to only parse
     * the annotations that will actually be used.
     *
     * @return string
     */
    public function getProcessableAnnotationNamespace(): string;
}

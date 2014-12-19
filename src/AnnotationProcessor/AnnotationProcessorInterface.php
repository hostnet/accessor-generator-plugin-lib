<?php
namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

/**
 * Interface for Annotation Processors.
 *
 * An annotation processor gets the annotation
 * class as $annotation and a PropertyInformation
 * object to store the knowledge extracted form
 * the annotation.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
interface AnnotationProcessorInterface
{
    /**
     * processAnnotations
     *
     * @param  object              $annotation  class annotated with @annotation
     * @param  PropertyInformation $information location to store new
     *                                          information about the propery
     * @return void
     */
    public function processAnnotation($annotation, PropertyInformation $information);

    /**
     * Get the namespace of the annotations that will be
     * parsed by this annotation parser.
     *
     * This list can be used to feed it into a doc parser to
     * only parse the annotations that will actually be used.
     *
     * @return string[]
     */
    public function getProcessableAnnotationNamespace();
}

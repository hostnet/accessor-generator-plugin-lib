<?php

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformationInterface;
use Hostnet\Component\AccessorGenerator\Reflection\Metadata;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;

/**
 * Create Accessor Methods Traits.
 */
interface CodeGeneratorInterface
{
    /**
     * Write Trait to file in Generated folder
     * relative to the source file of the $class.
     * The trait will be in a sub namespace of the
     * one of $class.
     *
     * Will return true if a trait was created for
     * this class and false if there where no annotations
     * found an creating a trait was not needed.
     *
     * @see generateTraitForClass
     * @param ReflectionClass $class
     * @param Metadata $metadata
     * @return bool
     */
    public function writeTraitForClass(ReflectionClass $class, Metadata $metadata);

    /**
     * Return the PHP code for the accessor method
     * trait for $class. The trait will have a sub
     * namespace of Generated relative to the one of
     * $class.
     *
     * Will return an empty string if no code generation
     * was requested.
     *
     * @param ReflectionClass $class
     * @param Metadata $metadata
     * @return string
     */
    public function generateTraitForClass(ReflectionClass $class, Metadata $metadata);

    /**
     * Generate Accessor methods for property
     * The output will contain white separated
     * accessors and no trailing white space.
     *
     * @param PropertyInformationInterface $info
     * @return string
     */
    public function generateAccessors(PropertyInformationInterface $info);
}

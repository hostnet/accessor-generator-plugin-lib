<?php

namespace Hostnet\Component\AccessorGenerator\Generator;

use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformationInterface;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;

/**
 * Creates traits with accessor methods.
 */
interface CodeGeneratorInterface
{
    /**
     * Write Trait to file in Generated folder relative to the source file of
     * the {$class}. The trait will be in a sub namespace relative to the
     * namespace of {$class}.
     *
     * Returns true if a trait was created for this class or false if there
     * where no annotations found and/or creating a trait was not needed.
     *
     * @see generateTraitForClass
     * @param ReflectionClass $class
     * @return bool
     */
    public function writeTraitForClass(ReflectionClass $class);

    /**
     * Returns the generated PHP code for the accessor methods trait for
     * the given {$class}. The trait will have a subs-namespace with the name
     * "Generated" relative to the namespace of {$class}.
     *
     * Returns an empty string if no code generation was needed nor done.
     *
     * @param  ReflectionClass $class
     * @return string
     */
    public function generateTraitForClass(ReflectionClass $class);

    /**
     * Generate Accessor methods for property associated with the given
     * {$info}. The output will consist of generated code for the accessors
     * separated with line-breaks.
     *
     * @param  PropertyInformationInterface $info
     * @return string
     */
    public function generateAccessors(PropertyInformationInterface $info);

    /**
     * Expects an array of aliases, each alias can contain a public key file and/or a private key file.
     * These aliases are used to generate an encryption (setter) or decryption (getter) sequence
     * for the columns on which the aliases have been defined.
     *
     * ex. [{encryption_alias} => ['public-key' => {key_file}, 'private-key' => {key_file}], ...]
     *
     * @param array $encryption_aliases
     */
    public function setEncryptionAliases(array $encryption_aliases);
}

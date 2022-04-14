<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

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
     */
    public function writeTraitForClass(ReflectionClass $class): bool;

    /**
     * Writes one or more enumerator accessors.
     *
     * @param ReflectionClass $class
     */
    public function writeEnumeratorAccessorsForClass(ReflectionClass $class): array;

    /**
     * Returns the generated PHP code for the accessor methods trait for
     * the given {$class}. The trait will have a subs-namespace with the name
     * "Generated" relative to the namespace of {$class}.
     *
     * Returns an empty string if no code generation was needed nor done.
     *
     * @param ReflectionClass $class
     */
    public function generateTraitForClass(ReflectionClass $class): string;

    /**
     * Generate Accessor methods for property associated with the given
     * {$info}. The output will consist of generated code for the accessors
     * separated with line-breaks.
     *
     * @param PropertyInformationInterface $info
     */
    public function generateAccessors(PropertyInformationInterface $info): string;

    /**
     * Expects an array of aliases, each alias can contain a public key file and/or a private key file.
     * These aliases are used to generate an encryption (setter) or decryption (getter) sequence
     * for the columns on which the aliases have been defined.
     *
     * ex. [{encryption_alias} => ['public-key' => {key_file}, 'private-key' => {key_file}], ...]
     *
     * @param array $encryption_aliases
     */
    public function setEncryptionAliases(array $encryption_aliases): void;

    /**
     * Method to write KeyRegistry class(es), call this after all the Traits have been generated
     * for a package. These KeyRegistry classes contain the encryption aliases and corresponding
     * public/private key paths.
     */
    public function writeKeyRegistriesForPackage(): bool;
}

<?php
/**
 * @copyright 2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Enum;

/**
 * This interface must be implemented on the entity that holds parameter records used by generated enumerator accessors
 * to ensure the correct method signatures are used.
 *
 * The value column/property must be nullable.
 *
 * Since this library has no control over the type of the owning entity, this parameter does not implement a type hint
 * in the class constructor.
 */
interface EnumeratorCompatibleEntityInterface
{
    /**
     * @param object $owning_entity The instance of owning side of this entity.
     * @param string $name The name of the parameter
     * @param string|null $value The value of the parameter
     */
    public function __construct($owning_entity, string $name, ?string $value);

    public function getName(): string;

    public function getValue(): ?string;

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     *
     * @param string|null $value
     */
    public function setValue($value);
}

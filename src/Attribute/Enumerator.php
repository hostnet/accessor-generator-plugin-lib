<?php
/**
 * @copyright 2026-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Attribute;

/**
 * Declares an enum accessor to generate for a parameterized collection.
 *
 * Use this class with the native attribute syntax:
 *   #[Enumerator(value: SomeEnum::class)]
 *
 * When used standalone on a property, $property is set post-construction by AccessorGenerationProcessor
 * to the name of the property it was found on. When nested inside Generate, $name is resolved
 * post-construction by CodeGenerator if not explicitly provided.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Enumerator
{
    public function __construct(
        /**
         * References the Enum class for the parameter collection.
         */
        private readonly ?string $value = null,
        /**
         * References the name of the property that holds the parameter collection.
         */
        private ?string $name = null,
        /**
         * References the property to assign the enum accessor to.
         */
        private ?string $property = null,
        /**
         * Specifies the parameter entity that is used to instantiate new parameter instances.
         * This information is only required if the Enumerator annotation is used outside the Generator annotation.
         */
        private readonly ?string $type = null,
    ) {
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function setProperty(?string $property): void
    {
        $this->property = $property;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEnumeratorClass(): ?string
    {
        return $this->value;
    }

    public function getPropertyName(): ?string
    {
        return $this->property;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}

<?php
/**
 * @copyright 2026-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Attribute;

use Doctrine\Common\Annotations\Annotation\Enum;

/**
 * Activates accessor method generation for a property.
 *
 * Use this class with the native attribute syntax:
 *   #[Generate(set: 'none')]
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Generate
{
    /**
     * No method should be generated.
     */
    public const string VISIBILITY_NONE = 'none';

    /**
     * A public method should be generated.
     */
    public const string VISIBILITY_PUBLIC = 'public';

    /**
     * A protected method should be generated.
     */
    public const string VISIBILITY_PROTECTED = 'protected';

    /**
     * A private method should be generated.
     */
    public const string VISIBILITY_PRIVATE = 'private';

    public function __construct(
        /**
         * Will generate a getter of the given visibility.
         *
         * Default: public.
         *
         * @Enum({"public", "protected", "private", "none"})
         */
        private ?string $get = null,
        /**
         * Will generate relevant methods to fully modify the property.
         *
         * Normally this will result in setXxx, though in case of a OneToMany or
         * ManyToMany it will generate an addXxx and removeXxx. The latter can also
         * be individually controlled by setting the add / remove properties.
         *
         * Default: public.
         *
         * @Enum({"public", "protected", "private", "none"})
         */
        private ?string $set = null,
        /**
         * Will generate an adder in the case of a OneToMany or ManyToMany
         * relation. Might already be disabled with the set property.
         *
         * Default: public.
         *
         * @Enum({"public", "protected", "private", "none"})
         */
        private ?string $add = null,
        /**
         * Will generate a remover in the case of a OneToMany or ManyToMany
         * relation. Might already be disabled with the set property.
         *
         * Default: public.
         *
         * @Enum({"public", "protected", "private", "none"})
         */
        private ?string $remove = null,
        /**
         * Will generate a isXxx for a boolean property. Might already be disabled
         * with the get property.
         *
         * Default: public.
         *
         * @Enum({"public", "protected", "private", "none"})
         */
        private readonly string $is = self::VISIBILITY_PUBLIC,
        /**
         * List of enum classes to generate accessor classes for.
         *
         * @var Enumerator[]
         */
        private readonly array $enumerators = [],
        /**
         * Determine the type hint to use for the setter/adder/remover, and the
         * return type of the getter.
         *
         * Insert the fully qualified class name here.
         */
        private readonly ?string $type = null,
        /**
         * By default a lot of validation is added into the methods. This is
         * awesome.
         *
         * - The setters will ensure your object is never in an invalid state.
         * - The getters will assume your object is in a valid state, and throw
         *   exceptions otherwise.
         * - The constructor is up to you, though.
         *
         * Examples:
         * - A setter for a limited length varchar column validates that you don't
         *   insert a string that is too long.
         * - A getter for non-nullable column will validate that the current value
         *   is not null.
         *
         * Set this property to false if you want to disable it. Only do this
         * though, if you're ok with an *invalid* state of your object.
         */
        private readonly bool $strict = true,
        /**
         * Determine if the property should be stored encrypted.
         *
         * Insert the unique name that's used to map the key files to the property.
         */
        private readonly ?string $encryption_alias = null,
    ) {
    }

    public function getGet(): ?string
    {
        return $this->get;
    }

    public function getSet(): ?string
    {
        return $this->set;
    }

    public function getAdd(): ?string
    {
        return $this->add;
    }

    public function getRemove(): ?string
    {
        return $this->remove;
    }

    public function getIs(): ?string
    {
        return $this->is;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function isStrict(): bool
    {
        return $this->strict;
    }

    public function getEncryptionAlias(): ?string
    {
        return $this->encryption_alias;
    }

    /**
     * @return Enumerator[]
     */
    public function getEnumerators(): array
    {
        return $this->enumerators;
    }

    /**
     * Resolves the most limited visibility for method generation.
     *
     * If A defines public and B defines private, the returned visibility
     * modifier will be private. Precedence is as following:
     *  - none
     *  - private
     *  - protected
     *  - public
     *
     * @param array ...$requirements
     */
    public static function getMostLimitedVisibility(...$requirements): string
    {
        foreach ([self::VISIBILITY_NONE, self::VISIBILITY_PRIVATE, self::VISIBILITY_PROTECTED] as $search_string) {
            foreach ($requirements as $requirement) {
                if ($requirement === $search_string) {
                    return $search_string;
                }
            }
        }

        return self::VISIBILITY_PUBLIC;
    }

    /**
     * Sets the given visibility to all accessors if they are not explicitly defined.
     *
     * @param string $visibility
     */
    public function setDefaultVisibility(string $visibility): void
    {
        if (null === $this->get) {
            $this->get = $visibility;
        }

        if (null === $this->set) {
            $this->set = $visibility;
        }

        if (null === $this->add) {
            $this->add = $visibility;
        }

        if (null !== $this->remove) {
            return;
        }

        $this->remove = $visibility;
    }
}

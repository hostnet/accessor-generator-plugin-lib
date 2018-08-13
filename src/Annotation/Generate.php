<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Annotation;

use Doctrine\Common\Annotations\Annotation\Enum;

/**
 * Annotation to activate accessor method generation for a property. You can
 * disable generation of certain methods by setting them to false in your
 * notation.
 *
 * The annotation is designed to be used with doctrine/annotations.
 *
 * @Annotation
 * @Target("PROPERTY")
 * @see http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html
 */
class Generate
{
    /**
     * No method should be generated.
     *
     * @var string
     */
    public const VISIBILITY_NONE = 'none';

    /**
     * A public method should be generated.
     *
     * @var string
     */
    public const VISIBILITY_PUBLIC = 'public';

    /**
     * A protected method should be generated.
     *
     * @var string
     */
    public const VISIBILITY_PROTECTED = 'protected';

    /**
     * A private method should be generated.
     *
     * @var string
     */
    public const VISIBILITY_PRIVATE = 'private';

    /**
     * Will generate a getter of the given visibility.
     *
     * Default: public.
     * True and false are available, though deprecated.
     *
     * @Enum({"public", "protected", "private", "none", true, false})
     */
    public $get;

    /**
     * Will generate relevant methods to fully modify the property.
     *
     * Normally this will result in setXxx, though in case of a OneToMany or
     * ManyToMany it will generate an addXxx and removeXxx. The latter can also
     * be individually controlled by setting the add / remove properties.
     *
     * Default: public.
     * True and false are available, though deprecated.
     *
     * @Enum({"public", "protected", "private", "none", true, false})
     */
    public $set;

    /**
     * Will generate an adder in the case of a OneToMany or ManyToMany
     * relation. Might already be disabled with the set property.
     *
     * Default: public.
     * True and false are available, though deprecated.
     *
     * @Enum({"public", "protected", "private", "none", true, false})
     */
    public $add;

    /**
     * Will generate a remover in the case of a OneToMany or ManyToMany
     * relation. Might already be disabled with the set property.
     *
     * Default: public.
     * True and false are available, though deprecated.
     *
     * @Enum({"public", "protected", "private", "none", true, false})
     */
    public $remove;

    /**
     * Will generate a isXxx for a boolean property. Might already be disabled
     * with the get property.
     *
     * Default: public.
     * True and false are available, though deprecated.
     *
     * @Enum({"public", "protected", "private", "none", true, false})
     */
    public $is = self::VISIBILITY_PUBLIC;

    /**
     * List of enum classes to generate accessor classes for.
     *
     * @var \Hostnet\Component\AccessorGenerator\Annotation\Enumerator[]
     */
    public $enumerators = [];

    /**
     * Determine the type hint to use for the setter/adder/remover, and the
     * return type of the getter.
     *
     * Insert the fully qualified class name here.
     */
    public $type;

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
     *
     * @var bool
     */
    public $strict = true;

    /**
     * Determine if the property should be stored encrypted.
     *
     * Insert the unique name that's used to map the key files to the property.
     */
    public $encryption_alias;

    /**
     * @return string|bool|null
     */
    public function getGet()
    {
        return $this->castToSupportedValue($this->get);
    }

    /**
     * @return string|bool|null
     */
    public function getSet()
    {
        return $this->castToSupportedValue($this->set);
    }

    /**
     * @return string|bool|null
     */
    public function getAdd()
    {
        return $this->castToSupportedValue($this->add);
    }

    /**
     * @return string|bool|null
     */
    public function getRemove()
    {
        return $this->castToSupportedValue($this->remove);
    }

    /**
     * @return string|bool|null
     */
    public function getIs()
    {
        return $this->castToSupportedValue($this->is);
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
     * Cast legacy values to their string representative.
     *
     * @param mixed $value
     *
     * @return string|null
     */
    private function castToSupportedValue($value)
    {
        if ((bool) $value === $value) {
            @trigger_error(
                'Using a boolean for the visibility is deprecated. Use none, private, protected or public instead.',
                E_USER_DEPRECATED
            );
        }

        if ($value === true) {
            return self::VISIBILITY_PUBLIC;
        }

        if ($value === false) {
            return self::VISIBILITY_NONE;
        }

        return $value;
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
     *
     * @return string
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

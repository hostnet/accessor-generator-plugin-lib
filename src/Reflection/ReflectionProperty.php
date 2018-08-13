<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Reflection;

/**
 * Representation of a class property.
 * Full member of ReflectionClass.
 */
class ReflectionProperty
{
    private $modifiers;

    /**
     * @var string|null
     */
    private $doc_comment;

    /**
     * @var string|null
     */
    private $default;

    /**
     * @var ReflectionClass
     */
    private $class;

    /**
     * @var string
     */
    private $name;

    public function __construct(
        string $name,
        ?int $modifiers = null,
        ?string $default = null,
        ?string $doc_comment = null,
        ?ReflectionClass $class = null
    ) {
        $this->name        = $name;
        $this->default     = $default;
        $this->doc_comment = $doc_comment;
        $this->class       = $class;
        $this->setModifiers($modifiers);
    }

    /**
     * Check modifiers for right type and to have at least a visibility bit
     * enabled. Also turns null into private visibility.
     *
     * @throws \InvalidArgumentException
     * @throws \DomainException
     *
     * @param int|null $modifiers
     */
    private function setModifiers(?int $modifiers): void
    {
        // Default to private.
        if ($modifiers === null) {
            $this->modifiers = \ReflectionProperty::IS_PRIVATE;

            return;
        }

        // Get the number of active visibility modifiers amount all modifies.
        $active_visibility_modifiers =
            ((bool) ($modifiers & \ReflectionProperty::IS_PRIVATE)) +
            ((bool) ($modifiers & \ReflectionProperty::IS_PROTECTED)) +
            ((bool) ($modifiers & \ReflectionProperty::IS_PUBLIC));

        // Not one and only one of private, protected and public is selected, throw exception.
        if ($active_visibility_modifiers !== 1) {
            throw new \DomainException(sprintf(
                '$modifiers (%s) has not ONE of IS_PRIVATE, IS_PROTECTED or IS_PUBLIC set, but found %s.',
                $modifiers,
                $active_visibility_modifiers
            ));
        }

        $this->modifiers = $modifiers;
    }

    /**
     * Return the Class or Trait that this property belongs to.
     *
     * @return ReflectionClass
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Get the raw doc_comment as string.
     *
     * @return string|null
     */
    public function getDocComment(): ?string
    {
        return $this->doc_comment;
    }

    /**
     * Get the name of the property. The name is returned without the $-prefix.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns true if this property is static, false otherwise.
     *
     * @return bool
     */
    public function isStatic(): bool
    {
        return (bool) ($this->modifiers & \ReflectionProperty::IS_STATIC);
    }

    /**
     * Returns true if this property is private, false otherwise. If true,
     * isProtected and isPublic will return false.
     *
     * @return bool
     */
    public function isPrivate(): bool
    {
        return (bool) ($this->modifiers & \ReflectionProperty::IS_PRIVATE);
    }

    /**
     * Returns true if this property is protected, false otherwise.  If true,
     * isPrivate and isPublic will return false.
     *
     * @return bool
     */
    public function isProtected(): bool
    {
        return (bool) ($this->modifiers & \ReflectionProperty::IS_PROTECTED);
    }

    /**
     * Returns true if this property is public, false otherwise. If true,
     * isPrivate and isProtected will return false.
     *
     * @return bool
     */
    public function isPublic(): bool
    {
        return (bool) ($this->modifiers & \ReflectionProperty::IS_PUBLIC);
    }

    /**
     * Return the default assigned value of this property. For example for
     * private $foo = 'bar'.
     *
     * It will return 'bar' including the quotes. It will return null if no
     * default value was defined.
     *
     * @return string|null
     */
    public function getDefault(): ?string
    {
        return $this->default;
    }

    /**
     * Valid PHP string representation of how the parser understood the
     * property that was parsed.
     *
     * This can result in invalid PHP if the parsed code did not pass linting
     * (php -l).
     *
     * The string returned includes multiple lines if needed and no trailing
     * whitespace that was not there in the original code (doc comment).
     *
     * @return string
     */
    public function __toString()
    {
        $text = '';

        // Doc comment
        $doc = $this->getDocComment();
        if ($doc) {
            $text .= $doc . PHP_EOL;
        }

        // Visibility
        if ($this->isPrivate()) {
            $text .= 'private ';
        } elseif ($this->isProtected()) {
            $text .= 'protected ';
        } elseif ($this->isPublic()) {
            $text .= 'public ';
        }

        // Static
        if ($this->isStatic()) {
            $text .= 'static ';
        }

        // Name
        $text .= '$' . $this->getName();

        if ($this->getDefault()) {
            $text .= ' = ' . $this->getDefault();
        }

        return $text . ';';
    }
}

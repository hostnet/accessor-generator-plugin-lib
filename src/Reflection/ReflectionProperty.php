<?php
namespace Hostnet\Component\AccessorGenerator\Reflection;

/**
 * Representation of a class property.
 * Full member of ReflectionClass.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class ReflectionProperty
{
    const IS_PRIVATE   = \ReflectionProperty::IS_PRIVATE;
    const IS_PROTECTED = \ReflectionProperty::IS_PROTECTED;
    const IS_PUBLIC    = \ReflectionProperty::IS_PUBLIC;
    const IS_STATIC    = \ReflectionProperty::IS_STATIC;

    private $modifiers   = null;
    private $doc_comment = null;
    private $default     = null;
    private $class       = null;
    private $name        = '';

    /**
     *
     * @param string          $name
     * @param int             $modifiers
     * @param string          $default
     * @param string          $doc_comment
     * @param ReflectionClass $class
     */
    public function __construct(
        $name,
        $modifiers = null,
        $default = null,
        $doc_comment = null,
        ReflectionClass $class = null
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
     * @param int $modifiers
     */
    private function setModifiers($modifiers)
    {
        // Default to private.
        if ($modifiers === null) {
            $this->modifiers = self::IS_PRIVATE;

            return;
        }

        // Invalid type used for modifiers.
        if (!is_int($modifiers)) {
            throw new \InvalidArgumentException(sprintf('$modifiers (%s) is not a valid bit.', $modifiers));
        }

        // Get the number of active visibility modifiers amount all modifies.
        $active_visibility_modifiers =
            ((bool) ($modifiers & self::IS_PRIVATE)) +
            ((bool) ($modifiers & self::IS_PROTECTED)) +
            ((bool) ($modifiers & self::IS_PUBLIC));

        // Not one and only one of private, protected and public is selected, throw exception.
        if ($active_visibility_modifiers !== 1) {
            throw new \DomainException(
                sprintf(
                    '$modifiers (%s) has not ONE of IS_PRIVATE, IS_PROTECTED or IS_PUBLIC set, but found %s.',
                    $modifiers,
                    $active_visibility_modifiers
                )
            );
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
     * @return string
     */
    public function getDocComment()
    {
        return $this->doc_comment;
    }

    /**
     * Get the name of the property. The name is returned without the $-prefix.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns true if this property is static, false otherwise.
     *
     * @return boolean
     */
    public function isStatic()
    {
        return (bool) ($this->modifiers & self::IS_STATIC);
    }

    /**
     * Returns true if this property is private, false otherwise. If true,
     * isProtected and isPublic will return false.
     *
     * @return boolean
     */
    public function isPrivate()
    {
        return (bool) ($this->modifiers & self::IS_PRIVATE);
    }

    /**
     * Returns true if this property is protected, false otherwise.  If true,
     * isPrivate and isPublic will return false.
     *
     * @return boolean
     */
    public function isProtected()
    {
        return (bool) ($this->modifiers & self::IS_PROTECTED);
    }

    /**
     * Returns true if this property is public, false otherwise. If true,
     * isPrivate and isProtected will return false.
     *
     * @return boolean
     */
    public function isPublic()
    {
        return (bool) ($this->modifiers & self::IS_PUBLIC);
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
    public function getDefault()
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

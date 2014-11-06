<?php
namespace Hostnet\Component\AccessorGenerator\Reflection;

use Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException;
use Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException;

/**
 * Parse PHP files containing classes that are
 * valid PHP (php -l) but are not (yet) valid
 * PHP because some interfaces or other hierargy
 * requirements are not fullfilled yet.
 *
 * This way we can generate code that will implement
 * an interace.
 *
 * @author Hidde Booomsma <hboomsma@hostnet.nl>
 */
class ReflectionClass
{
    /**
     * Filename of the file to parse.
     * @var string
     */
    private $filename;

    /**
     * The parsed token stream,
     * the normal PHP token stream
     * is a bit too raw.
     *
     * @var TokenStream
     */
    private $tokens;

    /**
     * Parsed name of the class.
     * This variable is lazy loaded.
     *
     * @var string
     */
    private $name = null;

    /**
     * Parsed name of the namespace.
     * This variable is lazy loaded.
     *
     * @var string
     */
    private $namespace = null;

    /**
     * All the properties defined within
     * the class inside the pares file.
     * @var ReflectionProperty[]
     */
    private $properties = null;

    /**
     * Al the use statements.
     * Not the traits but the class namespaces.
     * @var string[]
     */
    private $use_statements = null;

    /**
     * Location where the class name is found.
     * Used to prefend duplicate code for finding
     * the class name and location.
     *
     * @var int
     */
    private $class_location = null;

    /**
     * Create a reflection class for a class contained in
     * a filename. This can not be loaded class because than
     * it can not be invalid PHP any longer. This will NOT
     * load the class from $filename into memory.
     *
     * @param  string        $filename valid readable filename
     * @throws FileException
     */
    public function __construct($filename)
    {
        $this->filename = $filename;

        // Check if file exists
        if (! file_exists($filename)) {
            throw new FileException("File \"$filename\" does nog exist.");
        }

        // Check if file is readable
        if (! is_readable($filename)) {
            throw new FileException("File \"$filename\" is not readable.");
        }

        $this->tokens = new TokenStream(file_get_contents($filename));
    }

    /**
     * Filename of the parsed file
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Return the name of the class inside this file.
     * This is the simple class name and not the fully
     * qualified class name.
     */
    public function getName()
    {
        // Check cache.
        if ($this->name === null) {
             // Find class token
            $loc = $this->tokens->scan(0, [T_CLASS, T_TRAIT]);

            // Get the following token
            if ($loc !== null) {
                $loc = $this->tokens->next($loc);
            }

            // Make sure it is not :: but a name
            if ($loc !== null && $this->tokens->type($loc) === T_STRING) {
                // Read the name from the token
                $this->name           = $this->tokens->value($loc);
                $this->class_location = $loc;
            } else {
                // Mark the name as NOT found (in constrast to not initialized)
                $this->name = false;
            }
        }

        // Return the name if a class was found or throw an exception.
        if ($this->name) {
            return $this->name;
        } else {
            throw new ClassDefinitionNotFoundException('No class is found inside ' . $this->filename  . '.');
        }
    }

    /**
     * Get the namespace of the file.
     * If there is no namespace this will
     * return '' and not \ as the root
     * namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        // Check cache.
        if ($this->namespace === null) {
            // Find namespace token
            $loc = $this->tokens->scan(0, [T_NAMESPACE]);

            // Get the next token (start with namespace)
            if ($loc !== null) {
                $loc = $this->tokens->next($loc);
            }

            // If the start of the namespace is found,
            // parse it, otherwise save empty namespace.
            if ($loc !== null) {
                $this->namespace = $this->parseNamespace($loc);
            } else {
                $this->namespace = '';
            }
        }

        return $this->namespace;
    }

    /**
     * Returns an array with key values.
     * where the key is used as alias.
     *
     * @return string[] key is numeric when no alias is uses
     *                  and string if an alias is used.
     */
    public function getUseStatements()
    {
        // Check cache.
        if ($this->use_statements === null) {
            $this->use_statements = [];

            // Fetch start of class so we do not
            // include use statments that import traits.
            $class = $this->getClassNameLocation();

            // Find all the use statments and parse them one by one
            // and then add them to the use_statements cache.
            $loc = 0;
            while (($loc = $this->tokens->scan($loc, [T_USE])) && $loc < $class) {
                $this->use_statements = array_merge($this->use_statements, $this->parseUse($loc++));
            }
        }

        return $this->use_statements;
    }

    /**
     * Return all private, protected and public properties
     * for this class. Properties declared with var are not
     * provided as var is depricated.
     *
     * Only declared properties are returned. Properties created
     * at runtime are not taken into concideration.
     *
     * @throws ClassDefinitionNotFoundException
     * @return ReflectionProperty[]
     */
    public function getProperties()
    {
        // Check cache
        if ($this->properties === null) {
            // Create empty set, to denote that
            // we parsed all the properties
            $this->properties = [];

            // Start parsing from the class name location
            // and trigger ClassDefinitionNotFoundException when called
            // on a file not containing a class.
            $vis_loc = $this->getClassNameLocation();

            // Scan for public, protected and private because
            // these keywords denote the start of a property.
            // var is excluded because its use is depricated.
            while ($vis_loc = $this->tokens->scan(
                $vis_loc,
                [T_PRIVATE, T_PROTECTED, T_PUBLIC, T_VAR]
            )) {
                // Seek forward, skipping static, if it was found and check if we
                // really have a property here, otherwise contine and try to find more
                // properties.

                // We also skip final, to improve error handling and consistent behaviour,
                // otherswise final private $foo whould be parsed and private final $bar
                // would not be parsed.
                $var_loc = $this->tokens->next($vis_loc, [T_COMMENT, T_WHITESPACE, T_STATIC, T_FINAL]);
                if ($this->tokens->type($var_loc) === T_VARIABLE) {
                    $doc_comment = $this->parseDocComment($vis_loc);        // doc comment
                    $modifiers   = $this->parsePropertyModifiers($vis_loc); // public, protected, private, static
                    $name        = substr($this->tokens->value($var_loc), 1);  // property name
                    $default     = $this->parseDefaultValue($var_loc);      // default value
                    $property    = new ReflectionProperty($name, $modifiers, $default, $doc_comment, $this);

                    $this->properties[] = $property;
                }
            }
        }

        return $this->properties;
    }

    /**
     * Return the location of the class name token.
     *
     * @throws ClassDefinitionNotFoundException
     * @return int location of the class name token (T_STRING)
     */
    private function getClassNameLocation()
    {
        if ($this->class_location === null) {
            // call getName only for the side-effect of saving
            // the class name location, for performance we also
            // cache the name now we already no where it is.
            $this->getName();
        }

        return $this->class_location;
    }

    /**
     *
     * @param  int      $loc location of the T_USE token
     * @return string[] key as alias and value as the namespace
     */
    private function parseUse($loc)
    {
        // Parse FQCN
        $loc   = $this->tokens->next($loc);
        $use   = $this->parseNamespace($loc);
        $alias = 0; // default array index of PHP

        // Parse alias
        $loc = $this->tokens->next($loc, [T_NS_SEPARATOR, T_STRING, T_COMMENT, T_WHITESPACE]);
        if ($this->tokens->type($loc) == T_AS) {
            $loc   = $this->tokens->next($loc);
            $alias = $this->parseNamespace($loc);
        }

        return [$alias => $use];
    }

    /**
     * Parse the namespace and return as string
     *
     * @param  int    $loc location of the first namespace token (T_STRING)
     *                and not the T_NAMESPACE, T_AS or T_USE.
     * @return string
     */
    private function parseNamespace($loc)
    {
        $ns = '';
        while (in_array($this->tokens->type($loc), [T_NS_SEPARATOR, T_STRING])) {
            $ns .= $this->tokens->value($loc);
            $loc = $this->tokens->next($loc);
        }

        return $ns;
    }

    /**
     * Returns the doc comment for a property, function or class
     * The comment is stripped of indentation. Returns empty string
     * when no doc comment of an empty doc comment was found.
     *
     * @param $loc location of the visibility modifier or T_CLASS
     * @return string the contents of the doc comment
     */
    private function parseDocComment($loc)
    {
        // Look back from T_PUBLIC, T_PROTECTED, T_PRIVATE or T_CLASS
        // for the T_DOC_COMMENT token
        $loc = $this->tokens->previous($loc, [T_WHITESPACE, T_STATIC, T_FINAL]);

        // Check for doc comment
        if ($loc && $this->tokens->type($loc) == T_DOC_COMMENT) {
            $doc_comment = $this->tokens->value($loc);
            // strip off indentation
            $doc_comment = preg_replace('/^[ \t]*\*/m', ' *', $doc_comment);

            return $doc_comment;
        } else {
            return '';
        }
    }

    /**
     * Parse visibility and static modifier of the property
     * in to a bitfield combining all the modifiers as is
     * done in by PHP Reflection for the \ReflectionProperty.
     *
     * Note that properties can not be final and thus this
     * function does not scan for T_FINAL.
     *
     * @see \ReflectionProperty
     * @param  int $loc location of the visibility modifier
     * @return int
     */
    private function parsePropertyModifiers($loc)
    {
        $modifiers = 0; // initialize bit filed with all modifiers swithed off

        // Enable visibility bits
        switch ($this->tokens->type($loc)) {
            case T_PRIVATE:
                $modifiers |= \ReflectionProperty::IS_PRIVATE;
                break;
            case T_PROTECTED:
                $modifiers |= \ReflectionProperty::IS_PROTECTED;
                break;
            case T_VAR:
            case T_PUBLIC:
                $modifiers |= \ReflectionProperty::IS_PUBLIC;
                break;
        }

        // Look forward and backward for STATIC modifier
        $prev = $this->tokens->previous($loc);
        $next = $this->tokens->next($loc);

        // If found write the bits
        if ($this->tokens->type($prev) == T_STATIC || $this->tokens->type($next) == T_STATIC) {
            $modifiers |= \ReflectionProperty::IS_STATIC;
        }

        return $modifiers;
    }

    /**
     * Parse the default value assignment for a property
     * and return the default value or null if there is
     * no default value assigned.
     *
     * The returned value includes
     * single or double quotes as used in the code. This
     * way we can keep those and this also enables us to
     * parse a default value of null.
     *
     * Does not support HERE DOC;
     *
     * @param  int         $loc location of the property name (T_STRING)
     * @return null|string will return null if there is no default
     *                     value, string if there is
     */
    private function parseDefaultValue($loc)
    {
        $default = '';
        $loc     = $this->tokens->next($loc);

        if ($this->tokens->value($loc) == '=') {
            $loc  = $this->tokens->next($loc);
            $type = $this->tokens->type($loc);
            if (in_array($type, [T_DNUMBER, T_LNUMBER, T_CONSTANT_ENCAPSED_STRING])) {
                $default = $this->tokens->value($loc);
            } elseif (in_array($type, [T_START_HEREDOC, T_START_NOWDOC])) {
                $default = $this->parseHereNowDocConcat($loc);
            }
        }

        return $default;
    }

    /**
     * Parse heredoc and nowdoc into a concatenated
     * string representation to be usefull for default
     * values and inline assignment.
     *
     * @param int $loc
     * @return string|null
     */
    private function parseHereNowDocConcat($loc)
    {
        $type = substr($this->tokens->value($loc), 3, 1);
        $loc  = $this->tokens->next($loc);

        if ($loc) {
            $string = substr($this->tokens->value($loc), 0, -1);
            if ($type === '\'') {
                return '\'' . implode('\' . "\n" . \'', explode("\n", $string)) . '\'';
            } else {
                return '"' . str_replace("\n", '\n', $string) . '"';
            }
        }
        return null;
    }
}

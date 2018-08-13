<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Reflection;

use Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException;
use Hostnet\Component\AccessorGenerator\Reflection\Exception\FileException;

/**
 * Parse PHP files containing classes that are valid PHP (php -l) but are not
 * (yet) valid PHP because some interfaces or other hierarchy requirements are
 * not fulfilled yet.
 *
 * This way we can generate code that will later implement an interface.
 */
class ReflectionClass
{
    /**
     * Name of the file to parse.
     *
     * @var string
     */
    private $filename;

    /**
     * The parsed token stream.
     * The normal PHP token stream is a bit too raw.
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
     * All the properties declared within the class inside the parsed file.
     *
     * @var ReflectionProperty[]
     */
    private $properties = null;

    /**
     * A list of all imports (use statements).
     *
     * @var string[]
     */
    private $use_statements = null;

    /**
     * Location where the class name is found. Used to prevent duplicate code
     * for finding the class name and location.
     *
     * @var int
     */
    private $class_location = null;

    /**
     * @var \ReflectionClassConstant[]
     */
    private $constants;

    /**
     * Create a reflection class for a class contained in the given file. The
     * class should not be already loaded into memory previously, because this
     * implementation of ReflectionClass assumes that the class contains
     * invalid PHP due to missing implementations from an interface.
     *
     * @param string $filename valid readable filename
     * @throws FileException
     */
    public function __construct($filename)
    {
        $this->filename = $filename;

        // Check if file exists
        if (! file_exists($filename)) {
            throw new FileException("File \"$filename\" does not exist.");
        }

        // Check if file is readable
        if (! is_readable($filename)) {
            throw new FileException("File \"$filename\" is not readable.");
        }
    }

    /**
     * Returns the name of the parsed file.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Returns the name of the class, inside this file.
     *
     * This is the simple class name and not the fully
     * qualified class name.
     *
     * @throws Exception\ClassDefinitionNotFoundException
     * @throws \OutOfBoundsException
     *
     * @return string
     */
    public function getName()
    {
        // Check cache.
        if ($this->name === null) {
            $tokens = $this->getTokenStream();
            // Find class token
            try {
                $loc = $tokens->scan(0, [T_CLASS, T_TRAIT]);
            } catch (\OutOfBoundsException $e) {
                throw new ClassDefinitionNotFoundException('No class is found inside ' . $this->filename . '.', 0, $e);
            }

            // Get the following token
            if ($loc !== null) {
                $loc = $tokens->next($loc);
            }

            // Make sure it is not :: but a name
            if ($loc !== null && $tokens->type($loc) === T_STRING) {
                // Read the name from the token
                $this->name           = $tokens->value($loc);
                $this->class_location = $loc;
            } else {
                // Mark the name as NOT found (in contrast to not initialized)
                $this->name = false;
            }
        }

        // Return the name if a class was found or throw an exception.
        if ($this->name) {
            return $this->name;
        }

        throw new ClassDefinitionNotFoundException('No class is found inside ' . $this->filename . '.');
    }

    /**
     * Returns the namespace of the class in this file or an empty string if no
     * namespace was declared.
     *
     * @return string
     */
    public function getNamespace()
    {
        // Check cache.
        if ($this->namespace === null) {
            $tokens = $this->getTokenStream();
            // Find namespace token
            try {
                $loc = $tokens->scan(0, [T_NAMESPACE]);
            } catch (\OutOfBoundsException $e) {
                return $this->namespace = '';
            }

            // Get the next token (start with namespace)
            if ($loc !== null) {
                $loc = $tokens->next($loc);
            }

            // If the start of the namespace is found,
            // parse it, otherwise save empty namespace.
            $this->namespace = '';
            if ($loc !== null) {
                $this->namespace = $this->parseNamespace($loc);
            }
        }

        return $this->namespace;
    }

    /**
     * Returns the fully qualified class name, thus including the full
     * namespace, for the class in this file.
     *
     * @throws \OutOfBoundsException
     * @throws Exception\ClassDefinitionNotFoundException
     *
     * @return string
     */
    public function getFullyQualifiedClassName()
    {
        return $this->getNamespace() . '\\' . $this->getName();
    }

    /**
     * Returns an associative array of class imports.
     * If aliases are used in the file, the alias names are used as keys.
     *
     * @throws \OutOfBoundsException
     * @throws Exception\ClassDefinitionNotFoundException
     *
     * @return string[]
     */
    public function getUseStatements()
    {
        // Check cache.
        if ($this->use_statements === null) {
            $this->use_statements = [];

            // Fetch start of class so we do not
            // include use statements that import traits.
            $tokens = $this->getTokenStream();
            $class  = $this->getClassNameLocation();

            // Find all the use statements and parse them one by one
            // and then add them to the use_statements cache.
            $loc = 0;
            while (($loc = $tokens->scan($loc, [T_USE])) && $loc < $class) {
                list($alias, $use_statement) = $this->parseUse($loc++);
                if ($alias !== null) {
                    $this->use_statements[$alias] = $use_statement;
                } else {
                    $this->use_statements[] = $use_statement;
                }
            }
        }

        return $this->use_statements;
    }

    /**
     * Returns an associative array of class imports.
     * If aliases are used in the file, the alias names are used as keys.
     *
     * @throws \OutOfBoundsException
     * @throws Exception\ClassDefinitionNotFoundException
     *
     * @return string[]
     */
    public function getConstants()
    {
        // Check cache.
        if ($this->constants === null) {
            $tokens          = $this->getTokenStream();
            $this->constants = [];

            // Fetch start of class so we do not
            // include use statements that import traits.
            $class = $this->getClassNameLocation();

            // Find all the use statements and parse them one by one
            // and then add them to the use_statements cache.
            $loc = 0;
            while (($loc = $tokens->scan($loc, [T_CONST])) && $loc < $class) {
                $this->constants[] = $this->parseConst($loc++);
            }
        }

        return $this->constants;
    }

    /**
     * Returns all private, protected and public properties for this class.
     * Properties declared with var are not provided as var declarations are
     * deprecated.
     *
     * Only declared properties are returned. Properties created at runtime
     * are not taken into consideration.
     *
     * @throws ClassDefinitionNotFoundException
     * @return ReflectionProperty[]
     */
    public function getProperties()
    {
        // Check cache
        if ($this->properties === null) {
            $tokens = $this->getTokenStream();

            // Create empty set, to denote that
            // we parsed all the properties
            $this->properties = [];

            // Start parsing from the class name location
            // and trigger ClassDefinitionNotFoundException when called
            // on a file not containing a class.
            $vis_loc = $this->getClassNameLocation();

            // Scan for public, protected and private because
            // these keywords denote the start of a property.
            // var is excluded because its use is deprecated.
            while ($vis_loc = $tokens->scan(
                $vis_loc,
                [T_PRIVATE, T_PROTECTED, T_PUBLIC, T_VAR]
            )) {
                // Seek forward, skipping static, if it was found and check if we
                // really have a property here, otherwise confine and try to find more
                // properties.

                // We also skip final, to improve error handling and consistent behaviour,
                // otherwise final private $foo would be parsed and private final $bar
                // would not be parsed.
                $var_loc = $tokens->next($vis_loc, [T_COMMENT, T_WHITESPACE, T_STATIC, T_FINAL]);
                if ($tokens->type($var_loc) !== T_VARIABLE) {
                    continue;
                }

                $doc_comment = $this->parseDocComment($vis_loc);           // doc comment
                $modifiers   = $this->parsePropertyModifiers($vis_loc);    // public, protected, private, static
                $name        = substr($tokens->value($var_loc), 1);  // property name
                $default     = $this->parseDefaultValue($var_loc);         // default value
                $property    = new ReflectionProperty($name, $modifiers, $default, $doc_comment, $this);

                $this->properties[] = $property;
            }
        }

        return $this->properties;
    }

    /**
     * Returns the location of the class name token.
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
     * @param int $loc location of the T_USE token
     * @return array|null   [string|null $alias, string $namespace]
     * @throws \OutOfBoundsException
     */
    private function parseUse($loc)
    {
        // Parse FQCN
        $tokens = $this->getTokenStream();
        $loc    = $tokens->next($loc);
        $use    = $this->parseNamespace($loc);
        $alias  = null; // default array index of PHP

        // Parse alias
        $loc = $tokens->next($loc, [T_NS_SEPARATOR, T_STRING, T_COMMENT, T_WHITESPACE]);
        if ($tokens->type($loc) == T_AS) {
            $loc   = $tokens->next($loc);
            $alias = $this->parseNamespace($loc);
        }

        return [$alias, $use];
    }

    /**
     * Parse the namespace and return as string
     *
     * @param int $loc location of the first namespace token (T_STRING)
     *                  and not the T_NAMESPACE, T_AS or T_USE.
     * @return string
     */
    private function parseNamespace($loc)
    {
        $tokens = $this->getTokenStream();
        $ns     = '';

        if (\in_array($this->tokens->type($loc), [T_FUNCTION, T_CONST])) {
            $ns .= $this->tokens->value($loc) . ' ';
            $loc = $tokens->next($loc);
        }

        while (\in_array($tokens->type($loc), [T_NS_SEPARATOR, T_STRING])) {
            $ns .= $tokens->value($loc);
            $loc = $tokens->next($loc);
        }

        return $ns;
    }

    /**
     * Returns the doc comment for a property, method or class. The comment is
     * stripped of leading whitespaces. Returns an empty string if no doc-
     * comment or an empty doc comment was found.
     *
     * @param int $loc location of the visibility modifier or T_CLASS
     * @return string      the contents of the doc comment
     */
    private function parseDocComment($loc)
    {
        $tokens = $this->getTokenStream();

        // Look back from T_PUBLIC, T_PROTECTED, T_PRIVATE or T_CLASS
        // for the T_DOC_COMMENT token
        $loc = $tokens->previous($loc, [T_WHITESPACE, T_STATIC, T_FINAL]);

        // Check for doc comment
        if ($loc && $tokens->type($loc) == T_DOC_COMMENT) {
            $doc_comment = $tokens->value($loc);
            // strip off indentation
            $doc_comment = preg_replace('/^[ \t]*\*/m', ' *', $doc_comment);

            return $doc_comment;
        }

        return '';
    }

    /**
     * Parse visibility and static modifier of the property in to a bit field
     * combining all the modifiers as is done in by PHP Reflection for the
     * \ReflectionProperty.
     *
     * Note that properties can not be final and thus this function does not
     * scan for T_FINAL.
     *
     * @see \ReflectionProperty
     * @param int $loc location of the visibility modifier
     * @return int
     */
    private function parsePropertyModifiers($loc)
    {
        $tokens    = $this->getTokenStream();
        $modifiers = 0; // initialize bit filed with all modifiers switched off

        // Enable visibility bits
        switch ($tokens->type($loc)) {
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
        $prev = $tokens->previous($loc);
        $next = $tokens->next($loc);

        // If found write the bits
        if ($tokens->type($prev) == T_STATIC || $tokens->type($next) == T_STATIC) {
            $modifiers |= \ReflectionProperty::IS_STATIC;
        }

        return $modifiers;
    }

    /**
     * Parses the default value assignment for a property and returns the
     * default value or null if there is no default value assigned.
     *
     * The returned value includes single or double quotes as used in the code.
     * This way we can keep those and this also enables us to parse a default
     * value of null.
     *
     * @param int $loc location of the property name (T_STRING)
     * @return string|null Null if there is no default value, string otherwise
     */
    private function parseDefaultValue($loc)
    {
        $tokens  = $this->getTokenStream();
        $default = '';
        $loc     = $tokens->next($loc);

        if ($tokens->value($loc) == '=') {
            $loc  = $tokens->next($loc);
            $type = $tokens->type($loc);

            if (\in_array($type, [T_DNUMBER, T_LNUMBER, T_CONSTANT_ENCAPSED_STRING])) {
                // Easy numbers and strings.
                $default = $tokens->value($loc);
            } elseif (\in_array($type, [T_STRING, T_NS_SEPARATOR])) {
                // Constants, definitions and null
                $default = $this->parseNamespace($loc);
                $loc     = $tokens->next($loc, [T_WHITESPACE, T_COMMENT, T_STRING, T_NS_SEPARATOR]);
                if ($tokens->type($loc) == T_PAAMAYIM_NEKUDOTAYIM) {
                    $loc      = $tokens->next($loc);
                    $default .= '::' . $tokens->value($loc);
                }
            } elseif (\in_array($type, [T_ARRAY, '['])) {
                // Array types, both old array() and shorthand [] notation.
                $default = $this->parseArrayDefinition($loc);
            } elseif ($type === T_START_HEREDOC) {
                // Heredoc and Nowdoc
                $default = $this->parseHereNowDocConcat($loc);
            }
        }

        return $default;
    }

    /**
     * Parse an array definition. The definition can contain arrays itself. The
     * whole content of the array definition is stripped from comments and
     * excessive whitespaces.
     *
     * @param int $loc location of the token stream where the array starts.
     *                  This should point to a T_ARRAY or [ token.
     * @return string   code representation of the parsed array without any
     *                  comments or excessive whitespace.
     */
    private function parseArrayDefinition($loc)
    {
        $tokens = $this->getTokenStream();
        $found  = 0;
        $brace  = 0;
        $code   = '';
        do {
            $type = $tokens->type($loc);
            switch ($type) {
                case T_ARRAY:
                    $loc = $tokens->scan($loc, ['(']);
                    $brace++;
                // intentional fallthrough
                case '[':
                    $code .= '[';
                    $found++;
                    break;
                case '(':
                    $brace++;
                    $code .= '(';
                    break;
                case ']':
                    $found--;
                    $code .= ']';
                    break;
                case ')':
                    if (--$brace === 0) {
                        $found--;
                        $code .= ']';
                    } else {
                        $code .= ')';
                    }
                    break;
                default:
                    $code .= $this->arrayWhitespace($loc);
            }
        } while ($found > 0 && ($loc = $tokens->next($loc)));

        return $code;
    }

    /**
     * Returns tokens found within an array definition PSR conforming
     * whitespace to make the code more readable.
     *
     * @param int $loc location in the token stream
     * @return string code with PSR spacing for array notation
     */
    private function arrayWhitespace($loc)
    {
        $tokens = $this->getTokenStream();
        $type   = $tokens->type($loc);
        switch ($type) {
            case T_DOUBLE_ARROW:
                return ' => ';
            case ',':
                return ', ';
            default:
                return $tokens->value($loc);
        }
    }

    /**
     * Parse heredoc and nowdoc into a concatenated string representation to be
     * useful for default values and inline assignment.
     *
     * @param int $loc
     * @return string|null
     */
    private function parseHereNowDocConcat($loc)
    {
        $tokens = $this->getTokenStream();
        $type   = substr($tokens->value($loc), 3, 1);
        $loc    = $tokens->next($loc);

        if ($loc) {
            $string = substr($tokens->value($loc), 0, -1);
            if ($type === '\'') {
                return '\'' . implode('\' . "\n" . \'', explode("\n", $string)) . '\'';
            }

            return '"' . str_replace("\n", '\n', $string) . '"';
        }

        return null;
    }

    /**
     * Returns the TokenStream instance for the class.
     *
     * @return TokenStream
     */
    private function getTokenStream()
    {
        if (! $this->tokens) {
            $this->tokens = new TokenStream(file_get_contents($this->filename));
        }

        return $this->tokens;
    }
}

<?php
namespace Hostnet\Component\AccessorGenerator\Reflection;

class TokenStream
{
    /**
     * Location of type within the PHP Token
     * @internal
     * @var int
     */
    const TYPE = 0;

    /**
     * Location of value within the PHP Token
     * @internal
     * @var int
     */
    const VALUE = 1;

    /**
     * Search direction Left to Right
     * @internal
     * @var int
     */
    const LTR = 1;

    /**
     * Search direction Right to Left
     * @internal
     * @var int
     */
    const RTL = -1;

    /**
     * PHP Token Stack.
     * @var array
     */
    private $tokens = [];

    public function __construct($source)
    {
        $this->tokens = token_get_all($source);
    }

    /**
     * Returns the type of the token at $loc.
     *
     * For simple tokens PHP does not use an array
     * so we need to check every single token we inspect
     * to see if it is an array or a scalar type.
     *
     * @param  int                   $loc token location
     * @return string|int            the char value of the token or a numeric
     *                               value corresponding with the T_ constants.
     * @throws \OutOfBoundsException for invalid token location
     */
    public function type($loc)
    {
        return $this->token($loc, self::TYPE);
    }

    /**
     * Returns the value of the token at $loc.
     *
     * For simple tokens PHP does not use an array
     * so we need to check every single token we inspect
     * to see if it is an array or a scalar type.
     *
     * @param  int $loc              token location
     * @return string                value for this token
     * @throws \OutOfBoundsException for invalid token location
     */
    public function value($loc)
    {
        return $this->token($loc, self::VALUE);
    }

    /**
     * Scan the token stack for occurrence of the tokens
     * in the given array. Return the location of the
     * first one found after $loc.
     *
     * Does not inspect the current token.
     *
     * @param  int   $loc
     * @param  array $tokens          PHP tokens (T_*)
     * @throws \OutOfBoundsException
     * @return number|NULL
     */
    public function scan($loc, array $tokens)
    {
        // Check validity of start position
        // -1 is allowed and trying scanning
        // from the last position is also allowed
        // because your pointer could end up on
        // this position and than this function
        // should return you with null and serve
        // as boundary check.
        if (! isset($this->tokens[$loc + 1]) && ! isset($this->tokens[$loc])) {
            throw new \OutOfBoundsException(sprintf('Invalid start location %d given', $loc));
        }

        // Advance while there are still tokens left.
        while (++$loc < count($this->tokens)) {
            // Inspect token.
            $type = $this->type($loc);
            if (in_array($type, $tokens)) {
                // return the location where we found $token.
                return $loc;
            }
        }

        // Nothing found.
        return null;
    }

    /**
     * Return the location of the next token not of a
     * type given by $tokens.
     *
     * Does not include current token.
     *
     * Will return null if there are no tokens to skip.
     *
     * @param  int   $loc            start location
     * @param  array $tokens         list of tokens to skip over
     *                               defaults to whitespace and
     *                               comments
     * @return int|null for invalid token location
     */
    public function next($loc, array $tokens = [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT])
    {
        return $this->skip($loc, $tokens, self::LTR);
    }

    /**
     * Return the location of the previous token not of a
     * type given by $tokens.
     *
     * Will return null if there are no tokens to skip.
     *
     * Does not include current token.
     *
     * @param  int  $loc             start location
     * @param  array $tokens         list of tokens to skip over
     *                               defaults to whitespace and
     *                               comments
     * @throws \OutOfBoundsException for invalid token location
     * @return int|null              location of the next token found
     */
    public function previous($loc, array $tokens = [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT])
    {
        return $this->skip($loc, $tokens, self::RTL);
    }

    /**
     * Please use next() and previous()
     *
     * @see previous
     * @see next
     * @param  int   $loc            start location
     * @param  array $tokens         list of tokens to skip over defaults to whitespace and comments
     * @param  int   $direction      LTR or RTL, defaults to LTR
     * @throws \OutOfBoundsException for invalid token location
     * @return int|null              location of the next token found
     */
    private function skip($loc, array $tokens, $direction)
    {
        // Check validity of start position
        // The first and last position of the stream
        // are always considered inside of the domain
        // since you do not want to check against boundary
        // conditions when iterating but use this function
        // for that purpose.
        if (! isset($this->tokens[$loc + $direction]) && ! isset($this->tokens[$loc])) {
            throw new \OutOfBoundsException(sprintf('Invalid start location %d given', $loc));
        }

        // Do not advance over the boundaries of the token stack
        while ($loc + $direction < count($this->tokens) && $loc + $direction >= 0) {
            // Advance to next token, also advance first time, so
            // we will not match the current token.
            $loc += $direction; //LTR = 1, RTL = -1

            // Check if the token can be skipped
            if (! in_array($this->type($loc), $tokens)) {
                return $loc;
            }
        }

        return null;
    }

    /**
     * Returns the value or type of the token at $loc.
     *
     * For simple tokens PHP does not use an array
     * so we need to check every single token we inspect
     * to see if it is an array or a scalar type.
     *
     * @param  int $loc token location
     * @param  int $type self::TYPE or self::VALUE
     * @return int|string the value or type of the token
     */
    private function token($loc, $type)
    {
        // Check if the token exists
        if (isset($this->tokens[$loc])) {
            // Check if the token is array or scalar
            if (is_array($this->tokens[$loc])) {
                // Array
                return $this->tokens[$loc][$type];
            } else {
                // Scalar
                return $this->tokens[$loc];
            }
        } else {
            // Invalid location
            throw new \OutOfBoundsException(sprintf('Invalid location %d given', $loc));
        }
    }
}

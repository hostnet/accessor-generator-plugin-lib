<?php
namespace Hostnet\Component\AccessorGenerator\Generator\fixtures;

class ParamName
{
    /**
     * This is an array.
     */
    public const A_SOME_ARRAY = 'A_SOME_ARRAY';

    /**
     * This is a string.
     */
    public const S_SOME_STRING = 'S_SOME_STRING';

    /**
     * This is an integer.
     */
    public const I_SOME_INTEGER = 'I_SOME_INTEGER';

    /**
     * This is a float.
     */
    public const F_SOME_FLOAT = 'F_SOME_FLOAT';

    /**
     * This is a boolean.
     */
    public const B_SOME_BOOLEAN = 'B_SOME_BOOLEAN';

    /**
     * Private constructor by design because this is an enum class.
     */
    private function __construct()
    {
    }
}

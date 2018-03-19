<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Reflection\Exception;

/**
 * There is no class definition found but it
 * was expected. This is not 'Class::name' but
 * 'class Name {'.
 */
class ClassDefinitionNotFoundException extends \RuntimeException
{
}

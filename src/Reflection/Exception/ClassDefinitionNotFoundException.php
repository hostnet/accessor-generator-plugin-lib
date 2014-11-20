<?php
namespace Hostnet\Component\AccessorGenerator\Reflection\Exception;

/**
 * There is no class definition found but it
 * was expected. This is not 'Class::name' but
 * 'class Name {'.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class ClassDefinitionNotFoundException extends \Exception
{
}

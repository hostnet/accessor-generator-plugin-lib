<?php
namespace Hostnet\Component\AccessorGenerator\Generator\Exception;

/**
 * Thrown when a class was referenced from an annotation but was not found.
 * This usually means that a required package was not present or not required propertly through composer.
 */
class ReferencedClassNotFoundException extends \Exception
{
}

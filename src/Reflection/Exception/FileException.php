<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Reflection\Exception;

/**
 * Exception when interacting with the filesystem
 * a file does not exist or is not readable.
 */
class FileException extends \RuntimeException
{
}

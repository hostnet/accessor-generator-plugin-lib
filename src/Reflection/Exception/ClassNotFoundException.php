<?php
namespace Hostnet\Component\AccessorGenerator\Reflection\Exception;

/**
 * {@inheritDoc}
 */
class ClassNotFoundException extends \Exception
{
    /**
     * @param string $class
     * @param int $code = null
     * @param \Exception $previous = null
     */
    public function __construct($class, $code = 0, \Exception $previous = null)
    {
        parent::__construct(sprintf('Class "%s" can not be found.', $class), $code, $previous);
    }
}

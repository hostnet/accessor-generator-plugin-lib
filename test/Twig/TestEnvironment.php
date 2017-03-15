<?php
namespace Hostnet\Component\AccessorGenerator\Twig;

use Twig_ExtensionInterface;

/**
 * Prevents using the default registered extensions by \Twig_Environment.
 *
 * The default loader is always \Twig_Loader_Array.
 */
class TestEnvironment extends \Twig_Environment
{
    /**
     * @var \Twig_Extension
     */
    private $extension;

    public function __construct(\Twig_Extension $extension)
    {
        $this->extension = $extension;
        parent::__construct(new \Twig_Loader_Array());
        $this->addExtension($extension);
    }

    public function addExtension(Twig_ExtensionInterface $extension)
    {
        if ($this->extension === $extension) {
            parent::addExtension($extension);
        }
    }
}

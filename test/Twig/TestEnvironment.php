<?php
/**
 * @copyright 2017-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\ArrayLoader;

/**
 * Prevents using the default registered extensions by Twig\Environment.
 *
 * The default loader is always Twig\Loader\ArrayLoader.
 */
class TestEnvironment extends Environment
{
    /**
     * @var AbstractExtension
     */
    private $extension;

    public function __construct(AbstractExtension $extension)
    {
        $this->extension = $extension;
        parent::__construct(new ArrayLoader());
        $this->addExtension($extension);
    }

    public function addExtension(ExtensionInterface $extension)
    {
        if ($this->extension !== $extension) {
            return;
        }

        parent::addExtension($extension);
    }
}

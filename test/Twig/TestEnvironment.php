<?php
/**
 * @copyright 2017-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Loader\ArrayLoader;

/**
 * A minimal Twig environment that adds a single extension for testing.
 *
 * The default loader is always Twig\Loader\ArrayLoader.
 */
class TestEnvironment extends Environment
{
    public function __construct(AbstractExtension $extension)
    {
        parent::__construct(new ArrayLoader(), ['autoescape' => false]);
        $this->addExtension($extension);
    }
}

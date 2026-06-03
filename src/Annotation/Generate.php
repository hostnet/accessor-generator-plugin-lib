<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Annotation;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Hostnet\Component\AccessorGenerator\Attribute\Generate as AttributeGenerate;

/**
 * @Annotation
 * @Target("PROPERTY")
 * @NamedArgumentConstructor
 * @see http://doctrine-common.readthedocs.org/en/latest/reference/annotations.html
 *
 * @deprecated Use the native PHP attribute #[AG\Generate] instead.
 *             This class will be removed when docblock annotation support is dropped.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Generate extends AttributeGenerate
{
}

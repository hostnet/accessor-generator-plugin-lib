<?php
/**
 * @copyright 2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Annotation;

use Doctrine\Common\Annotations\Annotation\NamedArgumentConstructor;
use Hostnet\Component\AccessorGenerator\Attribute\Enumerator as AttributeEnumerator;

/**
 * @Annotation(target={"ANNOTATION", "PROPERTY"})
 * @NamedArgumentConstructor
 *
 * @deprecated Use the native PHP attribute #[AG\Enumerator] instead.
 *             This class will be removed when docblock annotation support is dropped.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Enumerator extends AttributeEnumerator
{
}

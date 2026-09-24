<?php
/**
 * @copyright 2026-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Reflection;

/**
 * A loadable attribute class outside this package's own Attribute\ namespace, used to verify
 * malformed foreign attributes are still skipped silently by AttributeInstantiator.
 */
#[\Attribute]
class ForeignTestAttribute
{
    public function __construct(string $required)
    {
    }
}

<?php
/**
 * @copyright 2021-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

/**
 * @internal
 */
final class UniqueImports
{
    /**
     * Make sure our use statements are sorted alphabetically and unique. The
     * array_unique function can not be used because it does not take values
     * with different array keys into account. This loop does exactly that.
     * This is useful when a specific class name is imported and aliased as
     * well.
     *
     * @param string[] $imports
     * @return string[]
     */
    public static function filter(array $imports): array
    {
        uksort($imports, function ($a, $b) use ($imports) {
            $alias_a = is_numeric($a) ? " as $a;" : '';
            $alias_b = is_numeric($b) ? " as $b;" : '';

            return strcmp($imports[$a] . $alias_a, $imports[$b] . $alias_b);
        });

        $unique_imports = [];
        $next           = null;
        do {
            $key   = key($imports);
            $value = current($imports);
            $next  = next($imports);

            if ($value !== $next || (is_string($key) && $key !== key($imports))) {
                if (is_string($key)) {
                    $unique_imports[$key] = $value;
                } else {
                    $unique_imports[] = $value;
                }
            }
        } while ($next !== false);

        return $unique_imports;
    }
}

<?php
/**
 * @copyright 2026-present Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Reflection;

/**
 * Instantiates PHP 8 native attributes from raw attribute text extracted by the token-stream parser.
 *
 * The token-stream parser never executes source files — it only reads them as text. This is intentional:
 * during composer dump-autoload the traits we are generating do not exist yet, so including a source file
 * would fail. The downside is that attribute arguments are available only as raw text.
 *
 * To evaluate them, this class writes a small synthetic PHP file containing just the attribute block
 * and includes it. The attribute classes themselves (e.g. Doctrine ORM mapping classes) are already
 * autoloadable, so PHP can resolve and instantiate them normally.
 */
class AttributeInstantiator
{
    /**
     * Instantiate all attribute objects described by $attr_text using the given use-statement map.
     *
     * @param string   $attr_text      Raw text inside a #[...] block, e.g. "AG\Generate(get: 'none')"
     * @param string[] $use_statements Map of alias => FQCN from the source file's use declarations
     *
     * @return object[] Instantiated attribute objects; empty when the class is not loadable or args are invalid
     */
    public static function instantiate(string $attr_text, array $use_statements): array
    {
        $hash = substr(md5($attr_text . serialize($use_statements)), 0, 12);
        $cls  = '_SyntheticAttrHost_' . $hash;

        if (!class_exists($cls, false)) {
            $php = "<?php\n";
            foreach ($use_statements as $alias => $fqn) {
                // Skip non-compound names (e.g. `use DateTime;`): they live in the
                // global namespace, need no import, and PHP warns the statement has
                // no effect — which aborts the include in strict environments.
                if (!str_contains($fqn, '\\')) {
                    continue;
                }
                $php .= is_int($alias) ? "use $fqn;\n" : "use $fqn as $alias;\n";
            }
            $php .= "class $cls { #[$attr_text] public \$p; }\n";

            $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $cls . '_' . getmypid() . '.php';
            file_put_contents($tmp, $php);
            try {
                include $tmp;
            } finally {
                unlink($tmp);
            }
        }

        $attrs  = (new \ReflectionClass($cls))->getProperty('p')->getAttributes();
        $result = [];
        foreach ($attrs as $attr) {
            try {
                $result[] = $attr->newInstance();
            } catch (\Throwable) {
                // Attribute class not loadable or constructor args invalid — skip silently.
            }
        }

        return $result;
    }
}

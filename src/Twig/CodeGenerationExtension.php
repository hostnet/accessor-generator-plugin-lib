<?php
namespace Hostnet\Component\AccessorGenerator\Twig;

use Doctrine\Common\Inflector\Inflector;

/**
 * Twig extension to have some filters and tags
 * available to be able to write concise template
 * code for the php that we are generating.
 *
 * Filters:
 *   classify:    Turn names with _ into valid PSR-2
 *                Class names. For example: table_name
 *                to TableName.
 *   singularize: Convert plural names to singluar ones.
 *                For example orders to order or sheep to
 *                sheep.
 * Tags:
 *   perline:     This is a block tag to apply prefixes and
 *                postfixes to a multi line twig variable,
 *                usefull for generating doc blocks, header
 *                boxes or indenting code. It does not generate
 *                trailing spaces on blank lines.
 *
 *                Usage: {% perline %}
 *                       prefix {{lines}} postfix
 *                       {% end perline %}
 *
 * @see Inflector::classify
 * @see Inflector::singularize
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class CodeGenerationExtension extends \Twig_Extension
{
    public function getTokenParsers()
    {
        return [new PerLineTokenParser()];
    }

    /**
     * @override
     */
    public function getFilters()
    {
        return
            [
                new \Twig_SimpleFilter('classify', function ($string) {
                    return Inflector::classify($string);
                }),
                new \Twig_SimpleFilter('singularize', function ($string) {
                    return Inflector::singularize($string);
                })
            ];
    }

    public function getName()
    {
        return 'Hostnet Twig Code Generation Extension';
    }
}

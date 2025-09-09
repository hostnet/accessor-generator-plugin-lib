<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Twig;

use Doctrine\Inflector\InflectorFactory;
use Twig\Error\RuntimeError;
use Twig\ExpressionParser\Prefix\LiteralExpressionParser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;

/**
 * Twig extension to have some filters and tags available to be able to write
 * concise template code for the php that we are generating.
 *
 * Filters:
 *   classify:    Turn names with _ into valid PSR-2
 *                Class names. For example: table_name
 *                to TableName.
 *   singularize: Convert plural names to singular ones For example orders to
 *                order or sheep to sheep.
 * Tags:
 *   perline:     This is a block tag to apply prefixes and postfixes to a
 *                multiline twig variable, useful for generating doc blocks,
 *                header boxes or indenting code. It does not generate trailing
 *                spaces on blank lines.
 *
 *                Usage: {% perline %}
 *                       prefix {{lines}} postfix
 *                       {% end perline %}
 *
 * @see Inflector::classify
 * @see Inflector::singularize
 */
class CodeGenerationExtension extends AbstractExtension
{
    private $inflector;

    public function __construct()
    {
        $this->inflector = InflectorFactory::create()->build();
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers(): array
    {
        return [new PerLineTokenParser()];
    }

    public function getExpressionParsers(): array
    {
        return [new LiteralExpressionParser()];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('classify', function ($string) {
                return $this->inflector->classify($string);
            }),
            new TwigFilter('singularize', function ($string) {
                return $this->inflector->singularize($string);
            }),
            new TwigFilter('phptype', function ($string) {
                if ($string === 'integer') {
                    return 'int';
                }
                if ($string === 'boolean') {
                    return 'bool';
                }
                return $string;
            }),
            new TwigFilter('twos_complement_min', function ($int) {
                try {
                    return self::twosComplementMin($int);
                } catch (\DomainException $e) {
                    throw new RuntimeError($e->getMessage(), -1, null, $e);
                }
            }),
            new TwigFilter('twos_complement_max', function ($int) {
                try {
                    return self::twosComplementMax($int);
                } catch (\DomainException $e) {
                    throw new RuntimeError($e->getMessage(), -1, null, $e);
                }
            }),
            new TwigFilter('decimal_right_shift', function ($input, $amount) {
                try {
                    return self::decimalRightShift($input, $amount);
                } catch (\InvalidArgumentException $e) {
                    throw new RuntimeError($e->getMessage(), -1, null, $e);
                }
            }),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new TwigTest('string', function ($input) {
                return is_string($input);
            }),
        ];
    }

    public function getName(): string
    {
        return 'Hostnet Twig Code Generation Extension';
    }

    /**
     * @throws \DomainException
     * @param mixed $bits
     */
    private static function twosComplementMin($bits): int
    {
        $bits     = (int) $bits;
        $max_bits = PHP_INT_SIZE << 3;

        if ($bits < 1) {
            throw new \DomainException('Bit size must be greater than 0');
        }

        if ($bits > $max_bits) {
            $bits = $max_bits;
        }

        return -1 << ($bits - 1);
    }

    /**
     * @throws \DomainException
     * @param mixed $bits
     */
    private static function twosComplementMax($bits): int
    {
        $bits     = (int) $bits;
        $max_bits = PHP_INT_SIZE << 3;

        if ($bits < 1) {
            throw new \DomainException('Bit size must be greater than 0');
        }

        if ($bits > $max_bits) {
            $bits = $max_bits;
        }

        return (1 << ($bits - 2)) - 1 + (1 << ($bits - 2));
    }

    /**
     * @throws \InvalidArgumentException
     * @param mixed $input
     * @param int   $amount
     * @return mixed|string
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint
     */
    private static function decimalRightShift($input, $amount = 0)
    {
        // Check input, to see if it is a valid numeric string with a decimal dot and not a
        // decimal comma or any other unwanted chars.
        if (!is_numeric($input) || !preg_match('/[0-9]*\.?[0-9]+/', (string) $input)) {
            throw new \InvalidArgumentException('Input is not a number or numeric string');
        }

        $input = (string) $input;

        // Check amount to see if it is of integer type.
        if (!\is_int($amount)) {
            throw new \InvalidArgumentException('Amount must be an integer value');
        }

        if ($amount > 0) {
            if (($loc = strpos($input, '.')) === false) {
                $loc = \strlen($input);
            } else {
                $input = str_replace('.', '', $input);
            }

            $loc -= $amount;
            if ($loc > 0) {
                return substr($input, 0, $loc) . '.' . substr($input, $loc);
            }

            return '0.' . str_repeat('0', abs($loc)) . $input;
        }

        return $input;
    }
}

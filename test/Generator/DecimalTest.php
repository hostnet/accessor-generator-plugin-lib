<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Inflector\InflectorFactory;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Decimal;
use PHPUnit\Framework\TestCase;

class DecimalTest extends TestCase
{
    private $inflector;

    public function setup(): void
    {
        $this->inflector = InflectorFactory::create()->build();
    }
    private function getTestValues($scale, $precision): iterable
    {
        $values    = [];
        $property  = sprintf('decimal_%d_%d', $scale, $precision);
        $exception = \DomainException::class;
        $before    = $precision - $scale;
        $on_value  = sprintf('%s.%s', str_repeat('2', $before), str_repeat('3', $scale));

        $i = bccomp($on_value, (string) PHP_INT_MAX, 0) <= 0;
        $f = $precision < 30; // arbirary boundary, only fit fore the used test values

        //Scale outside boundary, precision outside boundary.
        $case                             = $property . ' scale outside boundary, precision outside boundary';
        $values[$case . ' (string)']      = [$scale, $precision, '4' . $on_value . '0',          $exception];
        $f && $values[$case . ' (float)'] = [$scale, $precision, (float) ('4' . $on_value . '4'), $exception];

        //Scale outside boundary, precision on boundary.
        $case                        = $property . ' scale outside boundary, precision on boundary';
        $values[$case . ' (string)'] = [$scale, $precision, $on_value . '0', $exception];

        //Scale on boundary, precision outside boundary.
        $case                             = $property . ' scale on boundary, precision outside boundary';
        $values[$case . ' (string)']      = [$scale, $precision, '4' . $on_value,          $exception];
        $f && $values[$case . ' (float)'] = [$scale, $precision, (float) ('4' . $on_value), $exception];
        $i && $values[$case . ' (int)']   = [$scale, $precision, (int) ('4' . $on_value),   $exception];

        //Scale on boundary, precision on boundary.
        $case                             = $property . ' scale on boundary, precision on boundary';
        $values[$case . ' (string)']      = [$scale, $precision, $on_value       ];
        $f && $values[$case . ' (float)'] = [$scale, $precision, (float) $on_value];
        $i && $values[$case . ' (int)']   = [$scale, $precision, (int) ($on_value)];

        if ($scale > 0) {
            //Scale within boundary, precision outside boundary.
            $case                             = $property . ' scale within boundary, precision outside boundary';
            $value                            = substr($on_value, 0, -1);
            $values[$case . ' (string)']      = [$scale, $precision,  '4' . $value,         $exception];
            $f && $values[$case . ' (float)'] = [$scale, $precision, (float) ('4' . $value), $exception];

            //Scale within boundary, precision on boundary.
            $case                             = $property . ' scale within boundary, precision on boundary';
            $value                            = substr($on_value, 0, -1);
            $values[$case . ' (string)']      = [$scale, $precision, $value       ];
            $f && $values[$case . ' (float)'] = [$scale, $precision, (float) $value];

            if ($before > 0) {
                //Scale within boundary, precision within boundary.
                $case                             = $property . ' scale within boundary, precision within boundary';
                $value                            = substr($on_value, 1, -1);
                $values[$case . ' (string)']      = [$scale, $precision, $value       ];
                $f && $values[$case . ' (float)'] = [$scale, $precision, (float) $value];
            }
        }

        if ($before > 0) {
            //Scale outside boundary, precision within boundary.
            $case                        = $property . ' scale outside boundary, precision within boundary';
            $value                       = substr($on_value, 1);
            $values[$case . ' (string)'] = [$scale, $precision, $value . '0', $exception];

            //Scale on boundary, precision within boundary.
            $case                             = $property . ' scale on boundary, precision within boundary';
            $value                            = substr($on_value, 1);
            $values[$case . ' (string)']      = [$scale, $precision, $value       ];
            $f && $values[$case . ' (float)'] = [$scale, $precision, (float) $value];
            $values[$case . ' (int)']         = [$scale, $precision, (int) $value  ];
        }

        $values[$property . ' wrong type'] = [
            $scale,
            $precision,
            new \stdClass(),
            \InvalidArgumentException::class,
        ];

        $values[$property . ' non numeric string'] = [
            $scale,
            $precision,
            'a',
            \InvalidArgumentException::class,
        ];

        $values[$property . ' too many params'] = [
            $scale,
            $precision,
            1,
            \BadMethodCallException::class,
            1,
        ];

        return $values;
    }

    public function setProvider(): iterable
    {
        $values = [];
        $values = array_merge($values, $this->getTestValues(0, 10));
        $values = array_merge($values, $this->getTestValues(1, 10));
        $values = array_merge($values, $this->getTestValues(5, 10));
        $values = array_merge($values, $this->getTestValues(10, 10));
        $values = array_merge($values, $this->getTestValues(18, 20));
        $values = array_merge($values, $this->getTestValues(19, 20));
        $values = array_merge($values, $this->getTestValues(30, 65));
        return $values;
    }

    /**
     * @param string $type
     * @param mixed $value
     * @param string $exception
     * @param mixed $extra_parameter
     * @dataProvider setProvider
     */
    public function testSet($scale, $precision, $value, $exception = null, $extra_parameter = null): void
    {
        $exception && $this->expectException($exception);
        $decimal = new Decimal();

        $property = sprintf('decimal_%d_%d', $scale, $precision);
        $setter   = 'set' . $this->inflector->classify($property);

        if ($extra_parameter !== null) {
            $set = $decimal->$setter($value, false, $extra_parameter);
        } else {
            $set = $decimal->$setter($value);
        }

        // Decimals always return strings
        $value = (string) $value;

        // Add expected trailing zeroes
        if (($pos = strpos($value, '.')) !== false) {
            $value .= str_repeat('0', $scale - (\strlen(ltrim($value, '+-')) - $pos - 1));
        } elseif ($scale > 0) {
            $value .= '.' . str_repeat('0', $scale);
        }

        // Add leading zero if needed
        if (substr($value, 0, 1) === '.') {
            $value = '0' . $value;
        }

        // Remove lonely trailing point for integer values
        if (substr($value, -1) === '.') {
            $value = substr($value, 0, -1);
        }

        // Check result type
        $property = new \ReflectionProperty(Decimal::class, $property);
        $property->setAccessible(true);
        self::assertSame($value, $property->getValue($decimal));

        // Check for fluent interface
        self::assertSame($decimal, $set);
    }

    public function roundProvider(): iterable
    {
        $huge = str_repeat('1', 35) . '.' . str_repeat('5', 29);

        if (version_compare(PHP_VERSION, '7.4.0') >= 0) {
            $test_set = [
                ['decimal_18_20', -1.0E-30, '0.000000000000000000'],
                ['decimal_19_20', -1.0E-20, '0.0000000000000000000'],
                ['decimal_30_65', -1.6E-130, '0.000000000000000000000000000000'],
            ];
        } else { // before php 7.4.0
            $test_set = [
                ['decimal_18_20', -1.0E-30, '-0.000000000000000000'],
                ['decimal_19_20', -1.0E-20, '-0.0000000000000000000'],
                ['decimal_30_65', -1.6E-130, '-0.000000000000000000000000000000'],
            ];
        }

        $test_set = array_merge($test_set, [
            ['decimal_0_10', .5, '1'],
            ['decimal_0_10', .499, '0'],
            ['decimal_0_10',  1, '1'],
            ['decimal_0_10',  '.50000', '1'],
            ['decimal_0_10',  '-.50000', '-1'],
            ['decimal_0_10',  '.49999', '0'],
            ['decimal_0_10',  1.0E-30, '0'],
            ['decimal_1_10', .55, '0.6'],
            ['decimal_1_10', .499, '0.5'],
            ['decimal_1_10',  1.1, '1.1'],
            ['decimal_1_10',  1.0E-30, '0.0'],
            ['decimal_1_10',  '.55000', '0.6'],
            ['decimal_1_10',  '.49999', '0.5'],
            ['decimal_1_10',  '-.49999', '-0.5'],
            ['decimal_5_10',  '-.49999', '-0.49999'],
            ['decimal_5_10',  '-.499999', '-0.50000'],
            ['decimal_5_10',  '.499999', '0.50000'],
            ['decimal_5_10',  1.0E-30, '0.00000'],
            ['decimal_10_10', '.01234567891', '0.0123456789'],
            ['decimal_10_10', '.01234567895', '0.0123456790'],
            ['decimal_10_10', '-0.01234567895', '-0.0123456790'],
            ['decimal_10_10', 1.0E-30, '0.0000000000'],

            ['decimal_18_20',  1.0E-30, '0.000000000000000000'],
            ['decimal_18_20',  '12.3456789012345678915', '12.345678901234567892'],
            ['decimal_18_20',  '-12.3456789012345678915', '-12.345678901234567892'],

            ['decimal_19_20',  1.0E-30, '0.0000000000000000000'],
            ['decimal_19_20',  '1.23456789012345678915', '1.2345678901234567892'],
            ['decimal_19_20',  '-1.23456789012345678915', '-1.2345678901234567892'],

            ['decimal_30_65',  1.0E-20, '0.000000000000000000010000000000'],
            ['decimal_30_65',  1.6E-130, '0.000000000000000000000000000000'],
            ['decimal_30_65',  $huge . '54', $huge . '5'],
            ['decimal_30_65',  $huge . '55', $huge . '6'],
            ['decimal_30_65',  '-' . $huge . '55', '-' . $huge . '6'],
        ]);

        return $test_set;
    }

   /**
    * @dataProvider roundProvider
    */
    public function testRound($field, $value_in, $value_out): void
    {
        $decimal = new Decimal();
        $setter  = 'set' . $this->inflector->classify($field);
        $set     = $decimal->$setter($value_in, true);

        // Check for fluent interface
        self::assertSame($decimal, $set);

        // Check result type
        $property = new \ReflectionProperty(Decimal::class, $field);
        $property->setAccessible(true);
        self::assertSame($value_out, $property->getValue($decimal));
    }
}

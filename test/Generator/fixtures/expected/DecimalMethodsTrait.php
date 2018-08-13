<?php
// HEADER

namespace Hostnet\Component\AccessorGenerator\Generator\fixtures\Generated;

use Doctrine\ORM\Mapping as ORM;
use Hostnet\Component\AccessorGenerator\Annotation as AG;
use Hostnet\Component\AccessorGenerator\Generator\fixtures\Decimal;

trait DecimalMethodsTrait
{
    /**
     * Sets decimal_0_10
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $decimal_0_10
     * @param bool $round round the number fit in the precision and scale (round away from zero)
     *
     * @return $this|Decimal
     */
    public function setDecimal010($decimal_0_10, $round = false)
    {
        if (\func_num_args() > 2) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDecimal010() has one mandatory and one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if (!\is_int($decimal_0_10) && !\is_string($decimal_0_10) && !\is_float($decimal_0_10)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid type (%s) given. Only int, float and a numeric string are allowed.',
                    gettype($decimal_0_10)
                )
            );
        }

        $result           = [];
        $scientific_float = false;
        if (preg_match('/^([\-+]?[0-9]+\.?([0-9]*))[Ee]([\-+]?[0-9]+)$/', $decimal_0_10, $result)) {
            $scale = max(strlen($result[2]) - $result[3], 0);
            if (\is_float($decimal_0_10)) {
                $scientific_float = true;
            }
            $decimal_0_10 = bcmul($result[1], bcpow(10, $result[3], $scale), $scale);
        }

        if (!preg_match('/^(-?)([0-9]*)(?:\.([0-9]*))?$/', $decimal_0_10, $result)) {
            throw new \InvalidArgumentException(sprintf('String (%s) is not strictly numeric.', $decimal_0_10));
        }

        $minus  = isset($result[1]) ? $result[1] === '-': false;
        $before = isset($result[2]) ? ltrim($result[2], '0') : '';
        $after  = isset($result[3]) ? $result[3] : '';

        if (strlen($before) > 10) {
            throw new \DomainException(
                'More than 10 digit(s) ' .
                'before the decimal point given while only 10 is/are allowed'
            );
        }

        if ($round || \is_float($decimal_0_10) || $scientific_float || strlen($after) <= 0) {
            if (substr($after, 0, 1) >= 5) {
                if ($minus) {
                    $decimal_0_10 = bcsub($decimal_0_10, '1', 0);
                } else {
                    $decimal_0_10 = bcadd($decimal_0_10, '1', 0);
                }
            } else {
                $decimal_0_10 = bcadd($decimal_0_10, 0, 0);
            }
        } else {
            throw new \DomainException(
                'More than 0 digit(s) '.
                'after the decimal point given while only 0 is/are allowed'
            );
        }

        $this->decimal_0_10 = $decimal_0_10;

        return $this;
    }

    /**
     * Sets decimal_1_10
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $decimal_1_10
     * @param bool $round round the number fit in the precision and scale (round away from zero)
     *
     * @return $this|Decimal
     */
    public function setDecimal110($decimal_1_10, $round = false)
    {
        if (\func_num_args() > 2) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDecimal110() has one mandatory and one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if (!\is_int($decimal_1_10) && !\is_string($decimal_1_10) && !\is_float($decimal_1_10)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid type (%s) given. Only int, float and a numeric string are allowed.',
                    gettype($decimal_1_10)
                )
            );
        }

        $result           = [];
        $scientific_float = false;
        if (preg_match('/^([\-+]?[0-9]+\.?([0-9]*))[Ee]([\-+]?[0-9]+)$/', $decimal_1_10, $result)) {
            $scale = max(strlen($result[2]) - $result[3], 1);
            if (\is_float($decimal_1_10)) {
                $scientific_float = true;
            }
            $decimal_1_10 = bcmul($result[1], bcpow(10, $result[3], $scale), $scale);
        }

        if (!preg_match('/^(-?)([0-9]*)(?:\.([0-9]*))?$/', $decimal_1_10, $result)) {
            throw new \InvalidArgumentException(sprintf('String (%s) is not strictly numeric.', $decimal_1_10));
        }

        $minus  = isset($result[1]) ? $result[1] === '-': false;
        $before = isset($result[2]) ? ltrim($result[2], '0') : '';
        $after  = isset($result[3]) ? $result[3] : '';

        if (strlen($before) > 9) {
            throw new \DomainException(
                'More than 9 digit(s) ' .
                'before the decimal point given while only 9 is/are allowed'
            );
        }

        if ($round || \is_float($decimal_1_10) || $scientific_float || strlen($after) <= 1) {
            if (substr($after, 1, 1) >= 5) {
                if ($minus) {
                    $decimal_1_10 = bcsub($decimal_1_10, '0.1', 1);
                } else {
                    $decimal_1_10 = bcadd($decimal_1_10, '0.1', 1);
                }
            } else {
                $decimal_1_10 = bcadd($decimal_1_10, 0, 1);
            }
        } else {
            throw new \DomainException(
                'More than 1 digit(s) '.
                'after the decimal point given while only 1 is/are allowed'
            );
        }

        $this->decimal_1_10 = $decimal_1_10;

        return $this;
    }

    /**
     * Sets decimal_5_10
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $decimal_5_10
     * @param bool $round round the number fit in the precision and scale (round away from zero)
     *
     * @return $this|Decimal
     */
    public function setDecimal510($decimal_5_10, $round = false)
    {
        if (\func_num_args() > 2) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDecimal510() has one mandatory and one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if (!\is_int($decimal_5_10) && !\is_string($decimal_5_10) && !\is_float($decimal_5_10)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid type (%s) given. Only int, float and a numeric string are allowed.',
                    gettype($decimal_5_10)
                )
            );
        }

        $result           = [];
        $scientific_float = false;
        if (preg_match('/^([\-+]?[0-9]+\.?([0-9]*))[Ee]([\-+]?[0-9]+)$/', $decimal_5_10, $result)) {
            $scale = max(strlen($result[2]) - $result[3], 5);
            if (\is_float($decimal_5_10)) {
                $scientific_float = true;
            }
            $decimal_5_10 = bcmul($result[1], bcpow(10, $result[3], $scale), $scale);
        }

        if (!preg_match('/^(-?)([0-9]*)(?:\.([0-9]*))?$/', $decimal_5_10, $result)) {
            throw new \InvalidArgumentException(sprintf('String (%s) is not strictly numeric.', $decimal_5_10));
        }

        $minus  = isset($result[1]) ? $result[1] === '-': false;
        $before = isset($result[2]) ? ltrim($result[2], '0') : '';
        $after  = isset($result[3]) ? $result[3] : '';

        if (strlen($before) > 5) {
            throw new \DomainException(
                'More than 5 digit(s) ' .
                'before the decimal point given while only 5 is/are allowed'
            );
        }

        if ($round || \is_float($decimal_5_10) || $scientific_float || strlen($after) <= 5) {
            if (substr($after, 5, 1) >= 5) {
                if ($minus) {
                    $decimal_5_10 = bcsub($decimal_5_10, '0.00001', 5);
                } else {
                    $decimal_5_10 = bcadd($decimal_5_10, '0.00001', 5);
                }
            } else {
                $decimal_5_10 = bcadd($decimal_5_10, 0, 5);
            }
        } else {
            throw new \DomainException(
                'More than 5 digit(s) '.
                'after the decimal point given while only 5 is/are allowed'
            );
        }

        $this->decimal_5_10 = $decimal_5_10;

        return $this;
    }

    /**
     * Sets decimal_10_10
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $decimal_10_10
     * @param bool $round round the number fit in the precision and scale (round away from zero)
     *
     * @return $this|Decimal
     */
    public function setDecimal1010($decimal_10_10, $round = false)
    {
        if (\func_num_args() > 2) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDecimal1010() has one mandatory and one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if (!\is_int($decimal_10_10) && !\is_string($decimal_10_10) && !\is_float($decimal_10_10)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid type (%s) given. Only int, float and a numeric string are allowed.',
                    gettype($decimal_10_10)
                )
            );
        }

        $result           = [];
        $scientific_float = false;
        if (preg_match('/^([\-+]?[0-9]+\.?([0-9]*))[Ee]([\-+]?[0-9]+)$/', $decimal_10_10, $result)) {
            $scale = max(strlen($result[2]) - $result[3], 10);
            if (\is_float($decimal_10_10)) {
                $scientific_float = true;
            }
            $decimal_10_10 = bcmul($result[1], bcpow(10, $result[3], $scale), $scale);
        }

        if (!preg_match('/^(-?)([0-9]*)(?:\.([0-9]*))?$/', $decimal_10_10, $result)) {
            throw new \InvalidArgumentException(sprintf('String (%s) is not strictly numeric.', $decimal_10_10));
        }

        $minus  = isset($result[1]) ? $result[1] === '-': false;
        $before = isset($result[2]) ? ltrim($result[2], '0') : '';
        $after  = isset($result[3]) ? $result[3] : '';

        if (strlen($before) > 0) {
            throw new \DomainException(
                'More than 0 digit(s) ' .
                'before the decimal point given while only 0 is/are allowed'
            );
        }

        if ($round || \is_float($decimal_10_10) || $scientific_float || strlen($after) <= 10) {
            if (substr($after, 10, 1) >= 5) {
                if ($minus) {
                    $decimal_10_10 = bcsub($decimal_10_10, '0.0000000001', 10);
                } else {
                    $decimal_10_10 = bcadd($decimal_10_10, '0.0000000001', 10);
                }
            } else {
                $decimal_10_10 = bcadd($decimal_10_10, 0, 10);
            }
        } else {
            throw new \DomainException(
                'More than 10 digit(s) '.
                'after the decimal point given while only 10 is/are allowed'
            );
        }

        $this->decimal_10_10 = $decimal_10_10;

        return $this;
    }

    /**
     * Sets decimal_18_20
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $decimal_18_20
     * @param bool $round round the number fit in the precision and scale (round away from zero)
     *
     * @return $this|Decimal
     */
    public function setDecimal1820($decimal_18_20, $round = false)
    {
        if (\func_num_args() > 2) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDecimal1820() has one mandatory and one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if (!\is_int($decimal_18_20) && !\is_string($decimal_18_20) && !\is_float($decimal_18_20)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid type (%s) given. Only int, float and a numeric string are allowed.',
                    gettype($decimal_18_20)
                )
            );
        }

        $result           = [];
        $scientific_float = false;
        if (preg_match('/^([\-+]?[0-9]+\.?([0-9]*))[Ee]([\-+]?[0-9]+)$/', $decimal_18_20, $result)) {
            $scale = max(strlen($result[2]) - $result[3], 18);
            if (\is_float($decimal_18_20)) {
                $scientific_float = true;
            }
            $decimal_18_20 = bcmul($result[1], bcpow(10, $result[3], $scale), $scale);
        }

        if (!preg_match('/^(-?)([0-9]*)(?:\.([0-9]*))?$/', $decimal_18_20, $result)) {
            throw new \InvalidArgumentException(sprintf('String (%s) is not strictly numeric.', $decimal_18_20));
        }

        $minus  = isset($result[1]) ? $result[1] === '-': false;
        $before = isset($result[2]) ? ltrim($result[2], '0') : '';
        $after  = isset($result[3]) ? $result[3] : '';

        if (strlen($before) > 2) {
            throw new \DomainException(
                'More than 2 digit(s) ' .
                'before the decimal point given while only 2 is/are allowed'
            );
        }

        if ($round || \is_float($decimal_18_20) || $scientific_float || strlen($after) <= 18) {
            if (substr($after, 18, 1) >= 5) {
                if ($minus) {
                    $decimal_18_20 = bcsub($decimal_18_20, '0.000000000000000001', 18);
                } else {
                    $decimal_18_20 = bcadd($decimal_18_20, '0.000000000000000001', 18);
                }
            } else {
                $decimal_18_20 = bcadd($decimal_18_20, 0, 18);
            }
        } else {
            throw new \DomainException(
                'More than 18 digit(s) '.
                'after the decimal point given while only 18 is/are allowed'
            );
        }

        $this->decimal_18_20 = $decimal_18_20;

        return $this;
    }

    /**
     * Sets decimal_19_20
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $decimal_19_20
     * @param bool $round round the number fit in the precision and scale (round away from zero)
     *
     * @return $this|Decimal
     */
    public function setDecimal1920($decimal_19_20 = '1.2345678901234567890', $round = false)
    {
        if (\func_num_args() > 2) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDecimal1920() has two optional arguments but %d given.',
                    \func_num_args()
                )
            );
        }

        if (!\is_int($decimal_19_20) && !\is_string($decimal_19_20) && !\is_float($decimal_19_20)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid type (%s) given. Only int, float and a numeric string are allowed.',
                    gettype($decimal_19_20)
                )
            );
        }

        $result           = [];
        $scientific_float = false;
        if (preg_match('/^([\-+]?[0-9]+\.?([0-9]*))[Ee]([\-+]?[0-9]+)$/', $decimal_19_20, $result)) {
            $scale = max(strlen($result[2]) - $result[3], 19);
            if (\is_float($decimal_19_20)) {
                $scientific_float = true;
            }
            $decimal_19_20 = bcmul($result[1], bcpow(10, $result[3], $scale), $scale);
        }

        if (!preg_match('/^(-?)([0-9]*)(?:\.([0-9]*))?$/', $decimal_19_20, $result)) {
            throw new \InvalidArgumentException(sprintf('String (%s) is not strictly numeric.', $decimal_19_20));
        }

        $minus  = isset($result[1]) ? $result[1] === '-': false;
        $before = isset($result[2]) ? ltrim($result[2], '0') : '';
        $after  = isset($result[3]) ? $result[3] : '';

        if (strlen($before) > 1) {
            throw new \DomainException(
                'More than 1 digit(s) ' .
                'before the decimal point given while only 1 is/are allowed'
            );
        }

        if ($round || \is_float($decimal_19_20) || $scientific_float || strlen($after) <= 19) {
            if (substr($after, 19, 1) >= 5) {
                if ($minus) {
                    $decimal_19_20 = bcsub($decimal_19_20, '0.0000000000000000001', 19);
                } else {
                    $decimal_19_20 = bcadd($decimal_19_20, '0.0000000000000000001', 19);
                }
            } else {
                $decimal_19_20 = bcadd($decimal_19_20, 0, 19);
            }
        } else {
            throw new \DomainException(
                'More than 19 digit(s) '.
                'after the decimal point given while only 19 is/are allowed'
            );
        }

        $this->decimal_19_20 = $decimal_19_20;

        return $this;
    }

    /**
     * Sets decimal_30_65
     *
     * @throws \BadMethodCallException if the number of arguments is not correct
     * @throws \InvalidArgumentException if value is not of the right type
     *
     * @param string $decimal_30_65
     * @param bool $round round the number fit in the precision and scale (round away from zero)
     *
     * @return $this|Decimal
     */
    public function setDecimal3065($decimal_30_65, $round = false)
    {
        if (\func_num_args() > 2) {
            throw new \BadMethodCallException(
                sprintf(
                    'setDecimal3065() has one mandatory and one optional argument but %d given.',
                    \func_num_args()
                )
            );
        }

        if (!\is_int($decimal_30_65) && !\is_string($decimal_30_65) && !\is_float($decimal_30_65)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid type (%s) given. Only int, float and a numeric string are allowed.',
                    gettype($decimal_30_65)
                )
            );
        }

        $result           = [];
        $scientific_float = false;
        if (preg_match('/^([\-+]?[0-9]+\.?([0-9]*))[Ee]([\-+]?[0-9]+)$/', $decimal_30_65, $result)) {
            $scale = max(strlen($result[2]) - $result[3], 30);
            if (\is_float($decimal_30_65)) {
                $scientific_float = true;
            }
            $decimal_30_65 = bcmul($result[1], bcpow(10, $result[3], $scale), $scale);
        }

        if (!preg_match('/^(-?)([0-9]*)(?:\.([0-9]*))?$/', $decimal_30_65, $result)) {
            throw new \InvalidArgumentException(sprintf('String (%s) is not strictly numeric.', $decimal_30_65));
        }

        $minus  = isset($result[1]) ? $result[1] === '-': false;
        $before = isset($result[2]) ? ltrim($result[2], '0') : '';
        $after  = isset($result[3]) ? $result[3] : '';

        if (strlen($before) > 35) {
            throw new \DomainException(
                'More than 35 digit(s) ' .
                'before the decimal point given while only 35 is/are allowed'
            );
        }

        if ($round || \is_float($decimal_30_65) || $scientific_float || strlen($after) <= 30) {
            if (substr($after, 30, 1) >= 5) {
                if ($minus) {
                    $decimal_30_65 = bcsub($decimal_30_65, '0.000000000000000000000000000001', 30);
                } else {
                    $decimal_30_65 = bcadd($decimal_30_65, '0.000000000000000000000000000001', 30);
                }
            } else {
                $decimal_30_65 = bcadd($decimal_30_65, 0, 30);
            }
        } else {
            throw new \DomainException(
                'More than 30 digit(s) '.
                'after the decimal point given while only 30 is/are allowed'
            );
        }

        $this->decimal_30_65 = $decimal_30_65;

        return $this;
    }
}

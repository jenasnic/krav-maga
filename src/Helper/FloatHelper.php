<?php

namespace App\Helper;

final class FloatHelper
{
    public const PRECISION = 2;

    public const ROUND_MODE = PHP_ROUND_HALF_DOWN;

    public static function round(float $value, ?int $precision = null): float
    {
        $precision ??= self::PRECISION;

        return round($value, $precision, self::ROUND_MODE);
    }

    public static function equals(float $left, float $right): bool
    {
        return 0 == self::round($left - $right);
    }

    public static function notEquals(float $left, float $right): bool
    {
        return 0 != self::round($left - $right);
    }

    public static function greater(float $left, float $right): bool
    {
        return self::round($left - $right) > 0;
    }

    public static function greaterOrEquals(float $left, float $right): bool
    {
        return self::round($left - $right) >= 0;
    }

    public static function lower(float $left, float $right): bool
    {
        return self::round($left - $right) < 0;
    }

    public static function lowerOrEquals(float $left, float $right): bool
    {
        return self::round($left - $right) <= 0;
    }
}

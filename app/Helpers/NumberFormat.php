<?php

namespace App\Helpers;

class NumberFormat
{
    private const DEFAULT = '900T+';

    private const THRESHOLDS = [
        ''  => 900,
        'K' => 900000,
        'M' => 900000000,
        'B' => 900000000000,
        'T' => 90000000000000,
    ];

    public static function readable(float $value, int $precision = 1): string
    {
        foreach (self::THRESHOLDS as $suffix => $threshold) {
            if ($value < $threshold) {
                return self::format($value, $precision, $threshold, $suffix);
            }
        }

        return self::DEFAULT;
    }

    private static function format(float $value, int $precision, int $threshold, string $suffix): string
    {
        $formattedNumber = number_format($value / ($threshold / self::THRESHOLDS['']), $precision);
        $cleanedNumber = (false === strpos($formattedNumber, '.'))
            ? $formattedNumber
            : rtrim(rtrim($formattedNumber, '0'), '.');

        return $cleanedNumber . $suffix;
    }
}

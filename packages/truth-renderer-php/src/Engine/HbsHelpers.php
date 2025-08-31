<?php

namespace TruthRenderer\Engine;

final class HbsHelpers
{
    public static function upper($v): string
    {
        return strtoupper((string) $v);
    }

    public static function lower($v): string
    {
        return strtolower((string) $v);
    }

    public static function currency($v): string
    {
        return '$' . number_format((float) $v, 2);
    }

    public static function date($v, $fmt = 'Y-m-d'): string
    {
        return date($fmt, strtotime((string) $v));
    }

    public static function multiply($a, $b): float
    {
        return (float) $a * (float) $b;
    }
}

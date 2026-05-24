<?php

namespace App\Support;

class PriceFormatter
{
    public static function format(float|string $price, string $currency = '₦'): string
    {
        $p = (float) $price;
        if ($p == 0.0) {
            return '';
        }
        $str = number_format($p, 2, '.', ',');
        if (str_ends_with($str, '.00')) {
            $str = substr($str, 0, -3);
        }

        return $currency.$str;
    }
}

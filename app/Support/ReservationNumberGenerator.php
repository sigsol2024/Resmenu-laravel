<?php

namespace App\Support;

class ReservationNumberGenerator
{
    public static function generate(): string
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = strlen($chars);
        $result = '';
        for ($i = 0; $i < 8; $i++) {
            $result .= $chars[random_int(0, $len - 1)];
        }

        return $result;
    }
}

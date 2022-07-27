<?php

namespace App\Helper;

final class StringHelper
{
    public static function capitalizeFirstname(string $firstName): string
    {
        $res = preg_match_all('/([\p{L}]+)+/u', $firstName, $matches);

        if (false === $res) {
            return $firstName;
        }

        $capitalizedFirstname = $firstName;
        foreach ($matches[0] as $word) {
            $capitalizedWord = mb_strtoupper(mb_substr($word, 0, 1)).mb_strtolower(mb_substr($word, 1));
            $capitalizedFirstname = str_replace($word, $capitalizedWord, $capitalizedFirstname);
        }

        return $capitalizedFirstname;
    }
}

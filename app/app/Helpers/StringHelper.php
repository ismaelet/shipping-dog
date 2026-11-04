<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Split a string at the last occurrence of a separator.
     */
    public static function explodeLast(string $separator, ?string $string): array
    {
        if ($string === null) {
            return [];
        }

        if ($separator === '') {
            return [$string];
        }

        $position = strrpos($string, $separator);

        if ($position === false) {
            return [$string];
        }

        return [
            substr($string, 0, $position),
            substr($string, $position + strlen($separator)),
        ];
    }
}

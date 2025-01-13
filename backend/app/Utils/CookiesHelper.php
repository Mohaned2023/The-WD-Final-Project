<?php

namespace App\Utils;

class CookiesHelper {
    private static string $KEY = 'user';

    public static function createUserCookie(string $sessionInfo) {
        return cookie(static::$KEY, $sessionInfo, (60 * 60 * 24 * 7), '/','localhost', true, true, false, 'None');
    }

    public static function deleteCookie() {
        return cookie(static::$KEY, null, -1);
    }

    public static function getKey(): string {
        return static::$KEY;
    }
}
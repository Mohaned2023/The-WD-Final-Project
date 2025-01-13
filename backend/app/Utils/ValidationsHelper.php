<?php 

namespace App\Utils;

use Illuminate\Http\Request;

class ValidationsHelper {
    private static $nameReq = ['required', 'string', 'min:3', 'max:255'];
    private static $emailReq = ['required', 'string', 'min:5', 'max:255'];
    private static $passwordReq = ['required', 'string', 'min:8'];

    public static function register(Request &$req): void {
        $req->validate([
            'name' => static::$nameReq,
            'email' => static::$emailReq,
            'password' => static::$passwordReq
        ]);
    }

    public static function login(Request &$req): void {
        $req->validate([
            'email' => static::$emailReq,
            'password' => static::$passwordReq
        ]);
    }

    public static function update(Request &$req): void {
        $req->validate([
            'name' => array_slice(static::$nameReq, 1),
            'email' => array_slice(static::$emailReq, 1),
            'password' => array_slice(static::$passwordReq, 1)
        ]);
    }
}
<?php

namespace App\Utils;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\UnauthorizedException;

class SessionsHelper {
    public static function createSession($user): string {
        return Crypt::encrypt([
            'id' => $user->id,
            'email' => $user->email,
            'is_admin' => $user->is_admin
        ]);
    }

    public static function getUser(Request &$req): void {
        $userFromSession = $req->cookie(CookiesHelper::getKey());
        if ( $userFromSession == null ) throw new UnauthorizedException();
        $userData = Crypt::decrypt($userFromSession);
        $user = User::query()->find($userData['id']);
        if ( empty($user) ) throw new UnauthorizedException();
        $req['user'] = $user; 
    }
}


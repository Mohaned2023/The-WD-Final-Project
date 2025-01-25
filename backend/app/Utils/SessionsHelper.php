<?php

namespace App\Utils;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\UnauthorizedException;

class SessionsHelper {
    public static function createSession($user, string $ip, string $user_agent): string {
        $session = DB::table('sessions')->where('user_id', $user->id)->first();
        if (!empty($session)) return $session->data;
        
        $data = Crypt::encrypt([
            'id' => $user->id,
            'email' => $user->email,
            'is_admin' => $user->is_admin
        ]);
        DB::table('sessions')->insert([
            'user_id' => $user->id,
            'ip_address' => $ip,
            'user_agent' => $user_agent,
            'data' => $data
        ]);
        return $data;
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


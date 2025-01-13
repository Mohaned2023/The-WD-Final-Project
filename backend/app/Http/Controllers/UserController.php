<?php

namespace App\Http\Controllers;

use App\User;
use App\Utils\CookiesHelper;
use App\Utils\SessionsHelper;
use App\Utils\ValidationsHelper;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController {

    /**
     * This function use to Create new account in the app.
     * 
     * ### Errors:
     * - valdiation erros.
     * - UniqueConstraintViolationException error.
     * 
     * @param Request $req
     * @return void
     */
    public function register(Request $req) {
        ValidationsHelper::register($req);
        try {
            $user = User::query()->create([
                'name' => $req['name'],
                'email' => $req['email'],
                'password' => Hash::make($req['password'])
            ]);
            return response()
                ->json($user, 201)
                ->cookie(
                    CookiesHelper::createUserCookie(
                        SessionsHelper::createSession($user)
                    )
                );
        } catch(UniqueConstraintViolationException $error) {
            return response()->json([
                'error' => "The user '{$req->email}' found in the database!"
            ], Response::HTTP_FOUND);
        }
    }

    /**
     * This function use to login the user.
     *
     * ### Errors:
     * - valdiation erros.
     * - User Not Found.
     * - Unauthorized as Password Error.
     * 
     * @param Request $req
     * @return void
     */
    public function login(Request $req) {
        ValidationsHelper::login($req);
        $user = User::where('email', $req['email'])->first();
        if ( empty($user) )
            return response()->json(['error' => 'User NOT found!'], Response::HTTP_NOT_EXTENDED);
        if ( 
            !Hash::check($req['password'],$user->password)
        ) return response()->json(['error' => "Unauthorized!"], Response::HTTP_UNAUTHORIZED);
        return response()
            ->json($user)
            ->cookie(
                CookiesHelper::createUserCookie(
                    SessionsHelper::createSession($user)
                )
            );
    }

    /**
     * This function use to logout the user and delete the session.
     * 
     * ### Errors: No Erroes.
     *
     * @return void
     */
    public function logout() {
        return response()
            ->json(['message' => "Loged out."])
            ->cookie(CookiesHelper::deleteCookie());
    }

    /**
     * This function use to get all uses in the database.
     * 
     * ### Errors:
     * - `Unauthorized` as User is not admin.
     *
     * @param Request $req
     * @return void
     */
    public function getUsers(Request $req) {
        if ( !$req['user']['is_admin'] )
            return response()->json(['error' => 'Unauthorized!'], Response::HTTP_UNAUTHORIZED);
        return response()->json(User::query()->get());
    }

    /**
     * This function use to delete user.
     * 
     * ### Errors:
     * - `Invalid id` id is not a vilad int.
     * - `Unauthorized` permission error.
     * - `User NOT found` User not found.
     *
     * @param integer $id
     * @param Request $req
     * @return void
     */
    public function delete(int $id, Request $req) {
        if ($id < 0 ) 
            return response()->json(['error' => 'Invalid id!'], Response::HTTP_BAD_REQUEST);
        if ( $id != $req['user']['id'] && !$req['user']['is_admin'])
            return response()->json(['error' => 'Unauthorized!'], Response::HTTP_UNAUTHORIZED);

        $user = User::where('id', $id)->first();
        if ( empty($user) ) 
            return response()->json(['error' => 'User NOT found!'], Response::HTTP_NOT_EXTENDED);

        // TODO: Delete the user Session.
        $user->delete();
        return response()->json([]);
    }
}

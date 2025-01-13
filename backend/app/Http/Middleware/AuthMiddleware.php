<?php

namespace App\Http\Middleware;

use App\Utils\SessionsHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            SessionsHelper::getUser($request);
            return $next($request);
        } catch(UnauthorizedException) {
            return response()->json(['error' => 'Unauthorized!'], Response::HTTP_UNAUTHORIZED);
        }
    }
}

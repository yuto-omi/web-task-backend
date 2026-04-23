<?php

namespace App\Shared\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectTokenFromCookie
{
    /**
     * CookieのJWTトークンをAuthorizationヘッダーに移し替える。
     * auth:api ミドルウェアはAuthorizationヘッダーしか見ないため、
     * httpOnly Cookieで送られてきたトークンをここで橋渡しする。
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // すでにAuthorizationヘッダーがある場合はそのまま通す
        if (! $request->bearerToken() && $request->hasCookie('auth_token')) {
            $request->headers->set(
                'Authorization',
                'Bearer '.$request->cookie('auth_token')
            );
        }

        return $next($request);
    }
}

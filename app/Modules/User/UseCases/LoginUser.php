<?php

namespace App\Modules\User\UseCases;

use App\Modules\User\DTO\LoginDTO;
use App\Modules\User\Events\UserLoggedIn;
use App\Modules\User\Models\User;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\JWTGuard;

class LoginUser
{
    /**
     * @return array{access_token: string, token_type: string, expires_in: int, user: User}
     */
    public function handle(LoginDTO $data): array
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        $token = $guard->attempt([
            'email' => $data->email,
            'password' => $data->password,
        ]);

        if (! $token) {
            throw new AuthenticationException('Invalid credentials.');
        }

        $user = $guard->user();
        if (! $user) {
            throw new AuthenticationException('Authentication failed.');
        }

        event(new UserLoggedIn($user));

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $guard->factory()->getTTL() * 60,
            'user' => $user,
        ];
    }
}

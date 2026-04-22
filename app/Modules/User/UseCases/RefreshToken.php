<?php

namespace App\Modules\User\UseCases;

use Tymon\JWTAuth\JWTGuard;

class RefreshToken
{
    /**
     * @return array{access_token: string, token_type: string, expires_in: int}
     */
    public function handle(): array
    {
        /** @var JWTGuard $guard */
        $guard = auth('api');

        $token = $guard->refresh();

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $guard->factory()->getTTL() * 60,
        ];
    }
}

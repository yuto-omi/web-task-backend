<?php

namespace App\Modules\User\UseCases;

use App\Modules\User\Events\UserLoggedOut;

class Logout
{
    public function handle(): void
    {
        $user = auth('api')->user();
        auth('api')->logout();
        event(new UserLoggedOut($user));
    }
}

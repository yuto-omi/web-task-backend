<?php

namespace App\Modules\User\UseCases;

use App\Modules\User\Models\User;

class GetMe
{
    public function handle(): User
    {
        return auth('api')->user();
    }
}

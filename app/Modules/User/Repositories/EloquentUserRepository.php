<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Contracts\UserRepository;
use App\Modules\User\Models\User;

class EloquentUserRepository implements UserRepository
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }
}

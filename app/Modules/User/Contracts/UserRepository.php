<?php

namespace App\Modules\User\Contracts;

use App\Modules\User\Models\User;

interface UserRepository
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): User;

    public function findByEmail(string $email): ?User;
}

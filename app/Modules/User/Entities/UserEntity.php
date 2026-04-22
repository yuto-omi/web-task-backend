<?php

namespace App\Modules\User\Entities;

use App\Modules\User\ValueObjects\EmailAddress;

class UserEntity
{
    public function __construct(
        public readonly string $name,
        public readonly EmailAddress $email,
        public readonly string $passwordHash
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email->value(),
            'password' => $this->passwordHash,
        ];
    }
}

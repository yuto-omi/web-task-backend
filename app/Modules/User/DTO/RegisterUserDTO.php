<?php

namespace App\Modules\User\DTO;

class RegisterUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['name'],
            (string) $data['email'],
            (string) $data['password']
        );
    }
}

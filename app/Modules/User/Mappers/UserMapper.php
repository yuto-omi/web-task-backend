<?php

namespace App\Modules\User\Mappers;

use App\Modules\User\DTO\RegisterUserDTO;
use App\Modules\User\Entities\UserEntity;
use App\Modules\User\ValueObjects\EmailAddress;

class UserMapper
{
    public function toEntityFromRegisterDto(RegisterUserDTO $dto, string $passwordHash): UserEntity
    {
        $email = new EmailAddress($dto->email);

        return new UserEntity(
            name: $dto->name,
            email: $email,
            passwordHash: $passwordHash
        );
    }
}

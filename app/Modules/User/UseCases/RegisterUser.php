<?php

namespace App\Modules\User\UseCases;

use App\Modules\User\Contracts\UserRepository;
use App\Modules\User\DTO\RegisterUserDTO;
use App\Modules\User\Events\UserRegistered;
use App\Modules\User\Mappers\UserMapper;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTGuard;

class RegisterUser
{
    public function __construct(private readonly UserRepository $users) {}

    /**
     * @return array{access_token: string, token_type: string, expires_in: int, user: User}
     */
    public function handle(RegisterUserDTO $data): array
    {
        $entity = (new UserMapper)->toEntityFromRegisterDto($data, Hash::make($data->password));

        $user = $this->users->create($entity->toArray());
        event(new UserRegistered($user));

        /** @var JWTGuard $guard */
        $guard = auth('api');
        $token = $guard->login($user);

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $guard->factory()->getTTL() * 60,
            'user' => $user,
        ];
    }
}

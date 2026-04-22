<?php

namespace App\Modules\User\Controllers;

use App\Modules\User\Models\User;
use App\Modules\User\Resources\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController
{
    /** ユーザー一覧（メンバー選択用） */
    public function index(): AnonymousResourceCollection
    {
        $users = User::orderBy('name')->get();

        return UserResource::collection($users);
    }
}

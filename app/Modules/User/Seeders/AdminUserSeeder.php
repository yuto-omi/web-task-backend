<?php

namespace App\Modules\User\Seeders;

use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) config('admin.email', 'admin@example.com');
        $password = (string) config('admin.password', 'password');

        $user = User::query()->firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => Hash::make($password),
            ]
        );

        $adminRole = Role::findByName('admin', 'api');
        $user->syncRoles([$adminRole]);
    }
}

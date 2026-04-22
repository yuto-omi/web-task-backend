<?php

namespace App\Modules\User\Seeders;

use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $editorRole = Role::findByName('editor', 'api');

        // webチームの仮メンバーを作成（固定アカウント）
        $fixedUsers = [
            ['name' => '田中 太郎', 'email' => 'tanaka@example.com'],
            ['name' => '佐藤 花子', 'email' => 'sato@example.com'],
            ['name' => '鈴木 一郎', 'email' => 'suzuki@example.com'],
            ['name' => '高橋 美咲', 'email' => 'takahashi@example.com'],
            ['name' => '伊藤 健太', 'email' => 'ito@example.com'],
        ];

        foreach ($fixedUsers as $data) {
            $user = User::factory()->create([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
            $user->assignRole($editorRole);
        }

        // ランダムメンバーを追加で5人作成
        $randomUsers = User::factory()->count(5)->create();
        $randomUsers->each(fn (User $user) => $user->assignRole($editorRole));
    }
}

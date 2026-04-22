<?php

namespace Database\Seeders;

use App\Modules\News\Seeders\NewsSeeder;
use App\Modules\NewsCategory\Seeders\NewsCategorySeeder;
use App\Modules\Project\Seeders\ProjectSeeder;
use App\Modules\User\Seeders\AdminUserSeeder;
use App\Modules\User\Seeders\RolesAndPermissionsSeeder;
use App\Modules\User\Seeders\UserSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            UserSeeder::class,
            NewsCategorySeeder::class,
            NewsSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}

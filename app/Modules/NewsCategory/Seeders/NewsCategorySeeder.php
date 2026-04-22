<?php

namespace App\Modules\NewsCategory\Seeders;

use App\Modules\NewsCategory\Models\NewsCategory;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        NewsCategory::factory()->count(5)->create();
    }
}

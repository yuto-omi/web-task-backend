<?php

namespace App\Modules\News\Seeders;

use App\Modules\News\Models\News;
use App\Modules\NewsCategory\Models\NewsCategory;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = NewsCategory::query()->pluck('id');

        if ($categories->isEmpty()) {
            $categories = NewsCategory::factory()->count(3)->create()->pluck('id');
        }

        News::factory()
            ->count(10)
            ->make()
            ->each(function ($news) use ($categories): void {
                if (! $news instanceof News) {
                    return;
                }

                $news->category_id = $categories->random();
                $news->save();
            });
    }
}

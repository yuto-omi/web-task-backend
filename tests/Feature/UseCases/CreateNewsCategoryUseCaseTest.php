<?php

use App\Modules\NewsCategory\DTO\CreateNewsCategoryDTO;
use App\Modules\NewsCategory\Events\NewsCategoryCreated;
use App\Modules\NewsCategory\UseCases\CreateNewsCategory;
use Illuminate\Support\Facades\Event;

it('creates a news category via use case', function () {
    Event::fake([NewsCategoryCreated::class]);

    $useCase = app(CreateNewsCategory::class);

    $dto = CreateNewsCategoryDTO::fromArray([
        'name' => 'Tech',
    ]);

    $category = $useCase->handle($dto);

    expect($category->exists)->toBeTrue();
    expect($category->name)->toBe('Tech');
    expect($category->slug)->not->toBeEmpty();

    Event::assertDispatched(
        NewsCategoryCreated::class,
        fn (NewsCategoryCreated $event): bool => $event->category->is($category)
    );
});

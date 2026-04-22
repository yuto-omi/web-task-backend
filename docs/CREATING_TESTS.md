## Creating tests (Pest) – step by step

This project uses **Pest**. Tests live under `tests/Feature` and `tests/Unit`. The test suite is configured in `tests/Pest.php` and already uses `RefreshDatabase`.

### 1) Choose the test type

- **Feature tests** (`tests/Feature`): test HTTP endpoints (controllers, middleware, auth, policies, JSON responses).
- **Unit tests** (`tests/Unit`): test isolated logic (value objects, mappers, rules, DTO parsing).
- **UseCase tests** (recommended): even though they are not HTTP, these usually go in `tests/Feature/UseCases` because they hit the database and dispatch events.

### 2) Create the test file

You can create tests either by:

- **Artisan**:

```bash
php artisan make:test --pest CreateSomethingTest
```

- **Manually**: create a `.php` file under `tests/Feature` or `tests/Unit`.

### 3) Write a Pest test

Pest tests look like this:

```php
it('does something', function () {
    expect(true)->toBeTrue();
});
```

### 4) Use the project helpers (admin + auth header)

This repo provides helper functions in `tests/Pest.php`:

- `actingAsAdmin()` returns `{ user, token }` and assigns the needed permissions.
- `authHeader($auth)` returns the `Authorization: Bearer ...` header.

Example (admin request):

```php
$auth = actingAsAdmin();

$this->getJson('/api/admin/news', authHeader($auth))
    ->assertOk();
```

### 5) Testing a UseCase (example)

Use cases are typically resolved from the container and executed with a DTO:

```php
use App\Modules\NewsCategory\DTO\CreateNewsCategoryDTO;
use App\Modules\NewsCategory\Events\NewsCategoryCreated;
use App\Modules\NewsCategory\UseCases\CreateNewsCategory;
use Illuminate\Support\Facades\Event;

it('creates a news category via use case', function () {
    Event::fake([NewsCategoryCreated::class]);

    $useCase = app(CreateNewsCategory::class);

    $dto = CreateNewsCategoryDTO::fromArray([
        'name' => 'Tech',
        'description' => 'Technology news',
        'is_active' => true,
    ]);

    $category = $useCase->handle($dto);

    expect($category->exists)->toBeTrue();
    expect($category->slug)->not->toBeEmpty();

    Event::assertDispatched(
        NewsCategoryCreated::class,
        fn (NewsCategoryCreated $event): bool => $event->category->is($category)
    );
});
```

You can also start from the template:

- `tests/Unit/Templates/UseCaseTestTemplate.php`

### 6) Run tests

Run all tests:

```bash
php artisan test --compact
```

Run only one file:

```bash
php artisan test --compact tests/Feature/UseCases/CreateNewsCategoryUseCaseTest.php
```

Run only one test (filter by name):

```bash
php artisan test --compact --filter="creates a news category"
```

### 7) Common patterns

- **Events**: use `Event::fake()` + `Event::assertDispatched()`
- **DB assertions**: use `expect($model->exists)->toBeTrue()` or Laravel helpers like `assertDatabaseHas()`
- **HTTP assertions**: prefer `assertOk()`, `assertCreated()`, `assertNoContent()`, `assertForbidden()`, etc.


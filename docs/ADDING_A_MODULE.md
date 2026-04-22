## Adding a new module (step-by-step)

This project is a modular monolith. Every module lives under `app/Modules/{ModuleName}` with a **flattened** folder structure and **automatic provider discovery**.

### 0) Decide the module scope

- Pick a clear business capability name (e.g. `Billing`, `Comments`, `Inventory`).
- Decide whether it needs:
  - HTTP endpoints (public/admin)
  - persistence (Eloquent model + migrations)
  - authorization (policies/permissions)
  - events/listeners

### 1) Create the module folder skeleton

Create a new folder at `app/Modules/{ModuleName}`. Minimum recommended structure:

```
app/Modules/{ModuleName}/
  Routes/
    api.php
  Controllers/
  Requests/
  Resources/
  DTO/
  UseCases/
  Mappers/
  Entities/
  ValueObjects/
  Contracts/
  Repositories/
  Models/
  Policies/
  Events/
  Listeners/
  Providers/
  Migrations/
  Seeders/
```

Only create what you actually need (but keep naming consistent).

### 2) Add routes for the module

1. Define endpoints in `app/Modules/{ModuleName}/Routes/api.php`.
2. Register the module routes in the global API router:
   - Edit `routes/api.php` and add:

```php
require app_path('Modules/{ModuleName}/Routes/api.php');
```

Use route groups as needed (e.g. `Route::prefix('admin')->middleware('auth:api')...`).

### 3) Create Controllers (thin)

Place controllers in `app/Modules/{ModuleName}/Controllers`.

Controller responsibilities:

- authorize (policy/gates) when needed
- validate input via `Requests/*Request.php`
- build DTO from validated payload (`DTO::fromArray($validated)`)
- call a UseCase
- return a Resource

Keep controllers “thin”: no business rules inside.

### 4) Add Form Requests (validation)

Place requests in `app/Modules/{ModuleName}/Requests`.

- Implement rules for input validation.
- Use `authorize()` to block unauthorized requests early (optional).

### 5) Define DTOs for input

Place DTOs in `app/Modules/{ModuleName}/DTO`.

Conventions:

- Use separate DTOs for create/update when it makes sense (e.g. `CreateThingDTO`, `UpdateThingDTO`).
- Provide a `fromArray(array $data): self` constructor for controllers.

### 6) Add a Mapper (DTO → Entity)

Place the mapper in `app/Modules/{ModuleName}/Mappers`.

Conventions:

- Name: `{Aggregate}Mapper` (e.g. `NewsMapper`)
- Main method: `toEntity(...) : *Entity`
- Put cross-field rules here when they are part of building a valid entity (e.g. “published_at required when status=published”).

### 7) Create Entities and ValueObjects

Place:

- Entities in `Entities/`
- Value objects in `ValueObjects/`

Entities should:

- model the data you want to persist
- expose `toArray()` for repository persistence payloads

### 8) Define repository contract + implementation

1. Create a contract in `Contracts/`, e.g. `{Thing}Repository`.
2. Create implementation in `Repositories/`, e.g. `Eloquent{Thing}Repository`.
3. Use an Eloquent model in `Models/`.

### 9) Register module bindings (automatic discovery)

Create a module provider in `app/Modules/{ModuleName}/Providers/{ModuleName}ServiceProvider.php` and bind interfaces to implementations there:

- `Contracts\*Repository` → `Repositories\*Repository`

Important:

- Providers are discovered automatically by `App\Providers\ModuleDiscoveryServiceProvider` as long as the file matches:
  - `app/Modules/*/Providers/*ServiceProvider.php`
- You do **not** need to edit `bootstrap/providers.php` for module providers.

### 10) Add migrations (optional)

Put module migrations under `app/Modules/{ModuleName}/Migrations`.

Your `{ModuleName}ServiceProvider` can load them using:

- `$this->loadMigrationsFrom(dirname(__DIR__).'/Migrations');`

### 11) Add policies + permissions (optional but common for admin)

1. Create a policy in `app/Modules/{ModuleName}/Policies`.
2. Register it in `app/Providers/AuthServiceProvider.php` (central mapping).
3. If using Spatie permissions, add permission strings in:
   - `app/Modules/User/Seeders/RolesAndPermissionsSeeder.php`

Typical pattern:

- `thing.viewAny`, `thing.view`, `thing.create`, `thing.update`, `thing.delete`

### 12) Add events/listeners (optional)

1. Create event classes in `Events/`.
2. Create listeners in `Listeners/`.
3. Create an event provider in `Providers/` (e.g. `{ModuleName}EventServiceProvider`) and map event → listener(s).

This provider is also discovered automatically (same rule: `*ServiceProvider.php`).

### 13) Add API resources (response shaping)

Place Resources in `app/Modules/{ModuleName}/Resources`.

For Larastan/PHPStan, add a mixin to each JsonResource:

```php
/**
 * @mixin \App\Modules\{ModuleName}\Models\YourModel
 */
class YourResource extends JsonResource {}
```

### 14) Add tests (recommended)

Add at least:

- A **UseCase test** (fast, focused)
- A **Feature test** for the HTTP endpoint(s)

You can copy the template:

- `tests/Unit/Templates/UseCaseTestTemplate.php`

And see a working example:

- `tests/Feature/UseCases/CreateNewsCategoryUseCaseTest.php`

Run:

```bash
php artisan test --compact
composer analyse
```

### 15) (Optional) Document OpenAPI annotations

Controllers already include `@OA\...` annotations. Add/adjust annotations in controllers as needed, then run:

```bash
php artisan l5-swagger:generate
```


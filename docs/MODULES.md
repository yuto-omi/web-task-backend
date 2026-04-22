## Modules architecture (`app/Modules`)

This project is a **modular monolith** organized by business capability (module) and inspired by **Clean / Hexagonal** concepts.

Each module lives under `app/Modules/{ModuleName}`. The physical folder structure is **flattened** (no `Application/`, `Domain/`, `Infrastructure/`, `Interface/` folders), but the *responsibilities* are still separated by subfolders (DTO, UseCases, Entities, etc.).

Current modules:

- `News`
- `NewsCategory`
- `User`

### Typical module structure

```
app/Modules/{ModuleName}/
  Controllers/
  Requests/
  Resources/
  Routes/
    api.php
  Policies/

  DTO/
  UseCases/
  Contracts/
  Entities/
  ValueObjects/
  Rules/

  Models/
  Repositories/
  Factories/
  Seeders/
  Migrations/
  Providers/

  Events/
  Listeners/
```

## Responsibilities (by folder)

### HTTP / API boundary

These folders are responsible for transport-specific concerns:

- **`Controllers/`**: translate HTTP into use case calls (thin controllers)
- **`Requests/`**: validation + authorization for incoming requests (Form Requests)
- **`Resources/`**: shape JSON responses consistently (API Resources)
- **`Policies/`**: authorization rules for the module’s models
- **`Routes/api.php`**: route definitions for the module

Controllers should avoid business logic; they should delegate to `UseCases/*`.

### Use cases and application orchestration

These folders contain the module’s orchestration (application services):

- **`UseCases/`**: one business operation per class (e.g. `CreateNews`, `ListPublicNews`)
- **`DTO/`**: structured input data passed into use cases (e.g. `CreateNewsDTO`, `LoginDTO`)
- **`Events/` + `Listeners/`**: cross-cutting reactions to use case outcomes (e.g. cache invalidation)

Use cases:

- Are called by controllers
- Use domain objects (`Entities/`, `ValueObjects/`) for normalization/validation
- Talk to persistence via `Contracts/*` (repositories)

### Domain model

These folders contain business rules and reusable domain code:

- **`Entities/`**: business concepts and stable APIs to create/update state
- **`ValueObjects/`**: invariants and transformations (e.g. slugs, email addresses, statuses)
- **`Contracts/`**: interfaces the use cases depend on (e.g. `NewsRepository`)
- **`Rules/`**: domain/application rules that must be enforced consistently

The key idea remains: **UseCases depend on Contracts**, and **Repositories implement Contracts**.

### Persistence and framework integration

These folders provide concrete implementations:

- **`Models/`**: Eloquent models representing persisted state
- **`Repositories/`**: repository implementations (e.g. `EloquentNewsRepository`)
- **`Factories/`**: model factories (when used)
- **`Seeders/`**: module-specific seeders
- **`Migrations/`**: module migrations (optional)
- **`Providers/`**: module service providers (e.g. module event providers)

These folders should not contain business rules; they focus on framework integration and persistence.

## How modules are wired into the application

### Routes aggregation

Top-level API routing in `routes/api.php` requires each module’s route file:

- `app/Modules/User/Routes/api.php`
- `app/Modules/NewsCategory/Routes/api.php`
- `app/Modules/News/Routes/api.php`

This keeps each module’s endpoints colocated with the module while preserving a single Laravel route entrypoint.

### Dependency inversion (repository bindings)

Repository contracts are defined in `Contracts`, and implementations live in `Repositories`.

Bindings are registered in `app/Providers/AppServiceProvider.php`, for example:

- `App\Modules\News\Contracts\NewsRepository` → `App\Modules\News\Repositories\EloquentNewsRepository`
- `App\Modules\NewsCategory\Contracts\NewsCategoryRepository` → `App\Modules\NewsCategory\Repositories\EloquentNewsCategoryRepository`
- `App\Modules\User\Contracts\UserRepository` → `App\Modules\User\Repositories\EloquentUserRepository`

### Authorization (policies)

Policies live inside each module (`Policies`), but are registered centrally in
`app/Providers/AuthServiceProvider.php` via the `$policies` map.

### Migrations

The app can load module migrations via `AppServiceProvider::loadMigrationsFrom(...)`.

Currently, migrations are loaded from:

- `app/Modules/User/Migrations`
- `app/Modules/NewsCategory/Migrations`
- `app/Modules/News/Migrations`

Additionally, the project also contains standard Laravel migrations under `database/migrations`.

### Events and listeners

Modules publish application-level events from use cases using:

```php
event(new SomeEvent($payload));
```

Listeners are registered in module event providers under `app/Modules/*/Providers/*EventServiceProvider.php`,
which are loaded in `bootstrap/providers.php`.

Current examples:

- **News**: `NewsCreated|NewsUpdated|NewsDeleted` → `InvalidateNewsPublicCache`
- **NewsCategory**: `NewsCategoryCreated|NewsCategoryUpdated|NewsCategoryDeleted` → `InvalidateNewsCategoryPublicCache`
- **User**: `UserRegistered|UserLoggedIn|UserLoggedOut` → `UserAuthEventListener` (placeholder for future behavior)

This pattern keeps the core use case focused on the primary business operation while allowing
side-effects (like cache invalidation) to be implemented and tested independently.

## Flow: Request → DTO → Entity (with a standard Mapper)

This project uses a consistent flow for write operations:

1. **Request** (`Requests/*Request.php`) validates and normalizes input.
2. **DTO** (`DTO/*DTO.php`) is built from validated data via `::fromArray($validated)`.
3. **UseCase** (`UseCases/*`) orchestrates the operation and calls a **Mapper**.
4. **Mapper** (`Mappers/*Mapper.php`) converts DTOs to domain **Entities** (`Entities/*Entity.php`) and applies cross-field rules (e.g. `published_at` required when status is `published`).
5. **Entity** produces persistence payload via `->toArray()`.
6. **Repository** (`Contracts/*Repository.php` + `Repositories/*`) persists data.

### Mapper convention

- Mappers live in `Mappers/`
- Naming: `{Aggregate}Mapper` (e.g. `NewsMapper`)
- Primary method: `toEntity(...)` returning an `*Entity`

Examples in this codebase:

- `App\Modules\News\Mappers\NewsMapper`
- `App\Modules\NewsCategory\Mappers\NewsCategoryMapper`
- `App\Modules\User\Mappers\UserMapper`

## Adding a new module (checklist)

1. **Create the module folder** under `app/Modules/{NewModule}` with the standard subfolders you need.
2. **Define repository contracts** in `Contracts/` (e.g. `{Thing}Repository`).
3. **Implement models/repositories** in `Models/` and `Repositories/`.
4. **Bind contracts to implementations** in `app/Providers/AppServiceProvider.php`.
5. **Add routes** in `Routes/api.php` and include that file from `routes/api.php`.
6. **Create Form Requests** under `Requests/` for validation/authorization.
7. **Create API Resources** under `Resources/` for consistent JSON output.
8. **Add Policies** under `Policies/` and register them in `AuthServiceProvider` if needed.
9. **(Optional) Add module events/listeners** under `Events/` and `Listeners/`, register them in a module provider under `Providers/` and load it in `bootstrap/providers.php`.
10. **(Optional) Add migrations** under `Migrations/` and load them via `AppServiceProvider::loadMigrationsFrom(...)`.


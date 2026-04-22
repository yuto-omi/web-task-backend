## Static analysis (PHPStan/Larastan) and formatting (Pint)

This repo includes:

- **PHPStan + Larastan** (`nunomaduro/larastan`)
- **Laravel Pint** (`laravel/pint`)

### 1) Run static analysis

Composer script:

```bash
composer analyse
```

This runs:

```bash
vendor/bin/phpstan analyse --memory-limit=1G
```

Config file:

- `phpstan.neon`

### 2) Typical Larastan gotchas in this codebase

- **Resources (`JsonResource`)**: add `@mixin` to point to the Eloquent model so PHPStan understands “magic” properties.
- **JWT guard methods**: cast `auth('api')` to `Tymon\JWTAuth\JWTGuard` before calling JWT-specific methods.
- **Eloquent relationships PHPDoc**: keep generic types consistent (e.g. `BelongsTo<Model, $this>`).

### 3) Run Pint

Project rule: run Pint before finalizing changes.

```bash
vendor/bin/pint --dirty --format agent
```

If `vendor/bin/pint` is not executable in your environment, use:

```bash
php vendor/bin/pint --dirty --format agent
```

### 4) Suggested CI baseline

If you add CI, the minimum useful checks for this repo are:

- `composer analyse`
- `php artisan test --compact`
- `vendor/bin/pint --dirty --format agent` (or `--test` in CI if you prefer)


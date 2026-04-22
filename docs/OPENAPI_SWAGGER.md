## OpenAPI / Swagger (L5-Swagger)

This project uses `darkaonline/l5-swagger` and `zircote/swagger-php` to generate OpenAPI documentation by scanning annotations in `app/`.

### 1) Where OpenAPI “root” metadata lives

Base OpenAPI metadata and shared schemas live in:

- `app/OpenApi/OpenApi.php`

This file declares:

- API info (title/version/description)
- bearer auth security scheme (`bearerAuth`)
- shared schemas (AuthResponse, News, NewsCategory, etc.)

### 2) Generation command

Generate (or re-generate) docs:

```bash
php artisan l5-swagger:generate
```

Generated files are written to:

- `storage/api-docs/` (see `config/l5-swagger.php`)

### 3) Swagger UI route

Swagger UI is exposed at:

- `/api/documentation` (see `config/l5-swagger.php` → `documentations.default.routes.api`)

### 4) Where annotations should go

By default, L5-Swagger scans:

- `base_path('app')`

So you can add `@OA\Get`, `@OA\Post`, etc. annotations in module controllers, or in dedicated annotation classes under `app/`.

### 5) Common troubleshooting

#### “Skipping unknown …”

Common causes:

- namespace mismatch (class cannot be autoloaded)
- stale references after moving files/namespaces

Fix:

- ensure class namespaces match file paths under `app/`
- run `composer dump-autoload`
- run `php artisan l5-swagger:generate` again

#### Missing Bearer auth in UI

Ensure your operations reference security:

- `security={{"bearerAuth":{}}}`

Or set global security via your OpenAPI annotations.


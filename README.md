## Modular Laravel API Template

Modular monolith for Laravel 12, organized by business capability under `app/Modules/*`:

- **News**
- **NewsCategory**
- **User** (JWT auth)

Controllers are thin and call UseCases. Input is validated via Form Requests and module Validators; write operations dispatch events and listeners handle side effects (e.g. cache invalidation).

### Requirements

- PHP 8.2+
- Laravel 12
- Composer
- Node 18+ (Vite)

### Setup (local)

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan db:seed
```

Run the dev stack:

```bash
composer run dev
```

### Environment Variables

- `JWT_SECRET` (required for JWT)
- `JWT_TTL` (minutes, default 60)
- `CACHE_PUBLIC_TTL` (seconds, default 300)
- `ADMIN_EMAIL` / `ADMIN_PASSWORD` (admin seed user)

### Documentation

- `docs/MODULES.md` (module architecture)
- `docs/ADDING_A_MODULE.md` (step-by-step: create a module)
- `docs/CREATING_TESTS.md` (testing guide)
- `docs/AUTH_AND_PERMISSIONS.md` (JWT + Spatie permissions)
- `docs/LOCAL_DEVELOPMENT.md` (local setup/troubleshooting)
- `docs/OPENAPI_SWAGGER.md` (Swagger/OpenAPI)
- `docs/STATIC_ANALYSIS_AND_FORMATTING.md` (PHPStan/Larastan + Pint)
- `docs/MODULE_PROVIDER_DISCOVERY.md` (module provider auto-discovery)

### Auth Endpoints

- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `POST /api/auth/refresh`
- `GET /api/auth/me`

### Public Endpoints

- `GET /api/public/news`
- `GET /api/public/news/{slug}`
- `GET /api/public/news-categories`
- `GET /api/public/news-categories/{slug}`

### Admin Endpoints (JWT + permissions)

- `GET /api/admin/news`
- `POST /api/admin/news`
- `GET /api/admin/news/{id}`
- `PUT/PATCH /api/admin/news/{id}`
- `DELETE /api/admin/news/{id}`
- `GET /api/admin/news-categories`
- `POST /api/admin/news-categories`
- `GET /api/admin/news-categories/{id}`
- `PUT/PATCH /api/admin/news-categories/{id}`
- `DELETE /api/admin/news-categories/{id}`

### Cache Strategy

Public endpoints use cached responses with TTL from `CACHE_PUBLIC_TTL`. If the cache driver supports tags (Redis/Memcached/Array), tags are flushed on writes. Otherwise, a versioned namespace is used for invalidation.

### Running Tests

- `php artisan test --compact`

### Static analysis

- `composer analyse`

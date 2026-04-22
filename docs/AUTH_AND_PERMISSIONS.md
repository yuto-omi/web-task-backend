## Auth (JWT) and permissions (Spatie)

This API uses:

- **JWT auth** (`tymon/jwt-auth`) on the `api` guard
- **Spatie permissions** (`spatie/laravel-permission`) for authorization
- **Policies** per module for admin endpoints

### 1) How auth works in this repo

- Guard: `config/auth.php` → `guards.api.driver = jwt`
- Protected routes use: `middleware('auth:api')`

If you call an admin endpoint:

- **401** usually means: missing/invalid Bearer token
- **403** usually means: token is valid, but **permissions/policy** denied

### 2) Getting a token

Endpoints (see `app/Modules/User/Routes/api.php`):

- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/refresh` (requires auth)
- `GET /api/auth/me` (requires auth)
- `POST /api/auth/logout` (requires auth)

Example login:

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

Use the token:

```bash
curl http://localhost:8000/api/admin/news \
  -H "Authorization: Bearer <token>"
```

### 3) Why you may get 403 even with Bearer

Admin controllers call `$this->authorize(...)` which triggers module policies.

Example: `NewsPolicy::create()` requires the permission `news.create`.

If your user does not have the required permissions (via Spatie roles), you’ll get **403**.

### 4) Roles & permissions seeding

Seeders:

- `app/Modules/User/Seeders/RolesAndPermissionsSeeder.php`
- `app/Modules/User/Seeders/AdminUserSeeder.php`

Run:

```bash
php artisan db:seed --class="App\\Modules\\User\\Seeders\\RolesAndPermissionsSeeder"
php artisan db:seed --class="App\\Modules\\User\\Seeders\\AdminUserSeeder"
```

Admin credentials are configured via `config/admin.php`:

- `ADMIN_EMAIL`
- `ADMIN_PASSWORD`

### 5) Debug checklist for auth failures

1. Call `GET /api/auth/me` with your token to confirm which user is authenticated.
2. Verify that user has the right role/permissions (admin role, or specific permissions).
3. Check the policy used by the endpoint (e.g. `app/Modules/News/Policies/NewsPolicy.php`).


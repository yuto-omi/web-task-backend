## Local development

This project is a Laravel 12 API with a modular structure under `app/Modules/*`.

### 1) Install dependencies

```bash
composer install
npm install
```

### 2) Create `.env`

```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### 3) Database setup

Run migrations + seeders:

```bash
php artisan migrate
php artisan db:seed
```

Notes:

- Auth is JWT (`config/auth.php` guard `api` uses driver `jwt`).
- Admin seeding reads admin credentials from `config/admin.php` (which uses `ADMIN_EMAIL` / `ADMIN_PASSWORD`).

### 4) Run the app (API + queue + Vite)

Use the repo’s dev script:

```bash
composer run dev
```

This runs:

- `php artisan serve`
- `php artisan queue:listen`
- `npm run dev`

### 5) Common commands

- **Run tests**:

```bash
php artisan test --compact
```

- **Run static analysis**:

```bash
composer analyse
```

- **Generate Swagger/OpenAPI**:

```bash
php artisan l5-swagger:generate
```

### 6) Troubleshooting

#### Composer scripts failing due to DB

Some Composer scripts (e.g. `boost:update`) may attempt to access the configured database. If your DB container/host is not running, you can see connection errors.

#### Swagger “Skipping unknown …”

This is usually caused by stale namespaces or non-autoloadable classes referenced by annotations. Ensure namespaces match file paths, then re-run `php artisan l5-swagger:generate`.


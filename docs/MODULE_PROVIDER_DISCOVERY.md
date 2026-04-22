## Module provider discovery

This project automatically registers module service providers found under `app/Modules/*/Providers/`.

### 1) Why this exists

Instead of manually adding each module provider to `bootstrap/providers.php`, we keep the core providers list small and let modules “self-register”.

This keeps modules more independent and reduces merge conflicts.

### 2) How discovery works

The provider `App\Providers\ModuleDiscoveryServiceProvider` scans:

- `app/Modules/*/Providers/*ServiceProvider.php`

For each file found, it derives the class name and registers it via:

- `$this->app->register($providerClass)`

### 3) Where it is enabled

`bootstrap/providers.php` includes:

- `App\Providers\ModuleDiscoveryServiceProvider::class`

### 4) What modules should register in their providers

Typical module provider responsibilities:

- **Bindings** (repositories, services)
- **Event listeners** (via a module `*EventServiceProvider`)
- **Migrations** (`loadMigrationsFrom(...)`)
- **Policies** (if module handles it locally; currently global policies live in `App\Providers\AuthServiceProvider`)

### 5) Adding a new module provider

Create:

- `app/Modules/{Module}/Providers/{Module}ServiceProvider.php`

Optionally add:

- `app/Modules/{Module}/Providers/{Module}EventServiceProvider.php`

No manual change in `bootstrap/providers.php` is required as long as the file is under the discovery glob.


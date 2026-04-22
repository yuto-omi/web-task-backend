<?php

namespace App\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;

class ModuleDiscoveryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $providers = $this->discoverModuleProviders();

        foreach ($providers as $providerClass) {
            $this->app->register($providerClass);
        }
    }

    /**
     * @return list<class-string<ServiceProvider>>
     */
    private function discoverModuleProviders(): array
    {
        $filesystem = new Filesystem;
        $modulesPath = app_path('Modules');

        if (! $filesystem->isDirectory($modulesPath)) {
            return [];
        }

        $providerFiles = $filesystem->glob($modulesPath.'/*/Providers/*ServiceProvider.php') ?: [];
        sort($providerFiles);

        $providers = [];
        foreach ($providerFiles as $file) {
            $providers[] = $this->classFromAppPath($file);
        }

        return $providers;
    }

    /**
     * @return class-string<ServiceProvider>
     */
    private function classFromAppPath(string $absolutePath): string
    {
        $relative = str_replace(app_path().DIRECTORY_SEPARATOR, '', $absolutePath);
        $relative = str_replace(['/', '\\'], '\\', $relative);
        $relative = preg_replace('/\\.php$/', '', $relative) ?: $relative;

        /** @var class-string<ServiceProvider> $class */
        $class = 'App\\'.$relative;

        return $class;
    }
}

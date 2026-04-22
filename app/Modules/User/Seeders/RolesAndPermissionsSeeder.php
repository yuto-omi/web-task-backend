<?php

namespace App\Modules\User\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = 'api';

        $permissions = [
            'news.viewAny',
            'news.view',
            'news.create',
            'news.update',
            'news.delete',
            'newsCategory.viewAny',
            'newsCategory.view',
            'newsCategory.create',
            'newsCategory.update',
            'newsCategory.delete',
            'user.manage',
            'task.viewAny',
            'task.view',
            'task.create',
            'task.update',
            'task.delete',
        ];

        $permissions = array_values(array_unique(array_map('trim', $permissions)));

        $permissionModels = [];
        foreach ($permissions as $permission) {
            $permissionModels[] = Permission::findOrCreate($permission, $guard);
        }

        $adminRole = Role::findOrCreate('admin', $guard);
        $editorRole = Role::findOrCreate('editor', $guard);

        $adminRole->syncPermissions($permissionModels);

        $editorRole->syncPermissions(array_map('trim', [
            'news.viewAny',
            'news.view',
            'news.create',
            'news.update',
            'newsCategory.viewAny',
            'newsCategory.view',
            'newsCategory.create',
            'newsCategory.update',
            'task.viewAny',
            'task.view',
            'task.create',
            'task.update',
            'task.delete',
        ]));
    }
}

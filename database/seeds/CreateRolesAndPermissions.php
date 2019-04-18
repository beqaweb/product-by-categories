<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateRolesAndPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::findOrCreate('manage category');
        Permission::findOrCreate('manage product');

        // create roles
        $super_admin = Role::findOrCreate('super-admin');
        $admin_manager = Role::findOrCreate('admin-manager');
        $user = Role::findOrCreate('user');

        $super_admin->syncPermissions([
            'manage category',
            'manage product'
        ]);
    }
}

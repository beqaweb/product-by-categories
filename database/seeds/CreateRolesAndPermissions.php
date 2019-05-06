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
        Permission::findOrCreate('assign roles');

        // create roles
        $super_admin = Role::findOrCreate('Super admin');
        $admin_manager = Role::findOrCreate('Admin manager');
        $user = Role::findOrCreate('User');

        $super_admin->syncPermissions([
            'manage category',
            'manage product',
            'assign roles'
        ]);
    }
}

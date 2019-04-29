<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminUser = User::query()->firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Default user',
            'password' => Hash::make('secret')
        ]);

        $superAdminUser->syncRoles([
            'Super admin'
        ]);
    }
}

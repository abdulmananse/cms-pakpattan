<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateOrCreate([
            'username' => 'super_admin', 
        ], [
            'name' => 'Super Admin', 
            'role' => 'Super Admin',
            'email' => 'super_admin@dcpakpattan.gov.pk',
            'password' => Hash::make('Admin@p!t6')
        ]);
        $user->syncRoles(['Super Admin']);

        $user = User::updateOrCreate([
            'username' => 'admin',
        ], [
            'name' => 'Admin', 
            'role' => 'Admin',
            'email' => 'admin@dcpakpattan.gov.pk',
            'password' => Hash::make('Admin@pakpattan')
        ]);
        $user->syncRoles(['Admin']);

    }
}

<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\User;
use Spatie\Permission\Models\Role;

class AuthDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::whereEmail('admin@artsycrowd.com')->delete();

        $admin = User::factory()->create([
            'first_name' => 'Nikko',
            'last_name' => 'Mendoza',
            'email' => 'admin@artsycrowd.com',
            'password' => Hash::make('ueDL80vSUT9^'),
        ]);

        Role::firstOrCreate(['name' => 'ADMIN']);

        $admin->assignRole('ADMIN', '');
    }
}

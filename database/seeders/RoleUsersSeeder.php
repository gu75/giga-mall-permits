<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUsersSeeder extends Seeder
{
    /**
     * Creates one login per role so you can test the full approval chain.
     * Password for all: password
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@gigamall.test', 'role' => 'admin'],
            ['name' => 'Sample Tenant (Shop 101)', 'email' => 'tenant@gigamall.test', 'role' => 'tenant', 'shop_name' => 'Shop 101'],
            ['name' => 'Operations Officer', 'email' => 'operations@gigamall.test', 'role' => 'operations'],
            ['name' => 'HSE Officer', 'email' => 'hse@gigamall.test', 'role' => 'hse'],
            ['name' => 'Security Officer', 'email' => 'security@gigamall.test', 'role' => 'security'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'shop_name' => $user['shop_name'] ?? null,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}

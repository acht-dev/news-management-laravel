<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->data();
    }

    private function data()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'password' => Hash::make("password")
        ]);

        $admin->assignRole("admin");

        for($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'User ' . $i,
                'email' => 'user_' . $i . '@demo.com',
                'password' => Hash::make("password")
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@monolink.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123456'),
                'role' => 'admin',
                'status' => 'active'
            ]
        );

        if (!$admin->profile) {
            $profile = $admin->profile()->create([
                'username' => 'admin',
                'display_name' => 'Monolink Admin',
                'bio' => 'Platform Administrator',
            ]);
            $profile->theme()->create(\App\Models\Theme::defaults());
        }
    }
}

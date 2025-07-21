<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dapatkan ID Role
        $adminRole = Role::where('name', 'admin')->first();
        $editorRole = Role::where('name', 'editor')->first();
        $userRole = Role::where('name', 'user')->first();

        // Menggunakan updateOrCreate untuk menghindari error duplikat email
        User::updateOrCreate(
            ['email' => 'admin@sahabatnews.test'],
            [
                'name' => 'Admin SahabatNews',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor@sahabatnews.test'],
            [
                'name' => 'Editor SahabatNews',
                'password' => Hash::make('password'),
                'role_id' => $editorRole->id,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@sahabatnews.test'],
            [
                'name' => 'User SahabatNews',
                'password' => Hash::make('password'),
                'role_id' => $userRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Membuat 10 user dummy tambahan jika diperlukan
        User::factory(10)->create();
    }
} 
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

        // Buat User Admin
        User::create([
            'name' => 'Admin SahabatNews',
            'email' => 'admin@sahabatnews.test',
            'password' => Hash::make('password'), // Ganti dengan password aman
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);

        // Buat User Editor
        User::create([
            'name' => 'Editor SahabatNews',
            'email' => 'editor@sahabatnews.test',
            'password' => Hash::make('password'), // Ganti dengan password aman
            'role_id' => $editorRole->id,
            'email_verified_at' => now(),
        ]);

        // Buat User Biasa
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@sahabatnews.test',
            'password' => Hash::make('password'), // Ganti dengan password aman
            'role_id' => $userRole->id,
            'email_verified_at' => now(),
        ]);

        // Opsional: Buat beberapa user dummy lainnya
        User::factory(5)->create(['role_id' => $userRole->id]);
         User::factory(2)->create(['role_id' => $editorRole->id]);
    }
} 
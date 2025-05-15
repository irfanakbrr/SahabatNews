<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder lain di sini sesuai urutan
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            PostSeeder::class,
            PageSeeder::class,
            // CommentSeeder::class, // Jika ada seeder untuk comment
        ]);
    }
}

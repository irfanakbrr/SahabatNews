<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Role::create(['name' => 'admin']);
        // Role::create(['name' => 'editor']);
        // Role::create(['name' => 'user']);

        // Lebih aman menggunakan updateOrCreate agar tidak error jika sudah ada
        Role::updateOrCreate(['name' => 'admin']);
        Role::updateOrCreate(['name' => 'editor']);
        Role::updateOrCreate(['name' => 'user']);
    }
} 
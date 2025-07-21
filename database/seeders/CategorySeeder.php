<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Indonesia News', 'color' => 'bg-red-600'],
            ['name' => 'World News', 'color' => 'bg-sky-600'],
            ['name' => 'Politics', 'color' => 'bg-slate-600'],
            ['name' => 'Economics', 'color' => 'bg-amber-600'],
            ['name' => 'Sports', 'color' => 'bg-lime-600'],
            ['name' => 'Science', 'color' => 'bg-indigo-600'],
            ['name' => 'IT', 'color' => 'bg-blue-600'],
            ['name' => 'Nature', 'color' => 'bg-emerald-600'],
            ['name' => 'Islam', 'color' => 'bg-teal-600'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                'color' => $category['color'],
                'slug' => Str::slug($category['name'])
                ]
            );
        }
    }
} 
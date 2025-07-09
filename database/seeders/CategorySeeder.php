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
            ['name' => 'Indonesia News', 'color' => 'bg-red-500'],
            ['name' => 'World News', 'color' => 'bg-blue-500'],
            ['name' => 'Politics', 'color' => 'bg-gray-500'],
            ['name' => 'Economics', 'color' => 'bg-yellow-500'],
            ['name' => 'Sports', 'color' => 'bg-green-500'],
            ['name' => 'Science', 'color' => 'bg-indigo-500'],
            ['name' => 'IT', 'color' => 'bg-purple-500'],
            ['name' => 'Nature', 'color' => 'bg-teal-500'],
            ['name' => 'Islam', 'color' => 'bg-green-600'],
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
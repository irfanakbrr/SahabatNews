<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB; // Untuk transaksi jika perlu

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID Editor dan Kategori
        $editorUser = User::whereHas('role', function ($query) {
            $query->where('name', 'editor');
        })->first();
        
        if (!$editorUser) {
            $this->command->warn('Editor user not found, skipping post seeding.');
            return;
        }

        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->warn('No categories found, skipping post seeding.');
            return;
        }

        // Buat beberapa post dummy
        $postsData = [
            [
                'title' => 'Planet dalam Bahaya: Update Perubahan Iklim',
                'content' => 'Konten lengkap tentang perubahan iklim...',
                'image' => 'https://via.placeholder.com/1200x600?text=Perubahan+Iklim', 
                'category_name' => 'Nature',
                'status' => 'published',
            ],
            [
                'title' => 'Fokus Ekonomi: Analisis Pergeseran Fiskal',
                'content' => 'Konten analisis ekonomi...',
                'image' => 'https://via.placeholder.com/1200x600?text=Ekonomi', 
                'category_name' => 'Economics',
                'status' => 'published',
            ],
            [
                'title' => 'Update Arena: Terbaru dari Lintasan dan Lapangan',
                'content' => 'Konten berita olahraga...',
                'image' => 'https://via.placeholder.com/1200x600?text=Olahraga', 
                'category_name' => 'Sports',
                'status' => 'published',
            ],
             [
                'title' => 'Bincang Teknologi: Menjelajahi Chat GPT',
                'content' => 'Konten detail tentang Chat GPT...',
                'image' => 'https://via.placeholder.com/1200x600?text=Teknologi', 
                'category_name' => 'IT',
                'status' => 'published',
            ],
             [
                'title' => 'Politik Terkini: Manuver Jelang Pemilu',
                'content' => 'Konten analisis politik...',
                'image' => 'https://via.placeholder.com/1200x600?text=Politik', 
                'category_name' => 'Politics',
                'status' => 'draft', // Contoh draft
            ],
        ];

        foreach ($postsData as $data) {
            $category = $categories->firstWhere('name', $data['category_name']);
            if ($category) {
                Post::create([
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'image' => $data['image'],
                    'user_id' => $editorUser->id,
                    'category_id' => $category->id,
                    'status' => $data['status'],
                    'published_at' => $data['status'] === 'published' ? now()->subDays(rand(1, 30)) : null, // Tanggal publish acak
                    // Slug akan dibuat otomatis oleh Model boot method
                ]);
            }
        }

        // Opsional: Buat post dummy lainnya menggunakan factory
        // Post::factory(15)->create(); // Pastikan factory di-setup dengan benar
    }
} 
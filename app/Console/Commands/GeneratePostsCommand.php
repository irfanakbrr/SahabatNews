<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Role;
use Illuminate\Support\Facades\Http; // Kita mungkin butuh ini jika memanggil API eksternal, tapi coba pakai internal dulu
use Illuminate\Support\Str;
use Exception;

class GeneratePostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-posts {--count=5 : Jumlah post yang ingin dibuat} {--category= : ID Kategori spesifik}'; // Ganti nama command

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate contoh post berita/tutorial dengan konten dari AI (placeholder)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = (int) $this->option('count');
        $categoryId = $this->option('category');

        // Ambil ID role admin dan editor
        $editorRoleIds = Role::whereIn('name', ['admin', 'editor'])->pluck('id');
        if ($editorRoleIds->isEmpty()) {
            $this->error('Role "admin" atau "editor" tidak ditemukan. Silakan jalankan seeder.');
            return Command::FAILURE;
        }

        // Ambil user admin/editor yang tersedia
        $authors = User::whereIn('role_id', $editorRoleIds)->get();
        if ($authors->isEmpty()) {
            $this->error('Tidak ada user dengan role "admin" atau "editor" ditemukan untuk dijadikan penulis.');
            return Command::FAILURE;
        }

        // Ambil kategori
        $category = null;
        if ($categoryId) {
            $category = Category::find($categoryId);
            if (!$category) {
                $this->error("Kategori dengan ID {$categoryId} tidak ditemukan.");
                return Command::FAILURE;
            }
            $categories = collect([$category]); // Gunakan hanya kategori ini
        } else {
            $categories = Category::all();
            if ($categories->isEmpty()) {
                $this->error('Tidak ada kategori ditemukan. Buat kategori terlebih dahulu atau jalankan seeder.');
                return Command::FAILURE;
            }
        }

        $this->info("Memulai pembuatan {$count} contoh artikel...");
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            try {
                // Pilih author dan kategori secara acak
                $randomAuthor = $authors->random();
                $randomCategory = $categories->random();

                // --- Placeholder Generation (Ganti dengan LLM Call jika memungkinkan) ---
                // Karena saya tidak bisa memanggil diri sendiri secara langsung di sini,
                // saya akan gunakan placeholder yang lebih deskriptif.
                // Anda bisa ganti bagian ini dengan logika pemanggilan API LLM jika punya.

                $placeholderTitle = "Contoh Judul Artikel {$randomCategory->name} ke-" . ($i + 1) . " oleh AI";
                $placeholderContent = "Ini adalah konten contoh yang dihasilkan secara otomatis untuk artikel tentang {$randomCategory->name}.\n\nParagraf kedua berisi detail lebih lanjut mengenai topik ini. Seharusnya ini menjadi penjelasan yang cukup mendalam dan menarik bagi pembaca.\n\nParagraf ketiga bisa berisi kesimpulan atau ajakan untuk bertindak, atau mungkin data pendukung lainnya. Panjang artikel ini disesuaikan agar terlihat realistis.";
                // --- End Placeholder Generation ---

                Post::create([
                    'title' => $placeholderTitle,
                    'content' => $placeholderContent,
                    'user_id' => $randomAuthor->id,
                    'category_id' => $randomCategory->id,
                    'status' => 'published', // Langsung publish
                    'published_at' => now()->subMinutes(rand(5, 120)), // Waktu publish acak dalam 2 jam terakhir
                    'view_count' => rand(50, 1500), // View count acak
                    // Slug akan dibuat otomatis oleh model jika menggunakan trait Sluggable
                ]);

                $bar->advance();
            } catch (Exception $e) {
                $this->warn(" Gagal membuat artikel ke-" . ($i + 1) . ": " . $e->getMessage());
                // Lanjutkan ke iterasi berikutnya
            }
        }

        $bar->finish();
        $this->info("\nSelesai membuat {$count} contoh artikel.");

        return Command::SUCCESS;
    }
}

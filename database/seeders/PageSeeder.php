<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pages')->delete(); // Hapus data lama jika ada

        Page::create([
            'slug' => 'about-us',
            'title' => 'Tentang Kami - SahabatNews',
            'content' => '<h1>Tentang SahabatNews</h1><p>Ini adalah halaman tentang kami. SahabatNews adalah portal berita terdepan...</p><p>Tim kami terdiri dari...</p>',
            'meta_title' => 'Tentang Kami | SahabatNews',
            'meta_description' => 'Pelajari lebih lanjut tentang visi, misi, dan tim di balik SahabatNews.',
        ]);

        Page::create([
            'slug' => 'contact-info',
            'title' => 'Informasi Kontak - SahabatNews',
            'content' => '<h1>Hubungi Kami</h1><p><strong>Alamat:</strong> Jl. Pers No. 1, Jakarta Pusat</p><p><strong>Email:</strong> info@sahabatnews.test</p><p><strong>Telepon:</strong> (021) 123-4567</p>',
            'meta_title' => 'Kontak Kami | SahabatNews',
            'meta_description' => 'Informasi kontak SahabatNews. Hubungi kami untuk pertanyaan atau kerjasama.',
        ]);
        
        // Tambahkan halaman lain jika perlu, misalnya:
        // Page::create([
        //     'slug' => 'privacy-policy',
        //     'title' => 'Kebijakan Privasi - SahabatNews',
        //     'content' => '<h1>Kebijakan Privasi</h1><p>Detail kebijakan privasi...</p>',
        // ]);
        // Page::create([
        //     'slug' => 'terms-of-service',
        //     'title' => 'Syarat Layanan - SahabatNews',
        //     'content' => '<h1>Syarat dan Ketentuan Layanan</h1><p>Detail syarat layanan...</p>',
        // ]);
    }
}

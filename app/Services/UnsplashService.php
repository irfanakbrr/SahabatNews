<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UnsplashService
{
    protected $accessKey;
    protected $apiUrl;

    public function __construct()
    {
        // Sebaiknya pindahkan Access Key ke .env Anda sebagai UNSPLASH_ACCESS_KEY
        $this->accessKey = env('UNSPLASH_ACCESS_KEY', 'lGsEylaiynyTJCr5FBpQ96kbtcV6njk99n5diq2mOt0');
        $this->apiUrl = 'https://api.unsplash.com/search/photos';
    }

    /**
     * Mencari gambar di Unsplash, mengunduh yang pertama, dan menyimpannya.
     *
     * @param string $query Teks pencarian (prompt).
     * @return string|null Path ke file lokal atau null jika gagal.
     */
    public function fetchAndSaveImage(string $query): ?string
    {
        if (empty($this->accessKey)) {
            Log::error('UNSPLASH_ACCESS_KEY not set.');
            return null;
        }

        try {
            // 1. Cari gambar di Unsplash
            $response = Http::withHeaders([
                'Authorization' => 'Client-ID ' . $this->accessKey
            ])->get($this->apiUrl, [
                'query' => $query,
                'per_page' => 1,
                'orientation' => 'landscape',
            ]);

            if ($response->failed()) {
                Log::error('Unsplash API search failed.', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }
            
            // 2. Dapatkan URL gambar dari hasil pertama
            $imageUrl = data_get($response->json(), 'results.0.urls.regular');

            if (!$imageUrl) {
                Log::warning('No image found on Unsplash for query.', ['query' => $query]);
                return null; // Kembalikan null jika tidak ada gambar ditemukan
            }

            // 3. Unduh konten gambar
            $imageContents = Http::get($imageUrl)->body();

            if (empty($imageContents)) {
                 Log::error('Failed to download image from Unsplash URL.', ['url' => $imageUrl]);
                return null;
            }

            // 4. Simpan ke storage lokal
            $filename = 'covers/' . Str::uuid() . '.jpg';
            Storage::disk('public')->put($filename, $imageContents);

            return $filename;

        } catch (\Exception $e) {
            Log::error('Error during Unsplash image fetch/save process: ' . $e->getMessage());
            return null;
        }
    }
} 
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiContentService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        // Sebaiknya pindahkan API Key ke .env Anda
        $this->apiKey = env('GEMINI_API_KEY', 'AIzaSyBnGRmoce6EGq1LkLIXFhhSQHonK4dQUAc');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$this->apiKey}";
    }

    /**
     * Menghasilkan draf artikel (judul, konten, prompt gambar) dari satu topik.
     *
     * @param string $topic
     * @return array|null
     */
    public function generateFullArticle(string $topic): ?array
    {
        $prompt = $this->buildPrompt($topic);

        try {
            $response = Http::post($this->apiUrl, [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API request failed for content generation.', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $fullText = data_get($response->json(), 'candidates.0.content.parts.0.text');
            
            if (!$fullText) {
                 Log::error('Gagal mendapatkan teks dari respons AI.', ['response' => $response->json()]);
                return null;
            }

            $parsedData = $this->parseAiResponse($fullText);

            if (!$parsedData) {
                Log::error('Gagal mem-parsing respons AI.', ['response' => $fullText]);
                return null;
            }
            
            return [
                'title'        => $parsedData['title'],
                'content'      => $parsedData['content'],
                'image_prompt' => $parsedData['image_prompt'],
            ];

        } catch (\Exception $e) {
            Log::error('AI Content Generation Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Membangun prompt yang kompleks untuk AI.
     * Inilah bagian terpenting untuk mendapatkan hasil yang baik.
     */
    private function buildPrompt(string $topic): string
    {
        // Perintah ini sangat detail untuk mengontrol output AI
        return "Anda adalah seorang penulis konten profesional untuk sebuah platform berita di Indonesia.
        Tugas Anda adalah membuat draf artikel lengkap berdasarkan topik yang diberikan.

        Topik: \"{$topic}\"

        Format output HARUS mengikuti struktur ini dengan TEPAT, tanpa teks tambahan sebelum atau sesudah:

        ---TITLE---
        [Tulis judul artikel yang menarik dan SEO-friendly di sini]

        ---CONTENT---
        [Tulis konten artikel di sini. Jelaskan secara detail, seolah-olah ini adalah tutorial atau artikel informatif. Gunakan bahasa Indonesia yang baik dan benar. Sisipkan emoji yang relevan secara natural di dalam paragraf untuk membuatnya lebih menarik secara visual. JANGAN gunakan format markdown seperti *, **, #, atau list bernomor. Buat paragraf yang jelas.]

        ---IMAGE_PROMPT---
        [Tulis deskripsi gambar dalam BAHASA INGGRIS yang detail, sinematik, dan artistik yang merepresentasikan isi artikel. Deskripsi ini akan digunakan oleh AI text-to-image. Contoh: 'A vibrant, photorealistic shot of a healthy Monstera Deliciosa plant in a minimalist Scandinavian-style living room, with soft morning light filtering through a window, creating beautiful shadows. 🌿']";
    }

    /**
     * Memisahkan judul, konten, dan prompt gambar dari teks tunggal AI.
     */
    private function parseAiResponse(string $response): ?array
    {
        $titlePattern = '/---TITLE---(.*?)---CONTENT---/s';
        $contentPattern = '/---CONTENT---(.*?)---IMAGE_PROMPT---/s';
        $imagePromptPattern = '/---IMAGE_PROMPT---(.*)/s';

        preg_match($titlePattern, $response, $titleMatches);
        preg_match($contentPattern, $response, $contentMatches);
        preg_match($imagePromptPattern, $response, $imagePromptMatches);

        if (empty($titleMatches[1]) || empty($contentMatches[1]) || empty($imagePromptMatches[1])) {
            return null; // Gagal parsing jika salah satu bagian tidak ditemukan
        }

        return [
            'title' => trim($titleMatches[1]),
            'content' => trim($contentMatches[1]),
            'image_prompt' => trim($imagePromptMatches[1]),
        ];
    }
} 
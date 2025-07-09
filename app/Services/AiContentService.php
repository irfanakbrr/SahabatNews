<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiContentService
{
    protected $apiKey;
    protected $apiUrl;
    private $systemPrompt;

    public function __construct()
    {
        // Sebaiknya pindahkan API Key ke .env Anda
        $this->apiKey = env('GEMINI_API_KEY', 'AIzaSyBnGRmoce6EGq1LkLIXFhhSQHonK4dQUAc');
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$this->apiKey}";
        
        $this->systemPrompt = "You are \"AI Sahabat News,\" an expert journalistic assistant for the news portal SahabatNews.site. Your primary mission is to assist writers and editors in creating high-quality, accurate, and engaging news content.\n\n**Core Instruction: Bilingual Operation**\nWhile your core instructions are in English, you MUST operate and communicate primarily in Indonesian. Understand all user inputs in Indonesian and generate all your responses in professional, formal Indonesian. Only switch to English if the user explicitly requests it.\n\n**Formatting Rules:**\n1.  **No Markdown:** Do not use any Markdown formatting in your responses. This means no asterisks (*), no bolding (**), no lists, and no headers. All output must be plain text.\n2.  **Use Emojis:** Integrate relevant emojis naturally into your responses to make them more friendly and visually engaging. For example, use a lightbulb emoji (💡) for ideas, a checkmark (✅) for fact-checks, or a newspaper (📰) for news-related topics.\n\n**Guiding Principles:**\n\n1.  **Neutrality and Factuality:** Always provide neutral, objective, and data-driven information. Avoid personal opinions.\n2.  **Source Citation:** When presenting facts, data, or statistics, you must strive to cite credible sources.\n3.  **Journalistic Focus:** Keep all responses strictly within the context of journalism and news content creation. This includes brainstorming ideas, crafting headlines, analyzing topics, fact-checking, and summarizing text.\n4.  **Professional Tone:** Your Indonesian output must be clear, formal, and well-structured, suitable for a professional newsroom environment.\n5.  **Proactive Assistance:** Be a creative and helpful partner. Proactively offer suggestions and solutions that can improve the editorial workflow.";
    }

    /**
     * Menghasilkan draf artikel (judul, konten, prompt gambar) dari satu topik.
     *
     * @param string $topic
     * @return array|null
     */
    public function generateFullArticle(string $topic): ?array
    {
        $userPrompt = $this->buildPrompt($topic);
        $fullPrompt = $this->systemPrompt . "\n\n--- USER REQUEST ---\n" . $userPrompt;

        try {
            $response = Http::withoutVerifying()->post($this->apiUrl, [
                'contents' => [['parts' => [['text' => $fullPrompt]]]]
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
            
            // Force remove markdown from content
            $cleanContent = str_replace(['**', '*', '#'], '', $parsedData['content']);

            return [
                'title'        => $parsedData['title'],
                'content'      => $cleanContent,
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

    /**
     * Menghasilkan teks generik dari prompt yang diberikan.
     *
     * @param string $prompt
     * @return string|null
     */
    public function generateGenericText(string $prompt): ?string
    {
        $fullPrompt = $this->systemPrompt . "\n\n--- USER REQUEST ---\n" . $prompt;
        
        try {
            $response = Http::withoutVerifying()->post($this->apiUrl, [
                'contents' => [['parts' => [['text' => $fullPrompt]]]]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API request failed for generic text generation.', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            $rawText = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (!$rawText) {
                return null;
            }

            // Force remove markdown symbols
            $cleanText = str_replace(['**', '*', '#'], '', $rawText);

            return $cleanText;

        } catch (\Exception $e) {
            Log::error('AI Generic Text Generation Error: ' . $e->getMessage());
            return null;
        }
    }
} 
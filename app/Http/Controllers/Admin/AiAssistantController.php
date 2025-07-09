<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AiContentService;

class AiAssistantController extends Controller
{
    protected $aiContentService;

    public function __construct(AiContentService $aiContentService)
    {
        $this->aiContentService = $aiContentService;
    }

    public function ask(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
            'action' => 'nullable|string|in:generate_titles,check_facts,summarize,fix_grammar,find_synonyms',
        ]);

        $prompt = $request->input('prompt');
        $action = $request->input('action');
        $fullPrompt = '';

        switch ($action) {
            case 'generate_titles':
                $fullPrompt = "Anda adalah seorang editor berita handal. Berikan 3 alternatif judul yang menarik dan SEO-friendly untuk artikel dengan topik atau isi berikut:\n\n\"" . $prompt . "\"";
                break;
            case 'check_facts':
                $fullPrompt = "Anda adalah seorang fact-checker. Periksa kebenaran dari klaim berikut dan berikan penjelasan singkat beserta sumber jika memungkinkan:\n\n\"" . $prompt . "\"";
                break;
            case 'summarize':
                $fullPrompt = "Ringkas teks berikut menjadi 2-3 kalimat inti yang informatif:\n\n\"" . $prompt . "\"";
                break;
            case 'fix_grammar':
                $fullPrompt = "Perbaiki tata bahasa dan ejaan dari teks berikut tanpa mengubah maknanya. Kembalikan hanya teks yang sudah diperbaiki:\n\n\"" . $prompt . "\"";
                break;
            case 'find_synonyms':
                $fullPrompt = "Berikan 5 sinonim atau frasa alternatif untuk kata atau frasa berikut: \"" . $prompt . "\"";
                break;
            default:
                $fullPrompt = $prompt; // Gunakan prompt apa adanya jika tidak ada aksi spesifik
        }

        // Di sini kita tidak menggunakan metode generateFullArticle,
        // karena kita butuh respons yang lebih fleksibel.
        // Kita akan panggil API langsung atau buat metode baru di service.
        // Untuk sekarang, kita asumsikan service bisa handle prompt generik.
        
        try {
            $response = $this->aiContentService->generateGenericText($fullPrompt); // Asumsi ada method ini

            if ($response) {
                return response()->json(['success' => true, 'response' => $response]);
            }

            return response()->json(['success' => false, 'error' => 'Gagal mendapatkan respons dari AI.'], 500);

        } catch (\Exception $e) {
            \Log::error('AI Assistant Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Terjadi kesalahan pada server AI.'], 500);
        }
    }
}

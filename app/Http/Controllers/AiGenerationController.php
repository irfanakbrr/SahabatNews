<?php

namespace App\Http\Controllers;

use App\Services\AiContentService;
use App\Services\UnsplashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Jobs\GenerateArticle;
use Illuminate\Support\Facades\Log;

class AiGenerationController extends Controller
{
    protected $unsplashService;
    protected $aiContentService;

    public function __construct(UnsplashService $unsplashService, AiContentService $aiContentService)
    {
        $this->unsplashService = $unsplashService;
        $this->aiContentService = $aiContentService;
    }
    
    /**
     * Method untuk mencari dan menyimpan gambar dari Unsplash.
     */
    public function generateImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prompt' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()->first()], 422);
        }

        $imagePath = $this->unsplashService->fetchAndSaveImage($request->input('prompt'));

        if ($imagePath) {
            return response()->json([
                'success' => true,
                'path' => $imagePath,
                'url' => Storage::disk('public')->url($imagePath),
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Gagal mendapatkan gambar dari Unsplash. Mungkin tidak ada hasil untuk query tersebut.'
        ], 500);
    }

    /**
     * Method untuk generate artikel lengkap (judul, konten, dan gambar).
     */
    public function generateArticle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()->first()], 422);
        }

        try {
            // Dispatch the job to the queue
            GenerateArticle::dispatch(
                $request->input('topic'),
                $request->input('category_id'),
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Permintaan pembuatan artikel telah diterima dan sedang diproses di latar belakang. Artikel akan muncul sebagai draf dalam beberapa saat.'
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal men-dispatch GenerateArticle job: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal memulai proses pembuatan artikel. Silakan coba lagi.'
            ], 500);
        }
    }
} 
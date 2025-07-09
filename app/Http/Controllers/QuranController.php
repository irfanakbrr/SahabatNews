<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class QuranController extends Controller
{
    private $apiUrl = 'https://quran-api.santrikoding.com/api/surah';

    public function index()
    {
        try {
            // Cache daftar surah selama 24 jam
            $surahs = Cache::remember('quran_surahs', 86400, function () {
                $response = Http::get($this->apiUrl);
                return $response->successful() ? $response->json() : [];
            });

            return view('quran.index', compact('surahs'));
        } catch (\Exception $e) {
            return view('quran.index', ['surahs' => [], 'error' => 'Gagal mengambil data Al-Qur\'an']);
        }
    }

    public function show($nomor)
    {
        try {
            // Cache detail surah selama 24 jam
            $surah = Cache::remember("quran_surah_{$nomor}", 86400, function () use ($nomor) {
                $response = Http::get($this->apiUrl . '/' . $nomor);
                return $response->successful() ? $response->json() : null;
            });

            if (!$surah || !$surah['status']) {
                abort(404, 'Surah tidak ditemukan');
            }

            return view('quran.show', compact('surah'));
        } catch (\Exception $e) {
            abort(500, 'Gagal mengambil data surah');
        }
    }
} 
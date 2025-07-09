<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PrayerTimesController extends Controller
{
    public function index()
    {
        try {
            // Default location: Jakarta, Indonesia
            $prayerTimes = $this->getPrayerTimes(-6.2088, 106.8456, 'Jakarta');
            
            return view('prayer-times.index', [
                'prayerTimes' => $prayerTimes,
                'location' => 'Jakarta, Indonesia'
            ]);
        } catch (\Exception $e) {
            return view('prayer-times.index', [
                'error' => 'Gagal memuat jadwal sholat. Silakan coba lagi nanti.',
                'location' => 'Jakarta, Indonesia'
            ]);
        }
    }

    public function getByLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location' => 'required|string|max:255'
        ]);

        try {
            $prayerTimes = $this->getPrayerTimes(
                $request->latitude, 
                $request->longitude, 
                $request->location
            );

            return response()->json([
                'success' => true,
                'data' => $prayerTimes,
                'location' => $request->location
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat jadwal sholat untuk lokasi tersebut.'
            ], 500);
        }
    }

    private function getPrayerTimes($latitude, $longitude, $location)
    {
        $cacheKey = "prayer_times_{$latitude}_{$longitude}_" . date('Y-m-d');
        
        return Cache::remember($cacheKey, 3600, function () use ($latitude, $longitude) {
            $response = Http::timeout(10)->get('http://api.aladhan.com/v1/timings', [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'method' => 8, // Islamic Society of North America (ISNA)
                'tune' => '0,0,0,0,0,0,0,0,0' // No adjustments
            ]);

            if (!$response->successful()) {
                throw new \Exception('API request failed');
            }

            $data = $response->json();
            
            if (!isset($data['data']['timings'])) {
                throw new \Exception('Invalid API response');
            }

            $timings = $data['data']['timings'];
            $date = $data['data']['date'];

            return [
                'timings' => [
                    'Fajr' => $timings['Fajr'],
                    'Sunrise' => $timings['Sunrise'],
                    'Dhuhr' => $timings['Dhuhr'],
                    'Asr' => $timings['Asr'],
                    'Maghrib' => $timings['Maghrib'],
                    'Isha' => $timings['Isha']
                ],
                'date' => [
                    'hijri' => $date['hijri']['date'] . ' ' . $date['hijri']['month']['en'] . ' ' . $date['hijri']['year'],
                    'gregorian' => $date['readable']
                ]
            ];
        });
    }
} 
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class LogVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hindari pencatatan untuk bot/crawler umum dan request ke aset
        $userAgent = $request->userAgent();
        if (str_contains($userAgent, 'bot') || str_contains($userAgent, 'spider') || $request->is('assets/*')) {
            return $next($request);
        }

        // Simpan data pengunjung
        try {
            DB::table('visitors')->insert([
                'ip_address' => $request->ip(),
                'user_agent' => $userAgent,
                'visited_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Abaikan error jika terjadi masalah saat menyimpan (misal: koneksi database),
            // agar tidak mengganggu pengalaman pengguna. Cukup catat di log.
            \Illuminate\Support\Facades\Log::warning('Could not log visitor: ' . $e->getMessage());
        }

        return $next($request);
    }
}

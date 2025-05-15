<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles Roles yang diizinkan
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Jika user tidak login atau tidak punya role, redirect ke login
        if (!Auth::check() || !Auth::user()->role) {
            return redirect('login');
        }

        // Ambil nama role user
        $userRole = Auth::user()->role->name;

        // Cek apakah role user ada di dalam list role yang diizinkan
        if (!in_array($userRole, $roles)) {
            // Jika tidak diizinkan, bisa redirect ke home atau tampilkan error 403
            // abort(403, 'Unauthorized action.'); 
            return redirect('/')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
} 
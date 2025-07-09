<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception; // Untuk menangani error

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Provider authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider(string $provider)
    {
        // Pastikan provider didukung (contoh: hanya google)
        if (!in_array($provider, ['google'])) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback(string $provider)
    {
        if (!in_array($provider, ['google'])) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();

            // Cari user berdasarkan provider dan provider_id
            $user = User::where('provider', $provider)
                        ->where('provider_id', $socialUser->getId())
                        ->first();

            if ($user) {
                // Jika user ditemukan, login
                Auth::login($user);
                return redirect()->intended(route('dashboard', absolute: false));
            } else {
                // Jika tidak ada, cek berdasarkan email
                $user = User::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    // Email sudah terdaftar (login biasa), update data provider
                    // Atau bisa juga redirect ke login dengan pesan error
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(), // Update avatar jika ada
                    ]);
                    Auth::login($user);
                    return redirect()->intended(route('dashboard', absolute: false));
                } else {
                    // Email belum terdaftar, buat user baru
                    $newUser = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'avatar' => $socialUser->getAvatar(),
                        'password' => null, // Password null untuk social login
                        'email_verified_at' => now(), // Anggap email dari Google sudah terverifikasi
                        'role_id' => Role::where('name', 'user')->firstOrFail()->id, // Assign role 'user' default
                    ]);

                    Auth::login($newUser);
                    return redirect()->intended(route('dashboard', absolute: false));
                }
            }

        } catch (Exception $e) {
            // Tangani error (misal: user cancel, token invalid, dll)
            // dd($e->getMessage()); // Untuk debugging
            return redirect()->route('login')->with('error', 'Gagal login dengan ' . ucfirst($provider) . '. Silakan coba lagi.');
        }
    }
}

@extends('layouts.guest')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-400 via-pink-300 to-blue-400">
    <div class="relative w-full max-w-md flex flex-col items-center">
        <!-- Icon User Floating -->
        <div class="absolute left-1/2 -top-12 transform -translate-x-1/2 z-10 flex justify-center w-full">
            <div class="bg-blue-700 w-16 h-16 rounded-full flex items-center justify-center shadow-lg border-4 border-white">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1112 21a8.963 8.963 0 01-6.879-3.196z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
        </div>
        <!-- Card -->
        <div class="pt-14 pb-8 px-6 bg-white/30 backdrop-blur-md rounded-2xl shadow-2xl flex flex-col gap-6 w-full mt-8">
            <h2 class="text-center text-2xl font-bold text-gray-900 mb-2">Daftar Akun SahabatNews</h2>
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <!-- Name -->
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m8 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </span>
                    <input id="name" name="name" type="text" required autofocus placeholder="Nama Lengkap" value="{{ old('name') }}" class="pl-10 pr-3 py-2 w-full rounded bg-blue-900/70 text-white placeholder-white focus:ring-2 focus:ring-blue-400 border-none outline-none" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <!-- Email -->
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12H8m8 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    </span>
                    <input id="email" name="email" type="email" required placeholder="Email ID" value="{{ old('email') }}" class="pl-10 pr-3 py-2 w-full rounded bg-blue-900/70 text-white placeholder-white focus:ring-2 focus:ring-blue-400 border-none outline-none" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <!-- Password -->
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m0-6a2 2 0 100 4 2 2 0 000-4zm6 2a6 6 0 11-12 0 6 6 0 0112 0z" /></svg>
                    </span>
                    <input id="password" name="password" type="password" required placeholder="Password" class="pl-10 pr-3 py-2 w-full rounded bg-blue-900/70 text-white placeholder-white focus:ring-2 focus:ring-blue-400 border-none outline-none" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <!-- Confirm Password -->
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m0-6a2 2 0 100 4 2 2 0 000-4zm6 2a6 6 0 11-12 0 6 6 0 0112 0z" /></svg>
                    </span>
                    <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Konfirmasi Password" class="pl-10 pr-3 py-2 w-full rounded bg-blue-900/70 text-white placeholder-white focus:ring-2 focus:ring-blue-400 border-none outline-none" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
                <!-- Button -->
                <button type="submit" class="w-full py-2 rounded-lg bg-blue-700 text-white font-bold shadow hover:bg-blue-800 transition">DAFTAR</button>
                <div class="text-center mt-2">
                    <a href="{{ route('login') }}" class="text-sm text-blue-900 hover:underline">Sudah punya akun? Masuk</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

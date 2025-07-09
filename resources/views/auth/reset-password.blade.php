@extends('layouts.guest')

@section('content')
<div id="shadow" class="fixed top-0 left-0 z-10 h-32 w-32 rounded-full bg-purple-400 blur-[70px] transition-opacity duration-300 opacity-0"></div>
<div class="relative z-1 flex h-screen w-full flex-col overflow-auto p-5 sm:p-10 bg-gradient-to-br from-purple-900 via-purple-600 to-pink-600">
    <div id="card" class="relative mx-auto my-auto w-full max-w-md shrink-0 overflow-hidden rounded-3xl border-t border-white/20 bg-gradient-to-t from-purple-100/10 to-purple-950/50 to-50% p-8 text-white shadow-2xl shadow-black/50 outline -outline-offset-1 outline-white/5 backdrop-blur-2xl">
        
        <!-- Logo -->
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold mb-2">SahabatNews</h1>
            <p class="text-purple-200 text-sm">Reset Password</p>
        </div>

        <h2 class="mb-7 text-xl font-medium">Buat password baru Anda</h2>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="relative col-span-2 overflow-hidden">
                <input 
                    type="email" 
                    id="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="peer relative z-10 h-11 w-full rounded-md border border-white/8 bg-white/5 pr-4 pl-11 duration-300 placeholder:text-white/30 focus:bg-white/10 focus:outline-0 text-white" 
                    placeholder="Email Anda" 
                />
                <svg class="pointer-events-none absolute top-3 left-3.5 z-20 mt-px h-4.5 w-4.5 text-white/30 duration-300 peer-focus:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g fill="none">
                        <path d="M24 0v24H0V0zM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01z"></path>
                        <path fill="currentColor" d="M20 4a2 2 0 0 1 1.995 1.85L22 6v12a2 2 0 0 1-1.85 1.995L20 20H4a2 2 0 0 1-1.995-1.85L2 18v-1h2v1h16V7.414l-6.94 6.94a1.5 1.5 0 0 1-2.007.103l-.114-.103L4 7.414V8H2V6a2 2 0 0 1 1.85-1.995L4 4zM6 13a1 1 0 1 1 0 2H1a1 1 0 1 1 0-2zm12.586-7H5.414L12 12.586zM5 10a1 1 0 0 1 .117 1.993L5 12H2a1 1 0 0 1-.117-1.993L2 10z"></path>
                    </g>
                </svg>
                <span class="absolute bottom-0 left-0 z-20 h-px w-full bg-gradient-to-r from-transparent from-5% via-purple-400 to-transparent to-95% opacity-0 transition-opacity duration-300 peer-focus:opacity-40"></span>
                <span class="absolute inset-x-4 bottom-0 z-10 h-4 origin-bottom scale-y-0 -skew-x-12 bg-gradient-to-b from-purple-400 to-transparent opacity-0 blur-md duration-300 peer-focus:scale-100 peer-focus:opacity-30"></span>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-pink-300" />
            </div>

            <!-- Password -->
            <div class="relative col-span-2 overflow-hidden">
                <input 
                    type="password" 
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="peer relative z-10 h-11 w-full rounded-md border border-white/8 bg-white/5 pr-4 pl-11 duration-300 placeholder:text-white/30 focus:bg-white/10 focus:outline-0 text-white" 
                    placeholder="Password baru" 
                />
                <svg class="pointer-events-none absolute top-3 left-3.5 z-20 mt-px h-4.5 w-4.5 text-white/30 duration-300 peer-focus:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12 2C9.243 2 7 4.243 7 7v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V7c0-2.757-2.243-5-5-5zM9 7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9V7zm4 10.723V20h-2v-2.277a2 2 0 1 1 2 0z"/>
                </svg>
                <span class="absolute bottom-0 left-0 z-20 h-px w-full bg-gradient-to-r from-transparent from-5% via-purple-400 to-transparent to-95% opacity-0 transition-opacity duration-300 peer-focus:opacity-40"></span>
                <span class="absolute inset-x-4 bottom-0 z-10 h-4 origin-bottom scale-y-0 -skew-x-12 bg-gradient-to-b from-purple-400 to-transparent opacity-0 blur-md duration-300 peer-focus:scale-100 peer-focus:opacity-30"></span>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-pink-300" />
            </div>

            <!-- Confirm Password -->
            <div class="relative col-span-2 overflow-hidden">
                <input 
                    type="password" 
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="peer relative z-10 h-11 w-full rounded-md border border-white/8 bg-white/5 pr-4 pl-11 duration-300 placeholder:text-white/30 focus:bg-white/10 focus:outline-0 text-white" 
                    placeholder="Konfirmasi password baru" 
                />
                <svg class="pointer-events-none absolute top-3 left-3.5 z-20 mt-px h-4.5 w-4.5 text-white/30 duration-300 peer-focus:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12 2C9.243 2 7 4.243 7 7v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V7c0-2.757-2.243-5-5-5zM9 7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9V7zm4 10.723V20h-2v-2.277a2 2 0 1 1 2 0z"/>
                </svg>
                <span class="absolute bottom-0 left-0 z-20 h-px w-full bg-gradient-to-r from-transparent from-5% via-purple-400 to-transparent to-95% opacity-0 transition-opacity duration-300 peer-focus:opacity-40"></span>
                <span class="absolute inset-x-4 bottom-0 z-10 h-4 origin-bottom scale-y-0 -skew-x-12 bg-gradient-to-b from-purple-400 to-transparent opacity-0 blur-md duration-300 peer-focus:scale-100 peer-focus:opacity-30"></span>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-pink-300" />
            </div>

            <!-- Submit Button -->
            <button type="submit" class="mt-6 h-12 w-full cursor-pointer rounded-md bg-gradient-to-r from-purple-600 to-pink-600 px-2 text-sm font-semibold text-white shadow-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-[1.02]">
                {{ __('Reset Password') }}
            </button>
        </form>

        <div class="absolute inset-x-32 -bottom-20 left-32 h-10 bg-purple-400 blur-2xl"></div>
    </div>
</div>

<script>
    const shadow = document.getElementById("shadow");
    const card = document.getElementById("card");

    document.body.addEventListener("mousemove", (e) => {
        const { clientX, clientY } = e;
        if (e.target.closest("#card")) {
            shadow.style.setProperty(
                "transform",
                `translateX(${clientX - 60}px) translateY(${clientY - 60}px)`
            );
            shadow.style.setProperty(
                "opacity",
                "0.5"
            );
        } else {
            shadow.style.setProperty("opacity", "0");
        }
    });
</script>
@endsection

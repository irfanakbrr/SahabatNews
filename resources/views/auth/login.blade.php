@extends('layouts.guest')

@section('content')
<div id="shadow" class="fixed top-0 left-0 z-1 h-32 w-32 rounded-full bg-white blur-[70px] transition-opacity duration-300 opacity-0"></div>
<div class="radius-xl relative z-1 flex h-screen w-full flex-col overflow-auto p-5 sm:p-10" style="background-image: url('https://assets.codepen.io/344846/photo-1697899001862-59699946ea29.jpeg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
  <div id="card" class="relative mx-auto my-auto w-full max-w-md shrink-0 overflow-hidden rounded-4xl border-t border-white/20 bg-gradient-to-t from-zinc-100/10 to-zinc-950/50 to-50% p-8 text-white shadow-2xl shadow-black outline -outline-offset-1 outline-white/5 backdrop-blur-2xl">
    <div class="mb-8 inline-flex h-12 items-center rounded-full border-b border-b-white/12 bg-zinc-950/75 p-1 text-sm font-medium w-full">
      <a href="{{ route('register') }}" class="px-6 text-zinc-500 hover:text-white transition-colors flex-1 text-center">Sign up</a>
      <div class="inline-flex h-full items-center rounded-full border-t border-t-white/10 bg-zinc-800 px-6 outline -outline-offset-1 outline-white/4 flex-1 text-center">Sign in</div>
    </div>
    
    <h2 class="mb-7 text-[1.4rem] font-medium">Sign in to your account</h2>
    
    <!-- Enhanced Error Notification Area -->
    @if ($errors->any())
        <div id="error-notification" class="mb-4 p-4 bg-red-500/20 border border-red-500/50 rounded-lg backdrop-blur-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h4 class="text-red-300 font-semibold">Login Gagal!</h4>
                    <div class="text-red-200 text-sm mt-1">
                        @foreach ($errors->all() as $error)
                            <div>• {{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Enhanced Session Status -->
    @if (session('status'))
        <div id="success-notification" class="mb-4 p-4 bg-green-500/20 border border-green-500/50 rounded-lg backdrop-blur-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div class="text-green-300">{{ session('status') }}</div>
            </div>
        </div>
    @endif
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="flex grid-cols-2 flex-col gap-4 text-sm sm:grid">
            <!-- Email -->
            <div class="relative col-span-2 h-11 overflow-hidden">
                <input 
                    type="email" 
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="peer relative z-1 h-full w-full rounded-md border border-white/8 bg-white/5 pr-4 pl-11 duration-300 placeholder:text-white/50 focus:outline-0 focus:bg-white/10 text-white font-semibold focus:text-white @error('email') border-red-500/50 bg-red-500/10 @enderror" 
                    placeholder="Enter your email" 
                />
                <svg class="pointer-events-none absolute top-3 left-3.5 z-2 mt-px h-4.5 w-4.5 text-white/20 duration-300 peer-focus-visible:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g fill="none">
                        <path d="M24 0v24H0V0zM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01z"></path>
                        <path fill="currentColor" d="M20 4a2 2 0 0 1 1.995 1.85L22 6v12a2 2 0 0 1-1.85 1.995L20 20H4a2 2 0 0 1-1.995-1.85L2 18v-1h2v1h16V7.414l-6.94 6.94a1.5 1.5 0 0 1-2.007.103l-.114-.103L4 7.414V8H2V6a2 2 0 0 1 1.85-1.995L4 4zM6 13a1 1 0 1 1 0 2H1a1 1 0 1 1 0-2zm12.586-7H5.414L12 12.586zM5 10a1 1 0 0 1 .117 1.993L5 12H2a1 1 0 0 1-.117-1.993L2 10z"></path>
                    </g>
                </svg>
                <span class="absolute bottom-0 left-0 z-2 h-px w-full bg-gradient-to-r from-transparent from-5% via-white to-transparent to-95% opacity-0 transition-opacity duration-300 peer-focus-visible:opacity-40"></span>
                <span class="absolute inset-x-4 bottom-0 z-1 h-4 origin-bottom scale-y-0 -skew-x-12 bg-gradient-to-b from-white to-transparent opacity-0 blur-md duration-300 peer-focus-visible:scale-100 peer-focus-visible:opacity-30"></span>
            </div>

            <!-- Password -->
            <div class="relative col-span-2 h-11 overflow-hidden">
                <input 
                    type="password" 
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="peer relative z-1 h-full w-full rounded-md border border-white/8 bg-white/5 pr-4 pl-11 duration-300 placeholder:text-white/50 focus:outline-0 focus:bg-white/10 text-white font-semibold focus:text-white @error('password') border-red-500/50 bg-red-500/10 @enderror" 
                    placeholder="Enter your password" 
                />
                <svg class="pointer-events-none absolute top-3 left-3.5 z-2 mt-px h-4.5 w-4.5 text-white/20 duration-300 peer-focus-visible:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12 2C9.243 2 7 4.243 7 7v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V7c0-2.757-2.243-5-5-5zM9 7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9V7zm4 10.723V20h-2v-2.277a2 2 0 1 1 2 0z"/>
                </svg>
                <span class="absolute bottom-0 left-0 z-2 h-px w-full bg-gradient-to-r from-transparent from-5% via-white to-transparent to-95% opacity-0 transition-opacity duration-300 peer-focus-visible:opacity-40"></span>
                <span class="absolute inset-x-4 bottom-0 z-1 h-4 origin-bottom scale-y-0 -skew-x-12 bg-gradient-to-b from-white to-transparent opacity-0 blur-md duration-300 peer-focus-visible:scale-100 peer-focus-visible:opacity-30"></span>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-zinc-300 text-zinc-600 shadow-sm focus:ring-zinc-500" name="remember">
                <span class="ms-2 text-sm text-white/60">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-white/60 hover:text-white transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="mt-7 h-12 w-full cursor-pointer rounded-md bg-white px-2 text-sm font-medium text-zinc-800 shadow-xl hover:bg-gray-100 transition-colors">Sign in</button>
    </form>
    
    <div class="mt-6 text-center">
        <a href="{{ route('home') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
            &larr; Kembali ke Beranda
        </a>
    </div>
    <div class="absolute inset-x-32 -bottom-20 left-32 h-10 bg-white blur-2xl"></div>
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

// Auto-hide notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const errorNotification = document.getElementById('error-notification');
    const successNotification = document.getElementById('success-notification');
    
    if (errorNotification) {
        setTimeout(() => {
            errorNotification.style.opacity = '0';
            errorNotification.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                errorNotification.style.display = 'none';
            }, 300);
        }, 5000);
    }
    
    if (successNotification) {
        setTimeout(() => {
            successNotification.style.opacity = '0';
            successNotification.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                successNotification.style.display = 'none';
            }, 300);
        }, 5000);
    }
});
</script>
@endsection

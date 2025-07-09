@extends('layouts.guest')

@section('content')
<div id="shadow" class="fixed top-0 left-0 z-1 h-32 w-32 rounded-full bg-white blur-[70px] transition-opacity duration-300 opacity-0"></div>
<div class="radius-xl relative z-1 flex h-screen w-full flex-col overflow-auto p-5 sm:p-10" style="background-image: url('https://assets.codepen.io/344846/photo-1697899001862-59699946ea29.jpeg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
  <div id="card" class="relative mx-auto my-auto w-full max-w-110 shrink-0 overflow-hidden rounded-4xl border-t border-white/20 bg-gradient-to-t from-zinc-100/10 to-zinc-950/50 to-50% p-8 text-white shadow-2xl shadow-black outline -outline-offset-1 outline-white/5 backdrop-blur-2xl">
    <div class="mb-8 inline-flex h-12 items-center rounded-full border-b border-b-white/12 bg-zinc-950/75 p-1 text-sm font-medium w-full">
      <div class="inline-flex h-full items-center rounded-full border-t border-t-white/10 bg-zinc-800 px-6 outline -outline-offset-1 outline-white/4 flex-1 text-center">Sign up</div>
      <a href="{{ route('login') }}" class="px-6 text-zinc-500 hover:text-white transition-colors flex-1 text-center">Sign in</a>
    </div>
    
    <h2 class="mb-7 text-[1.4rem] font-medium">Create an account</h2>
    
    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        <div class="flex grid-cols-2 flex-col gap-4 text-sm sm:grid">
            <!-- First Name -->
            <div class="relative h-11 overflow-hidden">
                <input 
                    type="text" 
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="peer relative z-1 h-full w-full rounded-md border border-white/8 bg-white/2 px-4 duration-300 placeholder:text-white/20 focus:outline-0 text-white" 
                    placeholder="First name" 
                />
                <span class="absolute bottom-0 left-0 z-2 h-px w-full bg-gradient-to-r from-transparent from-5% via-white to-transparent to-95% opacity-0 transition-opacity duration-300 peer-focus-visible:opacity-40"></span>
                <span class="absolute inset-x-4 bottom-0 z-1 h-4 origin-bottom scale-y-0 -skew-x-12 bg-gradient-to-b from-white to-transparent opacity-0 blur-md duration-300 peer-focus-visible:scale-100 peer-focus-visible:opacity-30"></span>
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-300" />
            </div>

            <!-- Username -->
            <div class="relative h-11 overflow-hidden">
                <input 
                    type="text" 
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    class="peer relative z-1 h-full w-full rounded-md border border-white/8 bg-white/2 px-4 duration-300 placeholder:text-white/20 focus:outline-0 text-white" 
                    placeholder="Last name" 
                />
                <span class="absolute bottom-0 left-0 z-2 h-px w-full bg-gradient-to-r from-transparent from-5% via-white to-transparent to-95% opacity-0 transition-opacity duration-300 peer-focus-visible:opacity-40"></span>
                <span class="absolute inset-x-4 bottom-0 z-1 h-4 origin-bottom scale-y-0 -skew-x-12 bg-gradient-to-b from-white to-transparent opacity-0 blur-md duration-300 peer-focus-visible:scale-100 peer-focus-visible:opacity-30"></span>
                <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-300" />
            </div>

            <!-- Email -->
            <div class="relative col-span-2 h-11 overflow-hidden">
                <input 
                    type="email" 
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    class="peer relative z-1 h-full w-full rounded-md border border-white/8 bg-white/2 pr-4 pl-11 duration-300 placeholder:text-white/20 focus:outline-0 text-white" 
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
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
            </div>

            <!-- Phone -->
            <div class="relative col-span-2 h-11 overflow-hidden">
                <input 
                    type="tel" 
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    class="peer relative z-1 h-full w-full rounded-md border border-white/8 bg-white/2 pr-4 pl-11 duration-300 placeholder:text-white/20 focus:outline-0 text-white" 
                    placeholder="(775) 341-0482" 
                />
                <svg class="pointer-events-none absolute top-3 left-3.5 z-2 mt-px h-4.5 w-4.5 text-white/20 duration-300 peer-focus-visible:text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <g fill="none">
                        <path d="M24 0v24H0V0zM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01z"></path>
                        <path fill="currentColor" d="M6.857 2.445C8.12 3.366 9.076 4.66 9.89 5.849l.638.938A1.504 1.504 0 0 1 10.35 8.7l-1.356 1.356.143.304c.35.709.954 1.73 1.863 2.64a9.87 9.87 0 0 0 2.104 1.58l.367.197.327.162.146.067 1.355-1.356a1.502 1.502 0 0 1 1.918-.171l1.014.703c1.152.81 2.355 1.733 3.29 2.931a1.47 1.47 0 0 1 .189 1.485c-.837 1.953-2.955 3.616-5.158 3.534l-.3-.016-.233-.02-.258-.03-.281-.038-.305-.051-.326-.064-.346-.077-.366-.094-.385-.11-.402-.13c-1.846-.626-4.189-1.856-6.593-4.26-2.403-2.403-3.633-4.746-4.259-6.592l-.13-.402-.11-.385-.094-.366-.078-.346a11.79 11.79 0 0 1-.063-.326l-.05-.305-.04-.281-.029-.258-.02-.233-.016-.3c-.081-2.196 1.6-4.329 3.544-5.162a1.47 1.47 0 0 1 1.445.159M5.93 4.253c-1.072.56-2.11 1.84-2.063 3.121l.02.328.022.205.029.23.04.253.051.277.065.298.08.32.096.339.114.358c.042.122.086.247.134.375l.154.392.176.407c.628 1.382 1.652 3 3.325 4.672 1.672 1.672 3.29 2.697 4.672 3.325l.407.176.392.154c.128.048.253.092.375.134l.358.114.34.096.319.08.298.065.277.051.254.04.23.03.204.02.328.02c1.264.047 2.554-.985 3.112-2.043-.712-.835-1.596-1.52-2.571-2.21l-.748-.521-.19.199-.406.443-.215.226c-.586.597-1.27 1.104-2.09.773l-.226-.095-.276-.124-.154-.073-.338-.169-.371-.2a11.866 11.866 0 0 1-2.567-1.925 11.867 11.867 0 0 1-1.925-2.567l-.2-.37-.17-.339-.196-.43L7 10.48c-.311-.769.117-1.418.664-1.98l.224-.22.557-.513.2-.19-.473-.693c-.703-1.02-1.39-1.94-2.243-2.632Zm9.063 1.787.116.013a3.5 3.5 0 0 1 2.858 2.96 1 1 0 0 1-1.958.393l-.023-.115a1.5 1.5 0 0 0-1.07-1.233l-.155-.035a1 1 0 0 1 .232-1.983M15 3a6 6 0 0 1 6 6 1 1 0 0 1-1.993.117L19 9a3.998 3.998 0 0 0-3.738-3.991L15 5a1 1 0 1 1 0-2"></path>
                    </g>
                </svg>
                <span class="absolute bottom-0 left-0 z-2 h-px w-full bg-gradient-to-r from-transparent from-5% via-white to-95% opacity-0 transition-opacity duration-300 peer-focus-visible:opacity-40"></span>
                <span class="absolute inset-x-4 bottom-0 z-1 h-4 origin-bottom scale-y-0 -skew-x-12 bg-gradient-to-b from-white to-transparent opacity-0 blur-md duration-300 peer-focus-visible:scale-100 peer-focus-visible:opacity-30"></span>
                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-red-300" />
            </div>

            <!-- Password -->
            <div class="relative col-span-2 h-11 overflow-hidden">
                <input 
                    type="password" 
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="peer relative z-1 h-full w-full rounded-md border border-white/8 bg-white/2 px-4 duration-300 placeholder:text-white/20 focus:outline-0 text-white" 
                    placeholder="Password" 
                />
                <span class="absolute bottom-0 left-0 z-2 h-px w-full bg-gradient-to-r from-transparent from-5% via-white to-transparent to-95% opacity-0 transition-opacity duration-300 peer-focus-visible:opacity-40"></span>
                <span class="absolute inset-x-4 bottom-0 z-1 h-4 origin-bottom scale-y-0 -skew-x-12 bg-gradient-to-b from-white to-transparent opacity-0 blur-md duration-300 peer-focus-visible:scale-100 peer-focus-visible:opacity-30"></span>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
            </div>

            <!-- Confirm Password -->
            <div class="relative col-span-2 h-11 overflow-hidden">
                <input 
                    type="password" 
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="peer relative z-1 h-full w-full rounded-md border border-white/8 bg-white/2 px-4 duration-300 placeholder:text-white/20 focus:outline-0 text-white" 
                    placeholder="Confirm Password" 
                />
                <span class="absolute bottom-0 left-0 z-2 h-px w-full bg-gradient-to-r from-transparent from-5% via-white to-transparent to-95% opacity-0 transition-opacity duration-300 peer-focus-visible:opacity-40"></span>
                <span class="absolute inset-x-4 bottom-0 z-1 h-4 origin-bottom scale-y-0 -skew-x-12 bg-gradient-to-b from-white to-transparent opacity-0 blur-md duration-300 peer-focus-visible:scale-100 peer-focus-visible:opacity-30"></span>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-300" />
            </div>
        </div>

        <!-- Terms Checkbox (hidden but required) -->
        <input type="hidden" name="terms" value="1" />

        <button type="submit" class="mt-7 h-12 w-full cursor-pointer rounded-md bg-white px-2 text-sm font-medium text-zinc-800 shadow-xl hover:bg-gray-100 transition-colors">Create an account</button>
    </form>
    
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
</script>
@endsection

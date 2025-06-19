<footer class="bg-gray-100 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <!-- Kolom 1: Logo & About -->
            <div class="col-span-2 md:col-span-1">
                 <a href="{{ route('home') }}" class="block mb-4">
                    <span class="font-bold text-xl text-gray-800 dark:text-gray-200">SahabatNews</span>
                 </a>
                 <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Portal berita terkini dan terpercaya untuk Anda.
                 </p>
            </div>

            <!-- Kolom 2: Navigasi -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 tracking-wider uppercase mb-4">Navigasi</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-base text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">All News</a></li>
                    <li><a href="{{ route('about') }}" class="text-base text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">About Us</a></li>
                    <li><a href="{{ route('podcast') }}" class="text-base text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Podcast</a></li>
                    <li><a href="{{ route('contact') }}" class="text-base text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Contacts</a></li>
                     {{-- Tambahkan link Analytics jika user admin/editor --}}
                     @auth
                        @if(auth()->user()->hasAnyRole(['admin','editor']))
                        <li><a href="{{ route('analytics') }}" class="text-base text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Analytics</a></li>
                        @endif
                    @endauth
                </ul>
            </div>

            <!-- Kolom 3: Kategori (Contoh) -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 tracking-wider uppercase mb-4">Kategori Populer</h3>
                 {{-- TODO: Ganti dengan kategori dinamis nanti --}}
                <ul class="space-y-3">
                    <li><a href="#" class="text-base text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Politik</a></li>
                    <li><a href="#" class="text-base text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Ekonomi</a></li>
                    <li><a href="#" class="text-base text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Teknologi</a></li>
                    <li><a href="#" class="text-base text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Olahraga</a></li>
                </ul>
            </div>

             <!-- Kolom 4: Ikuti Kami -->
            <div>
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 tracking-wider uppercase mb-4">Ikuti Kami</h3>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Facebook</span>
                        {{-- Ganti dengan SVG Icon --}}
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Twitter</span>
                        {{-- Ganti dengan SVG Icon --}}
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Instagram</span>
                        {{-- Ganti dengan SVG Icon --}}
                         <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 3.808s-.012 2.74-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-3.808.06s-2.74-.012-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.048-1.067-.06-1.407-.06-3.808s.012-2.74.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.495 2.465c.636-.247 1.363-.416 2.427-.465C8.935 2.013 9.289 2 12.315 2zm0 1.621c-2.403 0-2.729.01-3.662.056-1.008.046-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.298.849-.344 1.857-.046 1.067-.056 1.366-.056 3.661s.01 2.594.056 3.661c.046 1.008.207 1.504.344 1.857.182.466.398.8.748 1.15.35.35.683.566 1.15.748.353.137.849.298 1.857.344 1.067.046 1.366.056 3.661.056 2.403 0 2.729-.01 3.662-.056 1.008-.046 1.504-.207 1.857-.344.467-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.298-.849.344-1.857.046-1.067.056-1.366.056-3.661s-.01-2.594-.056-3.661c-.046-1.008-.207-1.504-.344-1.857-.182-.467-.398-.8-.748-1.15-.35-.35-.683-.566-1.15-.748-.353-.137-.849-.298-1.857-.344C15.044 2.01 14.718 2 12.315 2zm0 2.993c-2.398 0-4.34 1.943-4.34 4.34s1.942 4.34 4.34 4.34 4.34-1.943 4.34-4.34-1.942-4.34-4.34-4.34zm0 7.172c-1.597 0-2.882-1.285-2.882-2.882s1.285-2.882 2.882-2.882 2.882 1.285 2.882 2.882-1.285 2.882-2.882 2.882zm4.958-7.802a1.153 1.153 0 11-2.305 0 1.153 1.153 0 012.305 0z" clip-rule="evenodd" /></svg>
                    </a>
                    {{-- Tambahkan social media lain jika perlu --}}
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
            <p class="text-base text-gray-400 text-center">&copy; {{ date('Y') }} SahabatNews. All rights reserved.</p>
            <div class="flex justify-center mt-2 space-x-4 text-xs text-gray-500 dark:text-gray-400">
                <a href="{{ route('terms') }}" class="hover:underline">Syarat & Ketentuan</a>
                <span>|</span>
                <a href="{{ route('privacy') }}" class="hover:underline">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
</footer> 
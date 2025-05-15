<!-- Bottom Navigation (Mobile Only) -->
<nav class="md:hidden fixed bottom-0 inset-x-0 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 shadow-lg z-40">
    <div class="max-w-md mx-auto px-1">
        <div class="flex justify-around items-stretch h-16"> {{-- h-16 untuk tinggi sekitar 4rem --}}
            <!-- Tombol Home -->
            <a href="{{ route('home') }}" 
               class="flex-1 flex flex-col items-center justify-center text-xs p-1 {{ request()->routeIs('home') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i class='bx bx-home-alt text-2xl'></i>
                <span class="mt-0.5">Home</span>
            </a>

            <!-- Tombol Kategori -->
            {{-- Jika ada halaman khusus daftar kategori, arahkan ke sana --}}
            <a href="{{ route('home') }}#categories-filter" {{-- Mengarah ke ID filter kategori di halaman home --}}
               class="flex-1 flex flex-col items-center justify-center text-xs p-1 {{ request()->is('categories') || request()->is('categories/*') || Str::contains(url()->current(), '#categories-filter') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i class='bx bx-category-alt text-2xl'></i>
                <span class="mt-0.5">Kategori</span>
            </a>

            <!-- Tombol Pencarian (Placeholder Fungsi) -->
            <button type="button" id="mobileSearchButton" 
                    class="flex-1 flex flex-col items-center justify-center text-xs p-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                <i class='bx bx-search text-2xl'></i>
                <span class="mt-0.5">Cari</span>
            </button>

            @auth
            <!-- Tombol Profile -->
            <a href="{{ route('profile.edit') }}"
               class="flex-1 flex flex-col items-center justify-center text-xs p-1 {{ request()->routeIs('profile.edit') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400' }}">
                <i class='bx bx-user text-2xl'></i>
                <span class="mt-0.5">Profil</span>
            </a>
            @else
            <!-- Tombol Login -->
            <a href="{{ route('login') }}"
               class="flex-1 flex flex-col items-center justify-center text-xs p-1 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">
                <i class='bx bx-log-in text-2xl'></i>
                <span class="mt-0.5">Login</span>
            </a>
            @endauth
        </div>
    </div>
</nav>

{{-- Tambahkan sedikit padding di bawah body untuk menghindari konten tertutup bottom nav --}}
<div class="pb-16 md:pb-0"></div>

@push('scripts')
<script>
    // Script untuk tombol pencarian mobile jika ingin memicu modal atau aksi lain
    // document.getElementById('mobileSearchButton').addEventListener('click', function() {
    //     alert('Fungsi pencarian mobile belum diimplementasikan.');
    //     // Contoh: buka modal pencarian
    //     // document.getElementById('searchModal').classList.remove('hidden'); 
    // });
</script>
@endpush 
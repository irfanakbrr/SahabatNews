<x-app-layout>
    <x-slot name="title">
        Podcast SahabatNews
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-10 text-center">
            <h1 class="text-3xl sm:text-4xl font-extrabold mb-2 text-gray-900 dark:text-white">Podcast SahabatNews</h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-4">Dengarkan diskusi mendalam, inspiratif, dan edukatif seputar isu terkini bersama narasumber terpercaya.</p>
            <a href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-full shadow transition mb-2">Subscribe Podcast</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Contoh Episode 1 -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex flex-col hover:scale-105 transition-transform">
                <img src="https://via.placeholder.com/400x200?text=Podcast+Episode+1" alt="Episode 1" class="rounded-xl mb-4 w-full object-cover h-44 shadow">
                <h2 class="text-xl font-bold mb-2 dark:text-white">Judul Episode Podcast 1</h2>
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-1">Deskripsi singkat tentang episode podcast ini. Membahas tentang topik A, B, dan C.</p>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                    <span><i class="fa fa-calendar mr-1"></i> 01 Jan 2024</span>
                    <span><i class="fa fa-clock mr-1"></i> 30:15</span>
                </div>
                <a href="#" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-full font-semibold text-sm transition">Dengarkan Sekarang</a>
            </div>
            <!-- Contoh Episode 2 -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex flex-col hover:scale-105 transition-transform">
                <img src="https://via.placeholder.com/400x200?text=Podcast+Episode+2" alt="Episode 2" class="rounded-xl mb-4 w-full object-cover h-44 shadow">
                <h2 class="text-xl font-bold mb-2 dark:text-white">Judul Episode Podcast 2</h2>
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-1">Pembahasan menarik lainnya di episode kedua. Fokus pada D dan E.</p>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                    <span><i class="fa fa-calendar mr-1"></i> 15 Jan 2024</span>
                    <span><i class="fa fa-clock mr-1"></i> 25:50</span>
                </div>
                <a href="#" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-full font-semibold text-sm transition">Dengarkan Sekarang</a>
            </div>
            <!-- Contoh Episode 3 -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 flex flex-col hover:scale-105 transition-transform">
                <img src="https://via.placeholder.com/400x200?text=Podcast+Episode+3" alt="Episode 3" class="rounded-xl mb-4 w-full object-cover h-44 shadow">
                <h2 class="text-xl font-bold mb-2 dark:text-white">Judul Episode Podcast 3</h2>
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-1">Episode terbaru dengan bintang tamu spesial membahas F.</p>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                    <span><i class="fa fa-calendar mr-1"></i> 01 Feb 2024</span>
                    <span><i class="fa fa-clock mr-1"></i> 40:00</span>
                </div>
                <a href="#" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-full font-semibold text-sm transition">Dengarkan Sekarang</a>
            </div>
            <!-- Tambahkan loop untuk episode lainnya jika sudah dinamis -->
        </div>
    </div>
</x-app-layout> 
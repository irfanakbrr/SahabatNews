<x-app-layout>
    <x-slot name="title">
        Podcast SahabatNews
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="mb-6 text-center">
            <h1 class="text-2xl sm:text-3xl font-bold mb-2 dark:text-white">Podcast SahabatNews</h1>
            <p class="text-gray-600 dark:text-gray-300">Dengarkan diskusi mendalam tentang isu terkini.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Contoh Episode 1 -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-5 flex flex-col">
            <img src="https://via.placeholder.com/400x200?text=Podcast+Episode+1" alt="Episode 1" class="rounded-md mb-4 w-full object-cover h-40">
                <h2 class="text-lg font-semibold mb-2 dark:text-white">Judul Episode Podcast 1</h2>
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-1">Deskripsi singkat tentang episode podcast ini. Membahas tentang topik A, B, dan C.</p>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                <span>Tanggal Publikasi: 01 Jan 2024</span>
                <span>Durasi: 30:15</span>
            </div>
                <button class="w-full bg-gray-900 dark:bg-gray-700 text-white py-2 rounded-full font-semibold text-sm hover:bg-gray-700 dark:hover:bg-gray-600 transition">Dengarkan Sekarang</button>
        </div>

        <!-- Contoh Episode 2 -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-5 flex flex-col">
            <img src="https://via.placeholder.com/400x200?text=Podcast+Episode+2" alt="Episode 2" class="rounded-md mb-4 w-full object-cover h-40">
                <h2 class="text-lg font-semibold mb-2 dark:text-white">Judul Episode Podcast 2</h2>
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-1">Pembahasan menarik lainnya di episode kedua. Fokus pada D dan E.</p>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                <span>Tanggal Publikasi: 15 Jan 2024</span>
                <span>Durasi: 25:50</span>
            </div>
                <button class="w-full bg-gray-900 dark:bg-gray-700 text-white py-2 rounded-full font-semibold text-sm hover:bg-gray-700 dark:hover:bg-gray-600 transition">Dengarkan Sekarang</button>
        </div>

        <!-- Contoh Episode 3 -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-5 flex flex-col">
            <img src="https://via.placeholder.com/400x200?text=Podcast+Episode+3" alt="Episode 3" class="rounded-md mb-4 w-full object-cover h-40">
                <h2 class="text-lg font-semibold mb-2 dark:text-white">Judul Episode Podcast 3</h2>
                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 flex-1">Episode terbaru dengan bintang tamu spesial membahas F.</p>
                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                <span>Tanggal Publikasi: 01 Feb 2024</span>
                <span>Durasi: 40:00</span>
                </div>
                <button class="w-full bg-gray-900 dark:bg-gray-700 text-white py-2 rounded-full font-semibold text-sm hover:bg-gray-700 dark:hover:bg-gray-600 transition">Dengarkan Sekarang</button>
            </div>
            <!-- Tambahkan loop untuk episode lainnya jika sudah dinamis -->
        </div>
    </div>
</x-app-layout> 
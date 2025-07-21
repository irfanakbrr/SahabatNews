<x-app-layout>
    <x-slot name="title">
        Pusat Bantuan & Dukungan
    </x-slot>

    <div class="container mx-auto px-4 py-12">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">Pusat Bantuan</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">Kami siap membantu Anda.</p>
        </div>

        <div class="max-w-4xl mx-auto mt-10">
            <!-- FAQ Section -->
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Pertanyaan Umum (FAQ)</h2>
            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <details class="group bg-gray-50 dark:bg-gray-800 p-6 rounded-lg cursor-pointer">
                    <summary class="flex items-center justify-between font-semibold text-gray-800 dark:text-white">
                        Bagaimana cara saya membuat akun?
                        <span class="transform transition-transform duration-300 group-open:rotate-180">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">
                        Anda dapat membuat akun dengan mengklik tombol "Register" di pojok kanan atas halaman. Isi formulir dengan nama, alamat email, dan password Anda. Setelah itu, Anda bisa langsung login.
                    </p>
                </details>

                <!-- FAQ Item 2 -->
                <details class="group bg-gray-50 dark:bg-gray-800 p-6 rounded-lg cursor-pointer">
                    <summary class="flex items-center justify-between font-semibold text-gray-800 dark:text-white">
                        Bagaimana cara mengirimkan artikel?
                        <span class="transform transition-transform duration-300 group-open:rotate-180">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">
                        Setelah login, masuk ke halaman Dashboard Anda. Di sana, Anda akan menemukan menu untuk membuat dan mengelola artikel. Tulisan yang Anda kirim akan direview terlebih dahulu oleh tim editor kami sebelum dipublikasikan.
                    </p>
                </details>

                <!-- FAQ Item 3 -->
                <details class="group bg-gray-50 dark:bg-gray-800 p-6 rounded-lg cursor-pointer">
                    <summary class="flex items-center justify-between font-semibold text-gray-800 dark:text-white">
                        Apakah saya bisa mengubah data profil saya?
                        <span class="transform transition-transform duration-300 group-open:rotate-180">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">
                        Tentu saja. Anda dapat mengubah nama, email, dan password Anda melalui halaman "Profile" yang dapat diakses dari menu dropdown di pojok kanan atas setelah Anda login.
                    </p>
                </details>
            </div>
            
            <!-- Contact Section -->
            <div class="mt-12 text-center bg-green-50 dark:bg-green-800/20 p-8 rounded-lg">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-3">Butuh Bantuan Lebih Lanjut?</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Jika Anda tidak menemukan jawaban atas pertanyaan Anda, jangan ragu untuk menghubungi tim kami secara langsung.
                </p>
                <a href="{{ route('contact') }}" class="inline-block bg-green-600 text-white font-semibold px-8 py-3 rounded-lg hover:bg-green-700 transition-colors duration-300">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</x-app-layout> 
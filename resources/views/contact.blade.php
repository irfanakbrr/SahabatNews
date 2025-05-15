<x-app-layout>
    <x-slot name="title">
        Hubungi SahabatNews
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="prose max-w-none dark:prose-invert">
                <h1 class="text-2xl sm:text-3xl font-bold mb-4">Hubungi Kami</h1>
                <p class="mb-6">Punya pertanyaan, masukan, atau ingin bekerja sama? Silakan isi form di samping atau hubungi kami melalui detail kontak di bawah.</p>

                <h2 class="text-xl font-semibold mb-3">Detail Kontak</h2>
                <div class="space-y-2">
                    <p><strong>Alamat:</strong> Jl. Contoh Berita No. 123, Jakarta, Indonesia</p>
                    <p><strong>Email:</strong> <a href="mailto:info@sahabatnews.test" class="text-blue-600 hover:underline">info@sahabatnews.test</a></p>
                    <p><strong>Telepon:</strong> +62 21 1234 5678</p>
                </div>

                <h2 class="text-xl font-semibold mb-3 mt-6">Media Sosial</h2>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-white"><span class="sr-only">Facebook</span><!-- Ganti dengan ikon FB --> F</a>
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-white"><span class="sr-only">Twitter</span><!-- Ganti dengan ikon Twitter --> T</a>
                    <a href="#" class="text-gray-600 dark:text-gray-300 hover:text-black dark:hover:text-white"><span class="sr-only">Instagram</span><!-- Ganti dengan ikon IG --> I</a>
                </div>
            </div>

            <div>
                <form action="#" method="POST" class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 space-y-4">
                    @csrf <!-- Tambahkan jika form akan diproses oleh Laravel -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nama Lengkap</label>
                        <input type="text" id="name" name="name" required class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-offset-gray-800">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Alamat Email</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-offset-gray-800">
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Subjek</label>
                        <input type="text" id="subject" name="subject" required class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-offset-gray-800">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Pesan Anda</label>
                        <textarea id="message" name="message" rows="4" required class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-offset-gray-800"></textarea>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-gray-900 dark:bg-gray-700 text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-700 dark:hover:bg-gray-600 transition">Kirim Pesan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 
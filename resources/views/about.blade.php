<x-app-layout>
    <x-slot name="title">
        Tentang SahabatNews
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="prose max-w-none dark:prose-invert">
            <h1 class="text-center text-2xl sm:text-3xl font-bold mb-6">Tentang SahabatNews</h1>

            <img src="https://via.placeholder.com/800x300" alt="Tim SahabatNews" class="rounded-lg mb-6 w-full object-cover h-48 sm:h-64" loading="lazy">

            <h2 class="text-xl sm:text-2xl font-semibold mb-3">Visi Kami</h2>
            <p>Menjadi sumber berita terpercaya dan relevan bagi masyarakat Indonesia, menyajikan informasi akurat, mendalam, dan berimbang untuk mencerahkan dan memberdayakan.</p>

            <h2 class="text-xl sm:text-2xl font-semibold mb-3 mt-6">Misi Kami</h2>
            <ul class="list-disc list-inside space-y-2">
                <li>Menyajikan berita terkini dari berbagai kategori dengan standar jurnalisme tinggi.</li>
                <li>Memberikan platform bagi penulis/editor untuk berbagi informasi berkualitas.</li>
                <li>Membangun komunitas pembaca yang kritis dan terinformasi.</li>
                <li>Menggunakan teknologi untuk menyajikan berita dengan cara yang menarik dan mudah diakses.</li>
            </ul>

            <h2 class="text-xl sm:text-2xl font-semibold mb-3 mt-6">Tim Kami</h2>
            <p>SahabatNews didukung oleh tim editor, jurnalis, dan kontributor yang berdedikasi untuk menyajikan berita terbaik. Kami percaya pada kekuatan informasi untuk membawa perubahan positif.</p>
            <!-- Bisa ditambahkan foto tim jika ada -->

            <h2 class="text-xl sm:text-2xl font-semibold mb-3 mt-6">Hubungi Kami</h2>
            <p>Punya pertanyaan, saran, atau ingin berkontribusi? Jangan ragu hubungi kami melalui halaman <a href="{{ route('contact') }}" class="text-blue-600 hover:underline">Kontak</a>.</p>

            <h2 class="text-xl sm:text-2xl font-semibold mb-3 mt-6">Ikuti Kami</h2>
            <div class="flex gap-4">
                <a href="#" class="text-gray-600 hover:text-black"><span class="sr-only">Facebook</span><!-- Ganti dengan ikon FB --> F</a>
                <a href="#" class="text-gray-600 hover:text-black"><span class="sr-only">Twitter</span><!-- Ganti dengan ikon Twitter --> T</a>
                <a href="#" class="text-gray-600 hover:text-black"><span class="sr-only">Instagram</span><!-- Ganti dengan ikon IG --> I</a>
            </div>
        </div>
    </div>
</x-app-layout> 
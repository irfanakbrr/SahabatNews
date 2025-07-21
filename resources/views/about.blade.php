<x-app-layout>
    <x-slot name="title">
        Tentang SahabatNews
    </x-slot>

    <div class="container mx-auto px-4 py-10">
        <div class="prose max-w-none dark:prose-invert">
            <h1 class="text-center text-3xl sm:text-4xl font-extrabold mb-8">Tentang SahabatNews</h1>
            <div class="flex flex-col sm:flex-row gap-8 items-center mb-8">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=871&q=80" alt="Tim SahabatNews" class="rounded-xl shadow-lg w-full sm:w-1/2 object-cover h-56 sm:h-72" loading="lazy">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold mb-3">Visi Kami</h2>
                    <p class="mb-4">Menjadi sumber berita terpercaya dan relevan bagi masyarakat Indonesia, menyajikan informasi akurat, mendalam, dan berimbang untuk mencerahkan dan memberdayakan.</p>
                    <h2 class="text-2xl font-bold mb-3 mt-6">Misi Kami</h2>
                    <ul class="list-disc list-inside space-y-2 mb-4">
                <li>Menyajikan berita terkini dari berbagai kategori dengan standar jurnalisme tinggi.</li>
                <li>Memberikan platform bagi penulis/editor untuk berbagi informasi berkualitas.</li>
                <li>Membangun komunitas pembaca yang kritis dan terinformasi.</li>
                <li>Menggunakan teknologi untuk menyajikan berita dengan cara yang menarik dan mudah diakses.</li>
            </ul>
                </div>
            </div>
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-3">Tim Kami</h2>
            <p>SahabatNews didukung oleh tim editor, jurnalis, dan kontributor yang berdedikasi untuk menyajikan berita terbaik. Kami percaya pada kekuatan informasi untuk membawa perubahan positif.</p>
            </div>
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-3">Hubungi Kami</h2>
            <p>Punya pertanyaan, saran, atau ingin berkontribusi? Jangan ragu hubungi kami melalui halaman <a href="{{ route('contact') }}" class="text-blue-600 hover:underline">Kontak</a>.</p>
            </div>
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-3">Ikuti Kami</h2>
            <div class="flex gap-4">
                    <a href="#" class="text-gray-600 hover:text-blue-600 text-2xl"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-gray-600 hover:text-blue-400 text-2xl"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-gray-600 hover:text-pink-500 text-2xl"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="text-center mt-10">
                <a href="{{ route('home') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-full shadow transition">Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</x-app-layout> 
<x-app-layout>
    <x-slot name="title">
        Hasil Pencarian untuk: "{{ $keyword }}"
    </x-slot>

    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- Form Pencarian (diisi dengan keyword saat ini) -->
        <form action="{{ route('search') }}" method="GET" class="mb-6 sm:mb-8">
            <input type="text" name="q" placeholder="Cari berita lain..." value="{{ $keyword }}" class="w-full px-3 py-2 sm:px-5 sm:py-3 rounded-full border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-black text-sm sm:text-base md:text-lg" />
        </form>

        <!-- Judul Hasil Pencarian -->
        <div class="text-center font-semibold text-black mb-4 sm:mb-6 text-xl sm:text-2xl md:text-3xl">
            Hasil Pencarian: "<span class="text-black">{{ $keyword }}</span>"
        </div>

        <!-- Konten Berita Hasil Pencarian -->
        @if ($posts->isNotEmpty())
            <div class="text-sm text-gray-600 mb-4">
                Ditemukan {{ $posts->total() }} hasil. (Halaman {{ $posts->currentPage() }} dari {{ $posts->lastPage() }})
            </div>

            <div class="w-full max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                    <a href="{{ route('posts.show', $post->slug) }}" class="bg-white rounded-xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden group">
                        <img src="{{ $post->image ? Storage::url($post->image) : 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="{{ $post->title }}" class="w-full h-40 object-cover" loading="lazy">
                        <div class="p-4 flex-1 flex flex-col">
                            @if($post->category)
                            <span class="inline-block {{ $post->category->color ?? 'bg-gray-500' }} text-white text-xs font-semibold px-2 py-1 rounded mb-2">{{ $post->category->name }}</span>
                            @endif
                            <div class="font-semibold text-base mb-1 group-hover:text-indigo-600 transition">{{ $post->title }}</div>
                            <div class="text-xs text-gray-500">{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            @if ($posts->hasPages())
                <div class="mt-8 pt-4 border-t border-gray-200">
                    {{ $posts->links() }}
                </div>
            @endif
        @else
            <div class="text-center text-gray-500 py-10">
                <p>Tidak ada hasil yang ditemukan untuk pencarian "<span class="font-semibold">{{ $keyword }}</span>".</p>
                <p class="mt-2 text-sm">Coba gunakan kata kunci yang berbeda.</p>
            </div>
        @endif
    </div>
</x-app-layout> 
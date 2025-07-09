<x-app-layout>
    <x-slot name="title">
        Artikel Tersimpan
    </x-slot>

    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="text-center font-semibold text-black mb-6 text-2xl md:text-3xl">
            <i class='bx bxs-bookmark-star align-middle'></i> Artikel Tersimpan Anda
        </div>

        @if ($bookmarkedPosts->isNotEmpty())
            <div class="w-full max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($bookmarkedPosts as $bookmark)
                    @php $post = $bookmark->post; @endphp
                    @if($post)
                    <a href="{{ route('posts.show', $post->slug) }}" class="bg-white rounded-xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden group">
                        <img src="{{ $post->image ? Storage::url($post->image) : 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="{{ $post->title }}" class="w-full h-40 object-cover" loading="lazy">
                        <div class="p-4 flex-1 flex flex-col">
                            @if($post->category)
                            <span class="inline-block {{ $post->category->color ?? 'bg-gray-500' }} text-white text-xs font-semibold px-2 py-1 rounded mb-2">{{ $post->category->name }}</span>
                            @endif
                            <div class="font-semibold text-base mb-1 group-hover:text-green-600 transition">{{ $post->title }}</div>
                            <div class="text-xs text-gray-500">{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</div>
                        </div>
                    </a>
                    @endif
                @endforeach
            </div>

            @if ($bookmarkedPosts->hasPages())
                <div class="mt-8 pt-4 border-t border-gray-200">
                    {{ $bookmarkedPosts->links() }}
                </div>
            @endif
        @else
            <div class="text-center text-gray-500 py-10 bg-white rounded-lg shadow-sm">
                <i class='bx bx-sad text-4xl mb-2'></i>
                <p>Anda belum menyimpan artikel apa pun.</p>
                <p class="mt-2 text-sm">Klik ikon "Simpan" pada artikel yang Anda sukai.</p>
            </div>
        @endif
    </div>
</x-app-layout> 
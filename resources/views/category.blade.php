<x-app-layout>
    <x-slot name="title">
        Berita Kategori: {{ $category->name }}
    </x-slot>

    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- Judul Section -->
        <div class="text-center font-semibold text-black mb-3 sm:mb-4 text-xl sm:text-2xl md:text-3xl">
            Kategori: <span class="text-black">{{ $category->name }}</span>
        </div>

        <!-- Search Bar -->
        <div class="mb-3 sm:mb-4">
            <input type="text" placeholder="Search dalam {{ $category->name }}..." class="w-full px-3 py-2 sm:px-5 sm:py-3 rounded-full border border-gray-200 bg-[#F7F7F9] focus:outline-none focus:ring-2 focus:ring-black text-sm sm:text-base md:text-lg" />
        </div>

        <!-- Filter Kategori -->
        <div class="flex gap-2 sm:gap-3 overflow-x-auto scrollbar-hide mb-4 sm:mb-8 pb-1">
            <a href="{{ route('home') }}" 
               class="px-4 sm:px-5 py-2 rounded-full border border-gray-200 bg-white text-gray-700 font-medium flex items-center gap-1 whitespace-nowrap text-xs sm:text-sm md:text-base transition hover:border-black hover:bg-gray-50">
                All
            </a>
            @if(isset($categories) && $categories->count() > 0)
                @php
                    $category_emojis = [
                        'Indonesia News' => '🇮🇩',
                        'World News' => '🌍',
                        'Politics' => '💼',
                        'Economics' => '💸',
                        'Sports' => '⚽',
                        'Science' => '🔬',
                        'IT' => '💻',
                        'Nature' => '🌳',
                    ];
                @endphp
                @foreach($categories as $cat)
                    <a href="{{ route('category.show', $cat->slug) }}" 
                       class="px-4 sm:px-5 py-2 rounded-full border font-medium flex items-center gap-1 whitespace-nowrap text-xs sm:text-sm md:text-base transition hover:border-black hover:bg-gray-50
                              {{ $category->slug == $cat->slug ? 'bg-black text-white border-black' : 'bg-white text-gray-700 border-gray-200' }}">
                        {{ $cat->name }}
                        @if(isset($category_emojis[$cat->name]))
                            <span class="ml-1">{{ $category_emojis[$cat->name] }}</span>
                        @endif
                    </a>
                @endforeach
            @endif
        </div>

        <!-- Konten Berita -->
        @if ($posts->isNotEmpty())
            <div class="w-full max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                    <a href="{{ route('posts.show', $post->slug) }}" class="bg-white rounded-xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden group">
                        <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="{{ $post->title }}" class="w-full h-40 object-cover" loading="lazy">
                        <div class="p-4 flex-1 flex flex-col">
                            <span class="inline-block {{ $post->category->color ?? 'bg-gray-500' }} text-white text-xs font-semibold px-2 py-1 rounded mb-2">{{ $post->category->name }}</span>
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
            <p class="text-center text-gray-500 py-10">Belum ada berita dalam kategori "{{ $category->name }}".</p>
        @endif
    </div>
</x-app-layout> 
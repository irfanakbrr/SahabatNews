<x-app-layout>
    <x-slot name="title">
        Berita Kategori: {{ $category->name }}
    </x-slot>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- Judul Section -->
        <div class="text-center font-semibold text-black dark:text-white mb-3 sm:mb-4 text-xl sm:text-2xl md:text-3xl">
            Kategori: <span class="{{ $category->color ?? 'text-black dark:text-white' }}">{{ $category->name }}</span>
        </div>

        <!-- Search Bar (Opsional, bisa di-include dari partial jika ada) -->
        <div class="mb-3 sm:mb-4">
            <input type="text" placeholder="Search dalam {{ $category->name }}..." class="w-full px-3 py-2 sm:px-5 sm:py-3 rounded-full border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-gray-500 text-sm sm:text-base md:text-lg" />
        </div>

        <!-- Filter Kategori -->
        <div class="flex gap-2 sm:gap-3 overflow-x-auto scrollbar-hide mb-4 sm:mb-8 pb-1">
            <a href="{{ route('home') }}" 
               class="px-4 sm:px-5 py-2 rounded-full border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium flex items-center gap-1 whitespace-nowrap text-xs sm:text-sm md:text-base transition hover:border-black dark:hover:border-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600">
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
                       class="px-4 sm:px-5 py-2 rounded-full border font-medium flex items-center gap-1 whitespace-nowrap text-xs sm:text-sm md:text-base transition hover:border-black dark:hover:border-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600
                              {{ $category->slug == $cat->slug ? 'bg-black text-white dark:bg-gray-900 dark:text-white border-black dark:border-gray-900' : 'bg-white text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600' }}">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($posts as $post)
                    <a href="{{ route('posts.show', $post->slug) }}" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden group">
                        <div class="relative">
                            <img src="{{ $post->image ? asset('storage/' . $post->image) : 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="{{ $post->title }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            @if($post->category)
                                <span class="absolute top-3 left-3 inline-block {{ $post->category->color ?? 'bg-blue-600' }} text-white text-[10px] sm:text-xs font-semibold px-2 py-0.5 rounded-full">{{ $post->category->name }}</span>
                            @endif
                        </div>
                        <div class="p-4 sm:p-5">
                            <h3 class="text-md sm:text-lg font-semibold text-black dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">{{ Str::limit($post->title, 60) }}</h3>
                            <div class="flex items-center gap-2 text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 mb-1">
                                <span>{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</span>
                                {{-- <span>• 5 min read</span> --}}
                            </div>
                            {{-- <p class="text-gray-600 dark:text-gray-300 text-xs sm:text-sm">{{ Str::limit(strip_tags($post->content), 80) }}</p> --}}
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination Links --}}
            @if ($posts->hasPages())
                <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $posts->links() }} {{-- Secara default, Laravel akan menggunakan view paginasi Tailwind --}}
                </div>
            @endif
        @else
            <p class="text-center text-gray-500 dark:text-gray-400 py-10">Belum ada berita dalam kategori "{{ $category->name }}".</p>
        @endif
    </div>
</x-app-layout> 
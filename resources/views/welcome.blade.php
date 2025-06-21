<x-app-layout>
    {{-- Hapus @extends dan @section, bungkus konten dengan <x-app-layout> --}}
    {{-- Tidak perlu header slot untuk welcome page --}}

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- Judul Section -->
        <div class="text-center font-semibold text-black mb-3 sm:mb-4 text-base sm:text-xl md:text-2xl">Latest News <span class="align-middle">👀</span></div>

        <!-- Search Bar -->
        <div class="mb-3 sm:mb-4">
            <input type="text" placeholder="Search" class="w-full px-3 py-2 sm:px-5 sm:py-3 rounded-full border border-gray-200 bg-[#F7F7F9] focus:outline-none focus:ring-2 focus:ring-black text-sm sm:text-base md:text-lg" />
        </div>

        <!-- Filter Kategori -->
        <div id="categories-filter" class="flex gap-2 sm:gap-3 overflow-x-auto scrollbar-hide mb-4 sm:mb-8 pb-1">
            <a href="{{ route('home') }}" class="px-4 sm:px-5 py-2 rounded-full {{ request()->routeIs('home') && !request()->route('category') ? 'bg-black text-white' : 'bg-white text-gray-700 border border-gray-200 hover:border-black hover:bg-gray-50' }} font-semibold whitespace-nowrap text-xs sm:text-sm md:text-base transition">All</a>
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
                        // Tambahkan kategori lain dan emojinya di sini
                    ];
                @endphp
                @foreach($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" 
                       class="px-4 sm:px-5 py-2 rounded-full border border-gray-200 font-medium flex items-center gap-1 whitespace-nowrap text-xs sm:text-sm md:text-base transition hover:border-black hover:bg-gray-50 
                              {{ request()->route('category') && request()->route('category')->slug == $category->slug ? 'bg-black text-white' : 'bg-white text-gray-700' }}">
                        {{ $category->name }}
                        @if(isset($category_emojis[$category->name]))
                            <span class="ml-1">{{ $category_emojis[$category->name] }}</span>
                        @endif
                    </a>
                @endforeach
            @endif
        </div>

        <!-- Konten Berita -->
        @if ($posts->isNotEmpty())
            <div class="flex flex-col gap-4 sm:gap-6 md:grid md:grid-cols-2 md:gap-8">
                <!-- Berita Utama (Ambil post pertama dari collection) -->
                @php $mainPost = $posts->first(); @endphp
                <a href="{{ route('posts.show', $mainPost) }}" class="block relative rounded-2xl overflow-hidden min-h-[160px] sm:min-h-[320px] md:min-h-[340px] flex flex-col justify-end bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out group">
                    <img src="{{ $mainPost->image ? $mainPost->image : 'https://via.placeholder.com/600x400?text=No+Image' }}" alt="{{ $mainPost->title }}" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-70 transition-opacity duration-300" loading="lazy" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div> {{-- Gradient overlay --}}
                    <div class="relative z-10 p-2 sm:p-6">
                        @if($mainPost->category)
                        <span class="inline-block {{ $mainPost->category->color ?? 'bg-blue-600' }} text-white text-[10px] sm:text-xs font-semibold px-2 sm:px-4 py-1 rounded-full mb-1 sm:mb-3">{{ $mainPost->category->name }}</span>
        @endif
                        <div class="flex items-center gap-2 text-[10px] sm:text-xs text-white/80 mb-1 sm:mb-2">
                            <span>{{ $mainPost->published_at ? $mainPost->published_at->format('d M Y') : '-' }}</span>
                            {{-- <span>•</span> --}}
                            {{-- <span>10 min read</span> --}} {{-- Estimasi waktu baca perlu logic tambahan --}}
                        </div>
                        <div class="text-sm sm:text-2xl md:text-3xl font-bold text-white leading-tight drop-shadow-lg group-hover:underline">{{ $mainPost->title }}</div>
                    </div>
                    {{-- Tombol panah bisa dihapus jika seluruh card sudah jadi link --}}
                    {{-- <button class="absolute bottom-2 right-2 sm:bottom-6 sm:right-6 bg-white/80 hover:bg-white text-black rounded-full p-1.5 sm:p-3 shadow transition">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="11"/><polyline points="12 8 16 12 12 16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    </button> --}}
                </a>

                <!-- List Berita Samping (Ambil sisa post) -->
                <div class="flex flex-col gap-2 sm:gap-4">
                    @foreach ($sidePosts as $post) {{-- Gunakan $sidePosts --}}
                        <a href="{{ route('posts.show', $post) }}" class="flex items-center bg-white rounded-2xl shadow-sm p-2 sm:p-5 gap-2 sm:gap-4 hover:shadow-md transition group">
                            <div class="flex-1">
                                <div class="text-xs sm:text-base font-semibold text-black mb-1 group-hover:text-indigo-600 transition">{{ $post->title }}</div>
                                <div class="flex items-center gap-2 text-[10px] sm:text-xs mb-1">
                                    @if($post->category)
                                    <span class="inline-block {{ $post->category->color ?? 'bg-gray-500' }} text-white px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium">{{ $post->category->name }}</span>
                                    @endif
                                    <span class="text-gray-500">{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</span>
                                    {{-- <span class="text-gray-400">• 10 min read</span> --}}
                                </div>
                            </div>
                            <div class="rounded-full border border-gray-300 p-1.5 sm:p-2 group-hover:bg-gray-100 transition">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-500 group-hover:text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="10"/><polyline points="11 7 15 11 11 15"/><line x1="7" y1="11" x2="15" y2="11"/></svg>
                        </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <p class="text-center text-gray-500">Belum ada berita terbaru.</p>
        @endif

        @if($bottomPosts->isNotEmpty())
            <div id="load-more-grid" class="w-full max-w-7xl mx-auto">
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 grid-news">
                    @foreach($bottomPosts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="bg-white rounded-xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden group">
                            <img src="{{ $post->image ? $post->image : 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="{{ $post->title }}" class="w-full h-40 object-cover" loading="lazy">
                            <div class="p-4 flex-1 flex flex-col">
                                <span class="inline-block {{ $post->category->color ?? 'bg-gray-500' }} text-white text-xs font-semibold px-2 py-1 rounded mb-2">{{ $post->category->name }}</span>
                                <div class="font-semibold text-base mb-1 group-hover:text-indigo-600 transition">{{ $post->title }}</div>
                                <div class="text-xs text-gray-500">{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="flex justify-center mt-6">
                    <button id="load-more-btn" class="px-6 py-2 rounded-full bg-black text-white font-semibold hover:bg-gray-800 transition">
                        Muat Berita Lain
                    </button>
                </div>
            </div>
        @endif

    </div> {{-- End of container --}}

</x-app-layout>

@push('scripts')
<script>
let page = 2;
const loadMoreBtn = document.getElementById('load-more-btn');
const grid = document.querySelector('#load-more-grid .grid-news');
console.log('Grid element:', grid);
if(loadMoreBtn) {
    loadMoreBtn.addEventListener('click', function() {
        console.log('Load more clicked', grid);
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerText = 'Memuat...';
        fetch('/load-more-posts?page=' + page)
            .then(res => res.json())
            .then(posts => {
                console.log('Fetched posts:', posts);
                if(posts.length === 0) {
                    loadMoreBtn.disabled = true;
                    loadMoreBtn.innerText = 'Tidak ada berita lagi';
                    return;
                }
                if (!grid) {
                  alert('Grid berita tidak ditemukan!');
                  return;
                }
                posts.forEach(post => {
                    let card = document.createElement('a');
                    card.href = '/posts/' + post.slug;
                    card.className = 'bg-white rounded-xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden group';
                    card.innerHTML = `
                        <img src="${post.image ? '/storage/' + post.image : 'https://via.placeholder.com/400x250?text=No+Image'}" alt="${post.title}" class="w-full h-40 object-cover" loading="lazy">
                        <div class=\"p-4 flex-1 flex flex-col\">
                            <span class=\"inline-block ${post.category && post.category.color ? post.category.color : 'bg-gray-500'} text-white text-xs font-semibold px-2 py-1 rounded mb-2\">${post.category ? post.category.name : ''}</span>
                            <div class=\"font-semibold text-base mb-1 group-hover:text-indigo-600 transition\">${post.title}</div>
                            <div class=\"text-xs text-gray-500\">${post.published_at ? new Date(post.published_at).toLocaleDateString('id-ID') : '-'}</div>
                        </div>
                    `;
                    grid.appendChild(card);
                });
                page++;
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerText = 'Muat Berita Lain';
            })
            .catch(() => {
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerText = 'Muat Berita Lain';
                alert('Gagal memuat berita. Silakan coba lagi.');
            });
    });
}
</script>
@endpush

<x-app-layout>
    {{-- Hapus @extends dan @section, bungkus konten dengan <x-app-layout> --}}
    {{-- Tidak perlu header slot untuk welcome page --}}

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- Judul Section -->
        <div class="text-center font-semibold text-black mb-3 sm:mb-4 text-base sm:text-xl md:text-2xl">Latest News <span class="align-middle">👀</span></div>

        <!-- Search Bar -->
        <div class="mb-3 sm:mb-4">
            <form action="{{ route('search') }}" method="GET">
                <input type="text" name="q" placeholder="Cari berita, topik, atau apa pun..." class="w-full px-3 py-2 sm:px-5 sm:py-3 rounded-full border border-gray-200 bg-[#F7F7F9] focus:outline-none focus:ring-2 focus:ring-black text-sm sm:text-base md:text-lg" required />
            </form>
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

        <!-- Hero Ad Space -->
        <div class="my-8">
            <x-ad-space />
        </div>


        @if($bottomPosts->isNotEmpty())
            <div id="load-more-grid" class="w-full max-w-7xl mx-auto">
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 grid-news">
                    @foreach($bottomPosts as $index => $post)
                        <!-- Regular News Card -->
                        <a href="{{ route('posts.show', $post) }}" class="bg-white rounded-xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden group">
                            <img src="{{ $post->image ? $post->image : 'https://via.placeholder.com/400x250?text=No+Image' }}" alt="{{ $post->title }}" class="w-full h-40 object-cover" loading="lazy">
                            <div class="p-4 flex-1 flex flex-col">
                                <span class="inline-block {{ $post->category->color ?? 'bg-gray-500' }} text-white text-xs font-semibold px-2 py-1 rounded mb-2">{{ $post->category->name }}</span>
                                <div class="font-semibold text-base mb-1 group-hover:text-indigo-600 transition">{{ $post->title }}</div>
                                <div class="text-xs text-gray-500">{{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}</div>
                            </div>
                        </a>

                        <!-- Insert Ad after every 3rd post -->
                        @if(($index + 1) % 3 === 0 && $index < count($bottomPosts) - 1)
                            @php
                                $adTypes = ['gradient', 'shopping', 'tech', 'food'];
                                $adType = $adTypes[($index / 3) % count($adTypes)];
                                
                                $adConfigs = [
                                    'gradient' => [
                                        'title' => 'Promo Terbatas!',
                                        'subtitle' => 'Dapatkan diskon hingga 70% untuk semua produk pilihan',
                                        'cta' => 'Belanja Sekarang'
                                    ],
                                    'shopping' => [
                                        'title' => 'Flash Sale Hari Ini!',
                                        'subtitle' => 'Gratis ongkir ke seluruh Indonesia + cashback 20%',
                                        'cta' => 'Ambil Promo'
                                    ],
                                    'tech' => [
                                        'title' => 'Gadget Terbaru 2025',
                                        'subtitle' => 'Smartphone, laptop, dan aksesoris dengan teknologi terdepan',
                                        'cta' => 'Lihat Produk'
                                    ],
                                    'food' => [
                                        'title' => 'Delivery 24 Jam',
                                        'subtitle' => 'Makanan favorit diantar ke rumah, gratis ongkir!',
                                        'cta' => 'Pesan Sekarang'
                                    ]
                                ];
                                
                                $config = $adConfigs[$adType];
                            @endphp
                            
                            <x-in-feed-ad 
                                :type="$adType"
                                :title="$config['title']"
                                :subtitle="$config['subtitle']"
                                :cta="$config['cta']"
                            />
                        @endif
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

    <!-- Load More JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Load More Script loaded');
        
        // Mulai dari page 2 karena page 1 (11 posts) sudah ditampilkan di awal
        let currentPage = 2;
        const loadMoreBtn = document.getElementById('load-more-btn');
        const grid = document.querySelector('#load-more-grid .grid-news');

        console.log('loadMoreBtn:', loadMoreBtn);
        console.log('grid:', grid);

        if (loadMoreBtn && grid) {
            console.log('Adding click listener to load more button');
            loadMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Button clicked! currentPage:', currentPage);
                
                loadMoreBtn.disabled = true;
                loadMoreBtn.innerHTML = `<svg class="inline w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Memuat...`;

                // Hitung page yang benar: page 1 di backend = request pertama (skip 11)
                const backendPage = currentPage - 1;
                
                console.log('Fetching page:', backendPage);
                
                fetch(`{{ route('load.more.posts') }}?page=${backendPage}`)
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received data:', data);
                        if (data && data.length > 0) {
                            data.forEach((post, index) => {
                                const card = document.createElement('a');
                                card.href = `/posts/${post.slug}`;
                                card.className = 'bg-white rounded-xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden group';
                                
                                const categoryColor = post.category ? post.category.color : 'bg-gray-500';
                                const categoryName = post.category ? post.category.name : 'Uncategorized';

                                card.innerHTML = `
                                    <img src="${post.image_url}" alt="${post.title}" class="w-full h-40 object-cover" loading="lazy" onerror="this.src='https://via.placeholder.com/400x250?text=No+Image'">
                                    <div class="p-4 flex-1 flex flex-col">
                                        <span class="inline-block ${categoryColor} text-white text-xs font-semibold px-2 py-1 rounded mb-2">${categoryName}</span>
                                        <div class="font-semibold text-base mb-1 group-hover:text-indigo-600 transition">${post.title}</div>
                                        <div class="text-xs text-gray-500">${post.published_at_formatted}</div>
                                    </div>
                                `;
                                grid.appendChild(card);
                                
                                // Add ad after every 4th post in load more content
                                if ((index + 1) % 4 === 0 && index < data.length - 1) {
                                    const adElement = createInFeedAd(Math.floor(index / 4));
                                    grid.appendChild(adElement);
                                }
                            });
                            currentPage++;
                            loadMoreBtn.disabled = false;
                            loadMoreBtn.innerHTML = 'Muat Berita Lain';
                        } else {
                            loadMoreBtn.innerHTML = 'Tidak Ada Berita Lagi';
                            loadMoreBtn.disabled = true;
                            loadMoreBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                            loadMoreBtn.classList.remove('bg-black', 'hover:bg-gray-800');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching more posts:', error);
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.innerHTML = 'Gagal Memuat. Coba Lagi';
                        loadMoreBtn.classList.add('bg-red-600', 'hover:bg-red-700');
                        loadMoreBtn.classList.remove('bg-black', 'hover:bg-gray-800');
                    });
            });
        } else {
            console.warn('Load more button or grid not found');
            console.warn('loadMoreBtn:', loadMoreBtn);
            console.warn('grid:', grid);
        }
    });

// Function to create in-feed ads dynamically
function createInFeedAd(adIndex) {
    const adTypes = ['gradient', 'shopping', 'tech', 'food'];
    const adType = adTypes[adIndex % adTypes.length];
    
    const adConfigs = {
        'gradient': {
            title: 'Penawaran Ekslusif!',
            subtitle: 'Jangan lewatkan kesempatan emas ini - diskon besar-besaran!',
            cta: 'Dapatkan Sekarang'
        },
        'shopping': {
            title: 'Super Sale Weekend!',
            subtitle: 'Belanja minimal 100K gratis ongkir + voucher 50K',
            cta: 'Shopping Yuk'
        },
        'tech': {
            title: 'Upgrade Gadgetmu!',
            subtitle: 'Teknologi terdepan dengan harga terjangkau, cicilan 0%',
            cta: 'Cek Produk'
        },
        'food': {
            title: 'Lapar? Order Aja!',
            subtitle: 'Ribuan restoran siap antar ke lokasi kamu',
            cta: 'Order Now'
        }
    };
    
    const config = adConfigs[adType];
    const adElement = document.createElement('div');
    adElement.className = 'in-feed-ad bg-white rounded-xl shadow-sm hover:shadow-md transition flex flex-col overflow-hidden group cursor-pointer';
    adElement.onclick = () => handleAdClick(adType);
    
    let adContent = '';
    
    if (adType === 'gradient') {
        adContent = `
            <div class="gradient-ad-bg w-full h-40 relative">
                <div class="absolute inset-0 animate-gradient-shift"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="floating-icons mb-2">
                            <i class="fas fa-gift text-3xl animate-bounce"></i>
                            <i class="fas fa-star text-xl animate-pulse ml-2"></i>
                            <i class="fas fa-heart text-lg animate-ping ml-1"></i>
                        </div>
                        <div class="ad-text-white text-lg font-bold animate-pulse">🎉 PROMO SPESIAL 🎉</div>
                    </div>
                </div>
            </div>`;
    } else if (adType === 'shopping') {
        adContent = `
            <div class="shopping-ad-bg w-full h-40 relative">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="shopping-cart-animation mb-2">
                            <i class="fas fa-shopping-cart text-4xl animate-bounce"></i>
                            <div class="inline-block ml-2">
                                <i class="fas fa-coins text-yellow-300 animate-spin"></i>
                                <i class="fas fa-percentage text-yellow-300 animate-pulse ml-1"></i>
                            </div>
                        </div>
                        <div class="ad-text-white text-lg font-bold">💰 DISKON 50% 💰</div>
                    </div>
                </div>
            </div>`;
    } else if (adType === 'tech') {
        adContent = `
            <div class="tech-ad-bg w-full h-40 relative">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="tech-icons mb-2">
                            <i class="fab fa-android text-3xl animate-pulse text-green-300"></i>
                            <i class="fab fa-apple text-3xl animate-bounce ml-2"></i>
                            <i class="fas fa-laptop text-2xl animate-pulse ml-2 text-blue-300"></i>
                        </div>
                        <div class="ad-text-white text-lg font-bold animate-pulse">📱 GADGET TERBARU 📱</div>
                    </div>
                </div>
            </div>`;
    } else {
        adContent = `
            <div class="food-ad-bg w-full h-40 relative">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div class="food-icons mb-2">
                            <i class="fas fa-pizza-slice text-3xl animate-bounce text-yellow-300"></i>
                            <i class="fas fa-hamburger text-2xl animate-pulse ml-2"></i>
                            <i class="fas fa-ice-cream text-2xl animate-bounce ml-2 text-pink-200"></i>
                        </div>
                        <div class="ad-text-white text-lg font-bold">🍔 DELIVERY GRATIS! 🍕</div>
                    </div>
                </div>
            </div>`;
    }
    
    adElement.innerHTML = `
        <div class="relative h-40 overflow-hidden">
            ${adContent}
            <div class="floating-particles absolute inset-0 pointer-events-none">
                <div class="particle particle-1"></div>
                <div class="particle particle-2"></div>
                <div class="particle particle-3"></div>
            </div>
        </div>
        <div class="p-4 flex-1 flex flex-col">
            <span class="sponsor-label inline-block text-xs font-bold px-2 py-1 rounded mb-2 animate-pulse">
                📢 IKLAN SPONSOR
            </span>
            <div class="ad-title font-semibold text-base mb-1 group-hover:text-purple-600 transition">${config.title}</div>
            <div class="ad-subtitle text-sm mb-2">${config.subtitle}</div>
            <div class="mt-auto">
                <button class="cta-button w-full text-white py-2 px-4 rounded-lg font-semibold transition-all transform">
                    ${config.cta} →
                </button>
            </div>
        </div>
    `;
    
    return adElement;
}
    </script>

</x-app-layout>

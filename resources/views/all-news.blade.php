<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Semua Berita</h1>
            <p class="text-gray-600 dark:text-gray-400">Jelajahi semua berita terbaru dengan filter yang tersedia</p>
        </div>

        <!-- Main Layout: Sidebar + Content -->
        <div class="all-news-container">
            
            <!-- Left Sidebar: Filters -->
            <div class="all-news-sidebar mobile-hidden" id="filter-sidebar">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 sidebar-sticky">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                            </svg>
                            Filter Berita
                        </h2>
                        <button id="close-filters" class="md:hidden p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <form method="GET" action="{{ route('all-news') }}" class="space-y-6">
                        
                        <!-- Search Input -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Cari Berita
                            </label>
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Masukkan kata kunci..." 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Kategori
                            </label>
                            <select id="category" 
                                    name="category" 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Year Filter -->
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Tahun
                            </label>
                            <select id="year" 
                                    name="year" 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Semua Tahun</option>
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="space-y-3">
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Terapkan Filter
                            </button>
                            
                            <a href="{{ route('all-news') }}" 
                               class="w-full block text-center px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors font-medium">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Reset Filter
                            </a>
                        </div>
                        
                        <!-- Active Filters Display -->
                        @if(request()->hasAny(['search', 'category', 'year']))
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Filter Aktif:</h3>
                                <div class="space-y-2">
                                    @if(request('search'))
                                        <div class="flex items-center justify-between bg-blue-50 dark:bg-blue-900/20 px-3 py-2 rounded-lg">
                                            <span class="text-sm text-blue-800 dark:text-blue-200">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                                </svg>
                                                "{{ request('search') }}"
                                            </span>
                                        </div>
                                    @endif
                                    @if(request('category'))
                                        @php $selectedCategory = $categories->find(request('category')); @endphp
                                        @if($selectedCategory)
                                            <div class="flex items-center justify-between bg-green-50 dark:bg-green-900/20 px-3 py-2 rounded-lg">
                                                <span class="text-sm text-green-800 dark:text-green-200">
                                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    {{ $selectedCategory->name }}
                                                </span>
                                            </div>
                                        @endif
                                    @endif
                                    @if(request('year'))
                                        <div class="flex items-center justify-between bg-purple-50 dark:bg-purple-900/20 px-3 py-2 rounded-lg">
                                            <span class="text-sm text-purple-800 dark:text-purple-200">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ request('year') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Right Content Area -->

            <div class="all-news-content">
                
                <!-- Mobile Filter Toggle Button -->
                <div class="filter-mobile-toggle hidden mb-6">
                    <button id="mobile-filter-toggle" 
                            class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                        </svg>
                        Filter Berita
                    </button>
                </div>
                
                <!-- Results Info -->
                <div class="mb-6">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-600 dark:text-gray-400">
                            Menampilkan {{ $posts->firstItem() ?? 0 }} - {{ $posts->lastItem() ?? 0 }} dari {{ $posts->total() }} berita
                            @if(request('search'))
                                untuk "<span class="font-semibold">{{ request('search') }}</span>"
                            @endif
                            @if(request('category'))
                                @php
                                    $selectedCategory = $categories->find(request('category'));
                                @endphp
                                @if($selectedCategory)
                                    dalam kategori "<span class="font-semibold">{{ $selectedCategory->name }}</span>"
                                @endif
                            @endif
                            @if(request('year'))
                                pada tahun "<span class="font-semibold">{{ request('year') }}</span>"
                            @endif
                        </p>
                    </div>
                </div>

                <!-- News Grid with In-Feed Ads -->
                @if($posts->count() > 0)
                    <div class="news-grid mb-8" id="news-grid">
                        @foreach($posts as $index => $post)
                            
                            <!-- News Article -->
                            <article class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                                
                                <!-- Image -->
                                <div class="aspect-video bg-gray-200 dark:bg-gray-700">
                                    @if($post->image)
                                        @php
                                            $imageUrl = 'https://via.placeholder.com/400x250?text=No+Image';
                                            if ($post->image) {
                                                if (Str::startsWith($post->image, ['http://', 'https://'])) {
                                                    $imageUrl = $post->image;
                                                } else {
                                                    $imageUrl = Storage::url($post->image);
                                                }
                                            }
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                             alt="{{ $post->title }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="p-6">
                                    
                                    <!-- Category & Date -->
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
                                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full text-xs font-medium">
                                            {{ $post->category->name }}
                                        </span>
                                        <span class="mx-2">•</span>
                                        <time>{{ $post->published_at ? $post->published_at->format('d M Y') : 'Belum Dipublish' }}</time>
                                    </div>

                                    <!-- Title -->
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 line-clamp-2">
                                        <a href="{{ route('posts.show', $post) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h2>

                                    <!-- Excerpt -->
                                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4">
                                        {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}
                                    </p>

                                    <!-- Author & Views -->
                                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $post->user->name }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $post->views ?? 0 }} views
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <!-- In-Feed Ad every 4 posts -->
                            @if(($index + 1) % 4 === 0 && $index < ($posts->count() - 1))
                                <x-in-feed-ad />
                            @endif

                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>

                @else
                    <!-- No Results -->
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Tidak ada berita ditemukan</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                            @if(request()->hasAny(['search', 'category', 'year']))
                                Coba ubah filter atau kriteria pencarian Anda.
                            @else
                                Belum ada berita yang dipublikasikan.
                            @endif
                        </p>
                        <a href="{{ route('all-news') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filter
                        </a>
                    </div>
                @endif
                
            </div>
        </div>
        
        <!-- Mobile Filter Modal -->
        <div class="filter-mobile-modal" id="mobile-filter-modal">
            <div class="filter-mobile-panel bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                        </svg>
                        Filter Berita
                    </h2>
                    <button id="close-mobile-filters" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Copy form dari sidebar untuk mobile -->
                <form method="GET" action="{{ route('all-news') }}" class="space-y-6">
                    
                    <!-- Search Input -->
                    <div>
                        <label for="mobile-search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Cari Berita
                        </label>
                        <input type="text" 
                               id="mobile-search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Masukkan kata kunci..." 
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="mobile-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Kategori
                        </label>
                        <select id="mobile-category" 
                                name="category" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Year Filter -->
                    <div>
                        <label for="mobile-year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Tahun
                        </label>
                        <select id="mobile-year" 
                                name="year" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Semua Tahun</option>
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Terapkan Filter
                        </button>
                        
                        <a href="{{ route('all-news') }}" 
                           class="w-full block text-center px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors font-medium">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Filter
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Mobile Filter Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('mobile-filter-toggle');
            const modal = document.getElementById('mobile-filter-modal');
            const closeBtn = document.getElementById('close-mobile-filters');

            if (toggleBtn && modal) {
                toggleBtn.addEventListener('click', function() {
                    modal.classList.add('show');
                    document.body.style.overflow = 'hidden';
                });

                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        modal.classList.remove('show');
                        document.body.style.overflow = '';
                    });
                }

                // Close on backdrop click
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                });
            }
        });
    </script>
</x-app-layout> 
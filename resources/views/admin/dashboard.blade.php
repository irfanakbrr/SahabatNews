@extends('layouts.admin-new')

@section('title', 'Dashboard')

@section('header-content')
<div class="flex items-center space-x-4">
    <h1 class="text-2xl font-semibold text-gray-800 hidden md:block">Dashboard</h1>
    <!-- Search -->
    <div class="relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <i class='bx bx-search text-gray-400'></i>
        </span>
        <form action="{{ route('search') }}" method="GET">
             <input class="w-full py-2 pl-10 pr-4 text-gray-700 bg-gray-100 border border-transparent rounded-full focus:outline-none focus:bg-white focus:border-green-500" type="text" name="q" placeholder="Cari artikel..." autocomplete="off">
        </form>
    </div>
</div>
<div class="flex items-center space-x-3">
    <a href="{{ route('dashboard.posts.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 flex items-center">
        <i class='bx bx-plus mr-1'></i>
        Tulis Artikel
    </a>
    {{-- Ikon Notifikasi bisa ditambahkan di sini jika diperlukan --}}
</div>
@endsection

@section('content')
<div id="dashboard-widgets">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <div class="p-6 bg-white/60 backdrop-blur-sm rounded-xl stat-card">
            <p class="text-sm font-medium text-gray-600 uppercase">Total Views</p>
            <p class="text-3xl font-bold text-gray-900" data-target="{{ $totalViewsAllPosts ?? 0 }}">0</p>
        </div>
        <div class="p-6 bg-white/60 backdrop-blur-sm rounded-xl stat-card">
            <p class="text-sm font-medium text-gray-600 uppercase">Pembaca Hari Ini</p>
            <p class="text-3xl font-bold text-gray-900" data-target="{{ $todayVisitors ?? 0 }}">0</p>
        </div>
        <div class="p-6 bg-white/60 backdrop-blur-sm rounded-xl stat-card">
            <p class="text-sm font-medium text-gray-600 uppercase">Artikel Terbit Hari Ini</p>
            <p class="text-3xl font-bold text-gray-900" data-target="{{ $articlesToday ?? 0 }}">0</p>
        </div>
        <div class="p-6 bg-white/60 backdrop-blur-sm rounded-xl stat-card">
            <p class="text-sm font-medium text-gray-600 uppercase">Komentar Pending</p>
            <p class="text-3xl font-bold text-gray-900" data-target="{{ $pendingComments ?? 0 }}">0</p>
        </div>
        <div class="p-6 bg-white/60 backdrop-blur-sm rounded-xl">
            <p class="text-sm font-medium text-gray-500 uppercase">Jumlah Draf</p>
            <p class="text-3xl font-bold text-gray-800">{{ number_format($draftCount ?? 0) }}</p>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-3">
        <!-- Popular Posts -->
        <div class="p-6 bg-white/60 backdrop-blur-sm rounded-xl lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800">Artikel Paling Populer (7 Hari Terakhir)</h3>
            <ul class="mt-4 space-y-3" id="popular-posts-list">
                @forelse($popularPostsLast7Days as $post)
                <li class="flex items-center justify-between">
                    <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="hover:text-purple-600 text-gray-700">{{ Str::limit($post->title, 60) }}</a>
                    <span class="text-sm font-semibold text-gray-600">{{ number_format($post->view_count) }} views</span>
                </li>
                @empty
                <p class="text-sm text-gray-500">Belum ada data populer untuk ditampilkan.</p>
                @endforelse
            </ul>
        </div>

        <!-- Team Activity (Placeholder) -->
        <div class="p-6 bg-white/60 backdrop-blur-sm rounded-xl">
            <h3 class="text-lg font-semibold text-gray-800">Aktivitas Tim Terkini</h3>
            <div class="mt-4 space-y-4 text-sm text-gray-400">
                <p class="text-center py-8">Widget aktivitas tim akan tersedia di pembaruan selanjutnya.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    // Import both libraries
    import anime from 'https://cdn.jsdelivr.net/npm/animejs/+esm';
    import Chart from 'https://cdn.jsdelivr.net/npm/chart.js/+esm';

    document.addEventListener('DOMContentLoaded', function() {
        // Animate stat cards
        document.querySelectorAll('.stat-card p[data-target]').forEach(card => {
            let target = { value: 0 };
            anime({
                targets: target,
                value: card.dataset.target,
                round: 1,
                easing: 'easeOutExpo',
                duration: 1500,
                update: () => card.textContent = new Intl.NumberFormat().format(target.value)
            });
        });

        // Animate widgets fade-in
        anime({
            targets: '.stat-card, .p-6.bg-white\\/60',
            opacity: [0, 1],
            translateY: [30, 0],
            delay: anime.stagger(100, {easing: 'easeOutQuad'}),
            duration: 800
        });

        // Animate popular posts list
        anime({
            targets: '#popular-posts-list li',
            opacity: [0, 1],
            translateX: [-30, 0],
            delay: anime.stagger(80, {start: 400}),
            easing: 'easeOutExpo'
        });

        // Chart.js implementation
        const monthlyCtx = document.getElementById('monthlyPostsChart');
        if (monthlyCtx && @json($trendLabels) && @json($trendData)) {
            new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: @json($trendLabels),
                    datasets: [{
                        label: 'Artikel Dipublikasi',
                        data: @json($trendData),
                        backgroundColor: 'rgba(84, 34, 158, 0.7)',
                        borderColor: 'rgba(84, 34, 158, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endpush 
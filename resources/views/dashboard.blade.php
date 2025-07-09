@extends('layouts.admin')

@section('header')
    <h4 class="fw-bold mb-0">Dashboard</h4>
@endsection

@section('content')
<div class="container-fluid px-0">
    @if(Auth::user()->hasRole('user'))
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm"><i class="bx bx-user"></i> Profil</a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="bx bx-log-out"></i> Logout</button>
                </form>
                <a href="{{ route('dashboard.userposts.index') }}" class="btn btn-outline-info btn-sm"><i class="bx bx-list-ul"></i> Artikel Saya</a>
                <a href="{{ route('dashboard.userposts.create') }}" class="btn btn-success btn-sm"><i class="bx bx-upload"></i> Tulis Artikel</a>
            </div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Berita Diajukan</h6>
                        <h2 class="fw-bold mb-0">{{ Auth::user()->posts()->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Berita Disetujui</h6>
                        <h2 class="fw-bold mb-0">{{ Auth::user()->posts()->where('status','published')->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Berita Menunggu</h6>
                        <h2 class="fw-bold mb-0">{{ Auth::user()->posts()->where('status','draft')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
        
        @if(Auth::user()->posts()->where('status','rejected')->count() > 0)
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 border-start border-danger border-3">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Berita Ditolak</h6>
                        <h2 class="fw-bold mb-0 text-danger">{{ Auth::user()->posts()->where('status','rejected')->count() }}</h2>
                        <small class="text-muted">Perlu diperbaiki dan diajukan ulang</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Artikel Terbaru User -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Artikel Terbaru Saya</h6>
                        <a href="{{ route('dashboard.userposts.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @php $recentPosts = Auth::user()->posts()->with('category')->latest()->take(5)->get(); @endphp
                        @if($recentPosts->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentPosts as $post)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ Str::limit($post->title, 50) }}</h6>
                                            <small class="text-muted">{{ $post->created_at->format('d M Y') }} • {{ $post->category->name }}</small>
                                        </div>
                                        <div>
                                            @if($post->status == 'published')
                                                <span class="badge bg-success">Dipublikasi</span>
                                            @elseif($post->status == 'draft')
                                                <span class="badge bg-warning">Menunggu</span>
                                            @elseif($post->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-file-blank fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Belum ada artikel</p>
                                <a href="{{ route('dashboard.userposts.create') }}" class="btn btn-primary btn-sm">Tulis Artikel Pertama</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom-0"><h5 class="mb-0">Daftar Berita yang Diajukan</h5></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Diajukan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(Auth::user()->posts()->latest()->get() as $post)
                                        <tr>
                                            <td>{{ $post->title }}</td>
                                            <td>{{ $post->category->name ?? '-' }}</td>
                                            <td>
                                                @if($post->status == 'published')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @endif
                                            </td>
                                            <td>{{ $post->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted">Belum ada berita diajukan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Artikel</h6>
                        <h2 class="fw-bold mb-0">{{ $postCount }}</h2>
                    </div>
                            </div>
                        </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Kategori</h6>
                        <h2 class="fw-bold mb-0">{{ $categoryCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total User</h6>
                        <h2 class="fw-bold mb-0">{{ $userCount }}</h2>
                    </div>
                            </div>
                        </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Artikel Draft</h6>
                        <h2 class="fw-bold mb-0">{{ $draftPostsCount ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom-0"><h5 class="mb-0">Artikel Terpublish per Kategori</h5></div>
                    <div class="card-body">
                @if(!empty($chartLabels) && $chartLabels->count() > 0 && !empty($chartData) && $chartData->count() > 0 && !empty($chartColors) && $chartColors->count() > 0)
                    <canvas id="publishedPostsChart"></canvas>
                @else
                    <p class="text-muted text-center py-5">Belum ada data artikel terpublish untuk ditampilkan dalam diagram.</p>
                @endif
            </div>
        </div>
    </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom-0"><h5 class="mb-0">Statistik Lainnya</h5></div>
            <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <div class="text-muted small">Total Views</div>
                                    <div class="fw-bold fs-4">{{ number_format($totalViews ?? 0) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light rounded-3 p-3 text-center">
                                    <div class="text-muted small">Draft</div>
                                    <div class="fw-bold fs-4">{{ $draftPostsCount ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom-0"><h5 class="mb-0">Artikel Masuk per Bulan</h5></div>
                    <div class="card-body">
                        <canvas id="postsPerMonthChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-bottom-0"><h5 class="mb-0">Artikel per Kategori (Bar)</h5></div>
            <div class="card-body">
                        <canvas id="postsPerCategoryBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('page-scripts')
@if(!empty($chartLabels) && $chartLabels->count() > 0 && !empty($chartData) && $chartData->count() > 0 && !empty($chartColors) && $chartColors->count() > 0)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('publishedPostsChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($chartLabelsArray),
                    datasets: [{
                        label: 'Artikel Terpublish',
                        data: @json($chartDataArray),
                        backgroundColor: @json($chartColorsArray),
                        borderColor: '#fff',
                        borderWidth: 2,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 10
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed + ' artikel';
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
        // Grafik dummy: Artikel Masuk per Bulan
        const postsPerMonthCtx = document.getElementById('postsPerMonthChart');
        if(postsPerMonthCtx) {
            new Chart(postsPerMonthCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Artikel Masuk',
                        data: [12, 19, 8, 15, 22, 30, 25, 18, 20, 24, 17, 21],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#3B82F6',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
        // Grafik dummy: Artikel per Kategori (Bar)
        const postsPerCategoryBarCtx = document.getElementById('postsPerCategoryBarChart');
        if(postsPerCategoryBarCtx) {
            new Chart(postsPerCategoryBarCtx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabelsArray),
                    datasets: [{
                        label: 'Artikel',
                        data: @json($chartDataArray),
                        backgroundColor: @json($chartColorsArray),
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    });
</script>
@endif
@endpush

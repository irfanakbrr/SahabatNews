@extends('layouts.admin')

@section('header')
    Analytics SahabatNews
@endsection

@section('content')
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading">Ringkasan Data</h4>
        <p>{{ __('Halaman ini menampilkan ringkasan data dan tren. Fitur analitik yang lebih canggih akan dikembangkan lebih lanjut.') }}</p>
    </div>

    {{-- Card Statistik --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Total Artikel Publish</h6>
                        <span class="badge bg-label-primary p-2"><i class="bx bx-file fs-4"></i></span>
                    </div>
                    <h3 class="fw-semibold d-block my-2">{{ $totalPublishedPosts ?? 0 }}</h3>
                    <small class="text-muted">Artikel yang sudah dipublikasi</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Total Views</h6>
                        <span class="badge bg-label-warning p-2"><i class="bx bx-show-alt fs-4"></i></span>
                    </div>
                    <h3 class="fw-semibold d-block my-2">{{ number_format($totalViewsAllPosts ?? 0) }}</h3>
                    <small class="text-muted">Dari semua artikel</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Total Komentar</h6>
                        <span class="badge bg-label-info p-2"><i class="bx bx-comment-detail fs-4"></i></span>
                    </div>
                    <h3 class="fw-semibold d-block my-2">{{ number_format($totalComments ?? 0) }}</h3>
                    <small class="text-muted">Komentar yang disetujui</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Pengunjung Hari Ini</h6>
                        <span class="badge bg-label-success p-2"><i class="bx bx-user-plus fs-4"></i></span>
                    </div>
                    <h3 class="fw-semibold d-block my-2">{{ number_format($todayVisitors ?? 0) }}</h3>
                    <small class="text-muted">Pengunjung unik hari ini</small>
                </div>
            </div>
        </div>
        </div>

    <!-- Tren Publikasi Artikel per Bulan -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tren Publikasi Artikel (6 Bulan Terakhir)</h5>
                </div>
                <div class="card-body">
                    @if(!empty($trendLabels) && !empty($trendData))
                        <div style="height: 300px;">
                            <canvas id="monthlyPostsChart"></canvas>
                        </div>
                    @else
                        <p class="text-muted text-center py-5">Data tren publikasi belum cukup.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Penulis Teratas -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Penulis Teratas</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @forelse($prolificAuthors as $author)
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <img src="{{ $author->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($author->name).'&background=random' }}" alt="{{ $author->name }}" class="rounded-circle">
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">{{ $author->name }}</h6>
                                    <small class="text-muted">{{ $author->email }}</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $author->posts_count }} Artikel</small>
                                </div>
                            </div>
                        </li>
                        @empty
                        <p class="text-muted text-center">Data penulis belum tersedia.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kategori Populer -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Kategori Populer</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @forelse($popularCategories as $category)
                        <li class="d-flex mb-4 pb-1">
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">{{ $category->name }}</h6>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $category->posts_count }} Artikel</small>
                                </div>
                            </div>
                        </li>
                        @empty
                        <p class="text-muted text-center">Data kategori belum tersedia.</p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Artikel Paling Banyak Dilihat -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Top 10 Artikel Populer</h5>
        </div>
        <div class="card-body p-0">
                     @if ($popularPosts->isNotEmpty())
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                                    <tr>
                                <th class="ps-3">Rank</th>
                                <th>Judul Artikel</th>
                                <th class="text-end pe-3">Jumlah Views</th>
                                    </tr>
                                </thead>
                        <tbody class="table-border-bottom-0">
                                    @foreach ($popularPosts as $index => $post)
                                        <tr>
                                    <td class="ps-3"><strong>{{ $index + 1 }}</strong></td>
                                    <td>
                                        <a href="{{ route('posts.show', $post->slug) }}" target="_blank" title="{{ $post->title }}" class="text-dark hover-primary">
                                            {{ Str::limit($post->title, 70) }}
                                                </a>
                                            </td>
                                    <td class="text-end pe-3">{{ number_format($post->view_count ?? 0) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                     @else
                <p class="text-muted p-3 text-center">Belum ada data view artikel populer.</p>
                     @endif
        </div>
    </div>
@endsection 

@push('page-scripts')
@if(!empty($trendLabels) && !empty($trendData))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthlyCtx = document.getElementById('monthlyPostsChart');
        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: @json($trendLabels),
                    datasets: [{
                        label: 'Artikel Dipublikasi',
                        data: @json($trendData),
                        borderColor: '#696cff',
                        backgroundColor: 'rgba(105, 108, 255, 0.1)',
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#696cff',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#696cff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Agar sumbu Y menampilkan angka bulat jika jumlahnya kecil
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Sembunyikan legend jika hanya 1 dataset
                        }
                    }
                }
            });
        }
    });
</script>
@endif
@endpush 
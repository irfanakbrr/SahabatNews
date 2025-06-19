@extends('layouts.admin')

@section('header')
    Dashboard
@endsection

@section('content')
<div class="row mb-3">
    <!-- Congratulations Card -->
    <div class="col-lg-8">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Selamat Datang {{ Auth::user()->name }}! 🎉</h5>
                        <p class="mb-4">
                            Anda telah berhasil login di Panel Admin SahabatNews. Kelola konten dan pengguna dengan mudah.
                        </p>
                        <a href="{{ route('dashboard.posts.create') }}" class="btn btn-sm btn-outline-primary">Buat Artikel Baru</a>
                    </div>
                </div>
                <div class="col-sm-5 d-flex align-items-center justify-content-center" style="min-height: 180px;">
                    <div class="card-body px-0 px-md-4 d-flex justify-content-center align-items-center h-100 m-0">
                        <img 
                            src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff' }}" 
                            height="100" 
                            alt="Avatar User" 
                            class="rounded-circle shadow"
                            style="object-fit:cover; width:100px; height:100px;"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Statistics Cards -->
    <div class="col-lg-4">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <span class="badge bg-label-primary p-2"><i class="bx bx-file fs-4"></i></span>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Artikel</span>
                        <h3 class="card-title mb-2">{{ $postCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <span class="badge bg-label-info p-2"><i class="bx bx-collection fs-4"></i></span>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Kategori</span>
                        <h3 class="card-title mb-2">{{ $categoryCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                 <span class="badge bg-label-success p-2"><i class="bx bx-user fs-4"></i></span>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total User</span>
                        <h3 class="card-title mb-2">{{ $userCount }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Artikel Terpublish per Kategori -->
    <div class="col-md-12 col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Artikel Terpublish per Kategori</h5>
            </div>
            <div class="card-body" style="position: relative; height: 350px;">
                @if(!empty($chartLabels) && $chartLabels->count() > 0 && !empty($chartData) && $chartData->count() > 0 && !empty($chartColors) && $chartColors->count() > 0)
                    <canvas id="publishedPostsChart"></canvas>
                @else
                    <p class="text-muted text-center py-5">Belum ada data artikel terpublish untuk ditampilkan dalam diagram.</p>
                @endif
            </div>
        </div>
    </div>
    <!--/ Artikel Terpublish per Kategori -->

    <!-- Statistik Tambahan (Total Views & Draft) -->
    <div class="col-md-12 col-lg-6 mb-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <span class="badge bg-label-warning p-2"><i class="bx bx-show-alt fs-4"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total Views (Semua Artikel)</span>
                <h3 class="card-title text-nowrap mb-2">{{ number_format($totalViews ?? 0) }}</h3>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <span class="badge bg-label-secondary p-2"><i class="bx bx-edit fs-4"></i></span>
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Artikel Draft</span>
                <h3 class="card-title text-nowrap mb-1">{{ $draftPostsCount ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <!--/ Statistik Tambahan -->
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
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Artikel Terpublish',
                        data: @json($chartData),
                        backgroundColor: @json($chartColors),
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
    });
</script>
@endif
@endpush

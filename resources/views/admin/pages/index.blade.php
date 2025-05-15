@extends('layouts.admin')

@section('header')
    Manajemen Halaman Statis
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Halaman</h5>
        {{-- Tidak ada tombol Tambah Halaman karena halaman statis biasanya sudah ditentukan dan dibuat via seeder --}}
        {{-- Jika ingin admin bisa membuat slug baru, tombol tambah bisa dipertimbangkan di sini --}}
    </div>
    <div class="card-body px-0 pt-0 pb-2"> {{-- Sesuaikan padding jika perlu --}}
        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-striped">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Judul Halaman</th>
                        <th>Slug</th>
                        <th>Terakhir Diperbarui</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($pages as $page)
                        <tr>
                            <td class="ps-4"><strong>{{ $page->title }}</strong></td>
                            <td><code>{{ $page->slug }}</code></td>
                            <td>{{ $page->updated_at->format('d M Y, H:i') }}</td>
                            <td class="text-center pe-4">
                                <a href="{{ route('dashboard.pages.edit', $page) }}" class="btn btn-sm btn-info" title="Edit Konten">
                                    <i class="bx bx-edit-alt me-1"></i> Edit Konten
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                Tidak ada halaman statis yang terdaftar atau belum di-seed.
                                <br>
                                <small>Pastikan Anda sudah menjalankan <code>php artisan db:seed --class=PageSeeder</code></small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- Jika ada paginasi untuk $pages, bisa ditambahkan di card-footer --}}
    {{-- @if ($pages->hasPages())
        <div class="card-footer">
            {{ $pages->links('pagination::bootstrap-5') }}
        </div>
    @endif --}}
</div>
@endsection 
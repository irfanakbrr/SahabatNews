@extends('layouts.admin')

@section('header')
    Manajemen Kategori
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Kategori</h5>
        <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-plus me-1"></i> Tambah Kategori
            </a>
        </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                                <tr>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Warna</th>
                        <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                <tbody class="table-border-bottom-0">
                                @forelse ($categories as $category)
                                    <tr>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                            @if($category->color)
                                    @if(Str::startsWith($category->color, '#') || Str::startsWith($category->color, 'rgb'))
                                        <span class="badge" style="background-color: {{ $category->color }}; color: {{ \App\Helpers\ColorHelper::isLight($category->color) ? '#000' : '#FFF' }};">{{ $category->color }}</span>
                                    @else
                                        {{-- Asumsi $category->color adalah nama kelas (e.g., bg-danger, text-success) --}}
                                        <span class="badge {{ $category->color }}">{{ $category->color }}</span>
                                    @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                            <td class="text-center">
                                <div class="d-inline-flex">
                                    <a href="{{ route('dashboard.categories.edit', $category) }}" class="btn btn-sm btn-info me-2" title="Edit">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <form action="{{ route('dashboard.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Menghapus kategori akan berpengaruh pada artikel terkait.');">
                                                @csrf
                                                @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                            </form>
                                </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                            <td colspan="4" class="text-center py-4">
                                            Belum ada kategori.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
    @if ($categories->hasPages())
        <div class="card-footer">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    @endif
    </div>
@endsection 
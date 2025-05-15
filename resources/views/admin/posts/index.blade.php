@extends('layouts.admin')

@section('header')
    Manajemen Artikel
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Artikel</h5>
        <a href="{{ route('dashboard.posts.create') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-plus me-1"></i> Tambah Artikel
        </a>
    </div>
    <div class="card-body">
        {{-- Notifikasi sudah dihandle di layouts.admin --}}

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Tanggal Publish</th>
                        <th>Views</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($posts as $post)
                        <tr>
                            <td>
                                <a href="{{ route('posts.show', $post->slug) }}" target="_blank" title="{{ $post->title }}">
                                    <strong>{{ Str::limit($post->title, 40) }}</strong>
                                </a>
                            </td>
                            <td>{{ $post->category->name ?? '-' }}</td>
                            <td>{{ $post->user->name ?? '-' }}</td>
                            <td>
                                @if($post->status == 'published')
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-warning">Draft</span>
                                @endif
                            </td>
                            <td>{{ $post->published_at ? $post->published_at->format('d M Y, H:i') : '-' }}</td>
                            <td>{{ number_format($post->view_count ?? 0) }}</td>
                            <td class="text-center">
                                <div class="d-inline-flex">
                                    <a href="{{ route('dashboard.posts.edit', $post) }}" class="btn btn-sm btn-info me-2" title="Edit">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    <form action="{{ route('dashboard.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
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
                            <td colspan="7" class="text-center py-4">
                                Belum ada artikel.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($posts->hasPages())
        <div class="card-footer">
            {{ $posts->links('pagination::bootstrap-5') }} {{-- Gunakan view paginasi bootstrap-5 --}}
        </div>
    @endif
</div>
@endsection 
@extends('layouts.admin')

@section('header')
    <h5 class="mb-0">Artikel Saya</h5>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-end">
            <a href="{{ route('dashboard.userposts.create') }}" class="btn btn-success btn-sm">
                <i class="bx bx-plus"></i> Tulis Artikel Baru
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Daftar Artikel</h5>
                </div>
                <div class="card-body">
                    @if($posts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($post->image)
                                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ Str::limit($post->title, 60) }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $post->category->color ?? '#6c757d' }};">
                                                {{ $post->category->name }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($post->status == 'published')
                                                <span class="badge bg-success">Dipublikasi</span>
                                            @elseif($post->status == 'draft')
                                                <span class="badge bg-warning">Menunggu Review</span>
                                            @elseif($post->status == 'rejected')
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($post->status == 'published')
                                                    <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="bx bx-show"></i> Lihat
                                                    </a>
                                                @endif
                                                
                                                @if(in_array($post->status, ['draft', 'rejected']))
                                                    <a href="{{ route('dashboard.userposts.edit', $post) }}" class="btn btn-sm btn-outline-warning">
                                                        <i class="bx bx-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('dashboard.userposts.destroy', $post) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus artikel ini?')">
                                                            <i class="bx bx-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-file-blank fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">Belum ada artikel</h5>
                            <p class="text-muted">Mulai tulis artikel pertama Anda!</p>
                            <a href="{{ route('dashboard.userposts.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus"></i> Tulis Artikel Baru
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
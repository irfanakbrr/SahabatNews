@extends('layouts.admin')

@section('header')
    <h5 class="mb-0">Berita Menunggu Persetujuan</h5>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Daftar Berita User (Draft/Pending)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Pengirim</th>
                        <th>Diajukan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingPosts as $post)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                                                         @if($post->image)
                                         <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                     @endif
                                    <div>
                                        <h6 class="mb-0">{{ $post->title }}</h6>
                                        <p class="text-muted small mb-0">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background-color: {{ $post->category->color ?? '#6c757d' }};">
                                    {{ $post->category->name ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $post->user->name ?? '-' }}</td>
                            <td>{{ $post->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#previewModal{{ $post->id }}">
                                        <i class="bx bx-show"></i> Preview
                                    </button>
                                    <form action="{{ route('dashboard.posts.approve', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve dan publish artikel ini?')">
                                            <i class="bx bx-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.posts.reject', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tolak artikel ini? User dapat mengedit dan mengajukan ulang.')">
                                            <i class="bx bx-x"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Preview -->
                        <div class="modal fade" id="previewModal{{ $post->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Preview: {{ $post->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                                                                 @if($post->image)
                                             <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="img-fluid mb-3 rounded">
                                         @endif
                                        <h4>{{ $post->title }}</h4>
                                        <p class="text-muted">
                                            <small>
                                                Kategori: {{ $post->category->name ?? '-' }} | 
                                                Penulis: {{ $post->user->name ?? '-' }} | 
                                                Diajukan: {{ $post->created_at->format('d M Y H:i') }}
                                            </small>
                                        </p>
                                        <div>
                                            {!! $post->content !!}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('dashboard.posts.approve', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Approve dan publish artikel ini?')">
                                                <i class="bx bx-check"></i> Approve & Publish
                                            </button>
                                        </form>
                                        <form action="{{ route('dashboard.posts.reject', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak artikel ini?')">
                                                <i class="bx bx-x"></i> Tolak
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bx bx-inbox fs-1"></i>
                                <p class="mt-2">Tidak ada artikel yang menunggu persetujuan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 
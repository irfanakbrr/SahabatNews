@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Moderasi Komentar') }}
    </h2>
@endsection

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-700 border border-green-300 dark:border-green-600 text-green-700 dark:text-green-100 px-4 py-3 rounded-md relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 bg-red-50 dark:bg-red-700 border border-red-300 dark:border-red-600 text-red-700 dark:text-red-100 px-4 py-3 rounded-md relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Daftar Komentar Menunggu Moderasi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Komentar</th>
                                    <th>Author</th>
                                    <th>Artikel</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse ($comments as $comment)
                                    <tr class="{{ !$comment->approved ? 'table-warning' : '' }}">
                                        <td style="white-space: normal; min-width: 250px;">
                                            {{ $comment->content }}
                                        </td>
                                        <td>{{ $comment->user->name ?? 'User Dihapus' }}</td>
                                        <td>
                                            @if($comment->post)
                                                <a href="{{ route('posts.show', $comment->post->slug) }}" target="_blank" title="{{ $comment->post->title }}">
                                                    {{ Str::limit($comment->post->title, 30) }}
                                                </a>
                                            @else
                                                Artikel Dihapus
                                            @endif
                                        </td>
                                        <td>{{ $comment->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            @if ($comment->approved)
                                                <span class="badge bg-success">Disetujui</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-inline-flex">
                                                @unless($comment->approved)
                                                    <form action="{{ route('dashboard.comments.approve', $comment) }}" method="POST" class="d-inline me-1">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                                                            <i class="bx bx-check"></i>
                                                        </button>
                                                    </form>
                                                @endunless
                                                <form action="{{ route('dashboard.comments.destroy', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');">
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
                                        <td colspan="6" class="text-center py-4">
                                            Belum ada komentar untuk dimoderasi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($comments->hasPages())
                    <div class="card-footer">
                        {{ $comments->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 
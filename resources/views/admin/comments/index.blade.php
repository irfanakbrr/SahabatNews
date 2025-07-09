@extends('layouts.admin-new')

@section('title', 'Manajemen Komentar')

@section('header-content')
    <h1 class="text-2xl font-semibold text-gray-800">Manajemen Komentar</h1>
@endsection

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Komentar</th>
                    <th scope="col" class="px-6 py-3">Penulis</th>
                    <th scope="col" class="px-6 py-3">Pada Artikel</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($comments as $comment)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ Str::limit($comment->content, 50) }}</td>
                    <td class="px-6 py-4">{{ $comment->user->name }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('posts.show', $comment->post->slug) }}" target="_blank" class="hover:underline">
                            {{ Str::limit($comment->post->title, 30) }}
                        </a>
                    </td>
                    <td class="px-6 py-4">
                        @if($comment->approved)
                            <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Disetujui</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            @unless($comment->approved)
                            <form action="{{ route('dashboard.comments.approve', $comment->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-2 text-green-500 bg-green-100 rounded-full hover:bg-green-200" title="Setujui">
                                    <i class='bx bx-check-circle'></i>
                                </button>
                            </form>
                            @endunless
                            <form action="{{ route('dashboard.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 bg-red-100 rounded-full hover:bg-red-200" title="Hapus">
                                    <i class='bx bxs-trash'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Belum ada komentar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($comments->hasPages())
        <div class="mt-4">
            {{ $comments->links() }}
        </div>
    @endif
</div>
@endsection 
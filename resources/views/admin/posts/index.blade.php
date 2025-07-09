@extends('layouts.admin-new')

@section('title', 'Manajemen Artikel')

@section('header-content')
    <h1 class="text-2xl font-semibold text-gray-800">Manajemen Artikel</h1>
@endsection

@section('content')
<div>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">Semua Artikel</h3>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Judul</th>
                    <th scope="col" class="px-6 py-3">Kategori</th>
                    <th scope="col" class="px-6 py-3">Penulis</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="hover:underline">
                            {{ Str::limit($post->title, 40) }}
                        </a>
                    </th>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $post->category->color ?? 'bg-gray-200 text-gray-800' }}">
                            {{ $post->category->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $post->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        @if($post->status == 'published')
                            <span class="text-green-600 font-semibold">Published</span>
                        @else
                            <span class="text-yellow-600 font-semibold">Draft</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <div class="relative inline-block text-left">
                                <button type="button" class="p-2 text-blue-500 bg-blue-100 rounded-full hover:bg-blue-200" onclick="toggleEditDropdown({{ $post->id }})" title="Edit">
                                    <i class='bx bxs-edit'></i>
                                </button>
                                <div id="editDropdown{{ $post->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                    <a href="{{ route('dashboard.posts.edit', $post->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class='bx bx-edit-alt mr-2'></i>Edit dengan Editor
                                    </a>
                                    <a href="{{ route('dashboard.posts.edit.simple', $post->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class='bx bx-edit mr-2'></i>Edit Sederhana
                                    </a>
                                </div>
                            </div>
                            <form action="{{ route('dashboard.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
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
                        Belum ada artikel.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($posts->hasPages())
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>

<script>
function toggleEditDropdown(postId) {
    const dropdown = document.getElementById('editDropdown' + postId);
    
    // Close all other dropdowns
    document.querySelectorAll('[id^="editDropdown"]').forEach(el => {
        if (el.id !== 'editDropdown' + postId) {
            el.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick^="toggleEditDropdown"]')) {
        document.querySelectorAll('[id^="editDropdown"]').forEach(el => {
            el.classList.add('hidden');
        });
    }
});
</script>
@endsection 
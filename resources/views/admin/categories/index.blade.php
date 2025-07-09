@extends('layouts.admin-new')

@section('title', 'Manajemen Kategori')

@section('header-content')
    <div class="flex items-center justify-between w-full">
        <h1 class="text-2xl font-semibold text-gray-800">Manajemen Kategori</h1>
        <a href="{{ route('dashboard.categories.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 flex items-center">
            <i class='bx bx-plus mr-1'></i>
            Buat Kategori
        </a>
    </div>
@endsection

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Kategori</th>
                    <th scope="col" class="px-6 py-3">Slug</th>
                    <th scope="col" class="px-6 py-3">Jumlah Artikel</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $category->color ?? 'bg-gray-200 text-gray-800' }}">
                            {{ $category->name }}
                        </span>
                    </th>
                    <td class="px-6 py-4">{{ $category->slug }}</td>
                    <td class="px-6 py-4">{{ $category->posts_count }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('dashboard.categories.edit', $category->id) }}" class="p-2 text-blue-500 bg-blue-100 rounded-full hover:bg-blue-200" title="Edit">
                                <i class='bx bxs-edit'></i>
                            </a>
                            <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
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
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        Belum ada kategori.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($categories->hasPages())
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection 
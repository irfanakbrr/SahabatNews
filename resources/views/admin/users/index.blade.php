@extends('layouts.admin-new')

@section('title', 'Manajemen Pengguna')

@section('header-content')
    <div class="flex items-center justify-between w-full">
        <h1 class="text-2xl font-semibold text-gray-800">Manajemen Pengguna</h1>
        <a href="{{ route('dashboard.users.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 flex items-center">
            <i class='bx bx-plus mr-1'></i>
            Tambah Pengguna
        </a>
    </div>
@endsection

@section('content')
<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Role</th>
                    <th scope="col" class="px-6 py-3">Tanggal Bergabung</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <div class="flex items-center">
                            <img class="w-8 h-8 mr-3 rounded-full" src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" alt="{{ $user->name }}">
                            <span>{{ $user->name }}</span>
                        </div>
                    </th>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4 uppercase text-xs font-semibold">{{ $user->role->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('dashboard.users.edit', $user->id) }}" class="p-2 text-blue-500 bg-blue-100 rounded-full hover:bg-blue-200" title="Edit">
                                <i class='bx bxs-edit'></i>
                            </a>
                            @if(Auth::id() !== $user->id)
                            <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 bg-red-100 rounded-full hover:bg-red-200" title="Hapus">
                                    <i class='bx bxs-trash'></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Belum ada pengguna.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection 
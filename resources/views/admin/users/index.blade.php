@extends('layouts.admin')

@section('header')
    Manajemen User
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar User</h5>
        <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary btn-sm">
            <i class="bx bx-plus me-1"></i> Tambah User
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Bergabung</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role)
                                    <span class="badge 
                                        @switch($user->role->name)
                                            @case('admin') bg-danger @break
                                            @case('editor') bg-info @break
                                            @default bg-secondary @break
                                        @endswitch
                                    ">{{ ucfirst($user->role->name) }}</span>
                                @else
                                    <span class="badge bg-light text-dark">N/A</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                @if($user->id !== auth()->id())
                                    <div class="d-inline-flex">
                                        <a href="{{ route('dashboard.users.edit', $user) }}" class="btn btn-sm btn-info me-2" title="Edit">
                                            <i class="bx bx-edit-alt"></i>
                                        </a>
                                        <form action="{{ route('dashboard.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="badge bg-light text-dark">(Anda)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                Belum ada user.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($users->hasPages())
        <div class="card-footer">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection 
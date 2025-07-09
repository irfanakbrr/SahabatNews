@extends('layouts.admin-new')

@section('title', 'Tambah Pengguna Baru')

@section('header-content')
    <h1 class="text-2xl font-semibold text-gray-800">Tambah Pengguna Baru</h1>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('dashboard.users.store') }}" method="POST">
        @include('admin.users._form')
    </form>
</div>
@endsection 
@extends('layouts.admin-new')

@section('title', 'Tambah Kategori Baru')

@section('header-content')
    <h1 class="text-2xl font-semibold text-gray-800">Tambah Kategori Baru</h1>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('dashboard.categories.store') }}" method="POST">
        @include('admin.categories._form')
    </form>
</div>
@endsection 
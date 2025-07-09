@extends('layouts.admin-new')

@section('title', 'Edit Kategori')

@section('header-content')
    <h1 class="text-2xl font-semibold text-gray-800">Edit Kategori</h1>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('dashboard.categories.update', $category->id) }}" method="POST">
        @method('PATCH')
        @include('admin.categories._form')
    </form>
</div>
@endsection 
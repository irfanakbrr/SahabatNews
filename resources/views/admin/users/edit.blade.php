@extends('layouts.admin-new')

@section('title', 'Edit Pengguna')

@section('header-content')
    <h1 class="text-2xl font-semibold text-gray-800">Edit Pengguna</h1>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <form action="{{ route('dashboard.users.update', $user->id) }}" method="POST">
        @method('PATCH')
        @include('admin.users._form')
    </form>
</div>
@endsection 
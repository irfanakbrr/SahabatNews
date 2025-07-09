@extends('layouts.admin-new')

@section('title', 'Edit Artikel')

@section('header-content')
    <h1 class="text-2xl font-semibold text-gray-800">Edit Artikel</h1>
@endsection

@section('content')
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('dashboard.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @method('PATCH')
                        @csrf {{-- Pastikan CSRF token ada di form utama --}}
                        {{-- Variabel $categories sudah otomatis tersedia dari controller, $post dikirim dari sini --}}
                        @include('admin.posts._form', ['post' => $post])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page-styles')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
@endpush

@push('page-scripts')
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush 
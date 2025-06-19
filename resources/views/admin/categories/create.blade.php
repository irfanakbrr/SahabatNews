@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Kategori Baru') }}
        </h2>
@endsection

@section('content')
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('dashboard.categories.store') }}" method="POST" class="space-y-6">
                        @csrf
                        @include('admin.categories._form', ['category' => new \App\Models\Category()])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 
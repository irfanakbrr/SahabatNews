@extends('layouts.admin')

@section('header')
    Edit Halaman: {{ $page->title }}
@endsection

@push('page-styles')
    {{-- Jika menggunakan editor WYSIWYG tertentu, CSS-nya bisa di-push di sini --}}
    {{-- Contoh untuk Trix Editor (perlu install package-nya dulu): --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css"> --}}
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Konten Halaman: {{ $page->title }} (<code>{{ $page->slug }}</code>)</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('dashboard.pages.update', $page) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Judul Halaman</label>
                <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" 
                       class="form-control @error('title') is-invalid @enderror" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Konten Halaman</label>
                {{-- Untuk Trix Editor: --}}
                {{-- <input id="content" type="hidden" name="content" value="{{ old('content', $page->content) }}"> --}}
                {{-- <trix-editor input="content" class="form-control @error('content') is-invalid @enderror" style="min-height: 300px;"></trix-editor> --}}
                
                {{-- Textarea standar --}}
                <textarea name="content" id="content" rows="15" 
                          class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $page->content) }}</textarea>
                <small class="form-text text-muted">Anda bisa menggunakan tag HTML di sini.</small>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr class="my-4">
            <h6 class="mb-3 text-muted">Pengaturan SEO (Opsional)</h6>

            <div class="mb-3">
                <label for="meta_title" class="form-label">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title) }}" 
                       class="form-control @error('meta_title') is-invalid @enderror">
                @error('meta_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="meta_description" class="form-label">Meta Description</label>
                <textarea name="meta_description" id="meta_description" rows="3" 
                          class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $page->meta_description) }}</textarea>
                @error('meta_description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="meta_keywords" class="form-label">Meta Keywords (pisahkan dengan koma)</label>
                <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}" 
                       class="form-control @error('meta_keywords') is-invalid @enderror">
                @error('meta_keywords')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-4 pt-3 border-top">
                <a href="{{ route('dashboard.pages.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bx bx-save me-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('page-scripts')
    {{-- Jika menggunakan editor WYSIWYG tertentu, JS-nya bisa di-push di sini --}}
    {{-- Contoh untuk Trix Editor: --}}
    {{-- <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script> --}}
@endpush 
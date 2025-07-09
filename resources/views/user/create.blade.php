@extends('layouts.admin')

@section('header')
    <h5 class="mb-0">Ajukan Berita Baru</h5>
@endsection

@section('content')
<form action="{{ route('dashboard.userposts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card mb-4">
        <div class="card-header"><h5 class="card-title mb-0">Konten Berita</h5></div>
        <div class="card-body">
            <div class="mb-3">
                <label for="title" class="form-label">Judul Berita</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title ?? '') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Isi Berita</label>
                <textarea name="content" id="content" rows="8" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $post->content ?? '') }}</textarea>
                @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Gambar Sampul (opsional)</label>
                <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Ajukan Berita</button>
        </div>
    </div>
</form>
@endsection 
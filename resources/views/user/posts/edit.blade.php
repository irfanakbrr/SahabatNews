@extends('layouts.admin')

@section('header')
    <h5 class="mb-0">Edit Artikel</h5>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h5 class="mb-0">Edit Artikel
                    @if($post->status == 'rejected')
                        <span class="badge bg-danger ms-2">Ditolak - Perbaiki dan ajukan ulang</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.userposts.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Artikel</label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $post->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Isi Artikel</label>
                        <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $post->content) }}</textarea>
                        @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Sampul</label>
                        @if($post->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                                <p class="text-muted small mt-1">Gambar saat ini. Upload gambar baru untuk menggantinya.</p>
                            </div>
                        @endif
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                        <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB.</div>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="text-end">
                        <a href="{{ route('dashboard.userposts.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Perbarui & Ajukan Ulang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 
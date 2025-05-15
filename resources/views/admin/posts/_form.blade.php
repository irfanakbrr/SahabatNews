@csrf

<div class="mb-3">
    <label for="title" class="form-label">Judul Artikel</label>
    <input type="text" name="title" id="title" value="{{ old('title', $post->title ?? '') }}" required
           class="form-control @error('title') is-invalid @enderror">
    @error('title')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="category_id" class="form-label">Kategori</label>
    <select name="category_id" id="category_id" required
            class="form-select @error('category_id') is-invalid @enderror">
        <option value="">Pilih Kategori</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="content-editor-trix" class="form-label">Konten</label>
    <input id="content-editor-trix" type="hidden" name="content" value="{{ old('content', $post->content ?? '') }}">
    <trix-editor input="content-editor-trix" class="form-control @error('content') is-invalid @enderror" style="min-height: 300px;"></trix-editor>
    @error('content')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="image" class="form-label">Gambar Utama (Opsional)</label>
    <input type="file" name="image" id="image"
           class="form-control @error('image') is-invalid @enderror">
    @if(isset($post) && $post->image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $post->image) }}" alt="Gambar saat ini" class="img-thumbnail" style="max-height: 100px;">
            <small class="form-text text-muted">Gambar saat ini. Upload baru untuk mengganti.</small>
        </div>
    @endif
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select name="status" id="status" required
            class="form-select @error('status') is-invalid @enderror">
        <option value="draft" {{ old('status', $post->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="published" {{ old('status', $post->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mt-4 pt-3 border-top">
    <a href="{{ route('dashboard.posts.index') }}" class="btn btn-secondary me-2">
        Batal
    </a>
    <button type="submit" class="btn btn-primary">
        {{ ($post->id ?? null) ? 'Update Artikel' : 'Simpan Artikel' }}
    </button>
</div>

@push('page-scripts')
<script>
    document.addEventListener('trix-attachment-add', function(event) {
        if (event.attachment.file) {
            uploadTrixFileAttachment(event.attachment);
        }
    });

    function uploadTrixFileAttachment(attachment) {
        const file = attachment.file;
        const form = new FormData();
        form.append("file", file);
        // Anda bisa menambahkan data lain ke form jika diperlukan oleh backend
        // form.append("Content-Type", file.type); 

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "{{ route('dashboard.tinymce.upload') }}", true);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

        xhr.upload.onprogress = function(event) {
            let progress = (event.loaded / event.total) * 100;
            attachment.setUploadProgress(progress);
        }

        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if(response.location){
                    // Berikan URL gambar ke Trix
                    attachment.setAttributes({
                        url: response.location,
                        href: response.location // Untuk gambar yang juga link
                    });
                } else if (response.error) {
                    console.error('Trix upload error:', response.error);
                    alert('Gagal mengupload gambar: ' + response.error);
                    attachment.remove();
                }
            } else {
                console.error('Trix upload failed:', xhr.status, xhr.responseText);
                alert('Gagal mengupload gambar. Status: ' + xhr.status);
                attachment.remove();
            }
        }
        
        xhr.onerror = function () {
            console.error('Trix upload XHR error');
            alert('Terjadi kesalahan saat mengupload gambar.');
            attachment.remove();
        }

        xhr.send(form);
    }

    // Opsional: Mencegah Trix menyisipkan file selain gambar sebagai attachment
    // document.addEventListener("trix-file-accept", function(event) {
    //    if (!event.file.type.match("image.*")) {
    //        event.preventDefault();
    //        alert("Hanya file gambar yang diizinkan.");
    //    }
    //});
</script>
@endpush 
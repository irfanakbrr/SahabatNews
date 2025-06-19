@extends('layouts.admin')

@section('header')
    <h5 class="mb-0">{{ __('Tambah Artikel Baru') }}</h5>
@endsection

@section('content')

{{-- Modal untuk AI Generator --}}
<div class="modal fade" id="aiGeneratorModal" tabindex="-1" aria-labelledby="aiGeneratorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="aiGeneratorModalLabel">🚀 Buat Draf Cepat dengan AI</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted small">
            Masukkan topik berita, dan AI akan membuatkan draf judul, isi berita, dan saran prompt gambar untuk Anda.
        </p>
        <div class="input-group">
            <input type="text" id="ai_topic_input" class="form-control form-control-lg" placeholder="Contoh: Manfaat minum kopi di pagi hari">
            <button class="btn btn-primary" type="button" id="generateArticleButton">
                <span id="articleSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                <span id="generateArticleBtnText">Generate</span>
            </button>
        </div>
        <div id="ai-error" class="text-danger mt-2 d-none"></div>
      </div>
    </div>
  </div>
</div>

{{-- Form utama --}}
<form id="articleForm" action="{{ route('dashboard.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        {{-- Kolom utama untuk semua konten --}}
        <div class="col-12">

            <!-- Card Konten Artikel -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Konten Artikel</h5>
                     <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#aiGeneratorModal">
                        <i class='bx bxs-magic-wand me-1'></i> Buat dengan AI
                    </button>
                </div>
                <div class="card-body">
                    <!-- Judul -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul Artikel</label>
                        <input type="text" name="title" id="title" class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title', $post->title ?? '') }}" required placeholder="Masukkan judul berita di sini...">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konten -->
                    <div>
                        <label for="content" class="form-label">Isi Konten</label>
                        <input id="content" type="hidden" name="content" value="{{ old('content', $post->content ?? '') }}">
                        <trix-editor input="content" class="form-control trix-content @error('content') is-invalid @enderror" style="min-height: 300px;"></trix-editor>
                        @error('content')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

             <!-- Card Gambar Sampul -->
            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Gambar Sampul</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                             <img id="imagePreview" src="{{ $post->image_url ?? 'https://via.placeholder.com/800x400.png?text=Preview+Gambar' }}" alt="Preview Gambar" class="img-fluid rounded" style="width: 100%; aspect-ratio: 16/9; object-fit: cover;">
                        </div>
                        <div class="col-md-6">
                            <ul class="nav nav-pills nav-fill mb-3" id="imageTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="nav-ai-tab" data-bs-toggle="tab" data-bs-target="#nav-ai" type="button" role="tab" aria-controls="nav-ai" aria-selected="true">
                                        <i class='bx bx-search-alt me-1'></i> Unsplash
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="nav-manual-tab" data-bs-toggle="tab" data-bs-target="#nav-manual" type="button" role="tab" aria-controls="nav-manual" aria-selected="false">
                                       <i class='bx bx-upload me-1'></i> Upload
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-ai" role="tabpanel" aria-labelledby="nav-ai-tab">
                                    <div class="mb-3">
                                        <label for="ai_prompt" class="form-label">Kata Kunci Gambar (Inggris)</label>
                                        <textarea class="form-control @error('ai_prompt') is-invalid @enderror" id="ai_prompt" rows="3" placeholder="Contoh: 'Man reading a newspaper with a coffee'"></textarea>
                                    </div>
                                    <button type="button" class="btn btn-info w-100" id="generateImageButton">
                                        <span id="imageSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        <span id="generateImageButtonText">Cari Gambar</span>
                                    </button>
                                </div>
                                <div class="tab-pane fade" id="nav-manual" role="tabpanel" aria-labelledby="nav-manual-tab">
                                    <label for="manual_image" class="form-label">Pilih file dari komputer</label>
                                    <input type="file" name="manual_image" id="manual_image" class="form-control @error('manual_image') is-invalid @enderror">
                                     @error('manual_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Publikasi -->
            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title mb-0">Publikasi</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                             <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
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
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="draft" {{ old('status', $post->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $post->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                     <a href="{{ route('dashboard.posts.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" id="saveArticleButton" class="btn btn-primary">
                        {{ ($post->exists) ? 'Update Artikel' : 'Simpan Artikel' }}
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Hidden Inputs -->
    <input type="hidden" name="cover_image_path" id="cover_image_path" value="{{ old('cover_image_path', $post->cover_image_path ?? '') }}">
    <input type="hidden" name="is_ai_generated" id="is_ai_generated" value="{{ old('is_ai_generated', $post->is_ai_generated ?? '0') }}">
</form>

@endsection

@push('page-scripts')
<script>
// Menjaga script Trix attachment tetap ada
document.addEventListener('trix-attachment-add', function(event) {
    if (event.attachment.file) {
        uploadTrixFileAttachment(event.attachment);
    }
});

function uploadTrixFileAttachment(attachment) {
    const file = attachment.file;
    const form = new FormData();
    form.append("file", file);
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "{{ route('dashboard.tinymce.upload') }}", true);
    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
    xhr.upload.onprogress = function(event) {
        attachment.setUploadProgress((event.loaded / event.total) * 100);
    }
    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if(response.location){
                return attachment.setAttributes({ url: response.location, href: response.location });
            }
        }
        alert('Gagal mengupload file. Silakan coba lagi.');
        attachment.remove();
    }
    xhr.onerror = function () { 
        alert('Terjadi kesalahan saat upload. Periksa koneksi Anda.');
        attachment.remove(); 
    }
    xhr.send(form);
}


document.addEventListener('DOMContentLoaded', function () {

    function initializePageLogic() {
        const aiGeneratorModal = new bootstrap.Modal(document.getElementById('aiGeneratorModal'));

        // === TOAST NOTIFICATION FUNCTION ===
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) return;

            const toastId = 'toast-' + Date.now();
            const bgClass = {
                success: 'bg-success',
                error: 'bg-danger',
                info: 'bg-info',
                warning: 'bg-warning'
            }[type] || 'bg-secondary';

            const toastHTML = `
                <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, { delay: 5000 });
            toast.show();
            toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
        }

        // === ELEMENTS ===
        const titleInput = document.getElementById('title');
        const trixEditorElement = document.querySelector('trix-editor');
        const imagePreview = document.getElementById('imagePreview');
        const coverImagePathInput = document.getElementById('cover_image_path');
        const isAiGeneratedInput = document.getElementById('is_ai_generated');
        const aiPromptInput = document.getElementById('ai_prompt');
        const manualImageInput = document.getElementById('manual_image');
        const aiErrorDiv = document.getElementById('ai-error');
        const aiTopicInput = document.getElementById('ai_topic_input');

        // === BUTTONS & SPINNERS ===
        const generateArticleButton = document.getElementById('generateArticleButton');
        const articleSpinner = document.getElementById('articleSpinner');
        const generateArticleBtnText = document.getElementById('generateArticleBtnText');
        const generateImageButton = document.getElementById('generateImageButton');
        const imageSpinner = document.getElementById('imageSpinner');
        const saveArticleButton = document.getElementById('saveArticleButton');

        // === LOADING STATES ===
        function setArticleLoading(isLoading) {
            generateArticleButton.disabled = isLoading;
            if (isLoading) {
                articleSpinner.classList.remove('d-none');
                generateArticleBtnText.textContent = 'Membuat...';
            } else {
                articleSpinner.classList.add('d-none');
                generateArticleBtnText.textContent = 'Generate';
            }
        }

        function setImageRegenLoading(isLoading) {
            const button = document.getElementById('generateImageButton');
            const spinner = document.getElementById('imageSpinner');
            const buttonTextSpan = button.querySelector('span:not(.spinner-border)'); 

            if (!button || !spinner || !buttonTextSpan) return;

            button.disabled = isLoading;
            if (isLoading) {
                spinner.classList.remove('d-none');
                buttonTextSpan.textContent = 'Mencari...';
                imagePreview.style.opacity = '0.5';
            } else {
                spinner.classList.add('d-none');
                buttonTextSpan.textContent = 'Cari Gambar';
                imagePreview.style.opacity = '1';
            }
        }

        function showAiError(message) {
            aiErrorDiv.textContent = message;
            aiErrorDiv.classList.remove('d-none');
        }

        // === EVENT: GENERATE FULL ARTICLE ===
        generateArticleButton.addEventListener('click', async function () {
            const topic = aiTopicInput.value;
            if (!topic) {
                showToast('Silakan masukkan topik berita.', 'warning');
                return;
            }

            setArticleLoading(true);
            aiErrorDiv.classList.add('d-none');
            showToast('AI sedang membuat draf artikel untuk Anda...', 'info');

            try {
                const response = await fetch('{{ route("dashboard.ai.generate.article") }}', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({ topic: topic })
                });
                const result = await response.json();

                if (result.success) {
                    titleInput.value = result.data.title;
                    trixEditorElement.editor.loadHTML(result.data.content);
                    aiPromptInput.value = result.data.image_prompt;
                    imagePreview.src = 'https://via.placeholder.com/800x400.png?text=Cari+gambar+dengan+kata+kunci';
                    coverImagePathInput.value = '';
                    isAiGeneratedInput.value = '0';
                    manualImageInput.value = '';
                    new bootstrap.Tab(document.getElementById('nav-ai-tab')).show();
                    aiGeneratorModal.hide();
                    showToast('Draf artikel berhasil dibuat! Sekarang cari gambarnya.', 'success');
                } else {
                    const errorMessage = result.error || 'Gagal membuat artikel.';
                    showAiError(errorMessage);
                    showToast(`Error: ${errorMessage}`, 'error');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                const connErrorMessage = 'Terjadi kesalahan koneksi.';
                showAiError(connErrorMessage);
                showToast(connErrorMessage, 'error');
            } finally {
                setArticleLoading(false);
            }
        });

        // === EVENT: RE-GENERATE IMAGE ONLY ===
        generateImageButton.addEventListener('click', async function () {
            const prompt = aiPromptInput.value;
            if (!prompt) {
                showToast('Silakan masukkan kata kunci gambar (prompt).', 'warning');
                return;
            }
            
            setImageRegenLoading(true);
            showToast('Unsplash sedang mencari gambar...', 'info');

            try {
                const response = await fetch('{{ route("dashboard.image.generate") }}', { 
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    body: JSON.stringify({ prompt: prompt })
                });
                const result = await response.json();
                if (result.success) {
                    imagePreview.src = result.url;
                    coverImagePathInput.value = result.path;
                    isAiGeneratedInput.value = '0'; // Gambar dari Unsplash tidak dianggap AI generated
                    manualImageInput.value = '';
                    showToast('Gambar berhasil ditemukan!', 'success');
                } else {
                    const errorMessage = result.error || 'Gagal mencari gambar.';
                    showToast(`Error: ${errorMessage}`, 'error');
                }
            } catch (error) {
                console.error('Fetch error:', error);
                showToast('Terjadi kesalahan koneksi saat mencari gambar.', 'error');
            } finally {
                setImageRegenLoading(false);
            }
        });
        
        // === EVENT: MANUAL UPLOAD ===
        manualImageInput.addEventListener('change', function(event){
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                }
                reader.readAsDataURL(event.target.files[0]);
                isAiGeneratedInput.value = '0';
                coverImagePathInput.value = '';
                aiPromptInput.value = '';
            }
        });

        // === TAB BEHAVIOR ===
        document.getElementById('nav-manual-tab').addEventListener('click', function() {
            isAiGeneratedInput.value = '0';
            coverImagePathInput.value = '';
            aiPromptInput.value = '';
        });

        document.getElementById('nav-ai-tab').addEventListener('click', function() {
            manualImageInput.value = ''; 
        });
    }

    // Tunggu hingga `window.bootstrap` benar-benar ada sebelum menjalankan kode kita.
    // Ini untuk mencegah race condition dengan script Vite yang di-defer.
    let checkBootstrapInterval = setInterval(function() {
        if (window.bootstrap) {
            clearInterval(checkBootstrapInterval);
            initializePageLogic();
        }
    }, 100);
});
</script>
@endpush

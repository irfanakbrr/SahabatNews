@extends('layouts.admin-new')

@section('title', 'Tambah Artikel Baru')

@section('header-content')
    <h1 class="text-2xl font-semibold text-gray-800">Tambah Artikel Baru</h1>
@endsection

@section('content')
<div x-data="{ isAiModalOpen: false }">
    <form action="{{ route('dashboard.posts.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.posts._form')
    </form>

    <!-- AI Generation Modal -->
    <div x-show="isAiModalOpen" @keydown.escape.window="isAiModalOpen = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
        <div @click.away="isAiModalOpen = false" class="w-full max-w-lg p-6 mx-4 bg-white rounded-lg shadow-xl">
            <h3 class="text-lg font-semibold text-gray-800">Buat Draf Cepat dengan AI</h3>
            <p class="mt-1 text-sm text-gray-600">Masukkan topik artikel, dan biarkan AI membuat draf pertama untuk Anda. Draf akan otomatis tersimpan di daftar artikel.</p>
            
            <div class="mt-4 space-y-4">
                <div>
                    <label for="ai-topic" class="block text-sm font-medium text-gray-700">Topik Artikel</label>
                    <input type="text" id="ai-topic" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="cth: Dampak AI pada industri media">
                </div>
                <div>
                    <label for="ai-category" class="block text-sm font-medium text-gray-700">Pilih Kategori</label>
                    <select id="ai-category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button @click="isAiModalOpen = false" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Batal
                </button>
                <button id="ai-submit-btn" type="button" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    Mulai Buat Draf
                </button>
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
    const aiSubmitBtn = document.getElementById('ai-submit-btn');
    const aiTopicInput = document.getElementById('ai-topic');
    const aiCategorySelect = document.getElementById('ai-category');
    
    // Listen for the custom event dispatched from the form
    document.addEventListener('open-ai-modal', () => {
        document.querySelector('[x-data]').__x.$data.isAiModalOpen = true;
    });

    aiSubmitBtn.addEventListener('click', function () {
        const topic = aiTopicInput.value;
        const categoryId = aiCategorySelect.value;

        if (!topic.trim()) {
            alert('Topik tidak boleh kosong.');
            return;
        }

        aiSubmitBtn.disabled = true;
        aiSubmitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Memproses...';

        fetch('{{ route("dashboard.ai.generate.article") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ topic: topic, category_id: categoryId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelector('[x-data]').__x.$data.isAiModalOpen = false;
                // Maybe add a toast notification here later
                alert('Berhasil! Draf artikel sedang dibuat di latar belakang dan akan segera muncul di daftar artikel.');
            } else {
                alert('Gagal: ' + (data.error || 'Terjadi kesalahan.'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan koneksi.');
        })
        .finally(() => {
            aiSubmitBtn.disabled = false;
            aiSubmitBtn.innerHTML = 'Mulai Buat Draf';
        });
    });

    // --- Logic for AI Editor Actions ---
    const trixEditor = document.querySelector('trix-editor');
    document.querySelectorAll('.ai-editor-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const editor = trixEditor.editor;
            const selectedRange = editor.getSelectedRange();
            
            if (selectedRange[0] === selectedRange[1]) {
                alert('Silakan pilih (sorot) teks di dalam editor terlebih dahulu.');
                return;
            }

            const selectedText = editor.getDocument().getStringAtRange(selectedRange);
            
            // Simpan posisi kursor/seleksi
            editor.setSelectedRange(selectedRange);

            // Kirim ke AI
            askEditorAI(selectedText, action, (newText) => {
                if(newText) {
                    editor.insertString(newText);
                }
            });
        });
    });

    async function askEditorAI(prompt, action, callback) {
        // Beri feedback visual di editor
        trixEditor.classList.add('opacity-50');
        try {
            const response = await fetch('{{ route("dashboard.ai.assistant.ask") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ prompt, action })
            });
            const data = await response.json();
            if (data.success) {
                // Hapus teks lama dan masukkan teks baru dari AI
                trixEditor.editor.deleteInDirection("forward");
                callback(data.response);
            } else {
                alert('AI Gagal merespons: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            console.error('AI Editor Fetch Error:', error);
            alert('Gagal terhubung ke server AI.');
        } finally {
            trixEditor.classList.remove('opacity-50');
        }
    }
});
</script>
@endpush

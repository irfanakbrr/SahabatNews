@csrf
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Main Content -->
    <div class="space-y-6 lg:col-span-2">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Konten Artikel</h3>
                <button type="button" @click.prevent="$dispatch('open-ai-modal')" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 flex items-center">
                    <i class='bx bx-party mr-1'></i>
                    Buat dengan AI
                </button>
            </div>
            <div class="mt-4 space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Judul Artikel</label>
                    <input type="text" name="title" id="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" value="{{ old('title', $post->title ?? '') }}" required>
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Konten</label>
                    <!-- AI Action Buttons for Editor -->
                    <div class="flex items-center space-x-2 mb-2 border-b pb-2">
                        <span class="text-xs font-semibold text-gray-500">Aksi Cepat AI:</span>
                        <button type="button" data-action="summarize" class="ai-editor-btn px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 hover:bg-yellow-200">Ringkas</button>
                        <button type="button" data-action="fix_grammar" class="ai-editor-btn px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200">Perbaiki Ejaan</button>
                        <button type="button" data-action="find_synonyms" class="ai-editor-btn px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800 hover:bg-indigo-200">Cari Sinonim</button>
                    </div>
                    <input id="content" type="hidden" name="content" value="{{ old('content', $post->content ?? '') }}">
                    <trix-editor input="content" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 trix-content"></trix-editor>
                    @error('content')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6 lg:col-span-1">
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800">Metadata</h3>
            <div class="mt-4 space-y-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" required>
                        <option value="draft" {{ old('status', $post->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $post->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                 <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Gambar Unggulan</label>
                     @if(isset($post) && $post->image)
                        <div class="mt-2 mb-2">
                            <img src="{{ asset('storage/' . $post->image) }}" alt="Preview Gambar" class="w-full h-auto rounded-md object-cover">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                    @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
        <button type="submit" class="w-full px-4 py-3 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
            Simpan Artikel
        </button>
    </div>
</div>

<style>
.trix-content {
    min-height: 300px;
    background-color: white;
}
.trix-content h1 {
    font-size: 1.25rem !important;
    line-height: 1.25rem !important;
    font-weight: 600 !important;
    margin-bottom: 1rem;
}
</style> 
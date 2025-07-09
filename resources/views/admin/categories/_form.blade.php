@csrf
<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" value="{{ old('name', $category->name ?? '') }}" required>
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
            <input type="text" name="slug" id="slug" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" value="{{ old('slug', $category->slug ?? '') }}">
            <p class="mt-1 text-xs text-gray-500">Kosongkan agar slug dibuat otomatis.</p>
            @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="color" class="block text-sm font-medium text-gray-700">Warna</label>
            <input type="text" name="color" id="color" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" value="{{ old('color', $category->color ?? '') }}">
            <p class="mt-1 text-xs text-gray-500">Gunakan nama kelas Tailwind, contoh: `bg-red-500`.</p>
            @error('color')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
<button type="submit" class="mt-6 w-full px-4 py-3 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
    Simpan Kategori
</button> 
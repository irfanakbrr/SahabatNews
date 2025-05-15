@csrf

<div class="mb-3">
    <label for="name" class="form-label">Nama Kategori</label>
    <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}" required
           class="form-control @error('name') is-invalid @enderror">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="color" class="form-label">Warna Kategori</label>
    <input type="text" name="color" id="color" value="{{ old('color', $category->color ?? '') }}"
           placeholder="cth: #FF0000 atau bg-danger atau text-primary"
           class="form-control @error('color') is-invalid @enderror">
    <small class="form-text text-muted">Bisa berupa kode hex (cth: #FF0000), atau kelas utilitas warna Bootstrap (cth: bg-primary, text-success).</small>
    @error('color')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mt-4 pt-3 border-top">
    <a href="{{ route('dashboard.categories.index') }}" class="btn btn-secondary me-2">
        Batal
    </a>
    <button type="submit" class="btn btn-primary">
        {{ ($category->id ?? null) ? 'Update Kategori' : 'Simpan Kategori' }}
    </button>
</div> 
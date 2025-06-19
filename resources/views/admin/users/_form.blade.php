@csrf

<div class="mb-3">
    <label for="name" class="form-label">Nama</label>
    <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}" required
           class="form-control @error('name') is-invalid @enderror">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" required
           class="form-control @error('email') is-invalid @enderror">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="role_id" class="form-label">Role</label>
    <select name="role_id" id="role_id" required
            class="form-select @error('role_id') is-invalid @enderror">
        <option value="">Pilih Role</option>
        @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                {{ ucfirst($role->name) }}
            </option>
        @endforeach
    </select>
    @error('role_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" name="password" id="password" {{ !($user->id ?? null) ? 'required' : '' }}
           class="form-control @error('password') is-invalid @enderror">
    @if($user->id ?? null)
    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
    @endif
    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
    <input type="password" name="password_confirmation" id="password_confirmation" {{ !($user->id ?? null) ? 'required' : '' }}
           class="form-control @error('password_confirmation') is-invalid @enderror">
     @error('password_confirmation')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mt-4 pt-3 border-top">
    <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary me-2">Batal</a>
    <button type="submit" class="btn btn-primary">
        {{ ($user->id ?? null) ? 'Update User' : 'Simpan User' }}
    </button>
</div> 
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua user kecuali admin yang sedang login (opsional)
        // $users = User::where('id', '!=', auth()->id())->with('role')->latest()->paginate(10);
        $users = User::with('role')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('dashboard.users.index')->with('success', 'User berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Jangan biarkan admin mengedit dirinya sendiri di halaman ini (lebih baik via profile)
        if ($user->id === auth()->id()) {
             return redirect()->route('dashboard.users.index')->with('error', 'Anda tidak dapat mengedit diri sendiri di sini.');
        }
        $roles = Role::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Jangan biarkan admin mengedit dirinya sendiri
        if ($user->id === auth()->id()) {
             return redirect()->route('dashboard.users.index')->with('error', 'Update profile sendiri melalui halaman Profile.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Password opsional saat update
        ]);

        $data = $request->only('name', 'email', 'role_id');

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('dashboard.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Jangan biarkan admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->route('dashboard.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Optional: Tambahkan validasi lain (misal: tidak bisa hapus admin terakhir)

        $user->delete();
        return redirect()->route('dashboard.users.index')->with('success', 'User berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'color' => 'nullable|string|max:50',
        ]);

        $data = $request->all();

        Category::create($data);

        return redirect()->route('dashboard.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Category $category)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'color' => 'nullable|string|max:50',
        ]);

        $data = $request->all();

        // Handle slug update jika nama berubah (jika tidak otomatis oleh model)
        // if ($request->name !== $category->name) {
        //     $data['slug'] = Str::slug($request->name);
        // }

        $category->update($data);

        return redirect()->route('dashboard.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Optional: Tambahkan pengecekan jika kategori masih digunakan oleh post
        // if ($category->posts()->exists()) {
        //     return redirect()->route('dashboard.categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh artikel.');
        // }

        $category->delete();
        return redirect()->route('dashboard.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}

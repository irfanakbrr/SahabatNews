<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Category $category) // Menggunakan Route Model Binding
    {
        // Ambil posts untuk kategori ini yang sudah published, beserta penulisnya (user)
        $posts = $category->posts()
                         ->with('user') // Optimalisasi N+1 untuk user
                         ->where('status', 'published')
                         ->latest('published_at')
                         ->paginate(9); // 9 post per halaman

        // Ambil semua kategori untuk filter, gunakan cache selama 1 jam
        $categories = \Cache::remember('categories', 3600, function() {
            return \App\Models\Category::orderBy('name')->get();
        });

        return view('category', compact('category', 'posts', 'categories'));
    }
} 
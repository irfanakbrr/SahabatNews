<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class PostController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Post $post) // Menggunakan Route Model Binding
    {
        // Increment view count
        // Cara sederhana:
        $post->increment('view_count');
        // Atau jika ingin lebih canggih (misal: mencegah double count dari session yg sama dlm waktu singkat):
        // if (!session()->has('viewed_post_' . $post->id)) {
        //     $post->increment('view_count');
        //     session()->put('viewed_post_' . $post->id, true, 60); // Simpan di session selama 60 menit
        // }

        // Pastikan post sudah published (opsional, tergantung logic akses)
        // if ($post->status !== 'published') {
        //     abort(404);
        // }

        // Ambil komentar yang sudah disetujui
        $comments = $post->comments()
                         ->where('approved', true)
                         ->latest()
                         ->paginate(5);

        // Ambil berita terkait (contoh: dari kategori yang sama, kecuali post ini)
        $relatedPosts = Post::where('category_id', $post->category_id)
                            ->where('id', '!=', $post->id)
                            ->where('status', 'published')
                            ->latest('published_at')
                            ->limit(4)
                            ->get();

        return view('post-detail', compact('post', 'comments', 'relatedPosts'));
    }

    // --- Admin/Editor Methods ---

    public function indexAdmin()
    {
        // Ambil post berdasarkan user yg login jika editor, semua post jika admin
        $user = auth()->user();
        if ($user->isAdmin()) {
            $posts = Post::with('category', 'user')->latest()->paginate(10);
        } else { // Editor hanya lihat post miliknya
            $posts = $user->posts()->with('category')->latest()->paginate(10);
        }
        return view('admin.posts.index', compact('posts'));
    }

    public function createAdmin()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:posts,title',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->except('image');
        $data['user_id'] = auth()->id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post_images', 'public'); // Simpan ke storage/app/public/post_images
            $data['image'] = $imagePath;
        }

        // Set published_at jika status published
        if ($request->status == 'published') {
            $data['published_at'] = now();
        }

        Post::create($data);

        return redirect()->route('dashboard.posts.index')->with('success', 'Artikel berhasil dibuat.');
    }

    public function editAdmin(Post $post)
    {
        // Autorisasi: Admin bisa edit semua, editor hanya post miliknya
        $this->authorizeAdminOrOwner($post);

        $categories = Category::orderBy('name')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function updateAdmin(Request $request, Post $post)
    {
         // Autorisasi: Admin bisa edit semua, editor hanya post miliknya
        $this->authorizeAdminOrOwner($post);

        $request->validate([
            'title' => 'required|string|max:255|unique:posts,title,' . $post->id,
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $data = $request->except('image');

        // Handle image upload jika ada gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = $request->file('image')->store('post_images', 'public');
            $data['image'] = $imagePath;
        }

         // Set/reset published_at berdasarkan status
         if ($request->status == 'published' && is_null($post->published_at)) {
            $data['published_at'] = now();
        } elseif ($request->status == 'draft') {
            $data['published_at'] = null;
        }

        // Update slug jika title berubah (model boot method akan handle ini)
        if ($request->title !== $post->title) {
             $data['slug'] = null; // Biarkan model generate ulang
        }

        $post->update($data);

        return redirect()->route('dashboard.posts.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroyAdmin(Post $post)
    {
        // Autorisasi: Admin bisa hapus semua, editor hanya post miliknya
        $this->authorizeAdminOrOwner($post);

        // Hapus gambar dari storage
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return redirect()->route('dashboard.posts.index')->with('success', 'Artikel berhasil dihapus.');
    }

    // Helper untuk otorisasi edit/delete
    protected function authorizeAdminOrOwner(Post $post)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $post->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
    }
} 
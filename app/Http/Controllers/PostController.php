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

        // Eager load relasi utama untuk post saat ini
        $post->load('user', 'category');

        // Ambil komentar yang sudah disetujui beserta relasi user-nya
        $comments = $post->comments()
                         ->with('user') // Optimalisasi N+1 untuk user komentar
                         ->where('approved', true)
                         ->latest()
                         ->paginate(5);

        // Ambil berita terkait (contoh: dari kategori yang sama, kecuali post ini)
        $relatedPosts = Post::where('category_id', $post->category_id)
                            ->where('id', '!=', $post->id)
                            ->where('status', 'published')
                            ->with('user', 'category') // Optimalisasi N+1 untuk berita terkait
                            ->latest('published_at')
                            ->limit(4)
                            ->get();

        $isBookmarked = auth()->check() ? auth()->user()->bookmarks()->where('post_id', $post->id)->exists() : false;

        return view('post-detail', compact('post', 'comments', 'relatedPosts', 'isBookmarked'));
    }

    /**
     * Display search results.
     */
    public function search(Request $request)
    {
        $keyword = $request->input('q');

        if (empty($keyword)) {
            return redirect()->route('home');
        }

        // Cari post menggunakan Scout, dan muat relasi yang diperlukan
        $posts = Post::search($keyword)
            ->where('status', 'published') // Hanya cari post yang sudah publish
            ->query(function ($query) {
                $query->with(['category', 'user']); // Eager load relasi
            })
            ->paginate(10)
            ->withQueryString(); // Agar paginasi tetap menyertakan query ?q=...

        return view('search-results', compact('posts', 'keyword'));
    }

    // --- Admin/Editor Methods ---

    public function indexAdmin()
    {
        // Admin dan Editor bisa lihat semua post
        $posts = Post::with('category', 'user')->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function createAdmin()
    {
        $categories = Category::orderBy('name')->get();
        $post = new Post(); // Buat instance Post baru
        return view('admin.posts.create', compact('categories', 'post'));
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

    public function editAdminSimple(Post $post)
    {
        // Autorisasi: Admin bisa edit semua, editor hanya post miliknya
        $this->authorizeAdminOrOwner($post);

        $categories = Category::orderBy('name')->get();
        return view('admin.posts.edit-simple', compact('post', 'categories'));
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
        
        // Admin bisa edit semua
        if ($user->isAdmin()) {
            return;
        }
        
        // Editor bisa edit semua artikel
        if ($user->hasRole('editor')) {
            return;
        }
        
        // User biasa hanya bisa edit miliknya
        if ($post->user_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    // --- User Biasa Methods ---
    public function createUser()
    {
        $categories = Category::orderBy('name')->get();
        $post = new Post();
        return view('user.posts.create', compact('categories', 'post'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:posts,title',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $data = $request->except('image');
        $data['user_id'] = auth()->id();
        $data['status'] = 'draft'; // Atau 'pending' jika ingin status menunggu acc
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post_images', 'public');
            $data['image'] = $imagePath;
        }
        Post::create($data);
        return redirect()->route('dashboard')->with('success', 'Artikel berhasil diajukan, menunggu persetujuan admin/editor.');
    }

    // Notifikasi/Approval untuk admin/editor
    public function pendingPosts()
    {
        $user = auth()->user();
        // Admin lihat semua, editor hanya kategori miliknya (atau semua jika ingin)
        $pendingPosts = Post::where('status', 'draft')->with('user', 'category')->latest()->get();
        return view('admin.posts.pending', compact('pendingPosts'));
    }

    public function approvePost(Post $post)
    {
        $post->status = 'published';
        $post->published_at = now();
        $post->save();
        return back()->with('success', 'Berita berhasil di-approve dan dipublish!');
    }

    public function rejectPost(Post $post)
    {
        $post->status = 'rejected';
        $post->save();
        return back()->with('success', 'Berita berhasil ditolak. User dapat mengedit dan mengajukan ulang.');
    }

    // Method untuk user biasa mengelola artikel mereka
    public function indexUser()
    {
        $posts = auth()->user()->posts()
            ->with('category')
            ->latest()
            ->paginate(10);
        return view('user.posts.index', compact('posts'));
    }

    public function editUser(Post $post)
    {
        // Pastikan user hanya bisa edit post miliknya sendiri
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya bisa edit jika status draft atau rejected
        if (!in_array($post->status, ['draft', 'rejected'])) {
            return redirect()->route('dashboard.userposts.index')
                ->with('error', 'Anda tidak dapat mengedit artikel yang sudah dipublish atau sedang dalam review.');
        }

        $categories = Category::orderBy('name')->get();
        return view('user.posts.edit', compact('post', 'categories'));
    }

    public function updateUser(Request $request, Post $post)
    {
        // Pastikan user hanya bisa edit post miliknya sendiri
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya bisa edit jika status draft atau rejected
        if (!in_array($post->status, ['draft', 'rejected'])) {
            return redirect()->route('dashboard.userposts.index')
                ->with('error', 'Anda tidak dapat mengedit artikel yang sudah dipublish atau sedang dalam review.');
        }

        $request->validate([
            'title' => 'required|string|max:255|unique:posts,title,' . $post->id,
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('image');
        $data['status'] = 'draft'; // Reset ke draft untuk review ulang

        // Handle image upload jika ada gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            $imagePath = $request->file('image')->store('post_images', 'public');
            $data['image'] = $imagePath;
        }

        $post->update($data);

        return redirect()->route('dashboard.userposts.index')
            ->with('success', 'Artikel berhasil diperbarui dan diajukan ulang untuk review.');
    }

    public function destroyUser(Post $post)
    {
        // Pastikan user hanya bisa hapus post miliknya sendiri
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hanya bisa hapus jika status draft atau rejected
        if (!in_array($post->status, ['draft', 'rejected'])) {
            return redirect()->route('dashboard.userposts.index')
                ->with('error', 'Anda tidak dapat menghapus artikel yang sudah dipublish atau sedang dalam review.');
        }

        // Hapus gambar dari storage
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return redirect()->route('dashboard.userposts.index')
            ->with('success', 'Artikel berhasil dihapus.');
    }
} 
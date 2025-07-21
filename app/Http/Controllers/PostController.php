<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\Category;
use Exception;

class PostController extends Controller
{
    /**
     * Display the specified resource with comprehensive error handling.
     */
    public function show(Post $post) // Menggunakan Route Model Binding
    {
        try {
            // === SAFE POST DETAIL WITH ERROR HANDLING ===
            
            // 1. Safe View Tracking (Most problematic part)
            $this->safeTrackView($post);

            // 2. Safe Status Check
            $this->safeStatusCheck($post);

            // 3. Safe Load Relationships
            $post = $this->safeLoadPostRelations($post);

            // 4. Safe Get Comments
            $comments = $this->safeGetComments($post);

            // 5. Safe Get Related Posts
            $relatedPosts = $this->safeGetRelatedPosts($post);

            // 6. Safe Check Bookmark Status
            $isBookmarked = $this->safeCheckBookmarkStatus($post);

            return view('post-detail', compact('post', 'comments', 'relatedPosts', 'isBookmarked'));

        } catch (Exception $e) {
            // Log error for debugging
            Log::error('PostController::show error: ' . $e->getMessage(), [
                'post_id' => $post->id,
                'post_slug' => $post->slug,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return safe fallback
            return $this->fallbackPostDetail($post);
        }
    }

    /**
     * Safe method to track post view
     */
    private function safeTrackView(Post $post)
    {
        try {
            // Check if PostView class exists and post_views table exists
            if (class_exists('\App\Models\PostView') && Schema::hasTable('post_views')) {
                \App\Models\PostView::trackView($post, request());
            } else {
                // Fallback: Simple increment view_count
                $this->fallbackTrackView($post);
            }
        } catch (Exception $e) {
            Log::error('Error tracking view: ' . $e->getMessage(), ['post_id' => $post->id]);
            $this->fallbackTrackView($post);
        }
    }

    /**
     * Fallback method for tracking views
     */
    private function fallbackTrackView(Post $post)
    {
        try {
            // Simple view count increment without detailed tracking
            $post->increment('view_count');
        } catch (Exception $e) {
            Log::error('Error in fallback view tracking: ' . $e->getMessage(), ['post_id' => $post->id]);
            // Ignore if even this fails
        }
    }

    /**
     * Safe method to check post status
     */
    private function safeStatusCheck(Post $post)
    {
        try {
            // Optional status check - uncomment if needed
            // if ($post->status !== 'published') {
            //     abort(404);
            // }
        } catch (Exception $e) {
            Log::error('Error checking post status: ' . $e->getMessage(), ['post_id' => $post->id]);
            // Continue anyway
        }
    }

    /**
     * Safe method to load post relationships
     */
    private function safeLoadPostRelations(Post $post)
    {
        try {
            // Eager load relasi utama untuk post saat ini
            $post->load('user', 'category');
            return $post;
        } catch (Exception $e) {
            Log::error('Error loading post relations: ' . $e->getMessage(), ['post_id' => $post->id]);
            return $post; // Return post as is if relations fail
        }
    }

    /**
     * Safe method to get comments
     */
    private function safeGetComments(Post $post)
    {
        try {
            // Ambil komentar yang sudah disetujui beserta relasi user-nya
            return $post->comments()
                        ->with('user') // Optimalisasi N+1 untuk user komentar
                        ->where('approved', true)
                        ->latest()
                        ->paginate(5);
        } catch (Exception $e) {
            Log::error('Error getting comments: ' . $e->getMessage(), ['post_id' => $post->id]);
            return $this->fallbackComments();
        }
    }

    /**
     * Fallback method for comments
     */
    private function fallbackComments()
    {
        try {
            // Return empty paginated collection
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // Empty items
                0, // Total count
                5, // Per page
                1, // Current page
                ['path' => request()->url()]
            );
        } catch (Exception $e) {
            Log::error('Error creating fallback comments: ' . $e->getMessage());
            return collect(); // Ultimate fallback
        }
    }

    /**
     * Safe method to get related posts
     */
    private function safeGetRelatedPosts(Post $post)
    {
        try {
            // Ambil berita terkait (contoh: dari kategori yang sama, kecuali post ini)
            return Post::where('category_id', $post->category_id)
                        ->where('id', '!=', $post->id)
                        ->where('status', 'published')
                        ->with('user', 'category') // Optimalisasi N+1 untuk berita terkait
                        ->latest('published_at')
                        ->limit(4)
                        ->get();
        } catch (Exception $e) {
            Log::error('Error getting related posts: ' . $e->getMessage(), ['post_id' => $post->id]);
            return collect(); // Return empty collection
        }
    }

    /**
     * Safe method to check bookmark status
     */
    private function safeCheckBookmarkStatus(Post $post)
    {
        try {
            if (!auth()->check()) {
                return false;
            }

            // Check if bookmarks table exists
            if (!Schema::hasTable('bookmarks')) {
                Log::warning('bookmarks table does not exist');
                return false;
            }

            return auth()->user()->bookmarks()->where('post_id', $post->id)->exists();
        } catch (Exception $e) {
            Log::error('Error checking bookmark status: ' . $e->getMessage(), ['post_id' => $post->id]);
            return false; // Default to not bookmarked
        }
    }

    /**
     * Ultimate fallback for post detail
     */
    private function fallbackPostDetail(Post $post)
    {
        try {
            // Basic post data without complex relationships
            $comments = $this->fallbackComments();
            $relatedPosts = collect();
            $isBookmarked = false;

            return view('post-detail', compact('post', 'comments', 'relatedPosts', 'isBookmarked'));
        } catch (Exception $e) {
            Log::error('Critical error in fallback post detail: ' . $e->getMessage());
            
            // If even fallback fails, show 404
            abort(404, 'Article not available at the moment');
        }
    }

    /**
     * Display search results.
     */
    public function search(Request $request)
    {
        try {
            $query = $request->input('q');
            
            if (empty($query)) {
                return redirect()->route('all-news');
            }

            $posts = Post::where('status', 'published')
                        ->where(function($q) use ($query) {
                            $q->where('title', 'like', "%{$query}%")
                              ->orWhere('content', 'like', "%{$query}%");
                        })
                        ->with('user', 'category')
                        ->latest('published_at')
                        ->paginate(10);

            return view('search-results', compact('posts', 'query'));

        } catch (Exception $e) {
            Log::error('Search error: ' . $e->getMessage(), ['query' => $request->input('q')]);
            
            // Fallback to all news page
            return redirect()->route('all-news')->with('error', 'Terjadi kesalahan saat mencari. Silakan coba lagi.');
        }
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

    /**
     * Display all news with pagination and filters.
     */
    public function allNews(Request $request)
    {
        $query = Post::where('status', 'published')
                    ->with('user', 'category');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('published_at', $request->year);
        }

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%");
            });
        }

        // Order by latest published
        $query->latest('published_at');

        // Pagination - 10 posts per page
        $posts = $query->paginate(10);

        // Get categories for filter dropdown
        $categories = Category::orderBy('name')->get();

        // Get available years for filter
        $availableYears = Post::where('status', 'published')
                             ->selectRaw('YEAR(published_at) as year')
                             ->distinct()
                             ->orderBy('year', 'desc')
                             ->pluck('year');

        return view('all-news', compact('posts', 'categories', 'availableYears'));
    }
} 
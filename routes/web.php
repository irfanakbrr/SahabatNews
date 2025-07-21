<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController; // Mungkin belum dipakai, tapi aliasnya ada
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\AnalyticsController; // Tambahkan use
use App\Http\Controllers\CommentController; // Tambahkan ini di atas jika belum ada
use App\Http\Controllers\AiGenerationController; // Tambahkan controller AI
use Illuminate\Support\Str;
use App\Models\Category; // Pastikan ini ada
use App\Http\Controllers\QuranController;
use App\Http\Controllers\BookmarkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman Publik
Route::get('/', function () {
    // Ambil 11 post terbaru (1 utama, 3 samping, 7 bawah)
    $posts = \App\Models\Post::where('status', 'published')->latest('published_at')->take(11)->get();
    $mainPost = $posts->first();
    $sidePosts = $posts->slice(1, 3);
    $bottomPosts = $posts->slice(4, 7); // 7 post berikutnya (post 5-11)
    // Gunakan cache untuk kategori
    $categories = \Cache::remember('categories', 3600, function() {
        return \App\Models\Category::orderBy('name')->get();
    });
    return view('welcome', compact('posts', 'sidePosts', 'categories', 'mainPost', 'bottomPosts'));
})->name('home');

Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::get('/podcast', function () { return view('podcast'); })->name('podcast');
Route::get('/support', function () { return view('support'); })->name('support');
Route::get('/quran', [QuranController::class, 'index'])->name('quran.index');
Route::get('/quran/{nomor}', [QuranController::class, 'show'])->name('quran.show');
Route::get('/all-news', [PostController::class, 'allNews'])->name('all-news');

// Prayer times routes
Route::get('/prayer-times', [App\Http\Controllers\PrayerTimesController::class, 'index'])->name('prayer-times.index');
Route::post('/prayer-times/location', [App\Http\Controllers\PrayerTimesController::class, 'getByLocation'])->name('prayer-times.location');

// Bookmark routes
Route::middleware('auth')->group(function () {
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/toggle/{post}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
});

// Route::get('/analytics', function () { return view('analytics'); })->name('analytics'); // Hapus route lama
Route::get('/analytics', [AnalyticsController::class, 'index'])->middleware(['auth', 'verified', 'checkrole:admin,editor'])->name('analytics'); // Gunakan controller dan tambahkan middleware

// Halaman Kategori
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('category.show');

// Halaman Detail Post
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

// Route untuk menyimpan komentar dari halaman publik
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');

// Halaman Dashboard & Admin
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', function () {
        // TODO: Ambil data untuk statistik dashboard
        $postCount = \App\Models\Post::count();
        $userCount = \App\Models\User::count();
        $categoryCount = \App\Models\Category::count();
        return view('dashboard', compact('postCount', 'userCount', 'categoryCount'));
    })->name('dashboard');

    // Profile (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Manajemen Post (Admin & Editor)
    Route::middleware(['checkrole:admin,editor'])->group(function () {
        // Gunakan method admin yang ada di PostController:
        Route::get('posts', [PostController::class, 'indexAdmin'])->name('posts.index');
        Route::get('posts/create', [PostController::class, 'createAdmin'])->name('posts.create');
        Route::post('posts', [PostController::class, 'storeAdmin'])->name('posts.store');
        Route::get('posts/{post}/edit', [PostController::class, 'editAdmin'])->name('posts.edit');
        Route::get('posts/{post}/edit-simple', [PostController::class, 'editAdminSimple'])->name('posts.edit.simple');
        Route::patch('posts/{post}', [PostController::class, 'updateAdmin'])->name('posts.update');
        Route::delete('posts/{post}', [PostController::class, 'destroyAdmin'])->name('posts.destroy');

        // Route untuk AI Content Generation
        Route::post('/generate-image', [AiGenerationController::class, 'generateImage'])->name('image.generate');
        Route::post('/generate-article', [AiGenerationController::class, 'generateArticle'])->name('ai.generate.article');

        // Route untuk AI Assistant
        Route::post('/ai-assistant/ask', [App\Http\Controllers\Admin\AiAssistantController::class, 'ask'])->name('ai.assistant.ask');

        // ... existing code ...
        Route::get('pending-posts', [PostController::class, 'pendingPosts'])->name('posts.pending');
        Route::patch('posts/{post}/approve', [PostController::class, 'approvePost'])->name('posts.approve');
        Route::delete('posts/{post}/reject', [PostController::class, 'rejectPost'])->name('posts.reject');
    });

    // Manajemen Kategori & Komentar (Admin & Editor)
    Route::middleware(['checkrole:admin,editor'])->group(function () {
        Route::resource('categories', AdminCategoryController::class)->except(['show']);
        Route::resource('comments', App\Http\Controllers\Admin\CommentController::class)->except(['show']);
        Route::patch('comments/{comment}/approve', [App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
        Route::post('tinymce/upload', [App\Http\Controllers\Admin\TinyMCEController::class, 'uploadImage'])->name('tinymce.upload');
    });

    // Manajemen User & Pages (Hanya Admin)
    Route::middleware(['checkrole:admin'])->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('pages', App\Http\Controllers\Admin\PageController::class)->only(['index', 'edit', 'update']);
    });

    // Routes untuk user biasa mengelola artikel mereka
    Route::get('user-posts', [PostController::class, 'indexUser'])->name('userposts.index');
    Route::get('user-posts/create', [PostController::class, 'createUser'])->name('userposts.create');
    Route::post('user-posts', [PostController::class, 'storeUser'])->name('userposts.store');
    Route::get('user-posts/{post}/edit', [PostController::class, 'editUser'])->name('userposts.edit');
    Route::patch('user-posts/{post}', [PostController::class, 'updateUser'])->name('userposts.update');
    Route::delete('user-posts/{post}', [PostController::class, 'destroyUser'])->name('userposts.destroy');
});

// Route dashboard yang berbeda untuk setiap role (dengan error handling)
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    try {
        $user = auth()->user();
        
        // Safe role checking dengan error handling
        $hasAdminRole = false;
        try {
            $hasAdminRole = $user && $user->role && $user->hasAnyRole(['admin', 'editor']);
        } catch (Exception $e) {
            \Log::error('Role check error in dashboard route: ' . $e->getMessage());
            $hasAdminRole = false;
        }
        
        // Redirect admin/editor ke halaman analytics
        if ($hasAdminRole) {
            try {
                return app(\App\Http\Controllers\AnalyticsController::class)->index();
            } catch (Exception $e) {
                \Log::error('AnalyticsController error in dashboard route: ' . $e->getMessage());
                // Fallback to simple dashboard jika AnalyticsController gagal
            }
        }
        
        // User biasa atau fallback ke dashboard sederhana
        $postCount = 0;
        $userCount = 0;
        $categoryCount = 0;
        
        try {
            $postCount = \App\Models\Post::count();
        } catch (Exception $e) {
            \Log::error('Error getting post count in dashboard: ' . $e->getMessage());
        }
        
        try {
            $userCount = \App\Models\User::count();
        } catch (Exception $e) {
            \Log::error('Error getting user count in dashboard: ' . $e->getMessage());
        }
        
        try {
            $categoryCount = \App\Models\Category::count();
        } catch (Exception $e) {
            \Log::error('Error getting category count in dashboard: ' . $e->getMessage());
        }
        
        return view('dashboard', compact('postCount', 'userCount', 'categoryCount'));
        
    } catch (Exception $e) {
        \Log::error('Critical error in dashboard route: ' . $e->getMessage());
        
        // Ultimate fallback dashboard
        return view('dashboard', [
            'postCount' => 0,
            'userCount' => 0,
            'categoryCount' => 0
        ]);
    }
})->name('dashboard');

// Route global agar route('profile.edit') selalu ada
Route::middleware(['auth', 'verified'])->get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');

// Route global agar route('profile.update') selalu ada
Route::middleware(['auth', 'verified'])->patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

// Route global agar route('profile.destroy') selalu ada
Route::middleware(['auth', 'verified'])->delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

// Socialite Routes
Route::get('/login/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('socialite.redirect');
Route::get('/login/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])->name('socialite.callback');

Route::get('/load-more-posts', function (\Illuminate\Http\Request $request) {
    $page = (int) $request->input('page', 1); // Request pertama dari JS adalah untuk halaman data ke-1
    $perPage = 6; // Jumlah post yang di-load setiap kali
    
    // 11 post sudah ditampilkan (1 main + 3 side + 7 bottom).
    // Halaman 1 (req pertama) -> skip 11, ambil 6
    // Halaman 2 (req kedua) -> skip 11 + 6, ambil 6
    $postsToSkip = 11 + ($page - 1) * $perPage;

    $posts = \App\Models\Post::where('status', 'published')
        ->latest('published_at')
        ->skip($postsToSkip)
        ->take($perPage)
        ->with('category') // Eager load untuk efisiensi
        ->get()
        ->map(function($post) {
            // Perbaiki logic image_url untuk menangani URL vs path storage
            $imageUrl = 'https://via.placeholder.com/400x250?text=No+Image';
            if ($post->image) {
                // Jika image sudah berupa URL (http/https), gunakan langsung
                if (Str::startsWith($post->image, ['http://', 'https://'])) {
                    $imageUrl = $post->image;
                } else {
                    // Jika image adalah path di storage, gunakan Storage::url
                    $imageUrl = \Storage::url($post->image);
                }
            }
            
            return [
                'slug' => $post->slug,
                'title' => $post->title,
                'image_url' => $imageUrl,
                'category' => $post->category ? ['name' => $post->category->name, 'color' => $post->category->color] : null,
                'published_at_formatted' => $post->published_at ? $post->published_at->format('d M Y') : '-',
            ];
        });
    return response()->json($posts);
})->name('load.more.posts');

Route::view('/terms', 'terms')->name('terms');
Route::view('/privacy', 'privacy')->name('privacy');

// Route untuk hasil pencarian
Route::get('/search', [PostController::class, 'search'])->name('search');

// API Routes for realtime views
Route::prefix('api')->group(function () {
    Route::get('/views/realtime', [App\Http\Controllers\Api\ViewsController::class, 'getRealtimeData'])->name('api.views.realtime');
    Route::get('/views/post/{postId}', [App\Http\Controllers\Api\ViewsController::class, 'getPostViews'])->name('api.views.post');
    Route::get('/views/dashboard', [App\Http\Controllers\Api\ViewsController::class, 'getDashboardSummary'])->name('api.views.dashboard');
});

// Test route for dashboard data
Route::get('/test-dashboard', function () {
    $controller = new \App\Http\Controllers\AnalyticsController();
    $view = $controller->index();
    
    // Extract data from view
    $viewData = $view->getData();
    
    return response()->json([
        'totalViewsAllPosts' => $viewData['totalViewsAllPosts'] ?? 'N/A',
        'todayVisitors' => $viewData['todayVisitors'] ?? 'N/A',
        'articlesToday' => $viewData['articlesToday'] ?? 'N/A',
        'pendingComments' => $viewData['pendingComments'] ?? 'N/A',
        'draftCount' => $viewData['draftCount'] ?? 'N/A',
        'viewName' => $view->getName()
    ]);
})->name('test.dashboard');

// Test route for admin dashboard view
Route::get('/test-admin-dashboard', function () {
    $totalViewsAllPosts = \App\Models\Post::sum('view_count');
    $todayVisitors = \DB::table('post_views')->whereDate('viewed_at', today())->distinct('ip_address')->count('ip_address');
    $articlesToday = \App\Models\Post::where('status', 'published')->whereDate('published_at', today())->count();
    $pendingComments = \App\Models\Comment::where('approved', false)->count();
    $draftCount = \App\Models\Post::where('status', 'draft')->count();
    
    return view('test-admin-dashboard', compact(
        'totalViewsAllPosts',
        'todayVisitors', 
        'articlesToday',
        'pendingComments',
        'draftCount'
    ));
})->name('test.admin.dashboard');

require __DIR__.'/auth.php';

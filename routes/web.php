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
    // Ambil 11 post terbaru (5 utama, 3 samping, 3 bawah)
    $posts = \App\Models\Post::where('status', 'published')->latest('published_at')->take(11)->get();
    $mainPost = $posts->first();
    $sidePosts = $posts->slice(1, 3);
    $bottomPosts = $posts->slice(4, 3); // 3 post berikutnya
    // Gunakan cache untuk kategori
    $categories = \Cache::remember('categories', 3600, function() {
        return \App\Models\Category::orderBy('name')->get();
    });
    return view('welcome', compact('posts', 'sidePosts', 'categories', 'mainPost', 'bottomPosts'));
})->name('home');

Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/contact', function () { return view('contact'); })->name('contact');
Route::get('/podcast', function () { return view('podcast'); })->name('podcast');
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
        Route::patch('posts/{post}', [PostController::class, 'updateAdmin'])->name('posts.update');
        Route::delete('posts/{post}', [PostController::class, 'destroyAdmin'])->name('posts.destroy');

        // Route untuk AI Content Generation
        Route::post('/generate-image', [AiGenerationController::class, 'generateImage'])->name('image.generate');
        Route::post('/generate-article', [AiGenerationController::class, 'generateArticle'])->name('ai.generate.article');
    });

    // Manajemen Kategori & Komentar (Hanya Admin)
    Route::middleware(['checkrole:admin'])->group(function () {
        Route::resource('categories', AdminCategoryController::class)->except(['show']);
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('comments', App\Http\Controllers\Admin\CommentController::class)->except(['show']);
        Route::patch('comments/{comment}/approve', [App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
        Route::resource('pages', App\Http\Controllers\Admin\PageController::class)->only(['index', 'edit', 'update']);
        Route::post('tinymce/upload', [App\Http\Controllers\Admin\TinyMCEController::class, 'uploadImage'])->name('tinymce.upload');
    });
});

// Route global agar route('dashboard') selalu ada
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    // Cache statistik dashboard selama 5 menit
    $dashboardStats = \Cache::remember('dashboard_stats', 300, function() {
    $postCount = \App\Models\Post::count();
    $userCount = \App\Models\User::count();
    $categoryCount = \App\Models\Category::count();
    $totalViews = \App\Models\Post::sum('view_count');
    $draftPostsCount = \App\Models\Post::where('status', 'draft')->count();
    // Data untuk chart Artikel Terpublish per Kategori
    $publishedPostsByCategory = \App\Models\Category::withCount([
        'posts' => fn($query) => $query->where('status', 'published')
    ])->having('posts_count', '>', 0)->orderBy('posts_count', 'desc')->get();
    $chartLabels = $publishedPostsByCategory->pluck('name');
    $chartData = $publishedPostsByCategory->pluck('posts_count');
    $chartColors = $publishedPostsByCategory->map(function ($category) {
            if (\Illuminate\Support\Str::startsWith($category->color, '#') || \Illuminate\Support\Str::startsWith($category->color, 'rgb')) {
            return $category->color;
            } elseif (\Illuminate\Support\Str::startsWith($category->color, 'bg-')) {
            $colorMap = [
                'bg-red-500' => '#EF4444', 'bg-blue-500' => '#3B82F6', 'bg-green-500' => '#22C55E',
                'bg-yellow-500' => '#EAB308', 'bg-indigo-500' => '#6366F1', 'bg-purple-500' => '#8B5CF6',
                'bg-pink-500' => '#EC4899', 'bg-gray-500' => '#6B7280', 'bg-sky-500' => '#0EA5E9',
                'bg-emerald-500' => '#10B981', 'bg-rose-500' => '#F43F5E', 'bg-orange-500' => '#F97316',
            ];
                return $colorMap[$category->color] ?? '#' . substr(md5(rand()), 0, 6);
        }
            return '#' . substr(md5($category->name), 0, 6);
        });
        return compact(
            'postCount', 'userCount', 'categoryCount', 'totalViews', 'draftPostsCount',
            'chartLabels', 'chartData', 'chartColors'
        );
    });
    extract($dashboardStats);
    return view('dashboard', compact(
        'postCount', 'userCount', 'categoryCount', 'totalViews', 'draftPostsCount',
        'chartLabels', 'chartData', 'chartColors'
    ));
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
    $page = $request->input('page', 2);
    $perPage = 5;
    $skip = ($page - 1) * $perPage + 7; // 7 = jumlah artikel awal yang sudah tampil
    $posts = \App\Models\Post::where('status', 'published')
        ->latest('published_at')
        ->skip($skip)
        ->take($perPage)
        ->with('category')
        ->get();
    return response()->json($posts);
});

require __DIR__.'/auth.php';

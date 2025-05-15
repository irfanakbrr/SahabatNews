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
    // TODO: Ambil data post terbaru untuk homepage
    $posts = \App\Models\Post::where('status', 'published')->latest('published_at')->take(5)->get();
    // Ambil juga 4 post sampingan (contoh: acak atau terbaru selain 5 utama)
    $sidePosts = \App\Models\Post::where('status', 'published')
                    ->whereNotIn('id', $posts->pluck('id')) // Jangan tampilkan yang sudah di list utama
                    ->latest('published_at')
                    ->take(3) // Ambil 3 untuk sisi kanan
                    ->get();
    $categories = Category::orderBy('name')->get(); // Ambil semua kategori
    return view('welcome', compact('posts', 'sidePosts', 'categories')); // Kirim categories ke view
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
    });

    // Manajemen Kategori & Komentar (Hanya Admin)
    Route::middleware(['checkrole:admin'])->group(function () {
        Route::resource('categories', AdminCategoryController::class)->except(['show']);
        Route::resource('users', AdminUserController::class)->except(['show']);
        Route::resource('comments', App\Http\Controllers\Admin\CommentController::class)->except(['show']);
        Route::resource('pages', App\Http\Controllers\Admin\PageController::class)->only(['index', 'edit', 'update']);
        Route::post('tinymce/upload', [App\Http\Controllers\Admin\TinyMCEController::class, 'uploadImage'])->name('tinymce.upload');
    });
});

// Route global agar route('dashboard') selalu ada
Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
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
        // Logika mapping warna sederhana (sesuaikan dengan kebutuhan Anda)
        if (Str::startsWith($category->color, '#') || Str::startsWith($category->color, 'rgb')) {
            return $category->color;
        } elseif (Str::startsWith($category->color, 'bg-')) {
            $colorMap = [
                'bg-red-500' => '#EF4444', 'bg-blue-500' => '#3B82F6', 'bg-green-500' => '#22C55E',
                'bg-yellow-500' => '#EAB308', 'bg-indigo-500' => '#6366F1', 'bg-purple-500' => '#8B5CF6',
                'bg-pink-500' => '#EC4899', 'bg-gray-500' => '#6B7280', 'bg-sky-500' => '#0EA5E9',
                'bg-emerald-500' => '#10B981', 'bg-rose-500' => '#F43F5E', 'bg-orange-500' => '#F97316',
            ];
            return $colorMap[$category->color] ?? '#' . substr(md5(rand()), 0, 6); // Warna acak jika tidak ketemu
        }
        return '#' . substr(md5($category->name), 0, 6); // Warna default acak berdasarkan nama
    });

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

require __DIR__.'/auth.php';

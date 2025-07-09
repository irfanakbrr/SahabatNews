<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB; // Untuk DB::raw
use Carbon\Carbon; // Untuk manipulasi tanggal

class AnalyticsController extends Controller
{
    public function index()
    {
        // Ambil 10 post terpopuler berdasarkan view_count
        $popularPosts = Post::where('status', 'published')
                            ->orderByDesc('view_count')
                            ->take(10)
                            ->get();

        $totalPublishedPosts = Post::where('status', 'published')->count();
        $totalViewsAllPosts = Post::sum('view_count');
        $totalComments = \App\Models\Comment::where('approved', true)->count();

        // Data untuk Line Chart: Tren Publikasi Artikel per Bulan (6 bulan terakhir)
        $monthlyPublishedPosts = Post::select(
                DB::raw('COUNT(id) as count'), 
                DB::raw('YEAR(published_at) as year'), 
                DB::raw('MONTH(published_at) as month')
            )
            ->where('status', 'published')
            ->where('published_at', '>=', Carbon::now()->subMonths(5)->startOfMonth()) // 6 bulan termasuk bulan ini
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $trendLabels = [];
        $trendData = [];
        $currentDate = Carbon::now()->subMonths(5)->startOfMonth();
        for ($i = 0; $i < 6; $i++) {
            $monthName = $currentDate->format('M Y'); // Format: Jan 2023
            $trendLabels[] = $monthName;
            
            $postData = $monthlyPublishedPosts->first(function ($item) use ($currentDate) {
                return $item->year == $currentDate->year && $item->month == $currentDate->month;
            });
            $trendData[] = $postData ? $postData->count : 0;
            $currentDate->addMonth();
        }

        // Hitung pengunjung unik hari ini (berdasarkan IP address di tabel visitors)
        // Sekarang tabel 'visitors' sudah ada dan middleware berjalan.
        $todayVisitors = DB::table('visitors')
            ->whereDate('visited_at', Carbon::today())
            ->distinct('ip_address')
            ->count('ip_address');
        
        // Statistik Penulis & Kategori
        $prolificAuthors = User::withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        $popularCategories = Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])
            ->having('posts_count', '>', 0)
            ->orderByDesc('posts_count')
            ->take(5)
            ->get();

        // Data baru untuk Dashboard Editor
        $articlesToday = Post::where('status', 'published')->whereDate('published_at', Carbon::today())->count();
        $pendingComments = \App\Models\Comment::where('approved', false)->count();
        $draftCount = Post::where('status', 'draft')->count();

        $popularPostsLast7Days = Post::where('status', 'published')
            ->where('published_at', '>=', Carbon::now()->subDays(7))
            ->orderByDesc('view_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'popularPosts', 
            'totalPublishedPosts', 
            'totalViewsAllPosts',
            'totalComments',
            'trendLabels',
            'trendData',
            'todayVisitors',
            'prolificAuthors',
            'popularCategories',
            'articlesToday',
            'pendingComments',
            'draftCount',
            'popularPostsLast7Days'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
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

        // Anda juga bisa menambahkan data lain di sini jika perlu
        // $totalViews = Post::sum('view_count');

        // Data untuk chart (jika ingin ada chart yang sama seperti di dashboard)
        // $publishedPostsByCategory = \App\Models\Category::withCount([
        //     'posts' => fn($query) => $query->where('status', 'published')
        // ])->having('posts_count', '>', 0)->orderBy('posts_count', 'desc')->get();

        // $chartLabels = $publishedPostsByCategory->pluck('name');
        // $chartData = $publishedPostsByCategory->pluck('posts_count');
        // $chartColors = $publishedPostsByCategory->map(function ($category) {
        //     if (Str::startsWith($category->color, '#') || Str::startsWith($category->color, 'rgb')) {
        //         return $category->color;
        //     } elseif (Str::startsWith($category->color, 'bg-')) {
        //         $colorMap = [
        //             'bg-red-500' => '#EF4444', 'bg-blue-500' => '#3B82F6', 'bg-green-500' => '#22C55E',
        //             'bg-yellow-500' => '#EAB308', 'bg-indigo-500' => '#6366F1', 'bg-purple-500' => '#8B5CF6',
        //             'bg-pink-500' => '#EC4899', 'bg-gray-500' => '#6B7280', 'bg-sky-500' => '#0EA5E9',
        //             'bg-emerald-500' => '#10B981', 'bg-rose-500' => '#F43F5E', 'bg-orange-500' => '#F97316',
        //         ];
        //         return $colorMap[$category->color] ?? '#' . substr(md5(rand()), 0, 6);
        //     }
        //     return '#' . substr(md5($category->name), 0, 6);
        // });

        return view('analytics', compact(
            'popularPosts', 
            'totalPublishedPosts', 
            'totalViewsAllPosts',
            'trendLabels',
            'trendData'
            // 'chartLabels', 'chartData', 'chartColors' // Uncomment jika ingin chart di analytics juga
        ));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostView;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ViewsController extends Controller
{
    /**
     * Get realtime analytics data
     */
    public function getRealtimeData()
    {
        // Total views today
        $todayViews = PostView::getTodayViews();
        
        // Total views this week
        $thisWeekViews = PostView::getThisWeekViews();
        
        // Total views this month
        $thisMonthViews = PostView::getThisMonthViews();
        
        // Total views all time
        $totalViews = Post::sum('view_count');
        
        // Popular posts today
        $popularPostsToday = Post::select('posts.*', DB::raw('COUNT(post_views.id) as today_views'))
            ->leftJoin('post_views', 'posts.id', '=', 'post_views.post_id')
            ->where('posts.status', 'published')
            ->whereDate('post_views.viewed_at', today())
            ->groupBy('posts.id')
            ->orderByDesc('today_views')
            ->limit(5)
            ->get();
        
        // Views per hour today (for chart)
        $hourlyViews = PostView::select(
            DB::raw('HOUR(viewed_at) as hour'),
            DB::raw('COUNT(*) as count')
        )
        ->whereDate('viewed_at', today())
        ->groupBy('hour')
        ->orderBy('hour')
        ->get();
        
        // Prepare hourly data (fill missing hours with 0)
        $hourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $hourData = $hourlyViews->where('hour', $i)->first();
            $hourlyData[] = [
                'hour' => $i,
                'count' => $hourData ? $hourData->count : 0
            ];
        }
        
        // Recent views (last 10)
        $recentViews = PostView::with('post')
            ->latest('viewed_at')
            ->limit(10)
            ->get();
        
        return response()->json([
            'today_views' => $todayViews,
            'this_week_views' => $thisWeekViews,
            'this_month_views' => $thisMonthViews,
            'total_views' => $totalViews,
            'popular_posts_today' => $popularPostsToday,
            'hourly_views' => $hourlyData,
            'recent_views' => $recentViews,
            'last_updated' => now()->format('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Get views for specific post
     */
    public function getPostViews($postId)
    {
        $post = Post::findOrFail($postId);
        
        $todayViews = PostView::where('post_id', $postId)
            ->whereDate('viewed_at', today())
            ->count();
            
        $thisWeekViews = PostView::where('post_id', $postId)
            ->whereBetween('viewed_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();
            
        $thisMonthViews = PostView::where('post_id', $postId)
            ->whereBetween('viewed_at', [
                now()->startOfMonth(),
                now()->endOfMonth()
            ])
            ->count();
        
        return response()->json([
            'post_id' => $postId,
            'total_views' => $post->view_count,
            'today_views' => $todayViews,
            'this_week_views' => $thisWeekViews,
            'this_month_views' => $thisMonthViews
        ]);
    }
    
    /**
     * Get dashboard summary
     */
    public function getDashboardSummary()
    {
        $totalPosts = Post::where('status', 'published')->count();
        $totalViews = Post::sum('view_count');
        $todayViews = PostView::getTodayViews();
        $draftPosts = Post::where('status', 'draft')->count();
        
        // Views trend (last 7 days)
        $dailyViews = PostView::select(
            DB::raw('DATE(viewed_at) as date'),
            DB::raw('COUNT(*) as count')
        )
        ->whereBetween('viewed_at', [
            now()->subDays(6)->startOfDay(),
            now()->endOfDay()
        ])
        ->groupBy('date')
        ->orderBy('date')
        ->get();
        
        return response()->json([
            'total_posts' => $totalPosts,
            'total_views' => $totalViews,
            'today_views' => $todayViews,
            'draft_posts' => $draftPosts,
            'daily_views' => $dailyViews
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class AnalyticsController extends Controller
{
    public function index()
    {
        try {
            // === SAFE DATA GATHERING WITH ERROR HANDLING ===
            
            // 1. Basic Post Statistics (Safe)
            $popularPosts = $this->safeGetPopularPosts();
            $totalPublishedPosts = $this->safeGetPublishedPostsCount();
            $totalViewsAllPosts = $this->safeGetTotalViews();
            $totalComments = $this->safeGetTotalComments();

            // 2. Today Visitors (Most likely to fail in shared hosting)
            $todayVisitors = $this->safeGetTodayVisitors();

            // 3. Trend Data (Safe with fallback)
            [$trendLabels, $trendData] = $this->safeGetTrendData();

            // 4. Author and Category Statistics (Safe)
            $prolificAuthors = $this->safeGetProlificAuthors();
            $popularCategories = $this->safeGetPopularCategories();

            // 5. Dashboard Editor Data (Safe)
            $articlesToday = $this->safeGetArticlesToday();
            $pendingComments = $this->safeGetPendingComments();
            $draftCount = $this->safeGetDraftCount();
            $popularPostsLast7Days = $this->safeGetPopularPostsLast7Days();

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

        } catch (Exception $e) {
            // Log error for debugging
            Log::error('AnalyticsController error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return ultimate fallback dashboard
            return $this->fallbackDashboard();
        }
    }

    /**
     * Safe method to get popular posts
     */
    private function safeGetPopularPosts()
    {
        try {
            return Post::where('status', 'published')
                        ->orderByDesc('view_count')
                        ->take(10)
                        ->get();
        } catch (Exception $e) {
            Log::error('Error getting popular posts: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Safe method to get published posts count
     */
    private function safeGetPublishedPostsCount()
    {
        try {
            return Post::where('status', 'published')->count();
        } catch (Exception $e) {
            Log::error('Error getting published posts count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safe method to get total views
     */
    private function safeGetTotalViews()
    {
        try {
            return Post::sum('view_count') ?? 0;
        } catch (Exception $e) {
            Log::error('Error getting total views: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safe method to get total comments
     */
    private function safeGetTotalComments()
    {
        try {
            return \App\Models\Comment::where('approved', true)->count();
        } catch (Exception $e) {
            Log::error('Error getting total comments: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safe method to get today visitors (most problematic)
     */
    private function safeGetTodayVisitors()
    {
        try {
            // Check if post_views table exists
            if (!Schema::hasTable('post_views')) {
                Log::warning('post_views table does not exist, using fallback');
                return $this->fallbackTodayVisitors();
            }

            // Try to query post_views table
            return DB::table('post_views')
                ->whereDate('viewed_at', Carbon::today())
                ->distinct('ip_address')
                ->count('ip_address');

        } catch (Exception $e) {
            Log::error('Error getting today visitors: ' . $e->getMessage());
            return $this->fallbackTodayVisitors();
        }
    }

    /**
     * Fallback method for today visitors
     */
    private function fallbackTodayVisitors()
    {
        try {
            // Fallback: count posts published today
            return Post::where('status', 'published')
                ->whereDate('published_at', Carbon::today())
                ->count();
        } catch (Exception $e) {
            Log::error('Error in fallback today visitors: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safe method to get trend data
     */
    private function safeGetTrendData()
    {
        try {
            $monthlyPublishedPosts = Post::select(
                    DB::raw('COUNT(id) as count'), 
                    DB::raw('YEAR(published_at) as year'), 
                    DB::raw('MONTH(published_at) as month')
                )
                ->where('status', 'published')
                ->where('published_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();

            $trendLabels = [];
            $trendData = [];
            $currentDate = Carbon::now()->subMonths(5)->startOfMonth();
            
            for ($i = 0; $i < 6; $i++) {
                $monthName = $currentDate->format('M Y');
                $trendLabels[] = $monthName;
                
                $postData = $monthlyPublishedPosts->first(function ($item) use ($currentDate) {
                    return $item->year == $currentDate->year && $item->month == $currentDate->month;
                });
                $trendData[] = $postData ? $postData->count : 0;
                $currentDate->addMonth();
            }

            return [$trendLabels, $trendData];

        } catch (Exception $e) {
            Log::error('Error getting trend data: ' . $e->getMessage());
            return $this->fallbackTrendData();
        }
    }

    /**
     * Fallback trend data
     */
    private function fallbackTrendData()
    {
        $trendLabels = [];
        $trendData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $trendLabels[] = $date->format('M Y');
            $trendData[] = 0;
        }
        
        return [$trendLabels, $trendData];
    }

    /**
     * Safe method to get prolific authors
     */
    private function safeGetProlificAuthors()
    {
        try {
            return User::withCount(['posts' => fn($q) => $q->where('status', 'published')])
                ->having('posts_count', '>', 0)
                ->orderByDesc('posts_count')
                ->take(5)
                ->get();
        } catch (Exception $e) {
            Log::error('Error getting prolific authors: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Safe method to get popular categories
     */
    private function safeGetPopularCategories()
    {
        try {
            return Category::withCount(['posts' => fn($q) => $q->where('status', 'published')])
                ->having('posts_count', '>', 0)
                ->orderByDesc('posts_count')
                ->take(5)
                ->get();
        } catch (Exception $e) {
            Log::error('Error getting popular categories: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Safe method to get articles today
     */
    private function safeGetArticlesToday()
    {
        try {
            return Post::where('status', 'published')
                ->whereDate('published_at', Carbon::today())
                ->count();
        } catch (Exception $e) {
            Log::error('Error getting articles today: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safe method to get pending comments
     */
    private function safeGetPendingComments()
    {
        try {
            return \App\Models\Comment::where('approved', false)->count();
        } catch (Exception $e) {
            Log::error('Error getting pending comments: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safe method to get draft count
     */
    private function safeGetDraftCount()
    {
        try {
            return Post::where('status', 'draft')->count();
        } catch (Exception $e) {
            Log::error('Error getting draft count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Safe method to get popular posts last 7 days
     */
    private function safeGetPopularPostsLast7Days()
    {
        try {
            return Post::where('status', 'published')
                ->where('published_at', '>=', Carbon::now()->subDays(7))
                ->orderByDesc('view_count')
                ->take(5)
                ->get();
        } catch (Exception $e) {
            Log::error('Error getting popular posts last 7 days: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Ultimate fallback dashboard
     */
    private function fallbackDashboard()
    {
        return view('admin.dashboard', [
            'popularPosts' => collect(),
            'totalPublishedPosts' => 0,
            'totalViewsAllPosts' => 0,
            'totalComments' => 0,
            'trendLabels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'trendData' => [0, 0, 0, 0, 0, 0],
            'todayVisitors' => 0,
            'prolificAuthors' => collect(),
            'popularCategories' => collect(),
            'articlesToday' => 0,
            'pendingComments' => 0,
            'draftCount' => 0,
            'popularPostsLast7Days' => collect()
        ]);
    }
}

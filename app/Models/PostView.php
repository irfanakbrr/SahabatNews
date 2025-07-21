<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostView extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'ip_address',
        'user_agent',
        'session_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Track a view for a post
     */
    public static function trackView(Post $post, $request = null)
    {
        $ipAddress = $request ? $request->ip() : request()->ip();
        $userAgent = $request ? $request->userAgent() : request()->userAgent();
        $sessionId = session()->getId();

        // Check if this view should be counted (prevent duplicate views from same session/IP in short time)
        $recentView = static::where('post_id', $post->id)
            ->where(function ($query) use ($ipAddress, $sessionId) {
                $query->where('ip_address', $ipAddress)
                      ->orWhere('session_id', $sessionId);
            })
            ->where('viewed_at', '>=', now()->subMinutes(30)) // Prevent duplicate within 30 minutes
            ->exists();

        if (!$recentView) {
            // Create new view record
            static::create([
                'post_id' => $post->id,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'session_id' => $sessionId,
                'viewed_at' => now(),
            ]);

            // Update the post's view_count
            $post->increment('view_count');
        }
    }

    /**
     * Get total views for a post
     */
    public static function getTotalViewsForPost($postId)
    {
        return static::where('post_id', $postId)->count();
    }

    /**
     * Get views for today
     */
    public static function getTodayViews()
    {
        return static::whereDate('viewed_at', today())->count();
    }

    /**
     * Get views for this week
     */
    public static function getThisWeekViews()
    {
        return static::whereBetween('viewed_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
    }

    /**
     * Get views for this month
     */
    public static function getThisMonthViews()
    {
        return static::whereBetween('viewed_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->count();
    }
}

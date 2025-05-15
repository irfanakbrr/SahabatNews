<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str; // Untuk slug otomatis

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'user_id',
        'category_id',
        'status',
        'published_at',
        'slug',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('approved', true); // Hanya tampilkan komen yg disetujui
    }

    // Boot method untuk slug otomatis
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
                // Pastikan slug unik
                $originalSlug = $post->slug;
                $count = 1;
                while (static::whereSlug($post->slug)->exists()) {
                    $post->slug = "{$originalSlug}-" . $count++;
                }
            }
        });

         static::updating(function ($post) {
             if ($post->isDirty('title') && empty($post->slug)) { // Atau jika ingin slug selalu update saat title berubah
                 $post->slug = Str::slug($post->title);
                 $originalSlug = $post->slug;
                 $count = 1;
                 while (static::whereSlug($post->slug)->where('id', '!=', $post->id)->exists()) {
                     $post->slug = "{$originalSlug}-" . $count++;
                 }
             }
         });
    }

    // Opsional: Jika ingin menggunakan slug untuk URL
    public function getRouteKeyName()
    {
        return 'slug';
    }
} 
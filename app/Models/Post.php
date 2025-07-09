<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str; // Untuk slug otomatis
use Laravel\Scout\Searchable; // Impor trait Searchable

class Post extends Model
{
    use HasFactory, Searchable; // Gunakan trait

    protected $fillable = [
        'title',
        'content',
        'image',
        'user_id',
        'category_id',
        'status',
        'published_at',
        'slug',
        'view_count',
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

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'title' => $this->title,
            'content' => strip_tags($this->content), // Hapus tag HTML dari konten
        ];
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PostRevision::class)->latest();
    }
} 
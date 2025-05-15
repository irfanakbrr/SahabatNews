<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'slug']; // Tambahkan slug jika akan digunakan

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Opsional: Jika ingin menggunakan slug untuk URL
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }
} 
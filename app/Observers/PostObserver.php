<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\PostRevision;
use Illuminate\Support\Facades\Auth;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "updating" event.
     */
    public function updating(Post $post): void
    {
        // Hanya simpan revisi jika judul atau kontennya benar-benar berubah
        if ($post->isDirty('title') || $post->isDirty('content')) {
            PostRevision::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'title' => $post->getOriginal('title'), // Simpan versi lama sebelum update
                'content' => $post->getOriginal('content'), // Simpan versi lama sebelum update
            ]);
        }
    }
}

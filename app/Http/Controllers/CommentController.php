<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\CommentPosted;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'approved' => true, // Asumsikan langsung disetujui untuk demo real-time
        ]);

        // Siarkan event ke semua listener
        broadcast(new CommentPosted($comment))->toOthers();

        // Notifikasi ke penulis post (jika berbeda) masih bisa dilakukan jika perlu,
        // dengan membuat Listener untuk event CommentPosted.
        // Untuk sekarang kita fokus pada real-time comment.

        // Kita tidak lagi mengembalikan back(), karena frontend akan menanganinya
        return response()->json(['status' => 'success', 'comment' => $comment->load('user')]);
    }
} 
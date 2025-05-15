<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Buat komentar baru
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = Auth::id(); // Pastikan user sudah login (ditangani middleware auth)
        $comment->post_id = $post->id;
        // Secara default, kolom 'approved' di model Comment akan false.
        // Admin akan menyetujui melalui panel admin.
        $comment->save();

        return back()->with('success', 'Komentar Anda berhasil dikirim dan akan dimoderasi.');
    }
} 
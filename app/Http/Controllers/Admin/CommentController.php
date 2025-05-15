<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments for moderation.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua komentar, urutkan yang belum diapprove duluan
        $comments = Comment::with(['user', 'post']) // Eager load relasi
                           ->orderBy('approved', 'asc') // Tampilkan yg false (pending) dulu
                           ->latest()
                           ->paginate(15);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Approve the specified comment.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Comment $comment)
    {
        $comment->update(['approved' => true]);
        return redirect()->route('dashboard.comments.index')->with('success', 'Komentar berhasil disetujui.');
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('dashboard.comments.index')->with('success', 'Komentar berhasil dihapus.');
    }
}

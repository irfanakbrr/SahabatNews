<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarkedPosts = Auth::user()->bookmarks()->with('post.category', 'post.user')->latest()->paginate(10);
        
        return view('bookmarks.index', compact('bookmarkedPosts'));
    }

    public function toggle(Post $post)
    {
        $bookmark = Auth::user()->bookmarks()->where('post_id', $post->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            $status = 'removed';
        } else {
            Auth::user()->bookmarks()->create(['post_id' => $post->id]);
            $status = 'added';
        }

        return response()->json(['status' => $status]);
    }
}

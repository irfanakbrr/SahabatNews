<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel untuk komentar real-time per post
Broadcast::channel('post.{postId}', function ($user, $postId) {
    // Izinkan semua user yang terotentikasi untuk mendengarkan channel ini.
    // Jika Anda ingin membatasi (misal: hanya user premium), logikanya bisa ditambahkan di sini.
    return Auth::check();
});

// Channel untuk aktivitas admin
Broadcast::channel('admin-activity', function ($user) {
    // Hanya izinkan admin dan editor untuk mendengarkan.
    return $user->hasAnyRole(['admin', 'editor']);
});

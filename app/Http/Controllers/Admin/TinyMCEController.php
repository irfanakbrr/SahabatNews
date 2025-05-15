<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TinyMCEController extends Controller
{
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Simpan ke storage/app/public/images/posts
            // Pastikan folder 'public/images/posts' sudah dibuat dan symlink storage sudah berjalan (php artisan storage:link)
            $path = $file->storeAs('public/images/posts', $filename);

            if ($path) {
                // URL yang akan dikembalikan ke TinyMCE
                $url = Storage::url($path); // Ini akan menghasilkan URL seperti /storage/images/posts/filename.jpg
                return response()->json(['location' => $url]);
            } else {
                return response()->json(['error' => 'Gagal menyimpan gambar.'], 500);
            }
        }

        return response()->json(['error' => 'Tidak ada file yang diupload.'], 400);
    }
}

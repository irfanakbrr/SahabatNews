<x-app-layout>
    {{-- Title bisa di-set via slot bernama jika diperlukan, atau langsung di head layout --}}
    {{-- <x-slot name="title">{{ $post->title }}</x-slot> --}}

    <article class="max-w-3xl mx-auto prose lg:prose-xl px-4 py-8 sm:py-12">
        <!-- Gambar Utama -->
        @if($post->image)
            <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="rounded-lg mb-4 sm:mb-6 w-full object-cover aspect-video">
        @else
             <img src="https://via.placeholder.com/1200x600?text=No+Image" alt="{{ $post->title }}" class="rounded-lg mb-4 sm:mb-6 w-full object-cover aspect-video">
        @endif

        <!-- Kategori & Judul -->
        <div class="mb-4 sm:mb-6">
            <a href="{{ route('category.show', $post->category) }}" class="inline-block {{ $post->category->color ?? 'bg-gray-500' }} text-white text-xs sm:text-sm font-semibold px-3 sm:px-4 py-1 rounded-full mb-2 transition hover:opacity-80">{{ $post->category->name }}</a>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold leading-tight mt-1">{{ $post->title }}</h1>
        </div>

        <!-- Info Penulis & Tanggal -->
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6 border-b pb-4">
            <span>Oleh: <strong>{{ $post->user->name }}</strong></span>
            <span>•</span>
            <span>{{ $post->published_at ? $post->published_at->format('d M Y, H:i') : 'Draft' }}</span>
            {{-- <span>•</span> --}}
            {{-- <span>Estimasi Baca: {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} Menit</span> --}} {{-- Estimasi waktu baca --}}
            <span>•</span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"></path></svg>
                {{ number_format($post->view_count ?? 0) }} views
            </span>
        </div>

        <!-- Isi Konten Artikel -->
        <div class="space-y-4 prose max-w-none">
            {!! nl2br(e($post->content)) !!} {{-- Tampilkan konten, ubah newline jadi <br>, escape HTML --}}
            {{-- Jika konten mengandung HTML (misal dari editor WYSIWYG), gunakan {!! $post->content !!} HATI-HATI XSS --}}
        </div>
        <!-- End Isi Konten Artikel -->

        <!-- Tombol Share -->
        <div class="mt-6 sm:mt-8 pt-4 border-t flex items-center gap-3">
            <span class="text-sm font-semibold">Bagikan:</span>
            {{-- Ganti # dengan URL share sesungguhnya --}}
            <a href="#" class="text-blue-600 hover:text-blue-800"><!-- Icon FB --> F</a>
            <a href="#" class="text-sky-500 hover:text-sky-700"><!-- Icon Twitter --> T</a>
            <a href="#" class="text-green-500 hover:text-green-700"><!-- Icon WhatsApp --> W</a>
            <a href="#" class="text-gray-500 hover:text-gray-700"><!-- Icon Link --> L</a>
        </div>

    </article>

    <!-- Komentar -->
    <section class="max-w-3xl mx-auto mt-8 sm:mt-12 px-4">
        <h2 class="text-xl sm:text-2xl font-semibold mb-4">Komentar ({{ $comments->total() }})</h2>
        <div class="space-y-6">
            @forelse ($comments as $comment)
                <div class="flex gap-3 {{ !$loop->first ? 'border-t pt-4' : '' }}">
                    {{-- Tampilkan avatar user jika ada --}}
                    <img src="{{ $comment->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name).'&background=random' }}" alt="{{ $comment->user->name }}" class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center font-bold">
                    <div>
                        <p class="font-semibold text-sm">{{ $comment->user->name }} <span class="text-gray-400 font-normal text-xs ml-1">• {{ $comment->created_at->diffForHumans() }}</span></p>
                        <p class="text-gray-700 text-sm mt-1">{{ $comment->content }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada komentar.</p>
            @endforelse

            <!-- Pagination Komentar -->
            @if($comments->hasPages())
                 <div class="mt-6">
                    {{ $comments->links() }}
                 </div>
            @endif

            <!-- Form Tambah Komentar -->
            @auth
                <form action="{{ route('comments.store', $post) }}" method="POST" class="border-t pt-6">
                     @csrf
                     <h3 class="text-lg font-semibold mb-2">Tinggalkan Komentar</h3>
                     <textarea name="content" rows="3" placeholder="Tulis komentar Anda..." required class="w-full px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-black mb-3 @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                     @error('content')
                        <p class="text-red-500 text-xs mt-1 mb-2">{{ $message }}</p>
                     @enderror
                     <button type="submit" class="bg-black text-white px-5 py-2 rounded-full font-semibold text-sm hover:bg-gray-800 transition">Kirim Komentar</button>
                </form>
            @else
                 <p class="text-sm text-gray-500 border-t pt-6">Silakan <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> atau <a href="{{ route('register') }}" class="text-blue-600 hover:underline">register</a> untuk meninggalkan komentar.</p>
            @endauth
        </div>
    </section>

    <!-- Berita Terkait -->
    @if($relatedPosts->count())
        <section class="max-w-6xl mx-auto mt-8 sm:mt-12 border-t pt-8 px-4 pb-12">
            <h2 class="text-xl sm:text-2xl font-semibold mb-4 text-center">Berita Terkait</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($relatedPosts as $relatedPost)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                         <a href="{{ route('posts.show', $relatedPost) }}">
                             <img src="{{ $relatedPost->image ? Storage::url($relatedPost->image) : 'https://via.placeholder.com/300x150?text=No+Image' }}" alt="{{ $relatedPost->title }}" class="w-full object-cover h-32 sm:h-40">
                         </a>
                         <div class="p-4 flex flex-col flex-1">
                             <h3 class="text-base font-semibold mb-2 flex-1">
                                 <a href="{{ route('posts.show', $relatedPost) }}" class="hover:text-black line-clamp-3">{{ $relatedPost->title }}</a>
                             </h3>
                             <span class="text-xs text-gray-500 mt-2">{{ $relatedPost->category->name }} • {{ $relatedPost->published_at ? $relatedPost->published_at->format('d M Y') : 'Draft' }}</span>
                         </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</x-app-layout> 
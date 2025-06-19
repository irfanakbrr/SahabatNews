<x-app-layout>
    {{-- Title bisa di-set via slot bernama jika diperlukan, atau langsung di head layout --}}
    {{-- <x-slot name="title">{{ $post->title }}</x-slot> --}}

    <article class="max-w-3xl mx-auto prose lg:prose-xl px-4 py-8 sm:py-12">
        <!-- Gambar Utama -->
        @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="rounded-lg mb-4 sm:mb-6 w-full object-cover aspect-video" loading="lazy">
        @else
             <img src="https://via.placeholder.com/1200x600?text=No+Image" alt="{{ $post->title }}" class="rounded-lg mb-4 sm:mb-6 w-full object-cover aspect-video" loading="lazy">
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
            {!! $post->content !!}
        </div>
        <!-- End Isi Konten Artikel -->

        <!-- Tombol Share -->
        <div class="mt-6 sm:mt-8 pt-4 border-t flex items-center gap-3">
            <span class="text-sm font-semibold">Bagikan:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener" class="text-blue-600 hover:text-blue-800" title="Bagikan ke Facebook">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.595 0 0 .592 0 1.326v21.348C0 23.408.595 24 1.325 24h11.495v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.797.143v3.24l-1.918.001c-1.504 0-1.797.715-1.797 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.408 24 22.674V1.326C24 .592 23.406 0 22.675 0z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="text-sky-500 hover:text-sky-700" title="Bagikan ke Twitter">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557a9.83 9.83 0 0 1-2.828.775 4.932 4.932 0 0 0 2.165-2.724c-.951.564-2.005.974-3.127 1.195a4.916 4.916 0 0 0-8.38 4.482C7.691 8.095 4.066 6.13 1.64 3.161c-.542.929-.856 2.01-.857 3.17 0 2.188 1.115 4.116 2.823 5.247a4.904 4.904 0 0 1-2.229-.616c-.054 2.281 1.581 4.415 3.949 4.89a4.936 4.936 0 0 1-2.224.084c.627 1.956 2.444 3.377 4.6 3.417A9.867 9.867 0 0 1 0 21.543a13.94 13.94 0 0 0 7.548 2.209c9.142 0 14.307-7.721 13.995-14.646A9.936 9.936 0 0 0 24 4.557z"/></svg>
            </a>
            <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . request()->fullUrl()) }}" target="_blank" rel="noopener" class="text-green-500 hover:text-green-700" title="Bagikan ke WhatsApp">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.52 3.478A11.826 11.826 0 0 0 12.004 0C5.373 0 .001 5.373.001 12c0 2.116.553 4.184 1.6 6.01L0 24l6.184-1.584A11.93 11.93 0 0 0 12.004 24c6.627 0 11.998-5.373 11.998-12 0-3.193-1.247-6.197-3.482-8.522zm-8.516 19.13c-1.843 0-3.675-.496-5.246-1.434l-.375-.222-3.674.942.982-3.584-.244-.369C2.1 15.6 1.574 13.822 1.574 12c0-5.753 4.677-10.43 10.43-10.43 2.788 0 5.406 1.087 7.377 3.06a10.37 10.37 0 0 1 3.053 7.37c0 5.753-4.677 10.43-10.43 10.43zm5.634-7.563c-.308-.154-1.822-.897-2.104-.998-.28-.102-.484-.154-.687.154-.202.308-.788.998-.967 1.205-.178.205-.356.231-.664.077-.308-.154-1.3-.479-2.477-1.527-.916-.817-1.535-1.826-1.715-2.134-.179-.308-.019-.474.135-.627.139-.138.308-.357.462-.535.154-.179.205-.308.308-.513.102-.205.051-.385-.025-.539-.077-.154-.687-1.658-.94-2.27-.247-.594-.5-.513-.687-.523-.178-.008-.385-.01-.593-.01-.205 0-.539.077-.822.385-.282.308-1.08 1.055-1.08 2.576 0 1.52 1.104 2.993 1.258 3.201.154.205 2.176 3.326 5.273 4.533.738.318 1.312.508 1.76.65.74.236 1.414.203 1.947.123.594-.087 1.822-.744 2.08-1.462.256-.718.256-1.334.179-1.462-.077-.128-.282-.205-.59-.359z"/></svg>
            </a>
            <a href="#" onclick="navigator.clipboard.writeText('{{ request()->fullUrl() }}'); return false;" class="text-gray-500 hover:text-gray-700" title="Salin Link">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 0 1 0 5.656m-3.656-3.656a4 4 0 0 1 5.656 0m-7.778 7.778a4 4 0 0 1 5.656 0l1.414-1.414a4 4 0 0 0 0-5.656m-3.656 3.656a4 4 0 0 0 0-5.656L7.05 7.05a4 4 0 0 1 5.656 0"/></svg>
            </a>
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
                             <img src="{{ $relatedPost->image ? Storage::url($relatedPost->image) : 'https://via.placeholder.com/300x150?text=No+Image' }}" alt="{{ $relatedPost->title }}" class="w-full object-cover h-32 sm:h-40" loading="lazy">
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
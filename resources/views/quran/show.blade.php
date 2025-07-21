@extends('layouts.main')

@section('title', $surah['nama_latin'] . ' - Al-Qur\'an')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Surah -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $surah['nama_latin'] }}</h1>
                <h2 class="text-4xl font-arabic mb-3">{{ $surah['nama'] }}</h2>
                <p class="text-green-100 mb-2">{{ $surah['arti'] }} • {{ $surah['jumlah_ayat'] }} ayat • {{ ucfirst($surah['tempat_turun']) }}</p>
            </div>
            <div class="text-right">
                <div class="bg-white text-green-600 rounded-full w-16 h-16 flex items-center justify-center font-bold text-xl">
                    {{ $surah['nomor'] }}
                </div>
            </div>
        </div>
        
        <!-- Audio Player -->
        @if(isset($surah['audio']))
            <div class="mt-4 p-4 bg-green-800 rounded-lg">
                <h3 class="text-lg font-semibold mb-2">🎵 Audio Murottal</h3>
                <audio controls class="w-full">
                    <source src="{{ $surah['audio'] }}" type="audio/mpeg">
                    Browser Anda tidak mendukung audio player.
                </audio>
            </div>
        @endif
    </div>

    <!-- Deskripsi Surah -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-xl font-bold text-gray-800 mb-3">Tentang Surah {{ $surah['nama_latin'] }}</h3>
        <div class="text-gray-600 leading-relaxed">
            {!! $surah['deskripsi'] !!}
        </div>
    </div>

    <!-- Ayat-ayat -->
    <div class="space-y-6">
        @foreach($surah['ayat'] as $ayat)
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-600" id="ayat-{{ $ayat['nomor'] }}">
                <div class="flex items-start justify-between mb-4">
                    <div class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold">
                        {{ $ayat['nomor'] }}
                    </div>
                    <button onclick="toggleAyatBookmark({{ $surah['nomor'] }}, {{ $ayat['nomor'] }})" 
                            class="ayat-bookmark-btn text-gray-400 hover:text-yellow-500 transition-colors duration-300"
                            data-surah="{{ $surah['nomor'] }}" 
                            data-ayat="{{ $ayat['nomor'] }}"
                            title="Bookmark ayat ini">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Teks Arab -->
                <div class="text-right mb-4">
                    <p class="text-3xl font-arabic leading-loose text-gray-800">{{ $ayat['ar'] }}</p>
                </div>
                
                <!-- Transliterasi -->
                <div class="mb-3">
                    <p class="text-sm text-gray-600 italic">{!! $ayat['tr'] !!}</p>
                </div>
                
                <!-- Terjemahan -->
                <div class="border-t pt-3">
                    <p class="text-gray-700 leading-relaxed">{{ $ayat['idn'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Ad Space -->
    <div class="mt-8">
        <x-ad-space class="!border-gray-200 !bg-gray-50 dark:!bg-gray-800/50" />
    </div>

    <!-- Navigasi Surah -->
    <div class="flex justify-between items-center mt-8 p-6 bg-gray-50 rounded-lg">
        @if(isset($surah['surat_sebelumnya']) && $surah['surat_sebelumnya'])
            <a href="{{ route('quran.show', $surah['surat_sebelumnya']['nomor']) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors duration-300">
                ← {{ $surah['surat_sebelumnya']['nama_latin'] }}
            </a>
        @else
            <div></div>
        @endif

        <a href="{{ route('quran.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors duration-300">
            Daftar Surah
        </a>

        @if(isset($surah['surat_selanjutnya']) && $surah['surat_selanjutnya'])
            <a href="{{ route('quran.show', $surah['surat_selanjutnya']['nomor']) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors duration-300">
                {{ $surah['surat_selanjutnya']['nama_latin'] }} →
            </a>
        @else
            <div></div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .font-arabic {
        font-family: 'Amiri', 'Arabic Typesetting', serif;
        line-height: 2;
    }
    
    .ayat-bookmark-btn.bookmarked {
        color: #f59e0b;
    }
    
    .ayat-bookmark-btn.bookmarked svg {
        fill: currentColor;
    }
</style>
@endpush

@push('scripts')
<script>
// Bookmark ayat functionality
function toggleAyatBookmark(surahNumber, ayatNumber) {
    const bookmarkBtn = document.querySelector(`[data-surah="${surahNumber}"][data-ayat="${ayatNumber}"]`);
    let bookmarks = JSON.parse(localStorage.getItem('ayat_bookmarks') || '{}');
    
    if (!bookmarks[surahNumber]) {
        bookmarks[surahNumber] = [];
    }
    
    const ayatIndex = bookmarks[surahNumber].indexOf(ayatNumber);
    
    if (ayatIndex > -1) {
        // Remove bookmark
        bookmarks[surahNumber].splice(ayatIndex, 1);
        bookmarkBtn.classList.remove('bookmarked');
    } else {
        // Add bookmark
        bookmarks[surahNumber].push(ayatNumber);
        bookmarkBtn.classList.add('bookmarked');
    }
    
    localStorage.setItem('ayat_bookmarks', JSON.stringify(bookmarks));
}

// Load ayat bookmarks on page load
document.addEventListener('DOMContentLoaded', function() {
    const bookmarks = JSON.parse(localStorage.getItem('ayat_bookmarks') || '{}');
    const currentSurah = {{ $surah['nomor'] }};
    
    if (bookmarks[currentSurah]) {
        bookmarks[currentSurah].forEach(ayatNumber => {
            const bookmarkBtn = document.querySelector(`[data-surah="${currentSurah}"][data-ayat="${ayatNumber}"]`);
            if (bookmarkBtn) {
                bookmarkBtn.classList.add('bookmarked');
            }
        });
    }
});
</script>
@endpush
@endsection 
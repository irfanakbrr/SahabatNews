@extends('layouts.main')

@section('title', 'Al-Qur\'an - Daftar Surah')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">🕌 Al-Qur'an</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Bacalah Al-Qur'an dengan mudah dan nyaman. Dilengkapi dengan audio murottal dari Syeikh Misyari Rasyid Al-'Afasi.
        </p>
    </div>

    <!-- Search Box -->
    <div class="max-w-md mx-auto mb-8">
        <div class="relative search-box">
            <input type="text" 
                   id="surahSearch" 
                   placeholder="Cari surah (nama atau nomor)..."
                   class="w-full px-4 py-3 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Tombol Lihat Bookmark -->
    <div class="flex justify-end mb-4">
        <button id="showBookmarkBtn" class="flex items-center bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-4 py-2 rounded shadow-sm text-sm font-medium transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
            Lihat Bookmark
        </button>
    </div>
    <!-- Section Bookmark Surah -->
    <div id="bookmarkSection" class="hidden mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Surah yang Ditandai</h2>
                <button id="closeBookmarkBtn" class="text-gray-400 hover:text-red-500 transition" title="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div id="bookmarkList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
            <div id="noBookmarkMsg" class="text-center py-8 hidden">
                <div class="text-6xl mb-4">📚</div>
                <h3 class="text-xl text-gray-600 mb-2">Belum ada surah yang di-bookmark</h3>
                <p class="text-gray-500 mb-4">Tandai surah favorit Anda untuk akses cepat.</p>
            </div>
        </div>
    </div>

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ $error }}
        </div>
    @endif

    @if(!empty($surahs))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="surahGrid">
            @foreach($surahs as $surah)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-gray-200 surah-card" 
                     data-surah-name="{{ strtolower($surah['nama_latin']) }}" 
                     data-surah-number="{{ $surah['nomor'] }}"
                     data-surah-meaning="{{ strtolower($surah['arti']) }}">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold">
                                {{ $surah['nomor'] }}
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                    {{ $surah['jumlah_ayat'] }} ayat
                                </span>
                                <button onclick="toggleBookmark({{ $surah['nomor'] }})" 
                                        class="bookmark-btn text-gray-400 hover:text-yellow-500 transition-colors duration-300"
                                        data-surah="{{ $surah['nomor'] }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-arabic text-right mb-2 text-gray-800">{{ $surah['nama'] }}</h3>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">{{ $surah['nama_latin'] }}</h4>
                        <p class="text-gray-600 text-sm mb-3">{{ $surah['arti'] }}</p>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 capitalize">{{ $surah['tempat_turun'] }}</span>
                            <a href="{{ route('quran.show', $surah['nomor']) }}" 
                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-300">
                                Baca Surah
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📖</div>
            <h3 class="text-xl text-gray-600 mb-2">Belum ada data surah</h3>
            <p class="text-gray-500">Silakan coba lagi nanti atau periksa koneksi internet Anda.</p>
        </div>
    @endif
</div>

@push('styles')
<style>
    .font-arabic {
        font-family: 'Amiri', 'Arabic Typesetting', serif;
        font-size: 1.5rem;
        line-height: 1.8;
    }
    
    .search-box {
        transition: all 0.3s ease;
    }
    
    .search-box:focus-within {
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        border-color: #10b981;
    }
    
    .bookmark-btn.bookmarked {
        color: #f59e0b;
    }
    
    .bookmark-btn.bookmarked svg {
        fill: currentColor;
    }
    
    .surah-card.hidden {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
// Search functionality
document.getElementById('surahSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const surahCards = document.querySelectorAll('.surah-card');
    
    surahCards.forEach(card => {
        const surahName = card.dataset.surahName;
        const surahNumber = card.dataset.surahNumber;
        const surahMeaning = card.dataset.surahMeaning;
        
        const isMatch = surahName.includes(searchTerm) || 
                       surahNumber.includes(searchTerm) || 
                       surahMeaning.includes(searchTerm);
        
        if (isMatch) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
});

// Bookmark functionality
function toggleBookmark(surahNumber) {
    const bookmarkBtn = document.querySelector(`[data-surah="${surahNumber}"]`);
    let bookmarks = JSON.parse(localStorage.getItem('quran_bookmarks') || '[]');
    
    if (bookmarks.includes(surahNumber)) {
        // Remove bookmark
        bookmarks = bookmarks.filter(id => id !== surahNumber);
        bookmarkBtn.classList.remove('bookmarked');
    } else {
        // Add bookmark
        bookmarks.push(surahNumber);
        bookmarkBtn.classList.add('bookmarked');
    }
    
    localStorage.setItem('quran_bookmarks', JSON.stringify(bookmarks));
}

// Load bookmarks on page load
document.addEventListener('DOMContentLoaded', function() {
    const bookmarks = JSON.parse(localStorage.getItem('quran_bookmarks') || '[]');
    
    bookmarks.forEach(surahNumber => {
        const bookmarkBtn = document.querySelector(`[data-surah="${surahNumber}"]`);
        if (bookmarkBtn) {
            bookmarkBtn.classList.add('bookmarked');
        }
    });
});

// Bookmark Section Logic
const showBookmarkBtn = document.getElementById('showBookmarkBtn');
const bookmarkSection = document.getElementById('bookmarkSection');
const closeBookmarkBtn = document.getElementById('closeBookmarkBtn');
const bookmarkList = document.getElementById('bookmarkList');
const noBookmarkMsg = document.getElementById('noBookmarkMsg');

showBookmarkBtn.addEventListener('click', function() {
    bookmarkSection.classList.remove('hidden');
    loadBookmarkList();
    window.scrollTo({ top: bookmarkSection.offsetTop - 80, behavior: 'smooth' });
});
if (closeBookmarkBtn) {
    closeBookmarkBtn.addEventListener('click', function() {
        bookmarkSection.classList.add('hidden');
    });
}

function loadBookmarkList() {
    bookmarkList.innerHTML = '';
    let bookmarks = JSON.parse(localStorage.getItem('quran_bookmarks') || '[]');
    if (!bookmarks.length) {
        noBookmarkMsg.classList.remove('hidden');
        bookmarkList.classList.add('hidden');
        return;
    }
    noBookmarkMsg.classList.add('hidden');
    bookmarkList.classList.remove('hidden');
    // Ambil data surah dari DOM
    bookmarks.forEach(surahNumber => {
        const surahCard = document.querySelector(`.surah-card[data-surah-number="${surahNumber}"]`);
        if (surahCard) {
            // Clone isi card, tapi ganti tombol bookmark jadi hapus
            const clone = surahCard.cloneNode(true);
            // Hapus tombol bookmark di clone
            const btn = clone.querySelector('.bookmark-btn');
            if (btn) btn.remove();
            // Tambah tombol hapus bookmark
            const removeBtn = document.createElement('button');
            removeBtn.className = 'ml-2 text-red-500 hover:text-red-700 text-xs font-medium';
            removeBtn.innerHTML = '<svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg> Hapus';
            removeBtn.onclick = function() { removeBookmark(surahNumber); };
            clone.querySelector('.flex.items-center.space-x-2').appendChild(removeBtn);
            bookmarkList.appendChild(clone);
        }
    });
}
function removeBookmark(surahNumber) {
    let bookmarks = JSON.parse(localStorage.getItem('quran_bookmarks') || '[]');
    bookmarks = bookmarks.filter(id => id !== surahNumber);
    localStorage.setItem('quran_bookmarks', JSON.stringify(bookmarks));
    // Update bookmark icon di daftar utama
    const btn = document.querySelector(`.bookmark-btn[data-surah="${surahNumber}"]`);
    if (btn) btn.classList.remove('bookmarked');
    loadBookmarkList();
}
</script>
@endpush
@endsection 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Scripts -->
    @vite(['resources/css/admin-new.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased"
      x-data="{
          sidebarOpen: (localStorage.getItem('sidebarOpen') === 'true') || false,
          aiPanelOpen: (localStorage.getItem('aiPanelOpen') === 'true') || false,
          isMobile: window.innerWidth < 1024,
          init() {
              this.$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val));
              this.$watch('aiPanelOpen', val => localStorage.setItem('aiPanelOpen', val));
              if (window.innerWidth < 1024) { this.sidebarOpen = false; }
          }
      }"
      x-init="init()">
    <div @resize.window="isMobile = window.innerWidth < 1024" class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        <aside :class="{ 'w-64': sidebarOpen, 'w-20': !sidebarOpen && !isMobile }" class="admin-sidebar fixed inset-y-0 left-0 z-30 flex flex-col text-white transform transition-all duration-300 ease-in-out lg:static lg:inset-0">
            <div class="flex items-center justify-center p-4 h-20 border-b border-white/10">
                <span class="text-2xl font-bold" x-show="sidebarOpen">SahabatNews</span>
                <i class='bx bxs-news text-3xl' x-show="!sidebarOpen"></i>
            </div>
            <nav class="mt-4 px-2 space-y-1 flex-1">
                <a href="{{ route('home') }}" target="_blank" class="sidebar-link flex items-center p-3 rounded-lg">
                    <i class='bx bx-globe text-2xl'></i>
                    <span class="ml-4 font-semibold" x-show="sidebarOpen">Lihat Situs</span>
                </a>
                <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center p-3 rounded-lg {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class='bx bxs-dashboard text-2xl'></i>
                    <span class="ml-4 font-semibold" x-show="sidebarOpen">Dashboard</span>
                </a>
                
                <div x-data="{ open: {{ request()->routeIs('dashboard.posts.*') ? 'true' : 'false' }} }" class="relative">
                    <a href="#" @click.prevent="open = !open" class="sidebar-link flex items-center justify-between w-full p-3 rounded-lg">
                        <div class="flex items-center">
                            <i class='bx bxs-file-doc text-2xl'></i>
                            <span class="ml-4 font-semibold" x-show="sidebarOpen">Artikel</span>
                        </div>
                        <i class='bx' :class="{'bx-chevron-down': !open, 'bx-chevron-up': open}" x-show="sidebarOpen"></i>
                    </a>
                    
                    <!-- Expanded Submenu -->
                    <div x-show="sidebarOpen && open" x-transition class="pt-1 pl-8 space-y-1">
                        <a href="{{ route('dashboard.posts.index') }}" class="block p-2 text-sm rounded-md hover:bg-white/20 {{ request()->routeIs('dashboard.posts.index') ? 'font-bold' : '' }}">
                            Daftar Artikel
                        </a>
                        <a href="{{ route('dashboard.posts.create') }}" class="block p-2 text-sm rounded-md hover:bg-white/20 {{ request()->routeIs('dashboard.posts.create') ? 'font-bold' : '' }}">
                            Tulis Artikel Baru
                        </a>
                    </div>

                    <!-- Collapsed Submenu (Fly-out) -->
                    <div x-show="!sidebarOpen && open" @click.away="open = false" x-transition class="absolute left-full top-0 w-48 ml-2 p-2 bg-purple-800 rounded-md shadow-lg z-20">
                        <a href="{{ route('dashboard.posts.index') }}" class="block p-2 text-sm text-white rounded-md hover:bg-purple-900">
                            Daftar Artikel
                        </a>
                        <a href="{{ route('dashboard.posts.create') }}" class="block p-2 text-sm text-white rounded-md hover:bg-purple-900">
                            Tulis Artikel Baru
                        </a>
                    </div>
                </div>

                <a href="{{ route('dashboard.categories.index') }}" class="sidebar-link flex items-center p-3 rounded-lg {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
                    <i class='bx bxs-purchase-tag text-2xl'></i>
                    <span class="ml-4 font-semibold" x-show="sidebarOpen">Kategori</span>
                </a>
                 <a href="{{ route('dashboard.comments.index') }}" class="sidebar-link flex items-center p-3 rounded-lg {{ request()->routeIs('dashboard.comments.*') ? 'active' : '' }}">
                    <i class='bx bxs-comment-dots text-2xl'></i>
                    <span class="ml-4 font-semibold" x-show="sidebarOpen">Komentar</span>
                </a>
                @if(Auth::user()->hasRole('admin'))
                <a href="{{ route('dashboard.users.index') }}" class="sidebar-link flex items-center p-3 rounded-lg {{ request()->routeIs('dashboard.users.*') ? 'active' : '' }}">
                    <i class='bx bxs-user-detail text-2xl'></i>
                    <span class="ml-4 font-semibold" x-show="sidebarOpen">Pengguna</span>
                </a>
                @endif
            </nav>
            <div class="w-full p-4 border-t border-white/10">
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="sidebar-link flex items-center p-3 rounded-lg">
                        <i class='bx bx-log-out text-2xl'></i>
                        <span class="ml-4 font-semibold" x-show="sidebarOpen">Logout</span>
                    </a>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
             <!-- Header -->
            <header class="flex items-center justify-between p-4 bg-white border-b">
                 <!-- Menu Toggle -->
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none">
                    <i class='bx bx-menu text-2xl'></i>
                </button>
                @yield('header-content')
            </header>
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6" style="background-color: #f0f2f5;">
                <div class="bg-white/30 backdrop-blur-lg p-6 rounded-2xl shadow-sm">
                    @yield('content')
                </div>
            </main>
        </div>

        <!-- AI Assistant Panel -->
        <div :class="{ 'w-96': aiPanelOpen, 'w-20': !aiPanelOpen }" class="hidden xl:flex flex-col h-screen bg-gray-50 border-l border-gray-200 transition-all duration-300 ease-in-out">
            <div class="p-4 font-bold text-lg border-b flex items-center justify-between bg-white h-20">
                <div x-show="aiPanelOpen" class="flex items-center">
                    <i class='bx bxs-bot text-purple-600 mr-2 text-2xl'></i>
                    <span>AI Sahabat News</span>
                </div>
                <div class="flex-1" x-show="!aiPanelOpen"></div>
                <div class="flex items-center space-x-2">
                    <button id="ai-clear-btn" title="Mulai sesi baru" class="text-gray-400 hover:text-gray-600" x-show="aiPanelOpen">
                        <i class='bx bx-refresh text-xl'></i>
                    </button>
                    <button @click="aiPanelOpen = !aiPanelOpen" title="Toggle AI Panel" class="text-gray-500 hover:text-gray-700">
                        <i class='bx text-2xl' :class="aiPanelOpen ? 'bx-chevrons-right' : 'bx-chevrons-left'"></i>
                    </button>
                </div>
            </div>
            
            <div x-show="aiPanelOpen" class="flex-1 flex flex-col" style="min-height: 0;">
                <div id="ai-chat-area" class="flex-1 p-4 space-y-4 overflow-y-auto">
                    <!-- Chat bubbles will be appended here -->
                    <div class="flex justify-start">
                        <div class="p-3 rounded-lg bg-green-100 text-gray-800 max-w-xs">
                            <p class="text-sm">Selamat datang! Saya adalah asisten AI Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-white border-t">
                    <div class="space-x-2 mb-2">
                        <button data-action="generate_titles" class="ai-action-btn px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800 hover:bg-blue-200">
                            Buat Judul
                        </button>
                         <button data-action="summarize" class="ai-action-btn px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 hover:bg-yellow-200">
                            Ringkas Teks
                        </button>
                        <button data-action="check_facts" class="ai-action-btn px-3 py-1 text-xs rounded-full bg-red-100 text-red-800 hover:bg-red-200">
                            Periksa Fakta
                        </button>
                    </div>
                    <form id="ai-form" class="relative">
                        <input type="text" id="ai-prompt-input" placeholder="Tanya sesuatu atau tempel teks..." class="w-full py-2 pl-4 pr-10 rounded-full border-gray-300 focus:ring-purple-500 focus:border-purple-500">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-full bg-purple-600 text-white hover:bg-purple-700">
                            <i class='bx bxs-paper-plane'></i>
                        </button>
                    </form>
                </div>
            </div>

            <div x-show="!aiPanelOpen" class="flex-1 flex items-center justify-center cursor-pointer" @click="aiPanelOpen = true">
                <div class="transform rotate-90 whitespace-nowrap text-center">
                    <i class='bx bxs-bot text-purple-600 text-2xl mb-2'></i>
                    <span class="font-semibold text-gray-600 tracking-wider">AI SahabatNews</span>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const aiForm = document.getElementById('ai-form');
            const aiInput = document.getElementById('ai-prompt-input');
            const aiChatArea = document.getElementById('ai-chat-area');
            const aiActionButtons = document.querySelectorAll('.ai-action-btn');
            const aiClearBtn = document.getElementById('ai-clear-btn');

            const addBubble = (content, type = 'ai') => {
                const bubbleWrapper = document.createElement('div');
                bubbleWrapper.className = `flex ${type === 'user' ? 'justify-end' : 'justify-start'}`;
                
                const bubble = document.createElement('div');
                bubble.className = `p-3 rounded-lg max-w-xs text-sm ${type === 'user' ? 'bg-gray-200 text-gray-800' : 'bg-green-100 text-gray-800'}`;
                bubble.innerHTML = content.replace(/\n/g, '<br>'); // Preserve line breaks
                
                bubbleWrapper.appendChild(bubble);
                aiChatArea.appendChild(bubbleWrapper);
                aiChatArea.scrollTop = aiChatArea.scrollHeight;
            };

            const askAI = async (prompt, action = null) => {
                if (!prompt.trim()) return;

                addBubble(prompt, 'user');
                aiInput.value = '';
                addBubble('<i class="bx bx-loader-alt bx-spin"></i> Sedang berpikir...', 'ai');

                try {
                    const response = await fetch('{{ route("dashboard.ai.assistant.ask") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ prompt, action })
                    });

                    // Remove "thinking" bubble
                    aiChatArea.removeChild(aiChatArea.lastChild);

                    if (!response.ok) {
                        throw new Error('Network response was not ok.');
                    }
                    const data = await response.json();
                    if(data.success) {
                        addBubble(data.response, 'ai');
                    } else {
                        addBubble(`Maaf, terjadi kesalahan: ${data.error || 'Unknown error'}`, 'ai');
                    }
                } catch (error) {
                    console.error('AI Fetch Error:', error);
                    aiChatArea.removeChild(aiChatArea.lastChild);
                    addBubble('Maaf, saya tidak dapat terhubung ke server AI saat ini.', 'ai');
                }
            };
            
            aiForm.addEventListener('submit', (e) => {
                e.preventDefault();
                askAI(aiInput.value);
            });

            aiActionButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const action = button.dataset.action;
                    const prompt = window.getSelection().toString().trim() || aiInput.value;
                     if (!prompt) {
                        alert('Silakan pilih teks atau ketik sesuatu di kotak input.');
                        return;
                    }
                    askAI(prompt, action);
                });
            });

            aiClearBtn.addEventListener('click', () => {
                aiChatArea.innerHTML = '';
                 addBubble('Sesi baru dimulai. Ada yang bisa saya bantu?', 'ai');
            });

            // Listen for Real-time Article Generation Notification
            const showToast = (message, url) => {
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = 'p-4 bg-green-600 text-white rounded-lg shadow-lg flex items-center space-x-3';
                toast.innerHTML = `
                    <i class='bx bxs-check-circle text-2xl'></i>
                    <div>
                        <p class="font-bold">Draf Selesai Dibuat!</p>
                        <p class="text-sm">${message}</p>
                        <a href="${url}" class="mt-1 inline-block text-sm font-semibold underline hover:text-green-200">Buka Draf</a>
                    </div>
                `;
                toastContainer.appendChild(toast);
                setTimeout(() => {
                    toast.remove();
                }, 7000);
            }

            @auth
            window.Echo.private('App.Models.User.{{ Auth::id() }}')
                .listen('.article.generated', (e) => {
                    console.log('Article generated event received:', e);
                    showToast(`"${e.title}" telah ditambahkan ke draf Anda.`, e.edit_url);
                });
            @endauth

            window.Echo.private('admin-activity')
                .listen('CommentPosted', (e) => {
                    console.log('Admin activity event received:', e);
                    if(feedPlaceholder) {
                        feedPlaceholder.style.display = 'none';
                    }

                    const newActivity = document.createElement('div');
                    newActivity.classList.add('flex', 'items-start', 'opacity-0', 'translate-x-4');
                    newActivity.innerHTML = `
                        <div class="p-2 bg-indigo-100 rounded-full mr-3">
                            <i class="bx bx-message-square-dots text-indigo-500"></i>
                        </div>
                        <div>
                            <p class="text-sm">
                                <span class="font-semibold">${e.comment.user.name}</span> mengomentari: <a href="/posts/${e.comment.post_slug}" target="_blank" class="font-semibold hover:underline">"${e.comment.post_title}"</a>.
                            </p>
                            <p class="text-xs text-gray-400">Baru saja</p>
                        </div>
                    `;
                    activityFeed.prepend(newActivity);
                    anime({ targets: newActivity, opacity: 1, translateX: 0, duration: 500, easing: 'easeOutExpo' });
                })
                .listen('.user.registered', (e) => {
                    console.log('User registered event received:', e);
                    if(feedPlaceholder) {
                        feedPlaceholder.style.display = 'none';
                    }

                    const newActivity = document.createElement('div');
                    newActivity.classList.add('flex', 'items-start', 'opacity-0', 'translate-x-4');
                    newActivity.innerHTML = `
                        <div class="p-2 bg-green-100 rounded-full mr-3">
                            <i class="bx bx-user-plus text-green-500"></i>
                        </div>
                        <div>
                            <p class="text-sm">
                                Pengguna baru <span class="font-semibold">${e.name}</span> (${e.email}) telah mendaftar.
                            </p>
                            <p class="text-xs text-gray-400">Baru saja</p>
                        </div>
                    `;
                    activityFeed.prepend(newActivity);
                    anime({ targets: newActivity, opacity: 1, translateX: 0, duration: 500, easing: 'easeOutExpo' });
                });
        });
    </script>
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 space-y-2"></div>
</body>
</html> 
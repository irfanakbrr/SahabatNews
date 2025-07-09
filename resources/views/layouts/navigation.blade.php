<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        {{-- <x-application-logo class="block h-9 w-auto fill-current text-gray-800" /> --}}
                        <span class="font-bold text-lg text-gray-800">SahabatNews Admin</span> {{-- Ganti dengan teks --}}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @auth
                        @if(auth()->user()->hasRole('user'))
                        <x-nav-link :href="route('dashboard.userposts.index')" :active="request()->routeIs('dashboard.userposts.*')">
                            {{ __('Artikel Saya') }}
                        </x-nav-link>
                        @endif
                        @if(auth()->user()->hasAnyRole(['admin', 'editor']))
                        <x-nav-link :href="route('dashboard.posts.index')" :active="request()->routeIs('dashboard.posts.*')">
                            {{ __('Manajemen Artikel') }}
                        </x-nav-link>
                        @endif
                        @if(auth()->user()->hasRole('admin'))
                        <x-nav-link :href="route('dashboard.categories.index')" :active="request()->routeIs('dashboard.categories.*')">
                            {{ __('Manajemen Kategori') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dashboard.users.index')" :active="request()->routeIs('dashboard.users.*')">
                            {{ __('Manajemen User') }}
                        </x-nav-link>
                        <x-nav-link :href="route('dashboard.comments.index')" :active="request()->routeIs('dashboard.comments.*')">
                            {{ __('Moderasi Komentar') }}
                        </x-nav-link>
                        @if(auth()->user()->hasAnyRole(['admin', 'editor']))
                        <x-nav-link :href="route('analytics')" :active="request()->routeIs('analytics')">
                            {{ __('Analytics') }}
                        </x-nav-link>
                        @endif
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- Notifications Dropdown -->
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <button class="relative inline-flex items-center p-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if(Auth::user()->unreadNotifications->count())
                                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                            {{ Auth::user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="p-2 font-semibold text-sm text-gray-700 border-b">Notifikasi</div>
                                @forelse (Auth::user()->unreadNotifications->take(5) as $notification)
                                    <x-dropdown-link href="{{ route('posts.show', $notification->data['post_slug']) }}#comments">
                                        <div class="text-xs text-gray-800">
                                            <strong>{{ $notification->data['commenter_name'] }}</strong> mengomentari:
                                            <span class="italic">"{{ Str::limit($notification->data['post_title'], 25) }}"</span>
                                        </div>
                                        <div class="text-right text-[10px] text-gray-400 mt-1">
                                            {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                        </div>
                                    </x-dropdown-link>
                                @empty
                                    <div class="p-4 text-sm text-gray-500 text-center">
                                        Tidak ada notifikasi baru.
                                    </div>
                                @endforelse
                                {{-- <div class="p-2 text-center text-xs text-gray-700 border-t">
                                    <a href="#" class="hover:underline">Lihat semua notifikasi</a>
                                </div> --}}
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard.profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ms-4 text-sm font-medium text-gray-500 hover:text-gray-700">Register</a>
                    @endif
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @auth
                @if(auth()->user()->hasRole('user'))
                <x-responsive-nav-link :href="route('dashboard.userposts.index')" :active="request()->routeIs('dashboard.userposts.*')">
                    {{ __('Artikel Saya') }}
                </x-responsive-nav-link>
                @endif
                @if(auth()->user()->hasAnyRole(['admin', 'editor']))
                <x-responsive-nav-link :href="route('dashboard.posts.index')" :active="request()->routeIs('dashboard.posts.*')">
                    {{ __('Manajemen Artikel') }}
                </x-responsive-nav-link>
                @endif
                @if(auth()->user()->hasRole('admin'))
                <x-responsive-nav-link :href="route('dashboard.categories.index')" :active="request()->routeIs('dashboard.categories.*')">
                    {{ __('Manajemen Kategori') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.users.index')" :active="request()->routeIs('dashboard.users.*')">
                    {{ __('Manajemen User') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('dashboard.comments.index')" :active="request()->routeIs('dashboard.comments.*')">
                    {{ __('Moderasi Komentar') }}
                </x-responsive-nav-link>
                @if(auth()->user()->hasAnyRole(['admin', 'editor']))
                <x-responsive-nav-link :href="route('analytics')" :active="request()->routeIs('analytics')">
                    {{ __('Analytics') }}
                </x-responsive-nav-link>
                @endif
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard.profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log In') }}
                    </x-responsive-nav-link>
                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>

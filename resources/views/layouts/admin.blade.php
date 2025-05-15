<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? config('app.name', 'Laravel') }} - Admin Panel</title>
        <meta name="description" content="SahabatNews Admin Panel" />

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" /> <!-- Pastikan favicon.ico ada di folder public -->

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        <!-- Core CSS -->
        <!-- Kita akan menggunakan Bootstrap yang sudah di-bundle via Vite -->

        <!-- Page CSS -->
        @stack('page-styles')
        <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">

        <!-- Helpers -->
        <!-- <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script> -->
        <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
        <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
        <!-- <script src="{{ asset('assets/js/config.js') }}"></script> -->

        <!-- Scripts -->
        @vite(['resources/scss/admin.scss', 'resources/js/app.js'])
    </head>

    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container d-flex">
                <!-- Menu -->
                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="width: 260px; transition: width 0.3s ease-out;">
                    <div class="app-brand demo">
                        <a href="{{ route('dashboard') }}" class="app-brand-link">
                            <span class="app-brand-logo demo">
                                <!-- GANTI DENGAN LOGO ANDA -->
                                <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <defs>
                                        <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1" fill="#696cff"></path>
                                        <path d="M5.47320593,6.00457225 C4.05321814,8.21623821 2.4946806,10.5321102 0.722781199,12.6079061 C0.459184041,12.8690964 0.342988619,13.1872264 0.436590096,13.4473797 C0.718618035,13.9413525 1.38466836,14.5247999 3.01630405,14.5247999 C3.74990585,14.5247999 4.33550526,14.2428626 4.9830526,13.7253052 C5.63059995,13.2077478 7.02185884,12.1474321 9.45244159,10.051711 C11.7430846,7.38446712 C12.4733968,6.05557312 12.7586363,4.6600872 12.4394639,3.28177995 C11.801997,1.86763288 10.9522587,1.24614547 C7.60739926,0.0375607593 C6.03883169,0.0375607593 4.59342836,0.530032734 3.51028779,1.43502001 L2.82971673,2.26414947 C2.2518767,3.22885809 1.45510841,4.77302653 0.978866391,6.00055835L0.856831121,6.35584804C0.617270398,7.79924698 0.401249658,9.2192043 0.240605277,10.5321102 C0.0799608973,11.8450161 -0.0129934358,12.9992986 0.000996022299,13.7985356 C0.0899648174,14.8749584 0.279603735,16.7151748 1.64928693,19.8806324 C3.53588753,22.8422816 6.69431227,26.8844173 10.8031113,33.0919028 C12.5224304,35.6681741 12.7344097,36.0778173 L13.2295768,37.2090121 C13.5739442,38.0997313 13.7898006,39.0084742 13.7898006,39.0084742 C13.6319343,39.7074251 13.2295768,40.7979404 12.7344097,41.9291352 C11.5559203,43.7752531 10.8031113,44.9150498 C9.62295547,46.7351777 6.69431227,51.1225352 3.53588753,55.1870293 C1.64928693,58.1486787 0.240605277,67.4971883 0.978866391,72.0294441 L2.82971673,75.7757905 C3.51028779,76.60492 7.60739926,77.9999624 10.9522587,76.7913776 C12.4394639,74.7557431 11.7430846,70.6530559 9.45244159,67.9868059 C7.02185884,65.8910849 3.01630405,63.513717 0.436590096,64.5911373 C0.722781199,65.4306109 5.47320593,72.0319939 5.47320593,72.0319939Z" id="path-3" fill="#696cff" fill-opacity="0.2"></path>
                                        <use xlink:href="#path-1" fill="#696cff"></use>
                                        <use xlink:href="#path-3" fill="#696cff" fill-opacity="0.2"></use>
                                    </defs>
                                    <!-- Simplified SVG or use an <img> tag for your logo -->
                                </svg>
                                <!-- GANTI DENGAN LOGO ANDA -->
                            </span>
                            <span class="app-brand-text demo menu-text fw-bolder ms-2">SahabatNews</span>
                        </a>
                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                            <i class="bx bx-chevron-left bx-sm align-middle"></i>
                        </a>
                    </div>
                    <div class="menu-inner-shadow"></div>

                    <ul class="menu-inner py-1">
                        <!-- Dashboard -->
                        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div data-i18n="Analytics">Dashboard</div>
                            </a>
                        </li>

                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">Manajemen Konten</span>
                        </li>
                        <li class="menu-item {{ request()->routeIs('dashboard.posts.*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                                <div data-i18n="Layouts">Artikel</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item {{ request()->routeIs('dashboard.posts.index') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.posts.index') }}" class="menu-link">
                                        <div data-i18n="Without menu">Semua Artikel</div>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->routeIs('dashboard.posts.create') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard.posts.create') }}" class="menu-link">
                                        <div data-i18n="Without navbar">Tambah Baru</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                         <li class="menu-item {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.categories.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-collection"></i>
                                <div data-i18n="Basic">Kategori</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('dashboard.comments.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.comments.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-chat"></i>
                                <div data-i18n="Basic">Komentar</div>
                            </a>
                        </li>

                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Manajemen Pengguna</span></li>
                         <li class="menu-item {{ request()->routeIs('dashboard.users.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.users.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user"></i>
                                <div data-i18n="User interface">Users</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('dashboard.pages.*') ? 'active' : '' }}">
                            <a href="{{ route('dashboard.pages.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-file-blank"></i>
                                <div data-i18n="Pages">Halaman Statis</div>
                            </a>
                        </li>

                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Lainnya</span></li>
                        <li class="menu-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
                            <a href="{{ route('analytics') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-line-chart"></i>
                                <div data-i18n="User interface">Analytics</div>
                            </a>
                        </li>
                    </ul>
                </aside>
                <!-- / Menu -->

                <!-- Layout container -->
                <div class="layout-page flex-grow-1 d-flex flex-column min-vh-100">
                    <!-- Navbar -->
                    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="bx bx-menu bx-sm"></i>
                            </a>
                        </div>

                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <!-- Tombol Navigasi Admin -->
                            <div class="navbar-nav align-items-center me-auto">
                                <a href="{{ route('dashboard') }}" class="nav-item nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard Admin">
                                    <i class="bx bx-home-alt fs-5 me-1"></i>
                                    <span class="d-none d-md-inline">Dashboard</span>
                                </a>
                                <a href="{{ route('home') }}" class="nav-item nav-link" target="_blank" title="Lihat Halaman Depan Website">
                                    <i class="bx bx-globe fs-5 me-1"></i>
                                    <span class="d-none d-md-inline">Lihat Situs</span>
                                </a>
                            </div>
                            <!-- /Tombol Navigasi Admin -->
                            
                            <!-- Search -->
                            <div class="navbar-nav align-items-center">
                                <div class="nav-item navbar-search-wrapper d-flex align-items-center">
                                    <i class="bx bx-search fs-4 lh-0"></i>
                                    <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." aria-label="Search..." />
                                </div>
                            </div>
                            <!-- /Search -->

                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <!-- User Name (Moved into dropdown for Sneat style) -->
                                <!-- <li class="nav-item lh-1 me-3 d-none d-xl-block">
                                    <span class="text-body">{{ Auth::user()->name }}</span>
                                </li> -->

                                <!-- User Dropdown -->
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://via.placeholder.com/40/696cff/fff?text='.strtoupper(substr(Auth::user()->name, 0, 1)) }}" alt class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar avatar-online">
                                                            <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://via.placeholder.com/40/696cff/fff?text='.strtoupper(substr(Auth::user()->name, 0, 1)) }}" alt class="w-px-40 h-auto rounded-circle" />
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                                        <small class="text-muted">{{ Auth::user()->role ? ucfirst(Auth::user()->role->name) : 'User' }}</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                                <i class="bx bx-user me-2"></i>
                                                <span class="align-middle">Profil Saya</span>
                                            </a>
                                        </li>
                                        {{-- Jika ada halaman settings terpisah --}}
                                        {{-- <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="bx bx-cog me-2"></i>
                                                <span class="align-middle">Settings</span>
                                            </a>
                                        </li> --}}
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                                    <i class="bx bx-power-off me-2"></i>
                                                    <span class="align-middle">Log Out</span>
                                                </a>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ User -->
                            </ul>
                        </div>
                    </nav>
                    <!-- / Navbar -->

                    <!-- Content wrapper -->
                    <div class="content-wrapper flex-grow-1">
                        <!-- Content -->
                        <div class="container-xxl flex-grow-1 container-p-y">
                            @isset($header)
                            <div class="row mb-4">
                                <div class="col">
                                    <!-- Ganti kelas text-gray-800 dark:text-gray-200 dengan kelas Bootstrap jika perlu -->
                                    <h4 class="fw-bold py-3 mb-0">@yield('header')</h4> 
                                </div>
                            </div>
                            @endisset

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @yield('content')
                        </div>
                        <!-- / Content -->

                        <!-- Footer -->
                        <footer class="content-footer footer bg-footer-theme mt-auto">
                            <div class="container-xxl d-flex flex-wrap justify-content-center py-2">
                                <div class="mb-2 mb-md-0">
                                    © <script>document.write(new Date().getFullYear());</script>
                                    , made with ❤️ by
                                    <a href="{{ route('home') }}" target="_blank" class="footer-link fw-bolder">SahabatNews Team</a>.
                                </div>
                                {{-- Opsional: Link tambahan bisa dihapus jika tidak perlu --}}
                                {{-- <div class="d-none d-lg-inline-block">
                                    <a href="#" class="footer-link me-4" target="_blank">License</a>
                                    <a href="#" target="_blank" class="footer-link me-4">Documentation</a>
                                    <a href="#" target="_blank" class="footer-link">Support</a>
                                </div> --}}
                            </div>
                        </footer>
                        <!-- / Footer -->

                        <div class="content-backdrop fade"></div>
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <!-- / Layout wrapper -->

        @stack('page-scripts')
        {{-- <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script> --}} {{-- TinyMCE Dihapus --}}
        <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const menuToggleButtons = document.querySelectorAll('.layout-menu-toggle');
                const htmlElement = document.documentElement;

                menuToggleButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        htmlElement.classList.toggle('layout-menu-expanded');
                    });
                });
                
                const menuLinks = document.querySelectorAll('ul.menu-inner .menu-link.menu-toggle');
                menuLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const parentLi = this.closest('.menu-item');
                        if (parentLi) {
                            parentLi.classList.toggle('open');
                        }
                    });
                });
            });
        </script>
    </body>
</html> 
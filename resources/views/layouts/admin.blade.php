<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Satya Palapa')</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logoukm.png') }}">
    <script>
        // Toggle Sidebar Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        
        // Close sidebar when clicking overlay
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar Mobile Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 lg:hidden hidden z-30" onclick="closeSidebar()"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white shadow-lg flex flex-col fixed lg:fixed h-screen border-r border-gray-200 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out lg:left-0 top-0">
            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 flex-shrink-0">
                <div class="flex items-center gap-3 mb-2">
                    <img src="{{ asset('assets/images/logoukm.png') }}" alt="Logo Satya Palapa" class="w-10 h-10 object-contain rounded-lg">
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">Satya Palapa</h1>
                        <p class="text-xs text-gray-500 font-medium">UKM Musik</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Section -->
            <nav class="flex-1 px-3 py-5 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-50">
                <!-- Main Menu -->
                <ul class="space-y-1">
                    {{-- Dashboard: Tampil untuk semua role --}}
                    <li class="mb-4">
                        <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium {{ request()->routeIs('admin.index') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 shadow-md rounded-xl' : 'hover:bg-yellow-50 rounded-lg transition-colors' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span class="text-sm">Dashboard</span>
                        </a>
                    </li>
                    
                    {{-- Super Admin Only: Kelola User --}}
                    @if(Auth::check() && Auth::user()->isSuperAdmin())
                    <li>
                        <p class="px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-wide">Sistem</p>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 shadow-md rounded-xl' : 'hover:bg-yellow-50 rounded-lg transition-colors' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="text-sm">Kelola User</span>
                        </a>
                    </li>
                    @endif
                    
                    {{-- Pengurus Only: Menu operasional --}}
                    @if(Auth::check() && Auth::user()->isPengurus())
                    <!-- Divider with label -->
                    <li class="mt-6 mb-3">
                        <p class="px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-wide">Operasional</p>
                    </li>

                    <li>
                        <a href="{{ route('admin.diklat.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium {{ request()->routeIs('admin.diklat.*') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 shadow-md rounded-xl' : 'hover:bg-yellow-50 rounded-lg transition-colors' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            <span class="text-sm">Pendaftaran Diklat</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.members.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium {{ request()->routeIs('admin.members.*') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 shadow-md rounded-xl' : 'hover:bg-yellow-50 rounded-lg transition-colors' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="text-sm">Data Anggota UKM</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.board.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium {{ request()->routeIs('admin.board.*') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 shadow-md rounded-xl' : 'hover:bg-yellow-50 rounded-lg transition-colors' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-sm">Struktur Pengurus</span>
                        </a>
                    </li>

                    <!-- Divider -->
                    <li class="mt-6 mb-3">
                        <p class="px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-wide">Administratif</p>
                    </li>

                    <li>
                        <a href="{{ route('admin.templates.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium {{ request()->routeIs('admin.templates.*') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 shadow-md rounded-xl' : 'hover:bg-yellow-50 rounded-lg transition-colors' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm">Template Surat</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.letters.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium {{ request()->routeIs('admin.letters.*') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 shadow-md rounded-xl' : 'hover:bg-yellow-50 rounded-lg transition-colors' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            <span class="text-sm">Arsip Surat</span>
                        </a>
                    </li>

                    <!-- Divider -->
                    <li class="mt-6 mb-3">
                        <p class="px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-wide">Keuangan & Aset</p>
                    </li>

                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium hover:bg-yellow-50 rounded-lg transition-colors opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm">Kelola Keuangan</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium hover:bg-yellow-50 rounded-lg transition-colors opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                            <span class="text-sm">Persewaan Alat</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium hover:bg-yellow-50 rounded-lg transition-colors opacity-50 cursor-not-allowed">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-sm">Persewaan Band</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.studio-bookings.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium hover:bg-yellow-50 rounded-lg transition-colors {{ request()->routeIs('admin.studio-bookings.*') ? 'bg-yellow-100 text-yellow-900' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-sm">Booking Studio</span>
                        </a>
                    </li>

                    <!-- Divider -->
                    <li class="mt-6 mb-3">
                        <p class="px-4 py-2 text-xs font-bold text-gray-500 uppercase tracking-wide">Konten</p>
                    </li>

                    <li>
                        <a href="{{ route('admin.activities.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium {{ request()->routeIs('admin.activities.*') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 shadow-md rounded-xl' : 'hover:bg-yellow-50 rounded-lg transition-colors' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm">Galeri Kegiatan</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.achievements.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 font-medium {{ request()->routeIs('admin.achievements.*') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 shadow-md rounded-xl' : 'hover:bg-yellow-50 rounded-lg transition-colors' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            <span class="text-sm">Galeri Prestasi</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-600 font-medium hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="text-sm">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64 flex flex-col h-screen">
            <!-- Top Navigation Bar -->
            <header class="bg-yellow-300 shadow-sm border-b border-yellow-200 sticky top-0 z-20 flex-shrink-0">
                <div class="flex items-center justify-between px-4 lg:px-8 py-4">
                    <!-- Left: Menu Toggle & Title -->
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <!-- Mobile Menu Toggle -->
                        <button onclick="toggleSidebar()" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition-colors flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <!-- Page Title and Breadcrumb -->
                        <div class="min-w-0">
                            <h1 class="text-base lg:text-xl font-bold text-gray-800 truncate">@yield('header', 'Dashboard')</h1>
                            <p class="text-xs lg:text-sm text-gray-500 mt-0.5 truncate">@yield('breadcrumb', '')</p>
                        </div>
                    </div>

                    <!-- Right: User Menu -->
                    <div class="flex items-center gap-2 lg:gap-4 flex-shrink-0">
                        <!-- User Info - Hidden on Mobile -->
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->role_label ?? 'Guest' }}</p>
                        </div>

                        <!-- User Avatar Dropdown -->
                        <div class="dropdown dropdown-end">
                            <button class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold hover:shadow-lg transition-shadow cursor-pointer ring-2 ring-offset-2 ring-yellow-200">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </button>
                            <ul class="dropdown-content z-50 menu p-2 shadow bg-white rounded-lg w-52 border border-gray-200">
                                <li class="menu-title"><span class="text-gray-700">Akun</span></li>
                                <li><a href="{{ route('admin.users.edit', Auth::id()) }}" class="text-sm hover:bg-yellow-50">Profil Saya</a></li>
                                <li><a href="#" class="text-sm hover:bg-yellow-50">Pengaturan</a></li>
                                <li class="divider m-0"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="p-0">
                                        @csrf
                                        <button type="submit" class="text-red-600 text-sm hover:bg-red-50 w-full text-left">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>

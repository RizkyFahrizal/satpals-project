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
</head>
<body class="bg-yellow-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg flex flex-col fixed h-full border-r border-yellow-200">
            <!-- Logo -->
            <div class="p-6 border-b border-yellow-200">
                <h1 class="text-2xl font-bold text-gray-800">Satya Palapa</h1>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-4 overflow-y-auto">
                <ul class="space-y-1">
                    {{-- Dashboard: Tampil untuk semua role --}}
                    <li>
                        <a href="{{ route('admin.index') }}" class="flex items-center px-4 py-3 text-gray-700 {{ request()->routeIs('admin.index') ? 'bg-yellow-400 text-gray-900 font-semibold shadow-sm' : 'hover:bg-yellow-100' }} rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    
                    {{-- Super Admin Only: Kelola User --}}
                    @if(Auth::check() && Auth::user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 text-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-yellow-400 text-gray-900 font-semibold shadow-sm' : 'hover:bg-yellow-100' }} rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Kelola User
                        </a>
                    </li>
                    @endif
                    
                    {{-- Pengurus Only: Menu operasional (tidak tampil untuk Super Admin) --}}
                    @if(Auth::check() && Auth::user()->isPengurus())
                    <li>
                        <a href="{{ route('admin.diklat.index') }}" class="flex items-center px-4 py-3 text-gray-700 {{ request()->routeIs('admin.diklat.*') ? 'bg-yellow-400 text-gray-900 font-semibold shadow-sm' : 'hover:bg-yellow-100' }} rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Pendaftaran Diklat
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.members.index') }}" class="flex items-center px-4 py-3 text-gray-700 {{ request()->routeIs('admin.members.*') ? 'bg-yellow-400 text-gray-900 font-semibold shadow-sm' : 'hover:bg-yellow-100' }} rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Data Anggota UKM
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.board.index') }}" class="flex items-center px-4 py-3 text-gray-700 {{ request()->routeIs('admin.board.*') ? 'bg-yellow-400 text-gray-900 font-semibold shadow-sm' : 'hover:bg-yellow-100' }} rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Struktur Pengurus
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.templates.index') }}" class="flex items-center px-4 py-3 text-gray-700 {{ request()->routeIs('admin.templates.*') ? 'bg-yellow-400 text-gray-900 font-semibold shadow-sm' : 'hover:bg-yellow-100' }} rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Template Surat
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-yellow-100 rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kelola Keuangan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.letters.index') }}" class="flex items-center px-4 py-3 text-gray-700 {{ request()->routeIs('admin.letters.*') ? 'bg-yellow-400 text-gray-900 font-semibold shadow-sm' : 'hover:bg-yellow-100' }} rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                            Arsip Surat
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-yellow-100 rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                            Persewaan Alat
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-yellow-100 rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Persewaan Band
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center px-4 py-3 text-gray-700 hover:bg-yellow-100 rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Booking Studio
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.activities.index') }}" class="flex items-center px-4 py-3 text-gray-700 {{ request()->routeIs('admin.activities.*') ? 'bg-yellow-400 text-gray-900 font-semibold shadow-sm' : 'hover:bg-yellow-100' }} rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Galeri Kegiatan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.achievements.index') }}" class="flex items-center px-4 py-3 text-gray-700 {{ request()->routeIs('admin.achievements.*') ? 'bg-yellow-400 text-gray-900 font-semibold shadow-sm' : 'hover:bg-yellow-100' }} rounded-xl transition-all">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            Galeri Prestasi
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t border-yellow-200">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 text-red-600 hover:bg-red-50 rounded-xl transition-all">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Header -->
            <header class="bg-yellow-400 shadow-md px-8 py-5 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800">@yield('header', 'Dashboard UKM Musik Satya Palapa')</h2>
                <div class="text-right">
                    <p class="font-bold text-gray-800">Hai, {{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-sm text-gray-700">{{ Auth::user()->role_label ?? 'Guest' }}</p>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>
</html>

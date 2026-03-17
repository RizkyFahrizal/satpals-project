<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Satpals Project')</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-100">
    <div class="drawer">
        <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <!-- Navbar - Yellow Background -->
            <div class="navbar bg-yellow-400 text-gray-800 shadow-lg sticky top-0 z-50">
                <div class="flex-1">
                    <label for="my-drawer-3" class="btn btn-ghost btn-circle drawer-button lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-6 h-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                    <a href="/" class="btn btn-ghost normal-case text-2xl font-bold">Satya Palapa</a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="flex-none hidden lg:flex gap-2">
                    <ul class="menu menu-horizontal px-1 gap-2">
                        <li><a href="{{ route('profil.index') }}" class="text-gray-800 font-semibold hover:bg-yellow-300 {{ request()->routeIs('profil.index') ? 'bg-yellow-300' : '' }}">Profil UKM Musik</a></li>
                        <li><a href="{{ route('struktur.index') }}" class="text-gray-800 font-semibold hover:bg-yellow-300 {{ request()->routeIs('struktur.index') ? 'bg-yellow-300' : '' }}">Struktur Pengurus</a></li>
                        <li><a href="{{ route('diklat.register') }}" class="text-gray-800 font-semibold hover:bg-yellow-300 {{ request()->routeIs('diklat.register') ? 'bg-yellow-300' : '' }}">Pendaftaran Diklat</a></li>
                        <li>
                            <details>
                                <summary class="text-gray-800 font-semibold hover:bg-yellow-300">
                                    Galeri
                                </summary>
                                <ul class="p-2 bg-yellow-300 rounded-t-none border-t-0">
                                    <li><a href="{{ route('kegiatan.index') }}" class="text-gray-800 hover:bg-yellow-200 {{ request()->routeIs('kegiatan.*') ? 'bg-yellow-200' : '' }}">Kegiatan</a></li>
                                    <li><a href="{{ route('prestasi.index') }}" class="text-gray-800 hover:bg-yellow-200 {{ request()->routeIs('prestasi.*') ? 'bg-yellow-200' : '' }}">Prestasi</a></li>
                                </ul>
                            </details>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content - No Padding -->
            <div class="flex-1">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="footer footer-center p-4 bg-gray-800 text-white">
                <aside>
                    <p>Copyright © 2026 - All right reserved by Satya Palapa UKM</p>
                </aside>
            </footer>
        </div>

        <div class="drawer-side z-50">
            <label for="my-drawer-3" class="drawer-overlay"></label>
            <ul class="menu p-4 w-80 bg-yellow-300 text-gray-800 min-h-full">
                <li class="text-lg font-bold mb-2"><a href="/">Satya Palapa</a></li>
                <li><a href="{{ route('profil.index') }}" class="{{ request()->routeIs('profil.index') ? 'bg-yellow-400' : '' }}">Profil UKM Musik</a></li>
                <li><a href="{{ route('struktur.index') }}" class="{{ request()->routeIs('struktur.index') ? 'bg-yellow-400' : '' }}">Struktur Pengurus</a></li>
                <li><a href="{{ route('diklat.register') }}" class="{{ request()->routeIs('diklat.register') ? 'bg-yellow-400' : '' }}">Pendaftaran Diklat</a></li>
                <li><a href="{{ route('kegiatan.index') }}" class="{{ request()->routeIs('kegiatan.*') ? 'bg-yellow-400' : '' }}">Galeri Kegiatan</a></li>
                <li><a href="{{ route('prestasi.index') }}" class="{{ request()->routeIs('prestasi.*') ? 'bg-yellow-400' : '' }}">Galeri Prestasi</a></li>
                <li class="mt-4 border-t border-yellow-400 pt-4"><a href="/admin">Admin Dashboard</a></li>
            </ul>
        </div>
    </div>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>

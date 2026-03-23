<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Satpals Project')</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.7.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logoukm.png') }}">
    <style>
        /* Custom animations */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .navbar-animate {
            animation: slideDown 0.5s ease-out;
        }
        
        /* Smooth transitions */
        * {
            @apply transition-all duration-300;
        }
        
        /* Button hover effects */
        .btn-primary-hover:hover {
            @apply scale-105 shadow-xl;
        }
        
        .nav-link {
            @apply relative px-3 py-2 text-gray-800 font-semibold rounded-lg transition-all duration-300 hover:bg-yellow-300 hover:shadow-md;
        }
        
        .nav-link.active {
            @apply bg-yellow-300 shadow-md;
        }
    </style>
</head>
<body class="bg-white">
    <div class="flex flex-col min-h-screen">
        <!-- Navbar -->
        <nav class="navbar-animate sticky top-0 z-50 bg-gradient-to-r from-yellow-300 via-yellow-400 to-orange-300 shadow-lg border-b-4 border-yellow-400">
            <div class="container mx-auto px-4 w-full flex items-center justify-between h-20">
                <!-- Logo and Brand -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    <!-- Mobile Menu Toggle -->
                    <input type="checkbox" id="navbar-toggle" class="hidden" />
                    <label for="navbar-toggle" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-lg hover:bg-yellow-200 transition-colors cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 stroke-current text-gray-800">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                    
                    <!-- Brand -->
                    <a href="/" class="flex items-center gap-2 group">
                        <img src="{{ asset('assets/images/logoukm.png') }}" alt="Logo Satya Palapa" class="w-10 h-10 object-contain rounded-lg group-hover:scale-110 transition-transform">
                        <div class="hidden sm:block">
                            <h1 class="text-lg font-bold text-gray-800">Satya Palapa</h1>
                            <p class="text-xs text-gray-700">UKM Musik</p>
                        </div>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center gap-1 ml-auto">
                    <a href="{{ route('profil.index') }}" class="nav-link {{ request()->routeIs('profil.index') ? 'active' : '' }}">
                        Tentang
                    </a>
                    <a href="{{ route('struktur.index') }}" class="nav-link {{ request()->routeIs('struktur.index') ? 'active' : '' }}">
                        Tim
                    </a>
                    <a href="{{ route('diklat.register') }}" class="nav-link {{ request()->routeIs('diklat.register') ? 'active' : '' }}">
                        Diklat
                    </a>
                    <div class="dropdown dropdown-hover">
                        <button class="nav-link">
                            Galeri
                        </button>
                        <ul class="dropdown-content z-50 menu p-2 shadow bg-white rounded-xl w-48 border border-yellow-200">
                            <li><a href="{{ route('kegiatan.index') }}" class="text-gray-800 hover:bg-yellow-50 {{ request()->routeIs('kegiatan.*') ? 'bg-yellow-100' : '' }}">Kegiatan</a></li>
                            <li><a href="{{ route('prestasi.index') }}" class="text-gray-800 hover:bg-yellow-50 {{ request()->routeIs('prestasi.*') ? 'bg-yellow-100' : '' }}">Prestasi</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="lg:hidden">
                <div id="mobile-menu" class="fixed left-0 top-20 w-full bg-white shadow-lg transform -translate-x-full transition-transform duration-300 z-40">
                    <div class="flex flex-col p-4 gap-2">
                        <a href="{{ route('profil.index') }}" class="px-4 py-3 text-gray-800 font-semibold hover:bg-yellow-50 rounded-lg {{ request()->routeIs('profil.index') ? 'bg-yellow-100' : '' }}">
                            Profil UKM
                        </a>
                        <a href="{{ route('struktur.index') }}" class="px-4 py-3 text-gray-800 font-semibold hover:bg-yellow-50 rounded-lg {{ request()->routeIs('struktur.index') ? 'bg-yellow-100' : '' }}">
                            Pengurus
                        </a>
                        <a href="{{ route('diklat.register') }}" class="px-4 py-3 text-gray-800 font-semibold hover:bg-yellow-50 rounded-lg {{ request()->routeIs('diklat.register') ? 'bg-yellow-100' : '' }}">
                            Pendaftaran Diklat
                        </a>
                        <a href="{{ route('kegiatan.index') }}" class="px-4 py-3 text-gray-800 font-semibold hover:bg-yellow-50 rounded-lg {{ request()->routeIs('kegiatan.*') ? 'bg-yellow-100' : '' }}">
                            Kegiatan
                        </a>
                        <a href="{{ route('prestasi.index') }}" class="px-4 py-3 text-gray-800 font-semibold hover:bg-yellow-50 rounded-lg {{ request()->routeIs('prestasi.*') ? 'bg-yellow-100' : '' }}">
                            Prestasi
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="mt-auto bg-gradient-to-r from-gray-800 to-gray-900 text-white py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <!-- About -->
                    <div class="text-center md:text-left" data-aos="fade-up">
                        <h3 class="text-xl font-bold mb-3 flex items-center gap-2 justify-center md:justify-start">
                            <img src="{{ asset('assets/images/logoukm.png') }}" alt="Logo" class="w-6 h-6">
                            Satya Palapa
                        </h3>
                        <p class="text-gray-300 text-sm">UKM Musik UPN "Veteran" Jawa Timur</p>
                    </div>
                    
                    <!-- Quick Links -->
                    <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                        <h4 class="font-bold mb-3">Menu</h4>
                        <ul class="space-y-2 text-sm text-gray-300">
                            <li><a href="{{ route('profil.index') }}" class="hover:text-yellow-400 transition-colors">Profil</a></li>
                            <li><a href="{{ route('diklat.register') }}" class="hover:text-yellow-400 transition-colors">Daftar Diklat</a></li>
                            <li><a href="{{ route('kegiatan.index') }}" class="hover:text-yellow-400 transition-colors">Galeri</a></li>
                        </ul>
                    </div>
                    
                    <!-- Contact -->
                    <div class="text-center md:text-right" data-aos="fade-up" data-aos-delay="200">
                        <h4 class="font-bold mb-3">Kontak</h4>
                        <p class="text-gray-300 text-sm">Email: satpals@upnjatim.ac.id</p>
                        <p class="text-gray-300 text-sm">Instagram: @satpals_upnvjt</p>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 pt-6 text-center text-sm text-gray-400">
                    <p>Copyright © 2026 - All right reserved by Satya Palapa UKM</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        // Mobile menu toggle
        const navbarToggle = document.getElementById('navbar-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        navbarToggle.addEventListener('change', function() {
            if (this.checked) {
                mobileMenu.classList.remove('-translate-x-full');
            } else {
                mobileMenu.classList.add('-translate-x-full');
            }
        });

        // Close menu when clicking on a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                navbarToggle.checked = false;
                mobileMenu.classList.add('-translate-x-full');
            });
        });

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

@extends('layouts.app')

@section('title', 'Home - Satya Palapa')

@section('content')
<!-- Hero Section with Modern Design -->
<div class="relative min-h-[85vh] flex items-center overflow-hidden" style="background-image: url('{{ asset('assets/images/herobg.png') }}'); background-size: cover; background-position: center;">
    <!-- Overlay with gradient -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/70 to-black/50"></div>
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-32 h-32 bg-yellow-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-20 w-48 h-48 bg-orange-400/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 py-16 md:py-20 flex flex-col lg:flex-row items-center justify-between relative z-10 w-full gap-8">
        <!-- Left Content -->
        <div class="flex-1 text-center lg:text-left" data-aos="fade-up">
            <div class="inline-block px-4 py-2 bg-yellow-400/20 backdrop-blur-sm rounded-full text-yellow-400 text-xs sm:text-sm font-semibold mb-6 border border-yellow-400/30">
                🎵 UKM Musik Terbaik di Kampus
            </div>
            <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-4 leading-tight">
                <span class="text-yellow-400 italic">Selengean</span>
                <span class="text-white"> Tapi</span>
            </h1>
            <h2 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-6 md:mb-8 leading-tight">
                <span class="text-white">Punya</span>
                <span class="bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent italic"> Sikap</span>
            </h2>
            <p class="text-gray-300 text-sm md:text-lg leading-relaxed max-w-xl mb-8 md:mb-10">
                Unit kegiatan mahasiswa musik Satya Palapa adalah UKM bidang minat bakat di bidang musik. Didirikan pada tanggal 9 September 1999, kami telah melahirkan banyak musisi berbakat.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center lg:justify-start">
                <a href="#services" class="px-6 md:px-8 py-3 md:py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-full hover:shadow-lg hover:shadow-yellow-400/30 transition-all duration-300 hover:-translate-y-1 hover:scale-105 text-sm md:text-base">
                    Jelajahi Layanan
                </a>
                <a href="#about" class="px-6 md:px-8 py-3 md:py-4 bg-white/10 backdrop-blur-sm text-white font-semibold rounded-full border border-white/30 hover:bg-white/20 transition-all duration-300 hover:scale-105 text-sm md:text-base">
                    Tentang Kami
                </a>
            </div>
        </div>
        
        <!-- Right Logo -->
        <div class="flex-1 flex justify-center lg:justify-end" data-aos="zoom-in">
            <div class="relative">
                <!-- Glow Effect -->
                <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/30 to-orange-500/30 rounded-full blur-3xl scale-110"></div>
                <img src="{{ asset('assets/images/logoukm.png') }}" alt="UKM Logo" class="w-48 h-48 sm:w-72 sm:h-72 lg:w-96 lg:h-96 object-contain relative z-10 drop-shadow-2xl hover:scale-110 transition-transform duration-500">
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
    </div>
</div>

<!-- Services Section - Modern Gradient Background -->
<div id="services" class="bg-gradient-to-br from-yellow-400 via-amber-400 to-orange-400 py-16 md:py-24 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-72 h-72 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full translate-x-1/2 translate-y-1/2"></div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="text-center mb-12 md:mb-16">
            <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-gray-800 text-xs md:text-sm font-semibold mb-4">Layanan Kami</span>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-800">Apa yang kita bisa?</h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Service 1 - Pendaftaran Diklat -->
            <a href="{{ route('diklat.register') }}" class="group bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-2 border border-white/50" data-aos="fade-up">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl mb-5 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <!-- Education/Training Icon -->
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2">Pendaftaran Diklat</h3>
                <p class="text-gray-600 text-sm">Pelatihan musik profesional untuk pemula hingga mahir</p>
                <div class="mt-4 flex items-center text-blue-600 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Daftar Sekarang</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            <!-- Service 2 - Persewaan Alat -->
            <div class="group bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-2 border border-white/50" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl mb-5 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <!-- Guitar/Instrument Icon -->
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.59 3.59L21 5l-1.41 1.41-1.41-1.41L19.59 3.59M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2m-1 9v3h2v-3h-2m4-10.59L16.41 8l1.41-1.41L19.24 8l1.41-1.41L19.24 5.17l1.41-1.41-1.41-1.41-1.41 1.41zM12 6c-3.31 0-6 2.69-6 6 0 2.22 1.21 4.15 3 5.19V21h6v-3.81c1.79-1.04 3-2.97 3-5.19 0-3.31-2.69-6-6-6z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2">Persewaan Alat</h3>
                <p class="text-gray-600 text-sm">Sewa alat musik berkualitas dengan harga terjangkau</p>
                <div class="mt-4 flex items-center text-emerald-600 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Lihat Katalog</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>

            <!-- Service 3 - Persewaan Band -->
            <div class="group bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-2 border border-white/50" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl mb-5 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <!-- Band/Group Icon -->
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3c-1.27 0-2.4.8-2.82 2H3v2h1.95L2 14c-.47 2 1 4 4 4s4.5-2 4-4L7.05 7H9.1c.32.86.94 1.55 1.74 1.97C10.3 9.25 10 9.88 10 10.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5c0-.62-.3-1.25-.84-1.53.8-.42 1.42-1.11 1.74-1.97h2.05L15 14c-.47 2 1.53 4 4 4s4.5-2 4-4l-2.95-7H22V5h-6.18c-.42-1.2-1.55-2-2.82-2zM4 10l2 5H2l2-5zm14 0l2 5h-4l2-5z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2">Persewaan Band</h3>
                <p class="text-gray-600 text-sm">Band profesional untuk acara spesial Anda</p>
                <div class="mt-4 flex items-center text-purple-600 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Booking Band</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>

            <!-- Service 4 - Sewa Studio -->
            <a href="{{ route('studio-bookings.index') }}" class="group bg-white/90 backdrop-blur-sm rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-2 border border-white/50 block" data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl mb-5 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <!-- Studio/Mic Icon -->
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm-1-9c0-.55.45-1 1-1s1 .45 1 1v6c0 .55-.45 1-1 1s-1-.45-1-1V5zm6 6c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2">Sewa Studio</h3>
                <p class="text-gray-600 text-sm">Studio rekaman dengan peralatan lengkap</p>
                <div class="mt-4 flex items-center text-orange-600 font-semibold text-sm group-hover:gap-2 transition-all">
                    <span>Reservasi</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- About Section - Modern Design -->
<div id="about" class="bg-gradient-to-b from-white to-gray-50 py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12 items-start">
            <!-- Left Column - Foto -->
            <div class="flex justify-center lg:col-span-1" data-aos="fade-right">
                <div class="relative group">
                    <!-- Decorative Elements -->
                    <div class="absolute -top-4 -left-4 w-full h-full bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl transform rotate-3 group-hover:rotate-6 transition-transform duration-300"></div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-yellow-400/30 rounded-full blur-2xl"></div>
                    
                    <!-- Photo Container -->
                    <div class="relative bg-gradient-to-br from-orange-400 to-orange-500 rounded-2xl overflow-hidden shadow-2xl" style="width: 280px; height: 350px;">
                        <img src="{{ asset('assets/images/ketua.png') }}" alt="Ketua UKM" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
            </div>

            <!-- Right Column - Content -->
            <div class="lg:col-span-2">
                <!-- Badge -->
                <div class="inline-block px-4 py-2 bg-orange-100 rounded-full text-orange-600 text-sm font-semibold mb-4">
                    👋 Salam dari Ketua
                </div>
                
                <!-- Name and Position -->
                <div class="mb-8">
                    <h3 class="text-4xl font-bold text-gray-800 mb-2">Daniel Eluzai Aruan</h3>
                    <div class="flex items-center gap-4">
                        <div class="h-1 w-20 bg-gradient-to-r from-orange-500 to-yellow-400 rounded-full"></div>
                        <p class="text-lg text-gray-600 font-semibold">Ketua Umum Periode 2025/2026</p>
                    </div>
                </div>

                <!-- Visi & Misi Carousel with Modern Design -->
                <div class="rounded-3xl p-8 bg-gradient-to-br from-yellow-50 to-orange-50 shadow-xl border border-yellow-100/50 relative overflow-hidden">
                    <!-- Decorative -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-yellow-400/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                    
                    <div id="carouselContainer" class="relative z-10">
                        <!-- Visi -->
                        <div class="carousel-item active block">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-gray-800">Visi</h4>
                            </div>
                            <p class="text-gray-700 leading-relaxed text-lg">
                                Menjadi unit kegiatan mahasiswa musik yang berkontribusi dalam pengembangan seni musik berkualitas dan memberikan pengalaman berharga bagi seluruh anggota komunitas musik satya palapa.
                            </p>
                        </div>

                        <!-- Misi -->
                        <div class="carousel-item hidden block">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-gray-800">Misi</h4>
                            </div>
                            <p class="text-gray-700 leading-relaxed text-lg">
                                Mengembangkan bakat anggota di bidang musik, menyediakan layanan jasa musik berkualitas tinggi, dan menciptakan komunitas yang solid, suportif, dan profesional dalam mengembangkan industri kreatif musik.
                            </p>
                        </div>
                    </div>

                    <!-- Navigation Controls - Modern -->
                    <div class="flex items-center justify-center gap-6 mt-8 relative z-10">
                        <button id="prevBtn" class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center text-gray-600 hover:bg-yellow-400 hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <div class="flex gap-3">
                            <span class="carousel-dot active w-3 h-3 bg-orange-500 rounded-full cursor-pointer transition-all hover:scale-125" data-index="0"></span>
                            <span class="carousel-dot w-3 h-3 bg-gray-300 rounded-full cursor-pointer transition-all hover:scale-125" data-index="1"></span>
                        </div>
                        <button id="nextBtn" class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center text-gray-600 hover:bg-yellow-400 hover:text-white transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Visi Misi Carousel
    let currentIndex = 0;
    const items = document.querySelectorAll('.carousel-item');
    const dots = document.querySelectorAll('.carousel-dot');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    function showItem(index) {
        items.forEach((item, i) => {
            item.classList.toggle('hidden', i !== index);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-gray-800', i === index);
            dot.classList.toggle('bg-gray-300', i !== index);
        });
    }

    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        showItem(currentIndex);
    });

    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % items.length;
        showItem(currentIndex);
    });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            currentIndex = parseInt(dot.dataset.index);
            showItem(currentIndex);
        });
    });
</script>

<!-- Gallery Kegiatan - Modern Design -->
<div id="kegiatan" class="bg-gradient-to-br from-yellow-400 via-amber-400 to-orange-400 py-24 relative overflow-hidden">
    <!-- Decorative -->
    <div class="absolute top-0 left-1/4 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-gray-800 text-sm font-semibold mb-4">📸 Dokumentasi</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800">Galeri Kegiatan</h2>
        </div>
        
        @if($activities->count() > 0)
        <div class="relative flex items-center justify-between gap-4">
            <button onclick="scrollKegiatan(-1)" class="w-12 h-12 rounded-full bg-gray-800/90 text-white border-0 hover:bg-gray-900 flex-shrink-0 flex items-center justify-center shadow-xl hover:scale-110 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <div id="kegiatanContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 flex-1">
                @foreach($activities as $activity)
                <!-- Activity Item -->
                <a href="{{ route('kegiatan.show', $activity) }}" class="group bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-2">
                    <div class="aspect-video bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center relative overflow-hidden">
                        @if($activity->foto_1)
                            <img src="{{ asset('storage/' . $activity->foto_1) }}" alt="{{ $activity->judul_kegiatan }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-yellow-100 to-orange-100">
                                <span class="text-5xl">📷</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <p class="text-gray-800 font-semibold line-clamp-2">{{ $activity->judul_kegiatan }}</p>
                        <p class="text-gray-500 text-sm mt-1">{{ $activity->tanggal_kegiatan->translatedFormat('d F Y') }}</p>
                        @if($activity->tempat_kegiatan)
                        <p class="text-gray-400 text-xs mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ Str::limit($activity->tempat_kegiatan, 25) }}
                        </p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            <button onclick="scrollKegiatan(1)" class="w-12 h-12 rounded-full bg-gray-800/90 text-white border-0 hover:bg-gray-900 flex-shrink-0 flex items-center justify-center shadow-xl hover:scale-110 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📷</div>
            <p class="text-gray-800 text-lg">Belum ada kegiatan yang ditampilkan</p>
        </div>
        @endif
        
        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gray-800 text-white font-semibold rounded-full hover:bg-gray-900 transition-all duration-300 hover:shadow-xl">
                Lihat Semua Kegiatan
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Gallery Prestasi - Modern Design -->
<div id="prestasi" class="bg-gradient-to-b from-gray-50 to-white py-24 relative">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block px-4 py-2 bg-yellow-100 rounded-full text-yellow-700 text-sm font-semibold mb-4">🏆 Prestasi Kami</span>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800">Galeri Prestasi</h2>
        </div>
        
        @if($achievements->count() > 0)
        <div class="relative flex items-center justify-between gap-4">
            <button onclick="scrollPrestasi(-1)" class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 text-white border-0 hover:from-yellow-500 hover:to-orange-600 flex-shrink-0 flex items-center justify-center shadow-xl hover:scale-110 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>

            <div id="prestasiContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 flex-1 overflow-x-auto scrollbar-hide">
                @foreach($achievements as $achievement)
                <!-- Prestasi Item -->
                <a href="{{ route('prestasi.show', $achievement) }}" class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer hover:-translate-y-2 border border-gray-100">
                    <div class="aspect-video bg-gradient-to-br from-yellow-100 to-orange-100 flex items-center justify-center relative overflow-hidden">
                        @if($achievement->foto_1)
                            <img src="{{ asset('storage/' . $achievement->foto_1) }}" alt="{{ $achievement->judul_lomba }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="flex items-center justify-center w-full h-full">
                                <span class="text-5xl">🏆</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-2">
                            @php
                                $badgeClass = match($achievement->juara) {
                                    'juara_1' => 'bg-yellow-100 text-yellow-700',
                                    'juara_2' => 'bg-gray-100 text-gray-700',
                                    'juara_3' => 'bg-orange-100 text-orange-700',
                                    'best_performance', 'best_vocal', 'best_musician' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                            @endphp
                            <span class="px-2 py-1 {{ $badgeClass }} text-xs font-semibold rounded-full">{{ $achievement->juara_label }}</span>
                        </div>
                        <p class="text-gray-800 font-semibold line-clamp-2">{{ $achievement->judul_lomba }}</p>
                        <p class="text-gray-500 text-sm mt-1">{{ $achievement->nama_band ?: $achievement->tempat_lomba }}</p>
                    </div>
                </a>
                @endforeach
            </div>

            <button onclick="scrollPrestasi(1)" class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 text-white border-0 hover:from-yellow-500 hover:to-orange-600 flex-shrink-0 flex items-center justify-center shadow-xl hover:scale-110 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">🏆</div>
            <p class="text-gray-600">Belum ada prestasi yang dipublikasikan</p>
        </div>
        @endif
        
        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="{{ route('prestasi.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-full hover:shadow-xl hover:shadow-yellow-400/30 transition-all duration-300 hover:-translate-y-1">
                Lihat Semua Prestasi
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<script>
    function scrollPrestasi(direction) {
        const container = document.getElementById('prestasiContainer');
        const scrollAmount = 300;
        container.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    }
    
    function scrollKegiatan(direction) {
        const container = document.getElementById('kegiatanContainer');
        const scrollAmount = 300;
        container.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
    }
</script>
@endsection

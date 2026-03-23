@extends('layouts.app')

@section('title', 'Profil UKM Musik Satya Palapa - UPN Veteran Jawa Timur')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-yellow-400 via-orange-400 to-red-500 py-16 sm:py-20 md:py-32 overflow-hidden">
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full -mr-48 -mt-48 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full -ml-48 -mb-48 blur-3xl"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-8 md:gap-12">
            <!-- Logo UKM -->
            <div class="flex-shrink-0 w-full sm:w-auto flex justify-center" data-aos="zoom-in">
                <div class="w-40 h-40 sm:w-48 sm:h-48 md:w-64 md:h-64 bg-white rounded-full shadow-2xl flex items-center justify-center p-4 border-4 border-white/50 hover:shadow-3xl transition-all duration-300 hover:scale-105">
                    <img src="{{ asset('assets/images/logoukm.png') }}" alt="Logo Satya Palapa" 
                         class="w-full h-full object-contain">
                </div>
            </div>
            
            <!-- Title & Tagline -->
            <div class="text-center lg:text-left text-white flex-1" data-aos="fade-up">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 drop-shadow-lg">
                    Satya Palapa
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl font-medium text-white/90 mb-2">
                    Unit Kegiatan Mahasiswa Musik
                </p>
                <p class="text-base sm:text-lg text-white/80 mb-6">
                    UPN "Veteran" Jawa Timur
                </p>
            </div>
        </div>
    </div>
    
    <!-- Wave Decoration -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

<!-- Sejarah Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    📜 Sejarah UKM Musik
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-orange-500 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
                <!-- Text Content -->
                <div class="space-y-4 md:space-y-6">
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-4 md:p-6 border border-yellow-100 hover:shadow-lg transition-all duration-300 hover:scale-105" data-aos="fade-right">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <span class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center text-white text-sm">🎵</span>
                            Awal Berdiri
                        </h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Unit Kegiatan Mahasiswa (UKM) Musik <strong>Satya Palapa</strong> didirikan sebagai wadah bagi para mahasiswa UPN "Veteran" Jawa Timur yang memiliki minat dan bakat di bidang musik. UKM ini menjadi rumah bagi para pencinta musik untuk mengekspresikan kreativitas dan mengembangkan kemampuan bermusik mereka.
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-4 md:p-6 border border-orange-100 hover:shadow-lg transition-all duration-300 hover:scale-105" data-aos="fade-right" data-aos-delay="100">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <span class="w-8 h-8 bg-orange-400 rounded-full flex items-center justify-center text-white text-sm">🎸</span>
                            Perkembangan
                        </h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Seiring berjalannya waktu, Satya Palapa terus berkembang dan melahirkan musisi-musisi berbakat. UKM ini aktif mengadakan berbagai kegiatan seperti pelatihan musik (Diklat), penampilan di berbagai acara kampus, hingga mengikuti kompetisi musik antar universitas.
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-4 md:p-6 border border-red-100 hover:shadow-lg transition-all duration-300 hover:scale-105" data-aos="fade-right" data-aos-delay="200">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <span class="w-8 h-8 bg-red-400 rounded-full flex items-center justify-center text-white text-sm">⭐</span>
                            Nama "Satya Palapa"
                        </h3>
                        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                            Nama <strong>"Satya Palapa"</strong> diambil dari bahasa Sanskerta yang bermakna kesetiaan dan persatuan. Nama ini mencerminkan semangat kebersamaan dan dedikasi para anggota dalam bermusik serta membangun komunitas yang solid dan harmonis.
                        </p>
                    </div>
                </div>
                
                <!-- Photo Sekretariat -->
                <div class="relative" data-aos="fade-left">
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl overflow-hidden shadow-xl aspect-[4/3] hover:shadow-2xl transition-all duration-300">
                        <img src="{{ asset('assets/images/herobg.png') }}" alt="Sekretariat Satya Palapa" 
                             class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Floating Badge -->
                    <div class="absolute -bottom-4 -right-4 bg-yellow-400 text-gray-800 px-6 py-3 rounded-xl shadow-lg font-bold">
                        📍 Sekretariat Satya Palapa
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visi Misi Section -->
<section class="py-16 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    🎯 Visi & Misi
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-orange-500 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Visi -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-yellow-100 hover:shadow-2xl transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-400 p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white">VISI</h3>
                        </div>
                    </div>
                    <div class="p-8">
                        <p class="text-gray-700 text-lg leading-relaxed text-center italic">
                            "Menjadi Unit Kegiatan Mahasiswa Musik yang unggul, kreatif, dan berprestasi dalam mengembangkan bakat seni musik mahasiswa serta berkontribusi positif bagi UPN Veteran Jawa Timur dan masyarakat."
                        </p>
                    </div>
                </div>
                
                <!-- Misi -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-orange-100 hover:shadow-2xl transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-orange-400 to-red-400 p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white">MISI</h3>
                        </div>
                    </div>
                    <div class="p-8">
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <span class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-orange-500 font-bold">1</span>
                                </span>
                                <p class="text-gray-700">Menyelenggarakan pelatihan musik (Diklat) secara berkala untuk mengembangkan kemampuan anggota.</p>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-orange-500 font-bold">2</span>
                                </span>
                                <p class="text-gray-700">Menciptakan lingkungan yang kondusif untuk berkreasi dan berkolaborasi dalam bermusik.</p>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-orange-500 font-bold">3</span>
                                </span>
                                <p class="text-gray-700">Aktif berpartisipasi dalam kegiatan kampus dan kompetisi musik tingkat regional maupun nasional.</p>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-orange-500 font-bold">4</span>
                                </span>
                                <p class="text-gray-700">Membangun jaringan dan kerjasama dengan komunitas musik lainnya.</p>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <span class="text-orange-500 font-bold">5</span>
                                </span>
                                <p class="text-gray-700">Melestarikan dan mengapresiasi berbagai genre musik untuk memperkaya wawasan seni.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lokasi Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    📍 Lokasi Sekretariat
                </h2>
                <div class="w-24 h-1 bg-gradient-to-r from-yellow-400 to-orange-500 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Info Cards -->
                <div class="space-y-6">
                    <!-- Alamat -->
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 border border-yellow-100">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-yellow-400 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-1">Alamat</h4>
                                <p class="text-gray-600 text-sm">
                                    Sekretariat UKM Musik Satya Palapa<br>
                                    UPN "Veteran" Jawa Timur<br>
                                    Jl. Rungkut Madya, Gn. Anyar<br>
                                    Kec. Gn. Anyar, Surabaya<br>
                                    Jawa Timur 60294<br>
                                    <span class="text-xs text-gray-500">📍 MQ8P+RPQ</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Jam Operasional -->
                    <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border border-orange-100">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-orange-400 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-1">Jam Sekretariat</h4>
                                <p class="text-gray-600 text-sm">
                                    Senin - Jumat: 09.00 - 17.00 WIB<br>
                                    Sabtu: 09.00 - 14.00 WIB<br>
                                    Minggu: Tutup
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kontak -->
                    <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-6 border border-red-100">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-red-400 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 mb-1">Kontak</h4>
                                <p class="text-gray-600 text-sm">
                                    Email: satpals@upnjatim.ac.id<br>
                                    Instagram: @satpals_upnvjt
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Direction Button -->
                    <a href="https://www.google.com/maps/search/UKM+Musik+Satya+Palapa+Jl.+Rungkut+Madya+Gn.+Anyar+Surabaya" target="_blank" 
                       class="block w-full bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-500 hover:to-orange-500 text-white font-bold py-4 px-6 rounded-xl text-center transition-all shadow-lg hover:shadow-xl">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                            Buka di Google Maps
                        </span>
                    </a>
                </div>
                
                <!-- Google Maps Embed -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-100 rounded-2xl overflow-hidden shadow-xl h-full min-h-[400px]">
                        <iframe  
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.1868552564006!2d112.78684779999999!3d-7.332900599999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fab85c6d7aab%3A0x2bb9506ca7981a2b!2sUKM%20Musik%20%22Satya%20Palapa%22!5e0!3m2!1sen!2sid!4v1769443838994!5m2!1sen!2sid" 
                            width="100%" 
                            height="100%" 
                            style="border:0; min-height: 400px;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="rounded-2xl">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-yellow-400 via-orange-400 to-red-500">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Tertarik Bergabung?
        </h2>
        <p class="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
            Ayo kembangkan bakat musikmu bersama Satya Palapa! Daftar sekarang untuk mengikuti pelatihan musik (Diklat) dan jadilah bagian dari keluarga besar kami.
        </p>
        <div class="flex flex-wrap justify-center gap-4" data-aos="fade-up">
            <a href="{{ route('diklat.register') }}" 
               class="bg-white text-orange-500 hover:bg-orange-500 hover:text-white font-bold py-3 px-6 md:px-8 rounded-xl transition-all shadow-lg hover:shadow-2xl hover:scale-105 duration-300">
                🎵 Daftar Diklat Sekarang
            </a>
            <a href="{{ route('struktur.index') }}" 
               class="bg-white/20 backdrop-blur-sm text-white hover:bg-white/40 font-bold py-3 px-6 md:px-8 rounded-xl transition-all border border-white/30 hover:shadow-xl hover:scale-105 duration-300">
                👥 Lihat Pengurus
            </a>
        </div>
    </div>
</section>
@endsection

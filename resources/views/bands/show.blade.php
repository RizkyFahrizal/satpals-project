@extends('layouts.app')

@section('title', $band->band_name . ' - Sewa Band')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white">
    <!-- Header -->
    <div class="bg-yellow-400 shadow-md">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900">{{ $band->band_name }}</h1>
            <a href="{{ route('public.bands.index') }}" class="btn btn-sm border border-gray-300 hover:bg-yellow-500 hover:border-yellow-500 text-gray-800">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Band Image & Description -->
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                    @if($band->photo)
                    <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->band_name }}" 
                         class="w-full h-80 object-cover">
                    @else
                    <div class="w-full h-80 bg-gradient-to-br from-yellow-100 to-amber-100 flex items-center justify-center">
                        <i class="fas fa-music text-yellow-400 text-8xl"></i>
                    </div>
                    @endif
                    <div class="p-6">
                        <p class="text-gray-700 mb-4">{{ $band->description }}</p>
                        
                        <!-- Status Badge -->
                        <div class="flex items-center gap-2">
                            <span class="text-gray-600">Status:</span>
                            @if($band->is_available)
                                <span class="badge badge-success">✓ Tersedia</span>
                            @else
                                <span class="badge badge-error">✗ Tidak Tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Members Section -->
                @if($band->members->count())
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-users text-yellow-500 mr-2"></i> Anggota Band ({{ $band->members->count() }})
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($band->members as $member)
                        <div class="text-center">
                            @if($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->member_name }}" 
                                 class="w-24 h-24 rounded-full object-cover mx-auto mb-2">
                            @else
                            <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-user text-gray-400 text-3xl"></i>
                            </div>
                            @endif
                            <p class="font-semibold text-gray-800">{{ $member->member_name }}</p>
                            <p class="text-sm text-gray-600">{{ $member->role }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Genres Section -->
                @if($band->genres->count())
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-music text-amber-500 mr-2"></i> Genre Musik
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($band->genres as $genre)
                        <span class="badge badge-lg badge-outline border-amber-300 text-amber-700">{{ $genre->genre_name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Portfolios Section -->
                @if($band->portfolios->count())
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-video text-red-500 mr-2"></i> Portfolio ({{ $band->portfolios->count() }})
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($band->portfolios as $portfolio)
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-2">{{ $portfolio->title }}</h3>
                            @if($portfolio->getYoutubeEmbedUrl())
                            <div class="aspect-video bg-black rounded">
                                <iframe 
                                    width="100%" 
                                    height="100%" 
                                    src="{{ $portfolio->getYoutubeEmbedUrl() }}" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen 
                                    class="rounded">
                                </iframe>
                            </div>
                            @endif
                            @if($portfolio->description)
                            <p class="text-sm text-gray-600 mt-2">{{ $portfolio->description }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Price Card -->
                <div class="bg-gradient-to-br from-yellow-400 to-amber-400 text-gray-900 rounded-2xl shadow-md p-6 mb-6 border border-yellow-300">
                    <h3 class="text-lg font-bold mb-4">💰 Harga Sewa</h3>
                    <div class="space-y-3 mb-6 bg-white/40 p-4 rounded-lg">
                        <div>
                            <p class="text-gray-700 text-sm font-semibold">Per Jam</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($band->price_per_hour, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 text-sm font-semibold">Per Event</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($band->price_per_event, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('public.bands.rental-form', $band) }}" class="btn btn-sm w-full bg-yellow-500 hover:bg-yellow-600 border-0 text-gray-900 font-bold">
                        <i class="fas fa-shopping-cart"></i> Sewa Sekarang
                    </a>
                </div>

                <!-- Social Media Card -->
                @if($band->whatsapp_number || $band->instagram_username || $band->tiktok_username || $band->youtube_url)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-4 border-b">📱 Hubungi Kami</h3>
                    <div class="space-y-3">
                        @if($band->whatsapp_number)
                        <a href="https://wa.me/{{ $band->whatsapp_number }}" target="_blank" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition">
                            <i class="fab fa-whatsapp text-2xl text-green-600 w-8"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">WhatsApp</p>
                                <p class="font-semibold text-gray-800 truncate">{{ $band->whatsapp_number }}</p>
                            </div>
                        </a>
                        @endif

                        @if($band->instagram_username && $band->instagram_username !== '')
                        <a href="https://instagram.com/{{ $band->instagram_username }}" target="_blank" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition">
                            <i class="fab fa-instagram text-2xl text-pink-600 w-8"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">Instagram</p>
                                <p class="font-semibold text-gray-800 truncate">{{ $band->instagram_username }}</p>
                            </div>
                        </a>
                        @endif

                        @if($band->tiktok_username && $band->tiktok_username !== '')
                        <a href="https://tiktok.com/@{{ $band->tiktok_username }}" target="_blank" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition">
                            <i class="fab fa-tiktok text-2xl text-black w-8"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">TikTok</p>
                                <p class="font-semibold text-gray-800 truncate">{{ $band->tiktok_username }}</p>
                            </div>
                        </a>
                        @endif

                        @if($band->youtube_url)
                        <a href="{{ $band->youtube_url }}" target="_blank" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition">
                            <i class="fab fa-youtube text-2xl text-red-600 w-8"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-500">YouTube</p>
                                <p class="font-semibold text-gray-800 truncate">Channel</p>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

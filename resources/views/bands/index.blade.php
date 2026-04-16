@extends('layouts.app')

@section('title', 'Sewa Band - Satya Palapa')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white">
    <!-- Header -->
    <div class="bg-yellow-400 text-gray-900 py-12 shadow-md">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-2">Sewa Band Profesional</h1>
            <p class="text-gray-700">Temukan band terbaik untuk acara Anda</p>
        </div>
    </div>

    <!-- Alerts -->
    <div class="container mx-auto px-4 py-4">
        @if(session('success'))
        <div class="alert alert-success shadow-lg mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error shadow-lg mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif
    </div>

    <!-- Band Grid -->
    <div class="container mx-auto px-4 py-12">
        @if($bands->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bands as $band)
            <div class="card bg-white shadow-md hover:shadow-lg hover:border-yellow-300 transition border border-gray-100 rounded-2xl">
                <!-- Band Image -->
                <figure class="h-48 bg-gradient-to-br from-gray-200 to-gray-300">
                    @if($band->photo)
                    <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->band_name }}" 
                         class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-yellow-100">
                        <i class="fas fa-music text-yellow-400 text-6xl"></i>
                    </div>
                    @endif
                </figure>

                <!-- Card Body -->
                <div class="card-body">
                    <!-- Band Name -->
                    <h2 class="card-title text-xl text-gray-800">{{ $band->band_name }}</h2>
                    
                    <!-- Description -->
                    <p class="text-gray-600 text-sm">{{ Str::limit($band->description, 80) }}</p>

                    <!-- Members Section -->
                    @if($band->members_count > 0)
                    <div class="bg-yellow-50 p-2 rounded-lg mb-2 border-l-4 border-yellow-400">
                        <p class="text-gray-600 text-xs font-semibold mb-1">Members:</p>
                        <div class="space-y-1">
                            @foreach($band->members->take(2) as $member)
                            <p class="text-xs text-gray-700">
                                <span class="font-semibold">{{ $member->member_name }}</span>
                                @if($member->role)
                                <span class="text-gray-500">({{ $member->role }})</span>
                                @endif
                            </p>
                            @endforeach
                            @if($band->members_count > 2)
                            <p class="text-xs text-yellow-600 font-semibold">+{{ $band->members_count - 2 }} lainnya</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Genres Section -->
                    @if($band->genres_count > 0)
                    <div class="bg-amber-50 p-2 rounded-lg mb-2 border-l-4 border-amber-400">
                        <p class="text-gray-600 text-xs font-semibold mb-1">Genre:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($band->genres->take(2) as $genre)
                            <span class="badge badge-sm badge-outline border-amber-300 text-amber-700 text-xs">{{ $genre->genre_name }}</span>
                            @endforeach
                            @if($band->genres_count > 2)
                            <span class="badge badge-sm badge-outline border-amber-300 text-amber-700 text-xs">+{{ $band->genres_count - 2 }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Price -->
                    <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-3 rounded-lg mb-3 border border-yellow-200">
                        <div class="flex justify-between items-center text-sm mb-1">
                            <span class="text-gray-600">Per Jam:</span>
                            <span class="font-bold text-green-600">Rp {{ number_format($band->price_per_hour, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Per Event:</span>
                            <span class="font-bold text-green-600">Rp {{ number_format($band->price_per_event, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card-actions gap-2">
                        <a href="{{ route('public.bands.show', $band) }}" class="btn btn-sm flex-1 border-2 border-gray-200 hover:border-yellow-400 hover:bg-yellow-50 hover:text-gray-900 text-gray-700">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <a href="{{ route('public.bands.rental-form', $band) }}" class="btn btn-sm flex-1 bg-yellow-400 hover:bg-yellow-500 border-0 text-gray-900">
                            <i class="fas fa-check"></i> Sewa
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            {{ $bands->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-music text-6xl text-yellow-300 mb-4 block"></i>
            <p class="text-gray-500 text-lg mb-4">Tidak ada band yang tersedia saat ini</p>
            <a href="{{ route('home') }}" class="btn bg-yellow-400 hover:bg-yellow-500 border-0 text-gray-900">Kembali ke Beranda</a>
        </div>
        @endif
    </div>
</div>
@endsection

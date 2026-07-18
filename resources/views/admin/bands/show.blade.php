@extends('layouts.admin')

@section('title', $band->band_name)

@section('header', 'Detail Band')

@section('breadcrumb', 'Persewaan Band')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🎵 {{ $band->band_name }}</h1>
            <p class="text-gray-500 mt-2">{{ $band->description }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.bands.edit', $band) }}" class="flex items-center gap-2 px-4 py-2.5 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition">
                <i class="fas fa-edit text-base"></i>
                <span>Edit</span>
            </a>
            <form action="{{ route('admin.bands.destroy', $band) }}" method="POST" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus band ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 rounded-xl border border-red-200 font-semibold hover:bg-red-100 transition">
                    <i class="fas fa-trash text-base"></i>
                    <span>Hapus</span>
                </button>
            </form>
            <a href="{{ route('admin.bands.index') }}" class="flex items-center gap-2 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        @if($band->photo)
        <div class="md:col-span-1">
            <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->band_name }}" 
                 class="w-full rounded-2xl shadow-md object-cover h-48">
        </div>
        <div class="md:col-span-3">
        @else
        <div class="md:col-span-4">
        @endif
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Harga/Jam</p>
                        <p class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($band->price_per_hour, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-orange-50 rounded-xl p-4 border border-orange-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Harga/Event</p>
                        <p class="text-xl font-bold text-gray-900 mt-1">Rp {{ number_format($band->price_per_event, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Personil</p>
                        <p class="text-xl font-bold text-gray-900 mt-1">{{ $band->members->count() }}</p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Status</p>
                        <div class="flex items-center gap-2 mt-2">
                            @if($band->is_available)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-green-100 text-green-700 text-xs font-semibold border border-green-300">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-semibold border border-red-300">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Tidak
                                </span>
                            @endif
                            <form action="{{ route('admin.bands.toggle-availability', $band) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center w-7 h-7 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition">
                                    <i class="fas fa-sync text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
        <div role="tablist" class="tabs tabs-lifted">
            <input type="radio" name="band_tabs" role="tab" class="tab font-semibold text-gray-700" aria-label="👥 Personil" checked/>
            <div role="tabpanel" class="tab-content rounded-t-2xl p-6 bg-gray-50 min-h-96">
                @include('admin.bands.sections.members', ['band' => $band])
            </div>

            <input type="radio" name="band_tabs" role="tab" class="tab font-semibold text-gray-700" aria-label="🎵 Genre"/>
            <div role="tabpanel" class="tab-content rounded-t-2xl p-6 bg-gray-50 min-h-96">
                @include('admin.bands.sections.genres', ['band' => $band])
            </div>

            <input type="radio" name="band_tabs" role="tab" class="tab font-semibold text-gray-700" aria-label="📹 Portfolio"/>
            <div role="tabpanel" class="tab-content rounded-t-2xl p-6 bg-gray-50 min-h-96">
                @include('admin.bands.sections.portfolios', ['band' => $band])
            </div>

            <input type="radio" name="band_tabs" role="tab" class="tab font-semibold text-gray-700" aria-label="📄 MoU"/>
            <div role="tabpanel" class="tab-content rounded-t-2xl p-6 bg-gray-50 min-h-96">
                @include('admin.bands.sections.mou', ['band' => $band])
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('admin.bands.modals.member-modal', ['band' => $band])
@include('admin.bands.modals.portfolio-modal', ['band' => $band])

@endsection


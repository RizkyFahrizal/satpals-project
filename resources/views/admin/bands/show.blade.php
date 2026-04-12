@extends('layouts.admin')

@section('title', $band->band_name)

@section('header', 'Detail Band')

@section('breadcrumb', 'Persewaan Band')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $band->band_name }}</h1>
            <p class="text-gray-500 mt-2">{{ $band->description }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.bands.edit', $band) }}" class="btn btn-warning">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <form action="{{ route('admin.bands.destroy', $band) }}" method="POST" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus band ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </button>
            </form>
            <a href="{{ route('admin.bands.index') }}" class="btn btn-ghost">Kembali</a>
        </div>
    </div>

    <!-- Info Card -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @if($band->photo)
        <div class="md:col-span-1">
            <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->band_name }}" 
                 class="w-full rounded-lg shadow">
        </div>
        <div class="md:col-span-3">
        @else
        <div class="md:col-span-4">
        @endif
            <div class="bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm">Harga Per Jam</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($band->price_per_hour, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Harga Per Event</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($band->price_per_event, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total Personil</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $band->members->count() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Status</p>
                        <div class="flex gap-2 items-center mt-1">
                            @if($band->is_available)
                                <span class="badge badge-success badge-outline">Tersedia</span>
                            @else
                                <span class="badge badge-error badge-outline">Tidak Tersedia</span>
                            @endif
                            <form action="{{ route('admin.bands.toggle-availability', $band) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-xs btn-ghost">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs tabs-bordered mb-6" role="tablist">
        <input type="radio" name="band_tabs" role="tab" class="tab" aria-label="👥 Personil" checked/>
        <div role="tabpanel" class="tab-content p-4">
            @include('admin.bands.sections.members', ['band' => $band])
        </div>

        <input type="radio" name="band_tabs" role="tab" class="tab" aria-label="🎵 Genre"/>
        <div role="tabpanel" class="tab-content p-4">
            @include('admin.bands.sections.genres', ['band' => $band])
        </div>

        <input type="radio" name="band_tabs" role="tab" class="tab" aria-label="📹 Portfolio"/>
        <div role="tabpanel" class="tab-content p-4">
            @include('admin.bands.sections.portfolios', ['band' => $band])
        </div>

        <input type="radio" name="band_tabs" role="tab" class="tab" aria-label="📄 MoU"/>
        <div role="tabpanel" class="tab-content p-4">
            @include('admin.bands.sections.mou', ['band' => $band])
        </div>
    </div>
</div>

<!-- Modals -->
@include('admin.bands.modals.member-modal', ['band' => $band])
@include('admin.bands.modals.portfolio-modal', ['band' => $band])

@endsection

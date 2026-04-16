@extends('layouts.admin')

@section('title', 'Kelola Persewaan Band')

@section('header', 'Kelola Persewaan Band')

@section('breadcrumb', 'Persewaan Band')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success mb-6 shadow-md rounded-2xl border border-green-200 bg-gradient-to-r from-green-50 to-emerald-50">
        <i class="fas fa-check-circle text-green-600 text-lg"></i>
        <span class="text-green-800 font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error mb-6 shadow-md rounded-2xl border border-red-200 bg-gradient-to-r from-red-50 to-pink-50">
        <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
        <span class="text-red-800 font-medium">{{ session('error') }}</span>
    </div>
    @endif
    
    <!-- Header Section -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Persewaan Band</h1>
            <p class="text-gray-500 mt-2">Kelola informasi dan data band Anda</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.band-rentals.index') }}" class="btn btn-outline btn-sm rounded-xl border-yellow-300 text-gray-700 hover:bg-yellow-100 hover:border-yellow-400 hover:text-gray-900">
                <i class="fas fa-inbox text-base"></i>
                <span>Permintaan</span>
            </a>
            <a href="{{ route('admin.bands.create') }}" class="btn btn-sm bg-yellow-400 text-gray-900 border-0 shadow-md rounded-xl hover:bg-yellow-500 font-semibold">
                <i class="fas fa-plus text-base"></i>
                <span>Tambah Band</span>
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 mb-8">
        <form action="{{ route('admin.bands.index') }}" method="GET" class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Band</label>
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Ketik nama band..." 
                    value="{{ request('search') }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="availability" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-white focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition">
                    <option value="all" {{ request('availability') === 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="available" {{ request('availability') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="unavailable" {{ request('availability') === 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition flex items-center gap-2">
                <i class="fas fa-search"></i>
                <span>Cari</span>
            </button>
            <a href="{{ route('admin.bands.index') }}" class="px-4 py-2.5 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">
                Reset
            </a>
        </form>
    </div>

    <!-- Table -->
    @if($bands->count())
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Band</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Harga</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Members</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Genre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bands as $band)
                <tr class="border-b border-gray-100 hover:bg-yellow-50/40 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($band->photo)
                            <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->band_name }}" 
                                 class="w-10 h-10 rounded-lg object-cover shadow-sm">
                            @else
                            <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center border border-yellow-200">
                                <i class="fas fa-music text-yellow-600"></i>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-800">{{ $band->band_name }}</p>
                                <p class="text-sm text-gray-500">{{ Str::limit($band->description, 30) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">
                            <p class="text-gray-800">Rp {{ number_format($band->price_per_hour, 0, ',', '.') }}/jam</p>
                            <p class="text-gray-500">Rp {{ number_format($band->price_per_event, 0, ',', '.') }}/event</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-700 font-semibold text-sm">
                            {{ $band->members_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-50 border border-orange-200 text-orange-700 font-semibold text-sm">
                            {{ $band->genres_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($band->is_available)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-50 text-green-700 border border-green-200">
                                <i class="fas fa-check-circle mr-1"></i>
                                Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-50 text-red-700 border border-red-200">
                                <i class="fas fa-times-circle mr-1"></i>
                                Tidak Tersedia
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.bands.show', $band) }}" 
                               class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition border border-blue-200 tooltip" 
                               data-tip="Lihat Detail">
                                <i class="fas fa-eye text-base"></i>
                            </a>
                            <a href="{{ route('admin.bands.edit', $band) }}" 
                               class="flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition border border-yellow-200 tooltip" 
                               data-tip="Edit">
                                <i class="fas fa-edit text-base"></i>
                            </a>
                            <form action="{{ route('admin.bands.destroy', $band) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus band ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center justify-center w-10 h-10 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition border border-red-200 tooltip" data-tip="Hapus">
                                    <i class="fas fa-trash text-base"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $bands->links() }}
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-12 text-center">
        <i class="fas fa-music text-5xl text-yellow-200 mb-4 block"></i>
        <p class="text-gray-600 text-lg font-medium mb-6">Tidak ada band ditemukan</p>
        <a href="{{ route('admin.bands.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition">
            <i class="fas fa-plus"></i>
            Tambah Band Baru
        </a>
    </div>
    @endif
</div>
@endsection

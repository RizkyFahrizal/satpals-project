@extends('layouts.admin')

@section('title', 'Daftar Band')

@section('header', 'Daftar Band')

@section('breadcrumb', 'Persewaan Band')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Success Alert -->
    @if(session('success'))
    <div class="alert alert-success mb-6 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Error Alert -->
    @if(session('error'))
    <div class="alert alert-error mb-6 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Band</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.band-rentals.index') }}" class="btn btn-outline btn-lg gap-2">
                <i class="fas fa-inbox"></i>
                <span>Kelola Permintaan</span>
            </a>
            <a href="{{ route('admin.bands.create') }}" class="btn btn-primary btn-lg gap-2">
                <i class="fas fa-plus text-lg"></i>
                <span>Tambah Band</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form action="{{ route('admin.bands.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari nama band..." 
                    value="{{ request('search') }}"
                    class="input input-bordered w-full"
                >
            </div>
            <div>
                <select name="availability" class="select select-bordered">
                    <option value="all" {{ request('availability') === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="available" {{ request('availability') === 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="unavailable" {{ request('availability') === 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>
            <button type="submit" class="btn btn-outline">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            <a href="{{ route('admin.bands.index') }}" class="btn btn-ghost">Reset</a>
        </form>
    </div>

    <!-- Table -->
    @if($bands->count())
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
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
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($band->photo)
                            <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->band_name }}" 
                                 class="w-10 h-10 rounded-lg object-cover">
                            @else
                            <div class="w-10 h-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-music text-gray-500"></i>
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
                        <span class="badge badge-lg">{{ $band->members_count }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="badge badge-lg">{{ $band->genres_count }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($band->is_available)
                            <span class="badge badge-success badge-outline">Tersedia</span>
                        @else
                            <span class="badge badge-error badge-outline">Tidak Tersedia</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('admin.bands.show', $band) }}" 
                               class="btn btn-sm btn-info btn-circle tooltip" 
                               data-tip="Lihat Detail">
                                <i class="fas fa-eye text-base text-white"></i>
                            </a>
                            <a href="{{ route('admin.bands.edit', $band) }}" 
                               class="btn btn-sm btn-warning btn-circle tooltip" 
                               data-tip="Edit">
                                <i class="fas fa-edit text-base text-white"></i>
                            </a>
                            <form action="{{ route('admin.bands.destroy', $band) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus band ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-error btn-circle tooltip" data-tip="Hapus">
                                    <i class="fas fa-trash text-base text-white"></i>
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
    <div class="mt-6 flex justify-center">
        {{ $bands->links() }}
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <i class="fas fa-music text-4xl text-gray-300 mb-4 block"></i>
        <p class="text-gray-500 text-lg">Tidak ada band ditemukan</p>
        <a href="{{ route('admin.bands.create') }}" class="btn btn-primary mt-4">
            Tambah Band Baru
        </a>
    </div>
    @endif
</div>
@endsection

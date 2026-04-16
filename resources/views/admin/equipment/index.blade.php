@extends('layouts.admin')

@section('title', 'Kelola Peralatan Sewa')

@section('header', 'Kelola Peralatan Sewa')

@section('breadcrumb', 'Manajemen Persewaan')

@section('content')
<div class="space-y-6">
    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success shadow-md rounded-2xl border border-green-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error shadow-md rounded-2xl border border-red-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m8-8l-2 2m0 0l-2-2m2 2l2-2m-2 2l-2 2" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Header dengan Tombol Tambah -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">📦 Daftar Peralatan</h2>
            <div class="flex gap-3">
                <a href="{{ route('admin.equipment-rental-requests.index') }}" class="btn btn-sm bg-blue-500 hover:bg-blue-600 border-0 text-white font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Permintaan Sewa
                </a>
                <a href="{{ route('admin.equipment.create') }}" class="btn btn-sm bg-yellow-400 hover:bg-yellow-500 border-0 text-gray-900 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Peralatan
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6">
        <form method="GET" action="{{ route('admin.equipment.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔍 Cari Peralatan</label>
                    <input type="text" name="search" placeholder="Nama peralatan..." 
                           value="{{ request('search') }}" class="input input-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20" />
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📂 Kategori</label>
                    <select name="category" class="select select-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20">
                        <option value="">Semua Kategori</option>
                        <option value="paket" {{ request('category') == 'paket' ? 'selected' : '' }}>📦 Paket</option>
                        <option value="satuan" {{ request('category') == 'satuan' ? 'selected' : '' }}>🎁 Satuan</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-sm bg-yellow-400 hover:bg-yellow-500 border-0 text-gray-900 font-semibold flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari
                    </button>
                    @if(request('search') || request('category'))
                    <a href="{{ route('admin.equipment.index') }}" class="btn btn-sm border border-gray-300 hover:bg-gray-100 text-gray-800">
                        Reset
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Peralatan -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b-2 border-yellow-200 bg-yellow-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold text-gray-800">No</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-800">Foto</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-800">Nama Peralatan</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-800">Kategori</th>
                        <th class="px-6 py-3 text-right font-bold text-gray-800">Harga/Hari</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-800">Status</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-800">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipments as $item)
                    <tr class="border-b border-gray-100 hover:bg-yellow-50 transition-colors">
                        <td class="px-6 py-4 text-gray-700">{{ ($equipments->currentPage() - 1) * $equipments->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            @if($item->photo)
                            <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" 
                                 class="w-12 h-12 object-cover rounded-lg shadow-sm" />
                            @else
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->name }}</td>
                        <td class="px-6 py-4">
                            <span class="badge {{ $item->category === 'paket' ? 'badge-info' : 'badge-warning' }} text-white font-semibold">
                                {{ $item->category === 'paket' ? '📦 Paket' : '🎁 Satuan' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-green-600">Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.equipment.toggleAvailability', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="badge {{ $item->is_available ? 'badge-success' : 'badge-error' }} text-white font-semibold cursor-pointer hover:opacity-80 transition">
                                    {{ $item->is_available ? '✓ Tersedia' : '✕ Tidak' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('admin.equipment.show', $item) }}" class="btn btn-xs btn-ghost" title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a href="{{ route('admin.equipment.edit', $item) }}" class="btn btn-xs btn-ghost" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.equipment.destroy', $item) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus peralatan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-ghost text-red-600 hover:text-red-700" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <div class="text-4xl mb-2">📦</div>
                            <p class="font-semibold">Belum ada peralatan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $equipments->links() }}
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Kelola Peralatan Sewa')

@section('header', 'Kelola Peralatan Sewa')

@section('breadcrumb', 'Manajemen Persewaan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header dengan Tombol Tambah -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Peralatan</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.equipment-rental-requests.index') }}" class="btn btn-accent gap-2">
                <i class="fas fa-list-check"></i> Kelola Permintaan
            </a>
            <a href="{{ route('admin.equipment.create') }}" class="btn btn-primary gap-2">
                <i class="fas fa-plus"></i> Tambah Peralatan
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success mb-6 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error mb-6 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m8-8l-2 2m0 0l-2-2m2 2l2-2m-2 2l-2 2" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filter & Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.equipment.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Cari Peralatan</span>
                    </label>
                    <input type="text" name="search" placeholder="Nama peralatan..." 
                           value="{{ request('search') }}" class="input input-bordered" />
                </div>

                <!-- Category Filter -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Kategori</span>
                    </label>
                    <select name="category" class="select select-bordered">
                        <option value="">Semua Kategori</option>
                        <option value="paket" {{ request('category') == 'paket' ? 'selected' : '' }}>Paket</option>
                        <option value="satuan" {{ request('category') == 'satuan' ? 'selected' : '' }}>Satuan</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="form-control flex justify-end">
                    <label class="label">
                        <span class="label-text font-semibold">&nbsp;</span>
                    </label>
                    <button type="submit" class="btn btn-outline">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Peralatan -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Foto</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Peralatan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kategori</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Harga/Hari</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipments as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ ($equipments->currentPage() - 1) * $equipments->perPage() + $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            @if($item->photo)
                            <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}" 
                                 class="w-12 h-12 object-cover rounded" />
                            @else
                            <div class="w-12 h-12 bg-gray-300 rounded flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $item->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="badge {{ $item->category === 'paket' ? 'badge-info' : 'badge-warning' }}">
                                {{ ucfirst($item->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <form action="{{ route('admin.equipment.toggleAvailability', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="badge {{ $item->is_available ? 'badge-success' : 'badge-error' }} cursor-pointer">
                                    {{ $item->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-center text-sm space-x-2">
                            <a href="{{ route('admin.equipment.show', $item) }}" class="btn btn-sm btn-info gap-1">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                            <a href="{{ route('admin.equipment.edit', $item) }}" class="btn btn-sm btn-warning gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.equipment.destroy', $item) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus peralatan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-error gap-1">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 block"></i>
                            Tidak ada peralatan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $equipments->links() }}
        </div>
    </div>
</div>
@endsection

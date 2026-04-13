@extends('layouts.admin')

@section('title', 'Detail Peralatan: ' . $equipment->name)

@section('header', 'Detail Peralatan')

@section('breadcrumb', 'Manajemen Persewaan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $equipment->name }}</h1>
            <p class="text-gray-500 mt-2">{{ ucfirst($equipment->category) }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.equipment.edit', $equipment) }}" class="btn btn-warning gap-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('admin.equipment.destroy', $equipment) }}" method="POST" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus peralatan ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error gap-2">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-ghost">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Foto -->
            @if($equipment->photo)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Foto Peralatan</h2>
                <img src="{{ asset('storage/' . $equipment->photo) }}" alt="{{ $equipment->name }}" 
                     class="w-full h-96 object-cover rounded-lg" />
            </div>
            @endif

            <!-- Deskripsi -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Deskripsi</h2>
                <div class="text-gray-700 whitespace-pre-line">
                    {{ $equipment->description ?? 'Tidak ada deskripsi' }}
                </div>
            </div>

            <!-- Catatan Produk -->
            @if($equipment->notes)
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Catatan Produk</h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $equipment->notes }}</p>
            </div>
            @endif

            <!-- Unit List (Paket Only) -->
            @if($equipment->category === 'paket' && $units->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Unit yang Disewakan ({{ $units->count() }} Unit)</h2>

                <div class="space-y-3">
                    @foreach($units as $unit)
                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $unit->unit_name }}</p>
                                <p class="text-sm text-gray-600">Jumlah: <span class="font-bold">{{ $unit->quantity }}</span></p>
                            </div>
                            <span class="badge badge-lg badge-outline">Unit {{ $loop->iteration }}</span>
                        </div>
                        @if($unit->description)
                        <p class="text-sm text-gray-600 mt-2">{{ $unit->description }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Informasi Harga -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Harga</h2>

                <div class="space-y-3">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200">
                        <p class="text-sm text-gray-600">Harga Per Hari</p>
                        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($equipment->price_per_day, 0, ',', '.') }}</p>
                    </div>

                    @if($equipment->category === 'paket' && $equipment->operator_crew_price)
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-sm text-gray-600">Harga Operator + Crew</p>
                        <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($equipment->operator_crew_price, 0, ',', '.') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Status</h2>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <p class="text-gray-700">Ketersediaan</p>
                        @if($equipment->is_available)
                        <span class="badge badge-success badge-lg">Tersedia</span>
                        @else
                        <span class="badge badge-error badge-lg">Tidak Tersedia</span>
                        @endif
                    </div>

                    <form action="{{ route('admin.equipment.toggleAvailability', $equipment) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline w-full">
                            <i class="fas fa-sync"></i> Ubah Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kategori Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi</h2>

                <div class="space-y-2">
                    <div class="flex justify-between items-center pb-3 border-b">
                        <p class="text-gray-600 text-sm">Kategori</p>
                        <span class="badge {{ $equipment->category === 'paket' ? 'badge-info' : 'badge-warning' }}">
                            {{ ucfirst($equipment->category) }}
                        </span>
                    </div>

                    @if($equipment->category === 'paket')
                    <div class="flex justify-between items-center pb-3 border-b">
                        <p class="text-gray-600 text-sm">Jumlah Unit</p>
                        <span class="font-bold text-lg">{{ $units->count() }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <p class="text-gray-600 text-sm">Dibuat</p>
                        <span class="text-sm text-gray-700">{{ $equipment->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Statistik Penyewaan -->
            @if($equipment->requestItems->count() > 0)
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg shadow p-6 border border-purple-200">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Statistik Penyewaan</h2>

                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-700">Total Permintaan</p>
                        <p class="font-bold text-lg text-purple-600">{{ $equipment->requestItems->count() }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Kelola Permintaan Persewaan')

@section('header', 'Kelola Permintaan Persewaan Alat')

@section('breadcrumb', 'Manajemen Persewaan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Permintaan Persewaan Alat</h1>
            <p class="text-gray-500 mt-2">Kelola seluruh permintaan persewaan alat dari pelanggan</p>
        </div>
        <a href="{{ route('admin.equipment.index') }}" class="btn btn-ghost">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
    </div>

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

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-1 mb-8 flex gap-1 overflow-x-auto">
        <a href="{{ route('admin.equipment-rental-requests.index') }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ !request('status') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-inbox text-base"></i>
            <span>Semua</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ !request('status') ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $allCount ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.equipment-rental-requests.index', ['status' => 'pending']) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'pending' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-hourglass-half text-base"></i>
            <span>Menunggu</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'pending' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $pendingCount ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.equipment-rental-requests.index', ['status' => 'approved']) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'approved' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-check-circle text-base"></i>
            <span>Disetujui</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'approved' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $approvedCount ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.equipment-rental-requests.index', ['status' => 'rejected']) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'rejected' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-times-circle text-base"></i>
            <span>Ditolak</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'rejected' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $rejectedCount ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.equipment-rental-requests.index', ['status' => 'cancelled']) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'cancelled' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-ban text-base"></i>
            <span>Dibatalkan</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'cancelled' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $cancelledCount ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.equipment-rental-requests.index', ['status' => 'completed']) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'completed' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-flag-checkered text-base"></i>
            <span>Selesai</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'completed' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $completedCount ?? 0 }}</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 mb-8">
        <form method="GET" action="{{ route('admin.equipment-rental-requests.index') }}" class="flex flex-col gap-4 lg:flex-row lg:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                <input type="text" name="search" placeholder="No. Pesanan atau Nama Penyewa..." value="{{ request('search') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition flex items-center gap-2">
                    <i class="fas fa-search"></i>
                    <span>Cari</span>
                </button>
                @if(request('search') || request('start_date') || request('end_date') || request('status'))
                <a href="{{ route('admin.equipment-rental-requests.index') }}" class="px-4 py-2.5 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition font-medium">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    @if($requests->count())
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Penyewa</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Sewa</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kode Sewa</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kategori Rental</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Dibuat</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)
                <tr class="border-b border-gray-100 hover:bg-yellow-50/30 transition">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $request->renter_name }}</p>
                            <p class="text-sm text-gray-600">{{ $request->renter_npm_nik }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $request->start_date->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600">s/d {{ $request->end_date->format('d M Y') }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-blue-100 text-blue-700 border border-blue-300">
                            {{ $request->order_number }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $rentalCategories = $request->items
                                ->map(fn ($item) => $item->equipment->category ?? null)
                                ->filter()
                                ->unique()
                                ->values();
                            $rentalCategoryLabel = $rentalCategories->count() === 1
                                ? ucfirst($rentalCategories->first())
                                : 'Campuran';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $rentalCategoryLabel === 'Paket' ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($rentalCategoryLabel === 'Satuan' ? 'bg-orange-50 text-orange-700 border border-orange-200' : 'bg-gray-50 text-gray-700 border border-gray-200') }}">
                            {{ $rentalCategoryLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @switch($request->status)
                            @case('pending')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700 border border-yellow-300">
                                    <i class="fas fa-hourglass-half mr-1"></i> Menunggu
                                </span>
                                @break
                            @case('approved')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700 border border-green-300">
                                    <i class="fas fa-check-circle mr-1"></i> Disetujui
                                </span>
                                @break
                            @case('completed')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-700 border border-emerald-300">
                                    <i class="fas fa-flag-checkered mr-1"></i> Selesai
                                </span>
                                @break
                            @case('rejected')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-700 border border-red-300">
                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                </span>
                                @break
                            @case('cancelled')
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-700 border border-gray-300">
                                    <i class="fas fa-ban mr-1"></i> Dibatalkan
                                </span>
                                @break
                            @default
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-700 border border-gray-300">
                                    {{ $request->status }}
                                </span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $request->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.equipment-rental-requests.show', $request) }}" class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition border border-blue-200 tooltip" data-tip="Lihat Detail">
                                <i class="fas fa-eye text-base"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8 flex justify-center">
        {{ $requests->links() }}
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-12 text-center">
        <i class="fas fa-inbox text-5xl text-yellow-200 mb-4 block"></i>
        <p class="text-gray-600 text-lg font-medium mb-6">Tidak ada permintaan persewaan alat</p>
        <a href="{{ route('admin.equipment.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Peralatan
        </a>
    </div>
    @endif
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Kelola Permintaan Persewaan')

@section('header', 'Kelola Permintaan Persewaan Alat')

@section('breadcrumb', 'Manajemen Persewaan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Permintaan Persewaan Alat</h1>
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

    <!-- Filter Tabs -->
    <div class="tabs tabs-bordered mb-6">
        <a href="{{ route('admin.equipment-rental-requests.index') }}" 
           class="tab {{ !request('status') ? 'tab-active' : '' }}">
            Semua ({{ $allCount ?? 0 }})
        </a>
        <a href="{{ route('admin.equipment-rental-requests.index', ['status' => 'pending']) }}" 
           class="tab {{ request('status') == 'pending' ? 'tab-active' : '' }}">
            Menunggu ({{ $pendingCount ?? 0 }})
        </a>
        <a href="{{ route('admin.equipment-rental-requests.index', ['status' => 'approved']) }}" 
           class="tab {{ request('status') == 'approved' ? 'tab-active' : '' }}">
            Disetujui ({{ $approvedCount ?? 0 }})
        </a>
        <a href="{{ route('admin.equipment-rental-requests.index', ['status' => 'in_progress']) }}" 
           class="tab {{ request('status') == 'in_progress' ? 'tab-active' : '' }}">
            Sedang Berlangsung ({{ $inProgressCount ?? 0 }})
        </a>
        <a href="{{ route('admin.equipment-rental-requests.index', ['status' => 'done']) }}" 
           class="tab {{ request('status') == 'done' ? 'tab-active' : '' }}">
            Selesai ({{ $doneCount ?? 0 }})
        </a>
        <a href="{{ route('admin.equipment-rental-requests.index', ['status' => 'rejected']) }}" 
           class="tab {{ request('status') == 'rejected' ? 'tab-active' : '' }}">
            Ditolak ({{ $rejectedCount ?? 0 }})
        </a>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.equipment-rental-requests.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Cari</span>
                    </label>
                    <input type="text" name="search" placeholder="No. Pesanan atau Nama Penyewa..." 
                           value="{{ request('search') }}" class="input input-bordered" />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Dari Tanggal</span>
                    </label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="input input-bordered" />
                </div>

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

    <!-- Tabel Permintaan -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100 border-b">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No. Pesanan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Penyewa</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Sewa</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Durasi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Total Harga</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono text-gray-800">{{ $request->order_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            <div class="font-medium">{{ $request->renter_name }}</div>
                            <div class="text-xs text-gray-500">{{ $request->renter_phone }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">
                            <div>{{ $request->start_date->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">s/d {{ $request->end_date->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $request->duration_days }} Hari</td>
                        <td class="px-6 py-4 text-sm font-semibold text-green-600">
                            Rp {{ number_format($request->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @switch($request->status)
                                @case('pending')
                                    <span class="badge badge-warning">Menunggu</span>
                                    @break
                                @case('approved')
                                    <span class="badge badge-info">Disetujui</span>
                                    @break
                                @case('in_progress')
                                    <span class="badge badge-primary">Berlangsung</span>
                                    @break
                                @case('done')
                                    <span class="badge badge-success">Selesai</span>
                                    @break
                                @case('rejected')
                                    <span class="badge badge-error">Ditolak</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-center text-sm">
                            <a href="{{ route('admin.equipment-rental-requests.show', $request) }}" 
                               class="btn btn-sm btn-info gap-1">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2 block"></i>
                            Tidak ada permintaan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection

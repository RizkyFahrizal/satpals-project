@extends('layouts.admin')

@section('title', 'Manajemen Permintaan Sewa Band')

@section('header', 'Permintaan Sewa Band')

@section('breadcrumb', 'Manajemen Band')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">📋 Permintaan Sewa Band</h1>
        <p class="text-gray-500 mt-2">Kelola permintaan sewa band dari pelanggan</p>
    </div>

    <!-- Alerts -->
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

    <!-- Filter Tabs -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-1 mb-8 flex gap-1 overflow-x-auto">
        <a href="{{ route('admin.band-rentals.index') }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === null ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-inbox text-base"></i>
            <span>Semua</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === null ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ \App\Models\BandRentalRequest::count() }}</span>
        </a>
        <a href="{{ route('admin.band-rentals.index', ['status' => 'pending']) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'pending' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-hourglass-half text-base"></i>
            <span>Menunggu</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'pending' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ \App\Models\BandRentalRequest::where('status', 'pending')->count() }}</span>
        </a>
        <a href="{{ route('admin.band-rentals.index', ['status' => 'approved']) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'approved' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-check-circle text-base"></i>
            <span>Disetujui</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'approved' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ \App\Models\BandRentalRequest::where('status', 'approved')->count() }}</span>
        </a>
        <a href="{{ route('admin.band-rentals.index', ['status' => 'rejected']) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'rejected' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-times-circle text-base"></i>
            <span>Ditolak</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'rejected' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ \App\Models\BandRentalRequest::where('status', 'rejected')->count() }}</span>
        </a>
        <a href="{{ route('admin.band-rentals.index', ['status' => 'completed']) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'completed' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-flag-checkered text-base"></i>
            <span>Selesai</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'completed' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ \App\Models\BandRentalRequest::where('status', 'completed')->count() }}</span>
        </a>
    </div>

    <!-- Table -->
    @if($rentals->count())
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Band</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Penyewa</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal Pertunjukan</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Dibuat</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentals as $rental)
                <tr class="border-b border-gray-100 hover:bg-yellow-50/30 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($rental->band->photo)
                            <img src="{{ asset('storage/' . $rental->band->photo) }}" alt="{{ $rental->band->band_name }}" 
                                 class="w-10 h-10 rounded-lg object-cover shadow-sm">
                            @else
                            <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center border border-yellow-200">
                                <i class="fas fa-music text-yellow-600"></i>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">{{ $rental->band->band_name }}</p>
                                <p class="text-xs text-gray-500">{{ $rental->band->members_count ?? 0 }} personil</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $rental->renter_name }}</p>
                            <p class="text-sm text-gray-600">{{ $rental->renter_phone }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $rental->performance_date->format('d M Y') }}</p>
                            @if($rental->performance_start_time && $rental->performance_end_time)
                            <p class="text-sm text-gray-600">{{ $rental->performance_start_time }} - {{ $rental->performance_end_time }}</p>
                            @else
                            <p class="text-sm text-gray-600">-</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($rental->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700 border border-yellow-300">
                                <i class="fas fa-hourglass-half mr-1"></i>
                                Menunggu
                            </span>
                        @elseif($rental->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700 border border-green-300">
                                <i class="fas fa-check-circle mr-1"></i>
                                Disetujui
                            </span>
                        @elseif($rental->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-700 border border-red-300">
                                <i class="fas fa-times-circle mr-1"></i>
                                Ditolak
                            </span>
                        @elseif($rental->status === 'completed')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 border border-blue-300">
                                <i class="fas fa-flag-checkered mr-1"></i>
                                Selesai
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-700 border border-gray-300">
                                {{ $rental->status }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $rental->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center">
                            <a href="{{ route('admin.band-rentals.show', $rental) }}" 
                               class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition border border-blue-200 tooltip" 
                               data-tip="Lihat Detail">
                                <i class="fas fa-eye text-base"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $rentals->links() }}
    </div>
    @else
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-12 text-center">
        <i class="fas fa-inbox text-5xl text-yellow-200 mb-4 block"></i>
        <p class="text-gray-600 font-medium">Tidak ada permintaan sewa band</p>
    </div>
    @endif
</div>
@endsection


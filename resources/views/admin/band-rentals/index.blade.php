@extends('layouts.admin')

@section('title', 'Manajemen Permintaan Sewa Band')

@section('header', 'Permintaan Sewa Band')

@section('breadcrumb', 'Manajemen Band')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Permintaan Sewa Band</h1>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filter Tabs -->
    <div class="mb-6 tabs tabs-bordered">
        <a href="{{ route('admin.band-rentals.index') }}" class="tab tab-active">
            <i class="fas fa-list mr-2"></i> Semua ({{ \App\Models\BandRentalRequest::count() }})
        </a>
        <a href="{{ route('admin.band-rentals.index', ['status' => 'pending']) }}" class="tab">
            <i class="fas fa-clock mr-2"></i> Menunggu ({{ \App\Models\BandRentalRequest::where('status', 'pending')->count() }})
        </a>
        <a href="{{ route('admin.band-rentals.index', ['status' => 'approved']) }}" class="tab">
            <i class="fas fa-check mr-2"></i> Disetujui ({{ \App\Models\BandRentalRequest::where('status', 'approved')->count() }})
        </a>
        <a href="{{ route('admin.band-rentals.index', ['status' => 'rejected']) }}" class="tab">
            <i class="fas fa-times mr-2"></i> Ditolak ({{ \App\Models\BandRentalRequest::where('status', 'rejected')->count() }})
        </a>
    </div>

    <!-- Table -->
    @if($rentals->count())
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Band</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Penyewa</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal Pertunjukan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Dibuat</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentals as $rental)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $rental->band->band_name }}</p>
                            @if($rental->band->photo)
                            <img src="{{ asset('storage/' . $rental->band->photo) }}" alt="{{ $rental->band->band_name }}" 
                                 class="w-8 h-8 rounded mt-1 object-cover">
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $rental->renter_name }}</p>
                            <p class="text-sm text-gray-600">{{ $rental->renter_phone }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-800">{{ $rental->performance_date->format('d M Y') }}</p>
                        <p class="text-sm text-gray-600">{{ $rental->performance_date->format('H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        @if($rental->status === 'pending')
                            <span class="badge badge-warning badge-outline">Menunggu</span>
                        @elseif($rental->status === 'approved')
                            <span class="badge badge-success badge-outline">Disetujui</span>
                        @elseif($rental->status === 'rejected')
                            <span class="badge badge-error badge-outline">Ditolak</span>
                        @else
                            <span class="badge badge-info badge-outline">Selesai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $rental->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('admin.band-rentals.show', $rental) }}" 
                               class="btn btn-sm btn-info" 
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center">
        {{ $rentals->links() }}
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <i class="fas fa-inbox text-4xl text-gray-300 mb-4 block"></i>
        <p class="text-gray-500 text-lg">Tidak ada permintaan sewa band</p>
    </div>
    @endif
</div>
@endsection

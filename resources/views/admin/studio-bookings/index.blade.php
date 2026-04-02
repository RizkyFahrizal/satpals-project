@extends('layouts.admin')

@section('title', 'Kelola Booking Studio')
@section('header', 'Booking Studio')
@section('breadcrumb', 'Kelola Penjadwalan Studio')

@section('content')
<div class="space-y-6">
    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-error shadow-lg">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ implode(', ', $errors->all()) }}</span>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success shadow-lg">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error shadow-lg">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info shadow-lg">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="tabs tabs-lifted bg-white shadow-lg rounded-t-lg">
        <input type="radio" name="studio_tabs" label="📋 Daftar Booking" class="tab" checked />
        <div class="tab-content bg-white rounded-lg shadow-lg p-6">
            <!-- All Bookings Table -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Daftar Semua Booking</h2>
                        <p class="text-gray-500 text-sm mt-1">Total: <strong>{{ $allBookings->total() }}</strong> booking</p>
                    </div>
                </div>

                <!-- Search & Filter Section -->
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <form method="GET" action="{{ route('admin.studio-bookings.index') }}" class="flex flex-col md:flex-row gap-4">
                        <!-- Search by Nama/NPM -->
                        <div class="flex-1">
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       placeholder="Cari nama pembooking atau NPM..."
                                       value="{{ request('search') }}"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Filter by Tanggal -->
                        <div class="w-full md:w-48">
                            <input type="date" 
                                   name="filter_tanggal" 
                                   value="{{ request('filter_tanggal') }}"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                        </div>

                        <!-- Filter by Status -->
                        <div class="w-full md:w-48">
                            <select name="filter_status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('filter_status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="approved" {{ request('filter_status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="rejected" {{ request('filter_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                <option value="completed" {{ request('filter_status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <button type="submit" class="px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold rounded-xl transition-all whitespace-nowrap">
                                Filter
                            </button>
                            @if(request('search') || request('filter_tanggal') || request('filter_status'))
                            <a href="{{ route('admin.studio-bookings.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all whitespace-nowrap">
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr class="bg-gray-100 text-gray-800">
                                <th class="text-left">NPM / Nama</th>
                                <th class="text-left">Tanggal & Sesi</th>
                                <th class="text-left">Keperluan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allBookings as $booking)
                                <tr class="border-b hover:bg-gray-50 transition-colors">
                                    <td>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $booking->nama_pemohon ?? ($booking->user?->name ?? 'N/A') }}</p>
                                            <p class="text-sm text-gray-500">{{ $booking->nomor_identitas ?? ($booking->user?->email ?? '-') }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->locale('id')->translatedFormat('d F Y') }}</p>
                                            <p class="text-sm text-gray-500">{{ $booking->sesi_label }} ({{ $booking->sesi_time }})</p>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm text-gray-600 line-clamp-2 max-w-xs">{{ $booking->keperluan }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if ($booking->status === 'pending')
                                            <span class="badge badge-warning gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Menunggu
                                            </span>
                                        @elseif ($booking->status === 'approved')
                                            <span class="badge badge-success gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Disetujui
                                            </span>
                                        @elseif ($booking->status === 'rejected')
                                            <span class="badge badge-error gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Ditolak
                                            </span>
                                        @elseif ($booking->status === 'completed')
                                            <span class="badge badge-info gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Selesai
                                            </span>
                                        @else
                                            <span class="badge badge-ghost">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center space-x-2 flex flex-wrap gap-1 justify-center">
                                        <!-- View Detail -->
                                        <a href="{{ route('admin.studio-bookings.show', $booking->id) }}" 
                                           class="btn btn-xs btn-info gap-1"
                                           title="Lihat Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>

                                        <!-- Approve Button (only for pending) -->
                                        @if ($booking->status === 'pending')
                                            <form action="{{ route('admin.studio-bookings.approve', $booking->id) }}" 
                                                  method="POST" 
                                                  class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-xs btn-success gap-1"
                                                        title="Terima Booking">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Terima
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Reject Button (only for pending) -->
                                        @if ($booking->status === 'pending')
                                            <button class="btn btn-xs btn-error gap-1" 
                                                    onclick="openRejectModal({{ $booking->id }})"
                                                    title="Tolak Booking">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Tolak
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-12">
                                        <div class="text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-lg font-medium">Tidak ada booking</p>
                                            <p class="text-sm">Belum ada booking yang dibuat</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($allBookings->hasPages())
                    <div class="flex justify-center">
                        {{ $allBookings->links() }}
                    </div>
                @endif
            </div>
        </div>

        <input type="radio" name="studio_tabs" label="📅 Kalender" class="tab" />
        <div class="tab-content bg-white rounded-lg shadow-lg p-6">
            <!-- Calendar View (Original) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Calendar Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Kalender Booking</h2>
                    <p class="text-gray-500 text-sm mt-1">Pilih tanggal untuk melihat ketersediaan sesi</p>
                </div>
            </div>

            <!-- Mini Calendar -->
            <div class="card bg-white shadow-lg border border-yellow-200">
                <div class="card-body p-6">
                    <!-- Date Selector -->
                    <div class="flex gap-3 mb-4">
                        <input type="date" 
                               id="dateInput"
                               class="input input-bordered input-sm flex-1" 
                               value="{{ $selectedDate->format('Y-m-d') }}"
                               onchange="document.location='?date=' + this.value">
                        <span class="text-gray-500 text-sm flex items-center">
                            <strong class="text-lg">{{ $selectedDate->format('d') }}</strong>
                            <span class="ml-2">{{ $selectedDate->locale('id')->translatedFormat('F Y') }}</span>
                        </span>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-center text-sm">
                            <thead>
                                <tr class="border-b-2 border-yellow-200">
                                    <th class="py-2 text-gray-600 font-semibold">Min</th>
                                    <th class="py-2 text-gray-600 font-semibold">Sel</th>
                                    <th class="py-2 text-gray-600 font-semibold">Rab</th>
                                    <th class="py-2 text-gray-600 font-semibold">Kam</th>
                                    <th class="py-2 text-gray-600 font-semibold">Jum</th>
                                    <th class="py-2 text-gray-600 font-semibold">Sab</th>
                                    <th class="py-2 text-gray-600 font-semibold">Min</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $firstDay = $selectedDate->copy()->startOfMonth();
                                    $lastDay = $selectedDate->copy()->endOfMonth();
                                    $startDate = $firstDay->copy()->startOfWeek();
                                    $endDate = $lastDay->copy()->endOfWeek();
                                    $currentDate = $startDate->copy();
                                    $weekNum = 0;
                                @endphp

                                @while ($currentDate <= $endDate)
                                    @if ($weekNum % 7 === 0)
                                        <tr class="border-b border-gray-100">
                                    @endif

                                    <td class="py-3 px-1">
                                        @if ($currentDate->month === $selectedDate->month)
                                            <a href="?date={{ $currentDate->format('Y-m-d') }}" 
                                               class="block py-2 px-2 rounded-lg font-medium transition-all
                                                   {{ $currentDate->format('Y-m-d') === $selectedDate->format('Y-m-d') 
                                                       ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-white shadow-md' 
                                                       : 'hover:bg-yellow-50 text-gray-700' }}
                                                   {{ isset($bookingsForMonth[$currentDate->format('Y-m-d')]) ? 'ring-2 ring-yellow-300' : '' }}">
                                                {{ $currentDate->day }}
                                                @if (isset($bookingsForMonth[$currentDate->format('Y-m-d')]))
                                                    <div class="text-xs opacity-75 mt-0.5">
                                                        {{ $bookingsForMonth[$currentDate->format('Y-m-d')]->count() }} booking
                                                    </div>
                                                @endif
                                            </a>
                                        @else
                                            <span class="text-gray-300 font-medium">{{ $currentDate->day }}</span>
                                        @endif
                                    </td>

                                    @if ($weekNum % 7 === 6)
                                        </tr>
                                    @endif

                                    @php 
                                        $currentDate->addDay();
                                        $weekNum++;
                                    @endphp
                                @endwhile
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sesi Details -->
            <div class="card bg-white shadow-lg border border-yellow-200">
                <div class="card-body p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        Jadwal Sesi - <span class="text-yellow-600">{{ $selectedDate->locale('id')->translatedFormat('d F Y') }}</span>
                    </h3>

                    <div class="space-y-3">
                        @forelse ($sesiData as $sesiNum => $sesi)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-yellow-300 hover:shadow-md transition-all
                                {{ $sesi['booking'] ? 'bg-gray-50' : 'bg-white' }}">
                                <div class="flex items-center justify-between gap-4">
                                    <!-- Sesi Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-bold text-gray-800">{{ $sesi['label'] }}</h4>
                                            <span class="text-sm text-gray-500">{{ $sesi['time'] }}</span>
                                        </div>

                                        @if ($sesi['booking'])
                                            <div class="bg-white p-3 rounded-lg border-l-4 border-yellow-400 mt-2">
                                                <p class="font-semibold text-gray-800">{{ $sesi['booking']->user?->name ?? $sesi['booking']->nama_pemohon }}</p>
                                                <p class="text-sm text-gray-600">NPM: {{ $sesi['booking']->user?->username ?? $sesi['booking']->nomor_identitas }}</p>
                                                <p class="text-sm text-gray-700 mt-2">{{ $sesi['booking']->keperluan }}</p>
                                                <div class="flex gap-2 mt-2">
                                                    <span class="badge 
                                                        @if($sesi['booking']->status === 'pending') badge-warning
                                                        @elseif($sesi['booking']->status === 'approved') badge-success
                                                        @elseif($sesi['booking']->status === 'rejected') badge-error
                                                        @endif">
                                                        {{ ucfirst($sesi['booking']->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <span class="badge badge-success">Tersedia</span>
                                                <span class="text-sm text-gray-500">Sesi ini masih kosong</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex gap-2">
                                        @if ($sesi['booking'])
                                            <a href="{{ route('admin.studio-bookings.show', $sesi['booking']->id) }}" 
                                               class="btn btn-sm btn-outline btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                </svg>
                                                Detail
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>Tidak ada data sesi</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Approvals Sidebar -->
        <div class="lg:col-span-1">
            <div class="card bg-white shadow-lg border border-yellow-200 sticky top-24">
                <div class="card-body p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Menunggu Approval</h3>
                        <span class="badge badge-warning">{{ $pendingBookings->count() }}</span>
                    </div>

                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @forelse ($pendingBookings as $booking)
                            <a href="{{ route('admin.studio-bookings.show', $booking->id) }}" 
                               class="block p-3 border border-yellow-200 rounded-lg hover:bg-yellow-50 transition-colors">
                                <div class="flex items-start justify-between gap-2 mb-2">
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $booking->user?->name ?? $booking->nama_pemohon }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->user?->username ?? $booking->nomor_identitas }}</p>
                                    </div>
                                    <span class="badge badge-warning badge-sm">Pending</span>
                                </div>
                                
                                <div class="text-xs text-gray-600 space-y-1 mb-2">
                                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->tanggal_booking)->locale('id')->translatedFormat('d F Y') }}</p>
                                    <p><strong>Sesi:</strong> {{ $booking->sesi_label }}</p>
                                </div>

                                <p class="text-xs text-gray-600 line-clamp-2">{{ $booking->keperluan }}</p>

                                <div class="mt-2 text-xs text-gray-400">
                                    {{ $booking->created_at->diffForHumans() }}
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-8">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                </svg>
                                <p class="text-gray-500 text-sm">Tidak ada booking pending</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<dialog id="rejectModal" class="modal">
    <div class="modal-box w-11/12 max-w-md">
        <h3 class="font-bold text-lg mb-4">Tolak Booking</h3>
        <form id="rejectForm" method="POST" action="">
            @csrf
            
            <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menolak booking ini?</p>

            <div class="modal-action">
                <button type="button" class="btn" onclick="rejectModal.close()">Batal</button>
                <button type="submit" class="btn btn-error">Tolak</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    const rejectModal = document.getElementById('rejectModal');
    const rejectForm = document.getElementById('rejectForm');

    function openRejectModal(bookingId) {
        rejectForm.action = `/admin/studio-bookings/${bookingId}/reject`;
        rejectModal.showModal();
    }
</script>
@endsection


@extends('layouts.admin')

@section('title', 'Manajemen Booking Studio')
@section('header', 'Booking Studio')
@section('breadcrumb', 'Manajemen Booking Studio')

@section('content')
<div class="container mx-auto px-4 py-8">

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

    @if(session('info'))
    <div class="alert alert-info mb-6 shadow-md rounded-2xl border border-blue-200 bg-gradient-to-r from-blue-50 to-sky-50">
        <i class="fas fa-info-circle text-blue-600 text-lg"></i>
        <span class="text-blue-800 font-medium">{{ session('info') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Pengaturan Harga Studio</h2>
                <p class="text-sm text-gray-500 mt-1">Harga per orang non-UKM yang dipakai untuk estimasi booking</p>
            </div>
            <form action="{{ route('admin.studio-bookings.settings') }}" method="POST" class="flex gap-3 items-end">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga per Orang</label>
                    <input type="number" name="price_per_person" min="0" value="{{ old('price_per_person', $pricePerPerson) }}" class="input input-bordered w-full md:w-56" required>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-xl shadow-md transition-all">
                    Simpan
                </button>
            </form>
        </div>
    </div>

    @php
        $baseFilters = request()->only(['search', 'filter_tanggal']);
        $totalCount = \App\Models\StudioBooking::count();
        $pendingCount = \App\Models\StudioBooking::where('status', 'pending')->count();
        $approvedCount = \App\Models\StudioBooking::where('status', 'approved')->count();
        $rejectedCount = \App\Models\StudioBooking::where('status', 'rejected')->count();
        $cancelledCount = \App\Models\StudioBooking::where('status', 'cancelled')->count();
        $completedCount = \App\Models\StudioBooking::where('status', 'completed')->count();
    @endphp

    <div class="tabs tabs-lifted mb-8">
        <input type="radio" name="studio_tabs" class="tab whitespace-nowrap px-6 min-w-fit" aria-label="📋 Daftar Booking" {{ request('view') === 'calendar' ? '' : 'checked' }} />
        <div class="tab-content bg-base-100 border-base-300 rounded-box p-6">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-1 mb-8 flex gap-1 overflow-x-auto">
                <a href="{{ route('admin.studio-bookings.index', $baseFilters) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === null ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm whitespace-nowrap">
            <i class="fas fa-inbox text-base"></i>
            <span>Semua</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === null ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $totalCount }}</span>
                </a>
                <a href="{{ route('admin.studio-bookings.index', array_merge($baseFilters, ['status' => 'pending'])) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'pending' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm whitespace-nowrap">
            <i class="fas fa-hourglass-half text-base"></i>
            <span>Menunggu</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'pending' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $pendingCount }}</span>
                </a>
                <a href="{{ route('admin.studio-bookings.index', array_merge($baseFilters, ['status' => 'approved'])) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'approved' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm whitespace-nowrap">
            <i class="fas fa-check-circle text-base"></i>
            <span>Disetujui</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'approved' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $approvedCount }}</span>
                </a>
                <a href="{{ route('admin.studio-bookings.index', array_merge($baseFilters, ['status' => 'rejected'])) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'rejected' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm whitespace-nowrap">
            <i class="fas fa-times-circle text-base"></i>
            <span>Ditolak</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'rejected' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $rejectedCount }}</span>
                </a>
                <a href="{{ route('admin.studio-bookings.index', array_merge($baseFilters, ['status' => 'cancelled'])) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'cancelled' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm whitespace-nowrap">
            <i class="fas fa-ban text-base"></i>
            <span>Dibatalkan</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'cancelled' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $cancelledCount }}</span>
                </a>
                <a href="{{ route('admin.studio-bookings.index', array_merge($baseFilters, ['status' => 'completed'])) }}" class="flex-1 min-w-fit px-5 py-3 rounded-xl {{ request('status') === 'completed' ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 font-semibold shadow-md' : 'text-gray-700 hover:bg-gray-50' }} transition flex items-center justify-center gap-2 text-sm whitespace-nowrap">
            <i class="fas fa-flag-checkered text-base"></i>
            <span>Selesai</span>
            <span class="inline-flex items-center justify-center w-6 h-6 {{ request('status') === 'completed' ? 'bg-white text-gray-900' : 'bg-gray-100 text-gray-700' }} rounded-lg text-xs font-bold">{{ $completedCount }}</span>
                </a>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
                <form method="GET" action="{{ route('admin.studio-bookings.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text"
                                   name="search"
                                   placeholder="Cari nama pembooking, NPM, email, atau kode booking..."
                                   value="{{ request('search') }}"
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="w-full md:w-48">
                        <input type="date"
                               name="filter_tanggal"
                               value="{{ request('filter_tanggal') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    </div>

                    <div class="w-full md:w-48">
                        <select name="filter_status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('filter_status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="approved" {{ request('filter_status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected" {{ request('filter_status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            <option value="cancelled" {{ request('filter_status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            <option value="completed" {{ request('filter_status') === 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold rounded-xl transition-all whitespace-nowrap">Filter</button>
                        @if(request('search') || request('filter_tanggal') || request('filter_status'))
                            <a href="{{ route('admin.studio-bookings.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all whitespace-nowrap">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            @if($allBookings->count())
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Pembooking</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal & Sesi</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kode Booking</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Dibuat</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allBookings as $booking)
                        <tr class="border-b border-gray-100 hover:bg-yellow-50/30 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $booking->nama_pemohon ?? ($booking->user?->name ?? 'N/A') }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->renter_phone ?? $booking->nomor_identitas ?? ($booking->user?->email ?? '-') }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $booking->tanggal_booking ? \Carbon\Carbon::parse($booking->tanggal_booking)->locale('id')->translatedFormat('d M Y') : '-' }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->sesi_label }} ({{ $booking->sesi_time }})</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->booking_code)
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-blue-100 text-blue-700 border border-blue-300">{{ $booking->booking_code }}</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-semibold bg-gray-100 text-gray-600 border border-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700 border border-yellow-300"><i class="fas fa-hourglass-half mr-1"></i>Menunggu</span>
                                @elseif($booking->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-700 border border-green-300"><i class="fas fa-check-circle mr-1"></i>Disetujui</span>
                                @elseif($booking->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-700 border border-red-300"><i class="fas fa-times-circle mr-1"></i>Ditolak</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-700 border border-red-300"><i class="fas fa-ban mr-1"></i>Dibatalkan</span>
                                @elseif($booking->status === 'completed')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-700 border border-blue-300"><i class="fas fa-flag-checkered mr-1"></i>Selesai</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-700 border border-gray-300">{{ $booking->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <a href="{{ route('admin.studio-bookings.show', $booking) }}" class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition border border-blue-200 tooltip" data-tip="Lihat Detail">
                                        <i class="fas fa-eye text-base"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-center">
                {{ $allBookings->links() }}
            </div>
            @else
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-12 text-center">
                <i class="fas fa-inbox text-5xl text-yellow-200 mb-4 block"></i>
                <p class="text-gray-600 font-medium">Tidak ada booking studio</p>
            </div>
            @endif
        </div>

        <input type="radio" name="studio_tabs" class="tab whitespace-nowrap px-6 min-w-fit" aria-label="📅 Kalender Booking" {{ request('view') === 'calendar' ? 'checked' : '' }} />
        <div class="tab-content bg-base-100 border-base-300 rounded-box p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Kalender Booking</h2>
                            <p class="text-gray-500 text-sm mt-1">Pilih tanggal untuk melihat ketersediaan sesi</p>
                        </div>
                    </div>

                    <div class="card bg-white shadow-lg border border-yellow-200">
                        <div class="card-body p-6">
                            <div class="flex gap-3 mb-4">
                                <input type="date"
                                       id="dateInput"
                                       class="input input-bordered input-sm flex-1"
                                       value="{{ $selectedDate->format('Y-m-d') }}"
                                       onchange="document.location='?date=' + this.value + '&view=calendar'">
                                <span class="text-gray-500 text-sm flex items-center">
                                    <strong class="text-lg">{{ $selectedDate->format('d') }}</strong>
                                    <span class="ml-2">{{ $selectedDate->locale('id')->translatedFormat('F Y') }}</span>
                                </span>
                            </div>

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
                                                    <a href="?date={{ $currentDate->format('Y-m-d') }}&view=calendar"
                                                       class="block py-2 px-2 rounded-lg font-medium transition-all
                                                           {{ $currentDate->format('Y-m-d') === $selectedDate->format('Y-m-d') ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-white shadow-md' : 'hover:bg-yellow-50 text-gray-700' }}
                                                           {{ isset($bookingsForMonth[$currentDate->format('Y-m-d')]) ? 'ring-2 ring-yellow-300' : '' }}">
                                                        {{ $currentDate->day }}
                                                        @if (isset($bookingsForMonth[$currentDate->format('Y-m-d')]))
                                                            <div class="text-xs opacity-75 mt-0.5">{{ $bookingsForMonth[$currentDate->format('Y-m-d')]->count() }} booking</div>
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

                    <div class="card bg-white shadow-lg border border-yellow-200">
                        <div class="card-body p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">
                                Jadwal Sesi - <span class="text-yellow-600">{{ $selectedDate->locale('id')->translatedFormat('d F Y') }}</span>
                            </h3>

                            <div class="space-y-3">
                                @forelse ($sesiData as $sesi)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-yellow-300 hover:shadow-md transition-all {{ $sesi['booking'] ? 'bg-gray-50' : 'bg-white' }}">
                                        <div class="flex items-center justify-between gap-4">
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
                                                                @elseif($sesi['booking']->status === 'cancelled') badge-error
                                                                @elseif($sesi['booking']->status === 'completed') badge-info
                                                                @endif">
                                                                {{ $sesi['booking']->statusLabel }}
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

                                            <div class="flex gap-2">
                                                @if ($sesi['booking'])
                                                    <a href="{{ route('admin.studio-bookings.show', $sesi['booking']->id) }}" class="btn btn-sm btn-outline btn-primary">
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

                <div class="lg:col-span-1">
                    <div class="card bg-white shadow-lg border border-yellow-200 sticky top-24">
                        <div class="card-body p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-800">Menunggu Approval</h3>
                                <span class="badge badge-warning">{{ $pendingBookings->count() }}</span>
                            </div>

                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @forelse ($pendingBookings as $booking)
                                    <a href="{{ route('admin.studio-bookings.show', $booking->id) }}" class="block p-3 border border-yellow-200 rounded-lg hover:bg-yellow-50 transition-colors">
                                        <div class="flex items-start justify-between gap-2 mb-2">
                                            <div>
                                                <p class="font-semibold text-gray-800 text-sm">{{ $booking->user?->name ?? $booking->nama_pemohon }}</p>
                                                <p class="text-xs text-gray-500">{{ $booking->user?->username ?? $booking->nomor_identitas }}</p>
                                            </div>
                                            <span class="badge badge-warning badge-sm">Menunggu</span>
                                        </div>

                                        <div class="text-xs text-gray-600 space-y-1 mb-2">
                                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->tanggal_booking)->locale('id')->translatedFormat('d F Y') }}</p>
                                            <p><strong>Sesi:</strong> {{ $booking->sesi_label }}</p>
                                        </div>

                                        <p class="text-xs text-gray-600 line-clamp-2">{{ $booking->keperluan }}</p>

                                        <div class="mt-2 text-xs text-gray-400">{{ $booking->created_at->diffForHumans() }}</div>
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
    </div>
</div>
@endsection


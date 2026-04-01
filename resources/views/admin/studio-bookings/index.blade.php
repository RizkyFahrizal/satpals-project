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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Calendar Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header dengan Tombol -->
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Kalender Booking</h2>
                    <p class="text-gray-500 text-sm mt-1">Pilih tanggal untuk melihat ketersediaan sesi</p>
                </div>
                <a href="{{ route('admin.studio-bookings.create') }}" class="btn btn-warning btn-sm gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Tambah Booking
                </a>
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
                                                <p class="font-semibold text-gray-800">{{ $sesi['booking']->user->name }}</p>
                                                <p class="text-sm text-gray-600">NPM: {{ $sesi['booking']->user->username }}</p>
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
                                        @else
                                            <a href="{{ route('admin.studio-bookings.create', ['date' => $selectedDate->format('Y-m-d'), 'sesi' => $sesiNum]) }}" 
                                               class="btn btn-sm btn-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                                </svg>
                                                Book
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
                                        <p class="font-semibold text-gray-800 text-sm">{{ $booking->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $booking->user->username }}</p>
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
@endsection


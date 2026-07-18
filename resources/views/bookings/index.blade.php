@extends('layouts.app')

@section('title', 'Pesanan Saya - Satya Palapa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Pesanan Saya</h1>

    <!-- Search by Email (for guest users) -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari dengan Email</label>
                <form method="GET" action="{{ route('bookings.index') }}" class="flex gap-2">
                    <input type="email" name="email" placeholder="Masukkan email penyewa" class="input input-bordered flex-1" value="{{ $email ?? '' }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </form>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <form method="GET" action="{{ route('bookings.index') }}" class="flex gap-2">
                    <input type="hidden" name="email" value="{{ $email ?? '' }}">
                    <select name="status" class="select select-bordered flex-1" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ $selectedStatus === $status ? 'selected' : '' }}>
                            {{ $status === 'completed' ? 'Selesai' : ($status === 'cancelled' ? 'Dibatalkan' : ucfirst($status)) }}
                        </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    @if($bookings->count() > 0)
    <!-- Bookings List -->
    <div class="space-y-4">
        @foreach($bookings as $booking)
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Order Info -->
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Nomor Pesanan</p>
                    <p class="text-lg font-bold text-gray-800">{{ $booking->order_number }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $booking->created_at->format('d M Y H:i') }}</p>
                </div>

                <!-- Renter Info -->
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Penyewa</p>
                    <p class="font-semibold text-gray-800">{{ $booking->renter_name }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ $booking->renter_phone }}</p>
                </div>

                <!-- Rental Period -->
                <div>
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Periode</p>
                    <p class="font-semibold text-gray-800">{{ $booking->duration_days }} hari</p>
                    <p class="text-xs text-gray-600 mt-1">
                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d M') }} - 
                        {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                    </p>
                </div>

                <!-- Total & Status -->
                <div class="flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-gray-600 uppercase tracking-wide">Total</p>
                        <p class="text-2xl font-bold text-green-600">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2 justify-between">
                        <span class="badge badge-lg {{ 
                            $booking->status === 'pending' ? 'badge-warning' :
                            ($booking->status === 'approved' ? 'badge-info' :
                            ($booking->status === 'completed' || $booking->status === 'done' ? 'badge-success' :
                            ($booking->status === 'cancelled' ? 'badge-neutral' : 'badge-error')))
                        }}">
                            {{ $booking->status === 'completed' ? 'Selesai' : ($booking->status === 'done' ? 'Selesai' : ($booking->status === 'cancelled' ? 'Dibatalkan' : ucfirst($booking->status))) }}
                        </span>
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-ghost">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-8">
        {{ $bookings->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow-lg p-12 text-center">
        <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Tidak Ada Pesanan</h2>
        <p class="text-gray-600 mb-6">
            @if($email)
            Tidak ada pesanan untuk email <strong>{{ $email }}</strong>
            @else
            Mulai buat pesanan dengan menyewa peralatan
            @endif
        </p>
        <a href="{{ route('equipment.index') }}" class="btn btn-primary gap-2">
            <i class="fas fa-shopping-cart"></i> Mulai Berbelanja
        </a>
    </div>
    @endif
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Detail Permintaan Persewaan Alat')
@section('header', 'Detail Permintaan Persewaan Alat')
@section('breadcrumb', 'Manajemen Persewaan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail Permintaan</h1>
            <p class="text-gray-500 mt-2">{{ $rentalRequest->order_number }}</p>
        </div>
        <a href="{{ route('admin.equipment-rental-requests.index') }}" class="btn btn-ghost">
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $rentalRequest->renter_name }}</h2>
                        <p class="text-gray-500">{{ $rentalRequest->renter_email }}</p>
                    </div>
                    @php
                        $status = $rentalRequest->status;
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                        {{ $status === 'pending' ? 'bg-yellow-100 text-yellow-700 border border-yellow-300' : '' }}
                        {{ $status === 'approved' ? 'bg-green-100 text-green-700 border border-green-300' : '' }}
                        {{ $status === 'rejected' ? 'bg-red-100 text-red-700 border border-red-300' : '' }}
                        {{ $status === 'cancelled' ? 'bg-gray-100 text-gray-700 border border-gray-300' : '' }}
                        {{ $status === 'completed' || $status === 'done' ? 'bg-emerald-100 text-emerald-700 border border-emerald-300' : '' }}">
                        {{ $status === 'done' ? 'Selesai' : ucfirst($status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Nomor Pesanan</p>
                        <p class="font-semibold text-gray-900">{{ $rentalRequest->order_number }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Lokasi Sewa</p>
                        <p class="font-semibold text-gray-900">{{ $rentalRequest->rental_location }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Tanggal Sewa</p>
                        <p class="font-semibold text-gray-900">{{ $rentalRequest->start_date->format('d M Y') }} - {{ $rentalRequest->end_date->format('d M Y') }}</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <p class="text-sm text-gray-500 mb-1">Durasi</p>
                        <p class="font-semibold text-gray-900">{{ $rentalRequest->duration_days }} Hari</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-900">Peralatan</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Qty</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Harga/Hari</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rentalRequest->items as $item)
                        <tr class="border-b border-gray-100">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $item->equipment->name }}</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($item->equipment->category) }}</p>
                            </td>
                            <td class="px-6 py-4">{{ $item->quantity }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Catatan Admin</h3>
                <p class="text-gray-700 whitespace-pre-line">{{ $rentalRequest->admin_notes ?? '-' }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Ringkasan</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4"><span class="text-gray-500">Total Harga</span><span class="font-semibold text-gray-900">Rp {{ number_format($rentalRequest->total_price, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between gap-4"><span class="text-gray-500">Tanggal Dibuat</span><span class="font-semibold text-gray-900">{{ $rentalRequest->created_at->format('d M Y H:i') }}</span></div>
                    <div class="flex justify-between gap-4"><span class="text-gray-500">Disetujui</span><span class="font-semibold text-gray-900">{{ $rentalRequest->approved_at?->format('d M Y H:i') ?? '-' }}</span></div>
                </div>

                @if($rentalRequest->income)
                <div class="mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200">
                    <p class="text-sm text-emerald-700 font-semibold mb-1">Income Terkait</p>
                    <p class="text-gray-900 font-bold">{{ $rentalRequest->income->title }}</p>
                    <p class="text-sm text-gray-600">Rp {{ number_format($rentalRequest->income->nominal, 0, ',', '.') }} · {{ ucfirst($rentalRequest->income->status) }}</p>
                </div>
                @endif

                @if(in_array($rentalRequest->status, ['approved', 'completed', 'done'], true))
                <div class="mt-6 flex flex-col gap-3">
                    <a href="{{ route('invoice.view', $rentalRequest->id) }}" class="btn btn-outline btn-warning w-full">Lihat Invoice</a>
                    <a href="{{ route('invoice.download', $rentalRequest->id) }}" class="btn bg-yellow-400 text-gray-900 border-0 hover:bg-yellow-500 w-full">Download Invoice</a>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Aksi</h3>
                <div class="space-y-3">
                    @if($rentalRequest->status === 'pending')
                        <form action="{{ route('admin.equipment-rental-requests.approve', $rentalRequest) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn bg-green-500 hover:bg-green-600 text-white border-0 w-full">Approve</button>
                        </form>
                        <form action="{{ route('admin.equipment-rental-requests.reject', $rentalRequest) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn bg-red-500 hover:bg-red-600 text-white border-0 w-full">Tolak</button>
                        </form>
                    @elseif($rentalRequest->status === 'approved')
                        <form action="{{ route('admin.equipment-rental-requests.mark-in-progress', $rentalRequest) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn bg-gray-600 hover:bg-gray-700 text-white border-0 w-full">Batalkan</button>
                        </form>
                        <form action="{{ route('admin.equipment-rental-requests.complete', $rentalRequest) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn bg-emerald-500 hover:bg-emerald-600 text-white border-0 w-full">Selesai</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
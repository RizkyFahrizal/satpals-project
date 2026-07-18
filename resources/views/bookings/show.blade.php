@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $booking->order_number . ' - Satya Palapa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <a href="{{ route('bookings.index') }}" class="btn btn-ghost mb-6">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Header -->
            <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg shadow-lg p-6 border border-blue-200">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm text-gray-600 uppercase tracking-wide">Nomor Pesanan</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $booking->order_number }}</p>
                    </div>
                    <span class="badge badge-xl {{ 
                        $booking->status === 'pending' ? 'badge-warning' :
                        ($booking->status === 'approved' ? 'badge-info' :
                        ($booking->status === 'completed' || $booking->status === 'done' ? 'badge-success' :
                        ($booking->status === 'cancelled' ? 'badge-neutral' : 'badge-error')))
                    }}">
                        {{ $booking->status === 'completed' ? 'Selesai' : ($booking->status === 'done' ? 'Selesai' : ($booking->status === 'cancelled' ? 'Dibatalkan' : ucfirst($booking->status))) }}
                    </span>
                </div>
                <p class="text-sm text-gray-600">Dibuat: {{ $booking->created_at->format('d M Y H:i') }}</p>
            </div>

            <!-- Items -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Peralatan yang Disewa</h2>

                <div class="space-y-4">
                    @foreach($booking->items as $item)
                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-bold text-gray-800">{{ $item->equipment->name }}</p>
                                <p class="text-sm text-gray-600">
                                    <span class="badge {{ $item->equipment->category === 'paket' ? 'badge-info' : 'badge-warning' }}">
                                        {{ ucfirst($item->equipment->category) }}
                                    </span>
                                </p>
                            </div>
                            <span class="badge badge-lg">Qty: {{ $item->quantity }}</span>
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Harga/Hari</p>
                                <p class="font-bold text-gray-800">Rp {{ number_format($item->price_per_day, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Durasi</p>
                                <p class="font-bold text-gray-800">{{ $booking->duration_days }} hari</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600">Subtotal</p>
                                <p class="font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Penyewa Info -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Informasi Penyewa</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                        <p class="font-semibold text-gray-800">{{ $booking->renter_name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">NPM / NIK</p>
                        <p class="font-semibold text-gray-800">{{ $booking->renter_npm_nik }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">No. Telepon</p>
                        <p class="font-semibold text-gray-800">{{ $booking->renter_phone }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="font-semibold text-gray-800">{{ $booking->renter_email }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600 mb-1">Lokasi Penyewaan</p>
                        <p class="font-semibold text-gray-800">{{ $booking->rental_location }}</p>
                    </div>

                    @if($booking->renter_ktp_ktm)
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600 mb-2">KTP / KTM</p>
                        <a href="{{ asset('storage/' . $booking->renter_ktp_ktm) }}" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-2">
                            <i class="fas fa-file-pdf"></i> Lihat Dokumen
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Rental Period -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Periode Penyewaan</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <p class="text-sm text-blue-600 font-medium">Tanggal Mulai</p>
                        <p class="text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}</p>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <p class="text-sm text-green-600 font-medium">Tanggal Selesai</p>
                        <p class="text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}</p>
                    </div>

                    <div class="md:col-span-2 bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <p class="text-sm text-purple-600 font-medium">Durasi Penyewaan</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $booking->duration_days }} Hari</p>
                    </div>
                </div>
            </div>

            <!-- Admin Notes -->
            @if($booking->status === 'rejected' && $booking->admin_notes)
            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6">
                <h2 class="text-lg font-bold text-red-800 mb-2">Alasan Penolakan</h2>
                <p class="text-gray-700">{{ $booking->admin_notes }}</p>
            </div>
            @endif

            @if($booking->status === 'approved' && $booking->admin_notes)
            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-6">
                <h2 class="text-lg font-bold text-green-800 mb-2">Catatan Admin</h2>
                <p class="text-gray-700">{{ $booking->admin_notes }}</p>
            </div>
            @endif

            <!-- Notes -->
            @if($booking->renter_notes)
            <div class="bg-gray-50 border-l-4 border-gray-500 rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2">Catatan Penyewa</h2>
                <p class="text-gray-700">{{ $booking->renter_notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Summary Card -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg shadow-lg p-6 border border-purple-200 sticky top-20">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Ringkasan Pesanan</h2>

                <!-- Status Info -->
                <div class="mb-6 pb-6 border-b">
                    <p class="text-sm text-gray-600 mb-2">Status Pesanan</p>
                    <span class="badge badge-lg {{ 
                        $booking->status === 'pending' ? 'badge-warning' :
                        ($booking->status === 'approved' ? 'badge-info' :
                        ($booking->status === 'completed' || $booking->status === 'done' ? 'badge-success' :
                        ($booking->status === 'cancelled' ? 'badge-neutral' : 'badge-error')))
                    }}">
                        {{ $booking->status === 'completed' ? 'Selesai' : ($booking->status === 'done' ? 'Selesai' : ($booking->status === 'cancelled' ? 'Dibatalkan' : ucfirst($booking->status))) }}
                    </span>

                    @if($booking->status === 'pending')
                    <p class="text-xs text-gray-600 mt-2">Menunggu persetujuan dari admin</p>
                    @elseif($booking->status === 'approved')
                    <p class="text-xs text-gray-600 mt-2">Pesanan telah disetujui, silakan lakukan pembayaran</p>
                    @elseif($booking->status === 'cancelled')
                    <p class="text-xs text-gray-600 mt-2">Pesanan telah dibatalkan</p>
                    @elseif($booking->status === 'completed' || $booking->status === 'done')
                    <p class="text-xs text-gray-600 mt-2">Penyewaan telah selesai</p>
                    @elseif($booking->status === 'rejected')
                    <p class="text-xs text-red-600 mt-2">Pesanan telah ditolak</p>
                    @endif
                </div>

                <!-- Items Count -->
                <div class="mb-6 pb-6 border-b">
                    <p class="text-sm text-gray-600 mb-2">Total Item</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $booking->items->count() }} Item</p>
                </div>

                <!-- Total Price -->
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-2">Total Harga</p>
                    <p class="text-3xl font-bold text-green-600">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Invoice Download -->
                @if($booking->status === 'approved' || $booking->status === 'completed' || $booking->status === 'done')
                <div class="space-y-2 mb-6">
                    <a href="{{ route('invoice.download', $booking->id) }}" 
                       class="btn btn-sm btn-success w-full gap-2">
                        <i class="fas fa-file-pdf"></i> Download Invoice
                    </a>
                    <a href="{{ route('invoice.view', $booking->id) }}" 
                       class="btn btn-sm btn-info w-full gap-2" target="_blank">
                        <i class="fas fa-eye"></i> Lihat Invoice
                    </a>
                </div>
                @endif

                <!-- Payment Info -->
                @if($booking->status === 'approved' || $booking->status === 'completed' || $booking->status === 'done')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 space-y-3">
                    <p class="text-sm font-semibold text-yellow-800">
                        <i class="fas fa-info-circle"></i> Instruksi Pembayaran
                    </p>
                    <ol class="text-xs text-yellow-800 space-y-1 list-decimal list-inside">
                        <li>Transfer ke rekening yang tertera di email</li>
                        <li>Kirim bukti transfer ke WhatsApp admin</li>
                        <li>Lampirkan nomor pesanan ini: <strong>{{ $booking->order_number }}</strong></li>
                        <li>Tunggu konfirmasi pembayaran dari admin</li>
                    </ol>
                </div>

                <!-- Contact Admin -->
                <a href="https://wa.me/628123456789?text=Halo%20Admin%2C%20saya%20ingin%20menanyakan%20tentang%20pesanan%20{{ $booking->order_number }}" 
                   target="_blank" class="btn btn-sm btn-outline w-full mt-4 gap-2">
                    <i class="fab fa-whatsapp"></i> Chat Admin
                </a>
                @endif
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Status Timeline</h3>
                
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 rounded-full bg-blue-500"></div>
                            <div class="w-0.5 h-8 bg-gray-300"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Pesanan Dibuat</p>
                            <p class="text-xs text-gray-600">{{ $booking->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($booking->status !== 'pending')
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 rounded-full {{ $booking->status === 'rejected' ? 'bg-red-500' : 'bg-green-500' }}"></div>
                            <div class="w-0.5 h-8 bg-gray-300"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">
                                {{ $booking->status === 'rejected' ? 'Pesanan Ditolak' : 'Pesanan Disetujui' }}
                            </p>
                            <p class="text-xs text-gray-600">{{ $booking->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    @if(in_array($booking->status, ['in_progress', 'done']))
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 rounded-full bg-yellow-500"></div>
                            <div class="w-0.5 h-8 bg-gray-300"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Sedang Berlangsung</p>
                            <p class="text-xs text-gray-600">{{ $booking->start_date }}</p>
                        </div>
                    </div>
                    @endif

                    @if($booking->status === 'done')
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-4 h-4 rounded-full bg-green-500"></div>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Selesai</p>
                            <p class="text-xs text-gray-600">{{ $booking->end_date }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

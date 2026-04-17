@extends('layouts.admin')

@section('title', 'Detail Booking Studio')
@section('header', 'Detail Booking Studio')
@section('breadcrumb', 'Lihat Detail Pemesanan')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Booking Information Card -->
        <div class="card bg-white shadow-lg border border-yellow-200">
            <div class="card-body p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-4 border-b-2 border-yellow-300">
                    Informasi Booking
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Booking ID -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">ID Booking</label>
                        <p class="text-lg font-semibold text-gray-800 mt-1">#{{ $booking->id }}</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</label>
                        <div class="mt-1">
                            <div class="badge badge-lg {{ $booking->statusBadge }}">
                                {{ $booking->statusLabel }}
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Booking -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Booking</label>
                        <p class="text-lg font-semibold text-gray-800 mt-1">
                            {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('l, d F Y') }}
                        </p>
                    </div>

                    <!-- Sesi -->
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sesi</label>
                        <p class="text-lg font-semibold text-gray-800 mt-1">
                            {{ $booking->sesiLabel }}
                        </p>
                        <p class="text-sm text-gray-600">{{ $booking->sesiTime }}</p>
                    </div>
                </div>

                <!-- Keperluan -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Keperluan / Tujuan Penggunaan</label>
                    <div class="bg-gray-50 rounded-lg p-4 mt-2 border-l-4 border-yellow-400">
                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $booking->keperluan }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Information Card -->
        <div class="card bg-white shadow-lg border border-blue-200">
            <div class="card-body p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2 border-blue-300 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    Informasi Pemesanan
                </h3>

                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Anggota</label>
                            <p class="text-lg font-semibold text-gray-800 mt-1">{{ $booking->user?->name ?? $booking->nama_pemohon }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Username/NPM</label>
                            <p class="text-lg font-semibold text-gray-800 mt-1">{{ $booking->user?->username ?? $booking->nomor_identitas }}</p>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</label>
                            <p class="text-lg font-semibold text-gray-800 mt-1">{{ $booking->user?->email ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">No. Telpon</label>
                            <p class="text-lg font-semibold text-gray-800 mt-1">{{ $booking->user?->phone ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Angkatan</label>
                            <p class="text-lg font-semibold text-gray-800 mt-1">{{ $booking->user?->angkatan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approval/Rejection Section (if pending) -->
        @if ($booking->status === 'pending')
            <div class="alert alert-warning shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 -6V7m0 6v2m0 -6a9 9 0 110 18 9 9 0 010 -18z" />
                </svg>
                <span class="font-semibold">Status Pending</span> - Booking ini menunggu untuk disetujui atau ditolak oleh admin
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Approve Form -->
                <form action="{{ route('admin.studio-bookings.approve', $booking->id) }}" method="POST">
                    @csrf
                    <div class="card bg-green-50 border border-green-300 shadow-lg">
                        <div class="card-body p-6">
                            <h4 class="font-bold text-green-900 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Setujui Booking
                            </h4>
                            <p class="text-sm text-gray-700 mb-4">
                                Klik tombol di bawah untuk menyetujui booking studio ini
                            </p>
                            <button type="submit" class="btn btn-success w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Setujui Booking
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Reject Form with Modal -->
                <form action="{{ route('admin.studio-bookings.reject', $booking->id) }}" method="POST">
                    @csrf
                    <div class="card bg-red-50 border border-red-300 shadow-lg">
                        <div class="card-body p-6">
                            <h4 class="font-bold text-red-900 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Tolak Booking
                            </h4>
                            <p class="text-sm text-gray-700 mb-4">
                                Klik tombol di bawah untuk menolak booking studio ini
                            </p>
                            <button type="button" onclick="document.getElementById('reject_modal_{{ $booking->id }}').showModal()" class="btn btn-error w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Tolak Booking
                            </button>
                        </div>
                    </div>

                    <!-- Reject Modal -->
                    <dialog id="reject_modal_{{ $booking->id }}" class="modal">
                        <div class="modal-box bg-white max-w-md">
                            <h3 class="font-bold text-lg text-gray-800 mb-4">Tolak Booking?</h3>
                            <p class="text-gray-600 mb-6">
                                Apakah Anda yakin ingin menolak booking studio untuk tanggal {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d F Y') }}, Sesi {{ $booking->sesiLabel }}?
                            </p>

                            <div class="modal-action">
                                <button type="button" onclick="document.getElementById('reject_modal_{{ $booking->id }}').close()" class="btn btn-ghost">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    Tolak
                                </button>
                            </div>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>Tutup</button>
                        </form>
                    </dialog>
                </form>
            </div>
        @else
            <!-- Approved/Rejected Status Info -->
            <div class="card bg-white shadow-lg border border-gray-200">
                <div class="card-body p-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b-2 border-gray-300">
                        Informasi Status
                    </h3>

                    @if ($booking->status === 'approved')
                        <div class="alert alert-success shadow-lg mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Booking ini telah <strong>disetujui</strong> oleh admin</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Disetujui Oleh</label>
                                <p class="text-lg font-semibold text-gray-800 mt-1">
                                    {{ $booking->approvedBy?->name ?? '-' }}
                                </p>
                            </div>

                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Persetujuan</label>
                                <p class="text-lg font-semibold text-gray-800 mt-1">
                                    {{ $booking->approved_at ? \Carbon\Carbon::parse($booking->approved_at)->translatedFormat('d F Y H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    @elseif ($booking->status === 'rejected')
                        <div class="alert alert-error shadow-lg mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Booking ini telah <strong>ditolak</strong></span>
                        </div>

                        @if ($booking->catatan_admin)
                            <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-400 mb-4">
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide block mb-2">Catatan Admin</label>
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $booking->catatan_admin }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Timeline Sidebar -->
    <div class="lg:col-span-1">
        <div class="card bg-white shadow-lg border border-yellow-200 sticky top-24">
            <div class="card-body p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1V3a1 1 0 011-1h5a1 1 0 011 1v1h1V3a1 1 0 011-1h1a2 2 0 012 2v2h1a1 1 0 110 2h-1v7h1a1 1 0 110 2h-1v2a2 2 0 01-2 2h-1a1 1 0 01-1-1v-1h-1v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-1H5v1a1 1 0 01-1 1H3a2 2 0 01-2-2v-2H0a1 1 0 110-2h1V9H0a1 1 0 010-2h1V5a2 2 0 012-2h1V3a1 1 0 011-1zm0 5h10V5H5v2z" clip-rule="evenodd" />
                    </svg>
                    Jadwal & Timeline
                </h3>

                <div class="space-y-4">
                    <div class="pb-4 border-b border-gray-200">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Booking</p>
                        <p class="text-gray-800 font-semibold mt-1">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('l, d F Y') }}</p>
                    </div>

                    <div class="pb-4 border-b border-gray-200">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sesi</p>
                        <p class="text-gray-800 font-semibold mt-1">{{ $booking->sesiLabel }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $booking->sesiTime }}</p>
                    </div>

                    <div class="pb-4 border-b border-gray-200">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Dibuat Pada</p>
                        <p class="text-gray-800 font-semibold mt-1">{{ $booking->created_at->translatedFormat('d F Y H:i') }}</p>
                    </div>

                    @if ($booking->approved_at)
                        <div class="pb-4 border-b border-gray-200">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Disetujui Pada</p>
                            <p class="text-gray-800 font-semibold mt-1">{{ $booking->approved_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    @endif

                    <div>
                        <a href="{{ route('admin.studio-bookings.index') }}" class="btn btn-outline btn-sm w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-close alert after 5 seconds
    const alert = document.querySelector('[role="alert"]');
    if (alert && alert.classList.contains('alert-warning')) {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    }
</script>
@endpush
@endsection

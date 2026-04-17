@extends('layouts.admin')

@section('title', 'Detail Permintaan Sewa')

@section('header', 'Detail Permintaan Sewa Band')

@section('breadcrumb', 'Manajemen Band')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $rental->band->band_name }}</h1>
            <p class="text-gray-500 mt-2">Permintaan dari: {{ $rental->renter_name }}</p>
        </div>
        <a href="{{ route('admin.band-rentals.index') }}" class="btn btn-ghost">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Rental Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Informasi Permintaan</h2>
                
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Nama Penyewa</p>
                        <p class="font-bold text-gray-800">{{ $rental->renter_name }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Nomor Telepon</p>
                        <p class="font-bold text-gray-800">
                            <a href="tel:{{ $rental->renter_phone }}" class="text-blue-600 hover:underline">
                                {{ $rental->renter_phone }}
                            </a>
                        </p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Tanggal Pertunjukan</p>
                        <p class="font-bold text-gray-800">{{ $rental->performance_date->format('d M Y') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Status</p>
                        <div class="mt-1">
                            @if($rental->status === 'pending')
                                <span class="badge badge-warning badge-outline">Menunggu</span>
                            @elseif($rental->status === 'approved')
                                <span class="badge badge-success badge-outline">Disetujui</span>
                            @elseif($rental->status === 'rejected')
                                <span class="badge badge-error badge-outline">Ditolak</span>
                            @else
                                <span class="badge badge-info badge-outline">Selesai</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Performance Times -->
                @if($rental->performance_start_time && $rental->performance_end_time)
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-green-50 p-4 rounded border-l-4 border-green-500">
                        <p class="text-gray-600 text-sm font-semibold">Waktu Mulai</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $rental->performance_start_time }}</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded border-l-4 border-red-500">
                        <p class="text-gray-600 text-sm font-semibold">Waktu Berakhir</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $rental->performance_end_time }}</p>
                    </div>
                </div>
                @endif

                <!-- Rental Purpose -->
                <div class="mb-6">
                    <p class="text-gray-600 text-sm font-semibold mb-2">Tujuan Penyewaan</p>
                    <div class="bg-blue-50 p-4 rounded border-l-4 border-blue-500">
                        <p class="text-gray-800">{{ $rental->rental_purpose }}</p>
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Dibuat</p>
                        <p class="font-semibold text-gray-800">{{ $rental->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Diperbarui</p>
                        <p class="font-semibold text-gray-800">{{ $rental->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Band Details -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-music mr-2 text-blue-600"></i> Informasi Band
                </h2>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Nama Band</p>
                        <p class="font-bold text-gray-800">{{ $rental->band->band_name }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Harga (Per Jam)</p>
                        <p class="font-bold text-green-600">Rp {{ number_format($rental->band->price_per_hour, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Harga (Per Event)</p>
                        <p class="font-bold text-green-600">Rp {{ number_format($rental->band->price_per_event, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded">
                        <p class="text-gray-600 text-sm">Status Band</p>
                        <div class="mt-1">
                            @if($rental->band->is_available)
                                <span class="badge badge-success badge-outline">Tersedia</span>
                            @else
                                <span class="badge badge-error badge-outline">Tidak Tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Social Media Section -->
                @if($rental->band->whatsapp_number || $rental->band->instagram_username || $rental->band->tiktok_username || $rental->band->youtube_url)
                <div class="mt-6 border-t pt-6">
                    <h3 class="font-bold text-gray-800 mb-4">Kontak & Social Media</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @if($rental->band->whatsapp_number)
                        <a href="https://wa.me/{{ $rental->band->whatsapp_number }}" target="_blank" class="flex items-center gap-2 p-3 bg-green-50 rounded hover:bg-green-100 transition">
                            <i class="fas fa-whatsapp text-green-600 text-lg"></i>
                            <div>
                                <p class="text-xs text-gray-600">WhatsApp</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $rental->band->whatsapp_number }}</p>
                            </div>
                        </a>
                        @endif

                        @if($rental->band->instagram_username)
                        <a href="https://instagram.com/{{ $rental->band->instagram_username }}" target="_blank" class="flex items-center gap-2 p-3 bg-pink-50 rounded hover:bg-pink-100 transition">
                            <i class="fas fa-instagram text-pink-600 text-lg"></i>
                            <div>
                                <p class="text-xs text-gray-600">Instagram</p>
                                <p class="text-sm font-semibold text-gray-800">@{{ $rental->band->instagram_username }}</p>
                            </div>
                        </a>
                        @endif

                        @if($rental->band->tiktok_username)
                        <a href="https://tiktok.com/@{{ $rental->band->tiktok_username }}" target="_blank" class="flex items-center gap-2 p-3 bg-black/5 rounded hover:bg-black/10 transition">
                            <i class="fas fa-tiktok text-black text-lg"></i>
                            <div>
                                <p class="text-xs text-gray-600">TikTok</p>
                                <p class="text-sm font-semibold text-gray-800">@{{ $rental->band->tiktok_username }}</p>
                            </div>
                        </a>
                        @endif

                        @if($rental->band->youtube_url)
                        <a href="{{ $rental->band->youtube_url }}" target="_blank" class="flex items-center gap-2 p-3 bg-red-50 rounded hover:bg-red-100 transition">
                            <i class="fas fa-youtube text-red-600 text-lg"></i>
                            <div>
                                <p class="text-xs text-gray-600">YouTube</p>
                                <p class="text-sm font-semibold text-gray-800">Channel</p>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Notes Section -->
            @if($rental->admin_notes)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg shadow p-6">
                <h3 class="font-bold text-gray-800 mb-2">
                    <i class="fas fa-sticky-note text-yellow-600 mr-2"></i> Catatan Admin
                </h3>
                <p class="text-gray-700">{{ $rental->admin_notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar - Actions -->
        <div class="lg:col-span-1">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="font-bold text-gray-800 mb-4">Status Permintaan</h3>
                
                @if($rental->status === 'pending')
                <div class="space-y-3">
                    <!-- Approve Form -->
                    <form action="{{ route('admin.band-rentals.approve', $rental) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <textarea name="admin_notes" placeholder="Catatan (opsional)" rows="2" class="textarea textarea-bordered textarea-sm w-full"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-full">
                            <i class="fas fa-check"></i> Setujui
                        </button>
                    </form>

                    <!-- Reject Form -->
                    <button onclick="rejectModal.showModal()" class="btn btn-error w-full">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </div>
                @elseif($rental->status === 'approved')
                <div class="mb-4">
                    <div class="badge badge-success badge-lg">Disetujui</div>
                </div>
                <form action="{{ route('admin.band-rentals.complete', $rental) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-info w-full">
                        <i class="fas fa-flag-checkered"></i> Tandai Selesai
                    </button>
                </form>
                @elseif($rental->status === 'rejected')
                <div class="badge badge-error badge-lg">Ditolak</div>
                @else
                <div class="badge badge-info badge-lg">Selesai</div>
                @endif
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 rounded-lg shadow p-6 border-2 border-red-200">
                <h3 class="font-bold text-red-800 mb-3">Zona Berbahaya</h3>
                <form action="{{ route('admin.band-rentals.destroy', $rental) }}" method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus permintaan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error btn-outline w-full btn-sm">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<dialog id="rejectModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Tolak Permintaan</h3>
        
        <form action="{{ route('admin.band-rentals.reject', $rental) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Alasan Penolakan *</span>
                </label>
                <textarea name="admin_notes" placeholder="Jelaskan alasan penolakan..." rows="4" class="textarea textarea-bordered" required></textarea>
            </div>

            <div class="modal-action">
                <button type="button" onclick="rejectModal.close()" class="btn">Batal</button>
                <button type="submit" class="btn btn-error">Tolak Permintaan</button>
            </div>
        </form>
    </div>
</dialog>
@endsection

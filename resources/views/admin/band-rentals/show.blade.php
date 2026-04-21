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
                            @elseif($rental->status === 'cancelled')
                                <span class="badge badge-error badge-outline">Dibatalkan</span>
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

                <!-- Break & Performance Duration -->
                @if($rental->break_duration_hours !== null || $rental->break_duration_minutes !== null)
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-yellow-50 p-4 rounded border-l-4 border-yellow-500">
                        <p class="text-gray-600 text-sm font-semibold">Total Jam Break</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $rental->break_duration_hours }} jam {{ $rental->break_duration_minutes }} menit</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded border-l-4 border-purple-500">
                        <p class="text-gray-600 text-sm font-semibold">Durasi Band Main</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $rental->performance_duration_hours }} jam {{ $rental->performance_duration_minutes }} menit</p>
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

                <!-- Venue Address -->
                @if($rental->venue_address)
                <div class="mb-6">
                    <p class="text-gray-600 text-sm font-semibold mb-2">Lokasi/Alamat Pertunjukan</p>
                    <div class="bg-green-50 p-4 rounded border-l-4 border-green-500">
                        <p class="text-gray-800">{{ $rental->venue_address }}</p>
                    </div>
                </div>
                @endif

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
                            <i class="fab fa-whatsapp text-green-600 text-lg"></i>
                            <div>
                                <p class="text-xs text-gray-600">WhatsApp</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $rental->band->whatsapp_number }}</p>
                            </div>
                        </a>
                        @endif

                        @if($rental->band->instagram_username)
                        <a href="https://instagram.com/{{ $rental->band->instagram_username }}" target="_blank" class="flex items-center gap-2 p-3 bg-pink-50 rounded hover:bg-pink-100 transition">
                            <i class="fab fa-instagram text-pink-600 text-lg"></i>
                            <div>
                                <p class="text-xs text-gray-600">Instagram</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $rental->band->instagram_username }}</p>
                            </div>
                        </a>
                        @endif

                        @if($rental->band->tiktok_username)
                        <a href="https://tiktok.com/@{{ $rental->band->tiktok_username }}" target="_blank" class="flex items-center gap-2 p-3 bg-black/5 rounded hover:bg-black/10 transition">
                            <i class="fab fa-tiktok text-black text-lg"></i>
                            <div>
                                <p class="text-xs text-gray-600">TikTok</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $rental->band->tiktok_username }}</p>
                            </div>
                        </a>
                        @endif

                        @if($rental->band->youtube_url)
                        <a href="{{ $rental->band->youtube_url }}" target="_blank" class="flex items-center gap-2 p-3 bg-red-50 rounded hover:bg-red-100 transition">
                            <i class="fab fa-youtube text-red-600 text-lg"></i>
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
                <div class="space-y-4">
                    <!-- Approval Form -->
                    <form id="approvalForm" action="{{ route('admin.band-rentals.approve', $rental) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Harga Pokok -->
                        <div class="form-control mb-3">
                            <label class="label">
                                <span class="label-text font-semibold">Harga Pokok *</span>
                            </label>
                            <input type="number" id="hargaPokok" placeholder="Rp 0" class="input input-bordered input-sm" disabled min="0">
                            <input type="hidden" name="harga_pokok" id="hargaPokokHidden" value="0">
                        </div>

                        <!-- Diskon Persen -->
                        <div class="form-control mb-3">
                            <label class="label">
                                <span class="label-text font-semibold">Diskon (%)</span>
                            </label>
                            <input type="number" id="diskonPersen" name="diskon_persen" placeholder="0 %" class="input input-bordered input-sm" min="0" max="100">
                        </div>

                        <!-- Diskon Nominal -->
                        <div class="form-control mb-3">
                            <label class="label">
                                <span class="label-text font-semibold">Diskon (Rp)</span>
                            </label>
                            <input type="number" id="diskonNominal" name="diskon_nominal" placeholder="Rp 0" class="input input-bordered input-sm" min="0">
                        </div>

                        <!-- Harga Final (Display Only) -->
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold">Harga Final</span>
                            </label>
                            <div class="bg-green-50 border-2 border-green-200 rounded px-3 py-2">
                                <p class="text-lg font-bold text-green-600">Rp <span id="hargaFinal">0</span></p>
                            </div>
                        </div>

                        <!-- Admin Notes -->
                        <div class="form-control mb-4">
                            <label class="label">
                                <span class="label-text font-semibold text-xs">Catatan (opsional)</span>
                            </label>
                            <textarea name="admin_notes" placeholder="Catatan untuk penyewa..." rows="2" class="textarea textarea-bordered textarea-sm"></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="space-y-2">
                            <button type="button" onclick="confirmApproval()" class="btn btn-success w-full">
                                <i class="fas fa-check"></i> Setujui
                            </button>
                            <button type="button" onclick="rejectModal.showModal()" class="btn btn-error w-full">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        </div>
                    </form>
                </div>
                @elseif($rental->status === 'approved')
                <div class="space-y-4">
                    <div class="badge badge-success badge-lg mb-4">Disetujui</div>
                    
                    <!-- Display Approved Info -->
                    <div class="bg-green-50 p-3 rounded">
                        <p class="text-xs text-gray-600">Kode Pesanan</p>
                        <p class="font-bold text-gray-800">{{ $rental->kode_order ?? '-' }}</p>
                    </div>
                    
                    <div class="bg-blue-50 p-3 rounded">
                        <p class="text-xs text-gray-600">Harga Pokok</p>
                        <p class="font-bold text-gray-800">Rp {{ number_format($rental->harga_pokok ?? 0, 0, ',', '.') }}</p>
                    </div>

                    <div class="bg-yellow-50 p-3 rounded">
                        <p class="text-xs text-gray-600">Diskon</p>
                        <p class="font-bold text-gray-800">
                            Rp {{ number_format($rental->diskon_nominal ?? 0, 0, ',', '.') }}
                            ({{ $rental->diskon_persen ?? 0 }}%)
                        </p>
                    </div>

                    <div class="bg-green-50 p-3 rounded border-2 border-green-300">
                        <p class="text-xs text-gray-600">Harga Final</p>
                        <p class="font-bold text-green-600 text-lg">Rp {{ number_format($rental->harga_final ?? 0, 0, ',', '.') }}</p>
                    </div>

                    <!-- Invoice & Actions -->
                    <div class="space-y-2">
                        <a href="{{ route('admin.band-rentals.invoice.download', $rental) }}" class="btn btn-primary w-full gap-2">
                            <i class="fas fa-file-pdf"></i> Download Invoice
                        </a>
                        <button onclick="cancelModal.showModal()" class="btn btn-error btn-outline w-full gap-2">
                            <i class="fas fa-times-circle"></i> Batalkan Permintaan
                        </button>
                    </div>

                    <form action="{{ route('admin.band-rentals.complete', $rental) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-info w-full">
                            <i class="fas fa-flag-checkered"></i> Tandai Selesai
                        </button>
                    </form>
                </div>
                @elseif($rental->status === 'rejected')
                <div class="badge badge-error badge-lg">Ditolak</div>
                @elseif($rental->status === 'cancelled')
                <div class="badge badge-error badge-lg">Dibatalkan</div>
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

<!-- Confirmation Modal for Approve -->
<dialog id="approvalModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Konfirmasi Persetujuan</h3>
        
        <div class="space-y-3 mb-6">
            <div class="bg-blue-50 p-3 rounded">
                <p class="text-xs text-gray-600">Nama Band</p>
                <p class="font-bold text-gray-800">{{ $rental->band->band_name }}</p>
            </div>

            <div class="bg-purple-50 p-3 rounded">
                <p class="text-xs text-gray-600">Permintaan dari</p>
                <p class="font-bold text-gray-800">{{ $rental->renter_name }}</p>
            </div>

            <div class="bg-green-50 p-3 rounded border-2 border-green-300">
                <p class="text-xs text-gray-600">Harga Final</p>
                <p class="font-bold text-green-600 text-lg">Rp <span id="modalHargaFinal">0</span></p>
            </div>
        </div>

        <p class="text-center text-gray-700 mb-6">
            <span class="font-semibold">Apakah anda yakin akan mengkonfirmasi</span> sewa band <span class="font-bold">{{ $rental->band->band_name }}</span> 
            permintaan user <span class="font-bold">{{ $rental->renter_name }}</span> 
            dengan harga <span class="font-bold text-green-600">Rp <span id="modalHargaFinalText">0</span></span>?
        </p>

        <div class="modal-action">
            <button type="button" onclick="approvalModal.close()" class="btn">Batal</button>
            <button type="button" onclick="submitApproval()" class="btn btn-success">
                <i class="fas fa-check"></i> Ya, Setujui
            </button>
        </div>
    </div>
</dialog>

<!-- Confirmation Modal for Reject -->
<dialog id="rejectModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Konfirmasi Penolakan</h3>
        
        <div class="space-y-3 mb-6">
            <div class="bg-blue-50 p-3 rounded">
                <p class="text-xs text-gray-600">Nama Band</p>
                <p class="font-bold text-gray-800">{{ $rental->band->band_name }}</p>
            </div>

            <div class="bg-purple-50 p-3 rounded">
                <p class="text-xs text-gray-600">Permintaan dari</p>
                <p class="font-bold text-gray-800">{{ $rental->renter_name }}</p>
            </div>

            <div class="bg-red-50 p-3 rounded">
                <p class="text-xs text-gray-600">Harga yang Ditawarkan</p>
                <p class="font-bold text-gray-800">Rp <span id="modalHargaReject">0</span></p>
            </div>
        </div>

        <p class="text-center text-gray-700 mb-4">
            <span class="font-semibold">Apakah anda yakin akan menolak</span> sewa band <span class="font-bold">{{ $rental->band->band_name }}</span> 
            permintaan user <span class="font-bold">{{ $rental->renter_name }}</span>?
        </p>

        <form id="rejectForm" action="{{ route('admin.band-rentals.reject', $rental) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Alasan Penolakan *</span>
                </label>
                <textarea name="admin_notes" placeholder="Jelaskan alasan penolakan..." rows="3" class="textarea textarea-bordered" required></textarea>
            </div>

            <div class="modal-action">
                <button type="button" onclick="rejectModal.close()" class="btn">Batal</button>
                <button type="submit" class="btn btn-error">
                    <i class="fas fa-times"></i> Ya, Tolak
                </button>
            </div>
        </form>
    </div>
</dialog>

<!-- JavaScript for Real-time Discount Calculator & Modal -->
<script>
const hargaPokokInput = document.getElementById('hargaPokok');
const diskonPersenInput = document.getElementById('diskonPersen');
const diskonNominalInput = document.getElementById('diskonNominal');
const hargaFinalDisplay = document.getElementById('hargaFinal');

// Data dari blade
const pricePerHour = {{ $rental->band->price_per_hour }};
const performanceDurationHours = {{ $rental->performance_duration_hours ?? 0 }};
const performanceDurationMinutes = {{ $rental->performance_duration_minutes ?? 0 }};

// Format number to Indonesian currency style (without Rp prefix)
function formatNumber(num) {
    return Math.floor(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Calculate duration in hours (with minutes as decimal)
function hitungDurasi() {
    const totalMinutes = (performanceDurationHours * 60) + performanceDurationMinutes;
    return Math.ceil(totalMinutes / 60); // Round up to next hour
}

// Auto-calculate harga pokok on page load
function initializeHargaPokok() {
    const durationHours = hitungDurasi();
    if (durationHours > 0 && pricePerHour > 0) {
        const calculatedPrice = durationHours * pricePerHour;
        // Store numeric value in both inputs
        hargaPokokInput.value = formatNumber(calculatedPrice);
        document.getElementById('hargaPokokHidden').value = calculatedPrice;
        hitungHargaFinal();
    }
}

// Calculate final price
let lastDiscountField = null;
function hitungHargaFinal() {
    // Read from hidden input which has the correct numeric value
    const hargaPokok = parseInt(document.getElementById('hargaPokokHidden').value) || 0;
    let diskonPersen = parseFloat(diskonPersenInput.value) || 0;
    let diskonNominal = parseInt(diskonNominalInput.value) || 0;

    // If diskon persen is the last field edited, calculate nominal from persen
    if (lastDiscountField === 'persen') {
        diskonNominal = Math.floor(hargaPokok * diskonPersen / 100);
        diskonNominalInput.value = diskonNominal;
    } 
    // If diskon nominal is the last field edited, calculate persen from nominal
    else if (lastDiscountField === 'nominal') {
        diskonPersen = hargaPokok > 0 ? (diskonNominal * 100 / hargaPokok) : 0;
        diskonPersenInput.value = diskonPersen.toFixed(2);
    }

    const hargaFinal = Math.max(0, hargaPokok - diskonNominal);
    hargaFinalDisplay.textContent = formatNumber(hargaFinal);

    // Update modal values
    document.getElementById('modalHargaFinal').textContent = formatNumber(hargaFinal);
    document.getElementById('modalHargaFinalText').textContent = formatNumber(hargaFinal);
    document.getElementById('modalHargaReject').textContent = formatNumber(hargaFinal);
}

// Event listeners for real-time calculation
diskonPersenInput.addEventListener('focus', () => {
    lastDiscountField = 'persen';
});
diskonPersenInput.addEventListener('input', hitungHargaFinal);

diskonNominalInput.addEventListener('focus', () => {
    lastDiscountField = 'nominal';
});
diskonNominalInput.addEventListener('input', hitungHargaFinal);

// Confirmation before approval
function confirmApproval() {
    const hargaPokok = parseInt(document.getElementById('hargaPokokHidden').value) || 0;
    
    if (!hargaPokok) {
        alert('Silakan isi harga pokok terlebih dahulu');
        return;
    }

    approvalModal.showModal();
}

// Submit approval form
function submitApproval() {
    document.getElementById('approvalForm').submit();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', initializeHargaPokok);
</script>

<!-- Confirmation Modal for Cancel -->
<dialog id="cancelModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4 text-red-600">Batalkan Permintaan Sewa</h3>
        
        <div class="space-y-3 mb-6">
            <div class="bg-blue-50 p-3 rounded">
                <p class="text-xs text-gray-600">Nama Band</p>
                <p class="font-bold text-gray-800">{{ $rental->band->band_name }}</p>
            </div>

            <div class="bg-purple-50 p-3 rounded">
                <p class="text-xs text-gray-600">Permintaan dari</p>
                <p class="font-bold text-gray-800">{{ $rental->renter_name }}</p>
            </div>

            <div class="bg-red-50 p-3 rounded border-2 border-red-300">
                <p class="text-xs text-gray-600">Kode Pesanan</p>
                <p class="font-bold text-gray-800">{{ $rental->kode_order }}</p>
            </div>
        </div>

        <p class="text-center text-gray-700 mb-4">
            <span class="font-semibold">Apakah anda yakin akan membatalkan</span> sewa band 
            <span class="font-bold">{{ $rental->band->band_name }}</span> 
            atas nama <span class="font-bold">{{ $rental->renter_name }}</span>?
        </p>

        <form id="cancelForm" action="{{ route('admin.band-rentals.cancel', $rental) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Alasan Pembatalan *</span>
                </label>
                <textarea name="cancellation_reason" class="textarea textarea-bordered" placeholder="Jelaskan alasan pembatalan..." required minlength="10"></textarea>
            </div>

            <div class="modal-action">
                <button type="button" onclick="cancelModal.close()" class="btn">Batal</button>
                <button type="submit" class="btn btn-error">
                    <i class="fas fa-times-circle"></i> Ya, Batalkan Sewa
                </button>
            </div>
        </form>
    </div>
</dialog>

@endsection

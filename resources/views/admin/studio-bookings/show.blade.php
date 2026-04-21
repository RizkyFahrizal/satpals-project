@extends('layouts.admin')

@section('title', 'Detail Booking Studio')
@section('header', 'Detail Booking Studio')
@section('breadcrumb', 'Lihat Detail Pemesanan')

@section('content')
@php
    $computedHargaPokok = $booking->harga_pokok ?: (($pricePerPerson ?? 15000) * ($booking->jumlah_non_ukm ?? 0));
    $computedHargaFinal = $booking->harga_final ?? max(0, $computedHargaPokok - ($booking->diskon_nominal ?? 0));
@endphp
<div class="container mx-auto px-4 py-6">
    @if(session('warning'))
        <div class="alert alert-warning shadow-lg mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 -6V7m0 6v2m0 -6a9 9 0 110 18 9 9 0 010 -18z" />
            </svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $booking->nama_pemohon }}</h1>
            <p class="text-gray-500 mt-2">Permintaan booking studio: {{ $booking->booking_code ?? ('#' . $booking->id) }}</p>
        </div>
        <a href="{{ route('admin.studio-bookings.index') }}" class="btn btn-ghost">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Booking Request Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Informasi Permintaan</h2>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-600 text-sm">Kode Booking</p>
                    <p class="font-bold text-gray-800">{{ $booking->booking_code ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-600 text-sm">Nama Penyewa</p>
                    <p class="font-bold text-gray-800">{{ $booking->nama_pemohon }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-600 text-sm">Tanggal Booking</p>
                    <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d M Y') }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-600 text-sm">Status</p>
                    <div class="mt-1">
                        <div class="badge badge-lg {{ $booking->statusBadge }}">{{ $booking->statusLabel }}</div>
                    </div>
                </div>
            </div>

            <!-- Session & Time Info -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded border-l-4 border-blue-500">
                    <p class="text-gray-600 text-sm font-semibold">Sesi</p>
                    <p class="font-bold text-gray-800 text-lg">{{ $booking->sesiLabel }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $booking->sesiTime }}</p>
                </div>
                <div class="bg-purple-50 p-4 rounded border-l-4 border-purple-500">
                    <p class="text-gray-600 text-sm font-semibold">Identitas</p>
                    <p class="font-bold text-gray-800 text-lg">{{ $booking->nomor_identitas ?? '-' }}</p>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-green-50 p-4 rounded border-l-4 border-green-500">
                    <p class="text-gray-600 text-sm font-semibold">Email</p>
                    <p class="font-bold text-gray-800">{{ $booking->renter_email ?? '-' }}</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded border-l-4 border-yellow-500">
                    <p class="text-gray-600 text-sm font-semibold">Nomor Telepon</p>
                    <p class="font-bold text-gray-800">{{ $booking->renter_phone ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Participant & Pricing Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Informasi Peserta & Harga</h2>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-600 text-sm">Jumlah Non-UKM</p>
                    <p class="font-bold text-gray-800 text-lg">{{ $booking->jumlah_non_ukm ?? 0 }} Orang</p>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-600 text-sm">Harga per Orang</p>
                    <p class="font-bold text-gray-800 text-lg">Rp {{ number_format($booking->harga_satuan ?? $pricePerPerson ?? 15000, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Pricing Details -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 p-4 rounded border-l-4 border-blue-500">
                    <p class="text-gray-600 text-sm font-semibold">Total Harga Pokok</p>
                    <p class="font-bold text-gray-800 text-lg">Rp {{ number_format($computedHargaPokok, 0, ',', '.') }}</p>
                </div>
            </div>

            @if(($booking->diskon_nominal ?? 0) > 0)
            <div class="mt-4 bg-yellow-50 p-4 rounded border-l-4 border-yellow-500">
                <p class="text-gray-600 text-sm font-semibold">Diskon</p>
                <p class="font-bold text-gray-800">Rp {{ number_format($booking->diskon_nominal, 0, ',', '.') }} ({{ $booking->diskon_persen ?? 0 }}%)</p>
            </div>
            @endif
        </div>

        <!-- Purpose Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Keperluan / Tujuan Penggunaan</h2>
            <div class="bg-blue-50 p-4 rounded border-l-4 border-blue-500">
                <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $booking->keperluan }}</p>
            </div>
        </div>

        <!-- Dates -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-600 text-sm">Dibuat</p>
                    <p class="font-semibold text-gray-800">{{ $booking->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded">
                    <p class="text-gray-600 text-sm">Diperbarui</p>
                    <p class="font-semibold text-gray-800">{{ $booking->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6 top-24">
            <h3 class="font-bold text-gray-800 mb-4">Status Permintaan</h3>

                @if ($booking->status === 'pending')
                    <form action="{{ route('admin.studio-bookings.approve', $booking->id) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Harga Pokok *</span>
                                </label>
                                <input type="number" id="hargaPokokInput" value="{{ old('harga_pokok', $computedHargaPokok) }}" class="input input-bordered input-sm" min="0" disabled>
                                <input type="hidden" name="harga_pokok" id="hargaPokokHidden" value="{{ $computedHargaPokok }}">
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Diskon (%)</span>
                                </label>
                                <input type="number" id="diskonPersenInput" name="diskon_persen" placeholder="0%" class="input input-bordered input-sm" min="0" max="100">
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Diskon (Rp)</span>
                                </label>
                                <input type="number" id="diskonNominalInput" name="diskon_nominal" placeholder="0" class="input input-bordered input-sm" min="0">
                            </div>

                            <div class="bg-green-50 p-3 rounded border-2 border-green-300">
                                <p class="text-xs text-gray-600">Harga Final</p>
                                <p class="text-lg font-bold text-green-600">Rp <span id="hargaFinalPreview">{{ number_format($computedHargaFinal, 0, ',', '.') }}</span></p>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Catatan Admin</span>
                                </label>
                                <textarea name="catatan" rows="2" class="textarea textarea-bordered textarea-sm" placeholder="Opsional"></textarea>
                            </div>

                            <div class="space-y-2 pt-2">
                                <button type="button" onclick="confirmApproval()" class="btn btn-success btn-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Setujui
                                </button>
                                <button type="button" onclick="document.getElementById('reject_modal_{{ $booking->id }}').showModal()" class="btn btn-error btn-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Tolak
                                </button>
                            </div>
                        </div>
                    </form>

                    <dialog id="reject_modal_{{ $booking->id }}" class="modal">
                        <div class="modal-box bg-white max-w-md">
                            <h3 class="font-bold text-lg text-gray-800 mb-4">Tolak Booking?</h3>
                            <p class="text-gray-600 mb-6">
                                Apakah Anda yakin ingin menolak booking studio untuk tanggal {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d F Y') }}, Sesi {{ $booking->sesiLabel }}?
                            </p>

                            <form action="{{ route('admin.studio-bookings.reject', $booking->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Penolakan</label>
                                    <textarea name="catatan" rows="4" class="textarea textarea-bordered w-full" placeholder="Jelaskan alasan penolakan" required></textarea>
                                </div>

                                <div class="modal-action">
                                    <button type="button" onclick="document.getElementById('reject_modal_{{ $booking->id }}').close()" class="btn btn-ghost">Batal</button>
                                    <button type="submit" class="btn btn-error">Tolak</button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>Tutup</button>
                        </form>
                    </dialog>

                @elseif ($booking->status === 'approved')                    
                    <div class="space-y-4">
                        <div class="bg-blue-50 p-3 rounded">
                            <p class="text-xs text-gray-600">Tanggal Booking</p>
                            <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d M Y') }}</p>
                        </div>

                        <div class="bg-purple-50 p-3 rounded">
                            <p class="text-xs text-gray-600">Sesi</p>
                            <p class="font-semibold text-gray-800">{{ $booking->sesiLabel }} - {{ $booking->sesiTime }}</p>
                        </div>

                        <div class="bg-yellow-50 p-3 rounded">
                            <p class="text-xs text-gray-600">Total Harga Final</p>
                            <p class="font-semibold text-gray-800">Rp {{ number_format($computedHargaFinal, 0, ',', '.') }}</p>
                        </div>

                        <div class="bg-green-50 p-3 rounded border-2 border-green-300">
                            <p class="text-xs text-gray-600">Disetujui Pada</p>
                            <p class="font-semibold text-gray-800">{{ $booking->approved_at?->translatedFormat('d M Y H:i') ?? '-' }}</p>
                        </div>

                        <div class="space-y-2">
                            <a href="{{ route('invoice.view', $booking->id) }}" target="_blank" class="btn btn-outline btn-primary btn-sm w-full">Lihat Invoice</a>
                            <a href="{{ route('invoice.download', $booking->id) }}" class="btn btn-primary btn-sm w-full">Download Invoice</a>
                            <form action="{{ route('admin.studio-bookings.complete', $booking->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info btn-sm w-full">Tandai Selesai</button>
                            </form>
                            <button type="button" onclick="document.getElementById('cancel_modal_{{ $booking->id }}').showModal()" class="btn btn-error btn-sm w-full">Batalkan</button>
                        </div>
                    </div>

                    <dialog id="cancel_modal_{{ $booking->id }}" class="modal">
                        <div class="modal-box bg-white max-w-md">
                            <h3 class="font-bold text-lg text-gray-800 mb-4">Batalkan Booking?</h3>
                            <form action="{{ route('admin.studio-bookings.cancel', $booking->id) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Pembatalan</label>
                                    <textarea name="cancellation_reason" class="textarea textarea-bordered w-full" rows="4" required></textarea>
                                </div>
                                <div class="modal-action">
                                    <button type="button" onclick="document.getElementById('cancel_modal_{{ $booking->id }}').close()" class="btn btn-ghost">Batal</button>
                                    <button type="submit" class="btn btn-error">Batalkan</button>
                                </div>
                            </form>
                        </div>
                        <form method="dialog" class="modal-backdrop">
                            <button>Tutup</button>
                        </form>
                    </dialog>

                @elseif ($booking->status === 'rejected')
                    <div class="badge badge-error badge-lg">Ditolak</div>
                    
                    @if ($booking->catatan_admin)
                        <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-400 mt-4">
                            <p class="text-xs text-gray-600 font-semibold">Catatan Penolakan</p>
                            <p class="text-gray-800 mt-1 whitespace-pre-wrap">{{ $booking->catatan_admin }}</p>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 rounded-lg shadow p-6 border-2 border-red-200 mt-6">
                <h3 class="font-bold text-red-800 mb-3">Zona Berbahaya</h3>
                <form action="{{ route('admin.studio-bookings.destroy', $booking->id) }}" method="POST" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus booking ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-error btn-outline w-full btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Hapus Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let lastDiscountField = null;

    // Format number to Indonesian currency style (without Rp prefix)
    function formatNumber(num) {
        return Math.floor(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Calculate final price with automatic discount sync
    function hitungHargaFinal() {
        const hargaPokokHidden = document.getElementById('hargaPokokHidden');
        const diskonPersenInput = document.getElementById('diskonPersenInput');
        const diskonNominalInput = document.getElementById('diskonNominalInput');
        const hargaFinalPreview = document.getElementById('hargaFinalPreview');

        // Check if all required elements exist
        if (!hargaPokokHidden || !diskonPersenInput || !diskonNominalInput || !hargaFinalPreview) {
            return;
        }

        // Read from hidden input which has the correct numeric value
        const hargaPokok = parseInt(hargaPokokHidden.value) || 0;
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
        hargaFinalPreview.textContent = formatNumber(hargaFinal);
        
        // Update modal values
        document.getElementById('modalHargaFinal').textContent = formatNumber(hargaFinal);
        document.getElementById('modalHargaFinalText').textContent = formatNumber(hargaFinal);
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const diskonPersenInput = document.getElementById('diskonPersenInput');
        const diskonNominalInput = document.getElementById('diskonNominalInput');

        // Track which field is being edited
        if (diskonPersenInput) {
            diskonPersenInput.addEventListener('focus', () => {
                lastDiscountField = 'persen';
            });
            diskonPersenInput.addEventListener('input', (e) => {
                lastDiscountField = 'persen';
                hitungHargaFinal();
            });
        }
        
        if (diskonNominalInput) {
            diskonNominalInput.addEventListener('focus', () => {
                lastDiscountField = 'nominal';
            });
            diskonNominalInput.addEventListener('input', (e) => {
                lastDiscountField = 'nominal';
                hitungHargaFinal();
            });
        }

        // Initialize harga final
        hitungHargaFinal();

        // Hide warning alert after 5 seconds
        const warningAlert = document.querySelector('.alert-warning');
        if (warningAlert) {
            setTimeout(() => {
                warningAlert.style.display = 'none';
            }, 5000);
        }
    });

    // Confirmation before approval
    function confirmApproval() {
        const hargaPokokHidden = document.getElementById('hargaPokokHidden');
        const hargaPokok = parseInt(hargaPokokHidden.value) || 0;
        
        if (!hargaPokok) {
            alert('Silakan isi harga pokok terlebih dahulu');
            return;
        }

        document.getElementById('approvalModal').showModal();
    }

    // Submit approval form
    function submitApproval() {
        document.getElementById('approvalForm').submit();
    }
</script>

<!-- Confirmation Modal for Approve -->
<dialog id="approvalModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Konfirmasi Persetujuan</h3>
        
        <div class="space-y-3 mb-6">
            <div class="bg-blue-50 p-3 rounded">
                <p class="text-xs text-gray-600">Nama Penyewa</p>
                <p class="font-bold text-gray-800">{{ $booking->nama_pemohon }}</p>
            </div>

            <div class="bg-purple-50 p-3 rounded">
                <p class="text-xs text-gray-600">Booking Code</p>
                <p class="font-bold text-gray-800">{{ $booking->booking_code ?? '-' }}</p>
            </div>

            <div class="bg-green-50 p-3 rounded border-2 border-green-300">
                <p class="text-xs text-gray-600">Harga Final</p>
                <p class="font-bold text-green-600 text-lg">Rp <span id="modalHargaFinal">0</span></p>
            </div>
        </div>

        <p class="text-center text-gray-700 mb-6">
            <span class="font-semibold">Apakah anda yakin akan mengkonfirmasi</span> booking studio 
            permintaan user <span class="font-bold">{{ $booking->nama_pemohon }}</span> 
            dengan harga <span class="font-bold text-green-600">Rp <span id="modalHargaFinalText">0</span></span>?
        </p>

        <div class="modal-action">
            <button type="button" onclick="document.getElementById('approvalModal').close()" class="btn">Batal</button>
            <button type="button" onclick="submitApproval()" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Ya, Setujui
            </button>
        </div>
    </div>
</dialog>

@endsection

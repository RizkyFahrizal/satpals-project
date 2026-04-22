@extends('layouts.admin')

@section('title', 'Detail Permintaan Persewaan Alat')
@section('header', 'Detail Permintaan Persewaan Alat')
@section('breadcrumb', 'Manajemen Persewaan')

@section('content')
@php
    $hargaPokok = (int) ($rentalRequest->harga_pokok ?? $rentalRequest->total_price ?? 0);
    $diskonPersen = (int) ($rentalRequest->diskon_persen ?? 0);
    $diskonNominal = (int) ($rentalRequest->diskon_nominal ?? 0);
    $hargaFinal = (int) ($rentalRequest->harga_final ?? max(0, $hargaPokok - $diskonNominal));
    $isApproved = in_array($rentalRequest->status, ['approved', 'completed', 'done'], true);
@endphp
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
                <h3 class="font-semibold text-gray-900 mb-4">Aksi</h3>

                @if($rentalRequest->status === 'pending')
                    <form id="approvalForm" action="{{ route('admin.equipment-rental-requests.approve', $rentalRequest) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        @if($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga Pokok</label>
                                <input type="text" id="hargaPokokDisplay" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-100 text-gray-700" value="Rp {{ number_format($hargaPokok, 0, ',', '.') }}" disabled>
                                <input type="hidden" name="harga_pokok" id="hargaPokokHidden" value="{{ $hargaPokok }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Diskon (%)</label>
                                <input type="number" id="diskonPersen" name="diskon_persen" min="0" max="100" placeholder="0" value="{{ old('diskon_persen') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Diskon (Rp)</label>
                                <input type="number" id="diskonNominal" name="diskon_nominal" min="0" placeholder="0" value="{{ old('diskon_nominal') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition">
                            </div>

                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                                <p class="text-sm text-gray-600 mb-1">Harga Final</p>
                                <p class="text-xl font-bold text-emerald-700">Rp <span id="hargaFinalDisplay">{{ number_format($hargaFinal, 0, ',', '.') }}</span></p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                                <textarea name="admin_notes" rows="3" placeholder="Catatan sebelum approve..." class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition">{{ old('admin_notes', $rentalRequest->admin_notes) }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button type="button" onclick="approvalModal.showModal()" class="btn btn-success w-full gap-2">
                                <i class="fas fa-check"></i>
                                Setujui
                            </button>
                            <button type="button" onclick="rejectModal.showModal()" class="btn btn-error w-full gap-2">
                                <i class="fas fa-times"></i>
                                Tolak
                            </button>
                        </div>
                    </form>
                @elseif($rentalRequest->status === 'approved')
                    <div class="space-y-3">
                        <div class="rounded-xl bg-green-50 border border-green-200 p-4">
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="font-semibold text-green-700">Disetujui</p>
                        </div>

                        <div class="space-y-2">
                            <a href="{{ route('invoice.download', $rentalRequest->id) }}" class="btn btn-primary w-full gap-2">
                                <i class="fas fa-file-pdf"></i>
                                Download Invoice
                            </a>
                        </div>

                        <button type="button" onclick="cancelModal.showModal()" class="btn btn-error btn-outline w-full gap-2">
                            <i class="fas fa-ban"></i>
                            Batalkan
                        </button>
                        <button type="button" onclick="completeModal.showModal()" class="btn btn-info w-full gap-2">
                            <i class="fas fa-flag-checkered"></i>
                            Selesai
                        </button>
                    </div>
                @elseif($rentalRequest->status === 'rejected')
                    <div class="badge badge-error badge-lg">Ditolak</div>
                @elseif($rentalRequest->status === 'cancelled')
                    <div class="badge badge-error badge-lg">Dibatalkan</div>
                @else
                    <div class="badge badge-info badge-lg">Selesai</div>
                @endif
            </div>
        </div>
    </div>
</div>

<dialog id="approvalModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Konfirmasi Persetujuan</h3>

        <div class="space-y-3 mb-6">
            <div class="bg-blue-50 p-3 rounded">
                <p class="text-xs text-gray-600">Nomor Pesanan</p>
                <p class="font-bold text-gray-800">{{ $rentalRequest->order_number }}</p>
            </div>

            <div class="bg-purple-50 p-3 rounded">
                <p class="text-xs text-gray-600">Permintaan dari</p>
                <p class="font-bold text-gray-800">{{ $rentalRequest->renter_name }}</p>
            </div>

            <div class="bg-green-50 p-3 rounded border-2 border-green-300">
                <p class="text-xs text-gray-600">Harga Final</p>
                <p class="font-bold text-green-600 text-lg">Rp <span id="modalHargaFinal">{{ number_format($hargaFinal, 0, ',', '.') }}</span></p>
            </div>
        </div>

        <p class="text-center text-gray-700 mb-6">
            <span class="font-semibold">Apakah anda yakin akan mengkonfirmasi</span> sewa alat <span class="font-bold">{{ $rentalRequest->order_number }}</span>
            <span class="font-bold text-green-600">dengan harga Rp <span id="modalHargaFinalText">{{ number_format($hargaFinal, 0, ',', '.') }}</span></span>?
        </p>

        <div class="modal-action">
            <button type="button" onclick="approvalModal.close()" class="btn">Batal</button>
            <button type="button" onclick="submitApproval()" class="btn btn-success">
                <i class="fas fa-check"></i> Ya, Setujui
            </button>
        </div>
    </div>
</dialog>

<dialog id="rejectModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Konfirmasi Penolakan</h3>

        <div class="space-y-3 mb-6">
            <div class="bg-blue-50 p-3 rounded">
                <p class="text-xs text-gray-600">Nomor Pesanan</p>
                <p class="font-bold text-gray-800">{{ $rentalRequest->order_number }}</p>
            </div>

            <div class="bg-purple-50 p-3 rounded">
                <p class="text-xs text-gray-600">Permintaan dari</p>
                <p class="font-bold text-gray-800">{{ $rentalRequest->renter_name }}</p>
            </div>
        </div>

        <p class="text-center text-gray-700 mb-4">
            <span class="font-semibold">Apakah Anda yakin akan menolak</span> permintaan sewa alat ini?
        </p>

        <form action="{{ route('admin.equipment-rental-requests.reject', $rentalRequest) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Alasan Penolakan *</span>
                </label>
                <textarea name="rejection_reason" placeholder="Jelaskan alasan penolakan..." rows="3" class="textarea textarea-bordered" required></textarea>
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

<dialog id="cancelModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4 text-red-600">Batalkan Permintaan Sewa</h3>

        <div class="space-y-3 mb-6">
            <div class="bg-blue-50 p-3 rounded">
                <p class="text-xs text-gray-600">Nomor Pesanan</p>
                <p class="font-bold text-gray-800">{{ $rentalRequest->order_number }}</p>
            </div>

            <div class="bg-purple-50 p-3 rounded">
                <p class="text-xs text-gray-600">Permintaan dari</p>
                <p class="font-bold text-gray-800">{{ $rentalRequest->renter_name }}</p>
            </div>

            <div class="bg-red-50 p-3 rounded border-2 border-red-300">
                <p class="text-xs text-gray-600">Status</p>
                <p class="font-bold text-red-700">Akan dibatalkan</p>
            </div>
        </div>

        <p class="text-center text-gray-700 mb-4">
            <span class="font-semibold">Apakah anda yakin akan membatalkan</span> sewa alat <span class="font-bold">{{ $rentalRequest->order_number }}</span>?
        </p>

        <form action="{{ route('admin.equipment-rental-requests.mark-in-progress', $rentalRequest) }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Alasan Pembatalan *</span>
                </label>
                <textarea name="cancellation_reason" rows="4" class="textarea textarea-bordered" placeholder="Jelaskan alasan pembatalan..." required minlength="10"></textarea>
            </div>

            <div class="modal-action">
                <button type="button" onclick="cancelModal.close()" class="btn">Batal</button>
                <button type="submit" class="btn btn-error">
                    <i class="fas fa-ban"></i> Ya, Batalkan
                </button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="completeModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Konfirmasi Selesai</h3>

        <div class="space-y-3 mb-6">
            <div class="bg-blue-50 p-3 rounded">
                <p class="text-xs text-gray-600">Nomor Pesanan</p>
                <p class="font-bold text-gray-800">{{ $rentalRequest->order_number }}</p>
            </div>

            <div class="bg-purple-50 p-3 rounded">
                <p class="text-xs text-gray-600">Permintaan dari</p>
                <p class="font-bold text-gray-800">{{ $rentalRequest->renter_name }}</p>
            </div>

            <div class="bg-emerald-50 p-3 rounded border-2 border-emerald-300">
                <p class="text-xs text-gray-600">Status</p>
                <p class="font-bold text-emerald-700">Akan ditandai selesai</p>
            </div>
        </div>

        <p class="text-center text-gray-700 mb-6">
            <span class="font-semibold">Apakah anda yakin ingin menandai</span> permintaan sewa alat <span class="font-bold">{{ $rentalRequest->order_number }}</span> sebagai selesai?
        </p>

        <form action="{{ route('admin.equipment-rental-requests.complete', $rentalRequest) }}" method="POST" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="modal-action">
                <button type="button" onclick="completeModal.close()" class="btn">Batal</button>
                <button type="submit" class="btn btn-info">
                    <i class="fas fa-flag-checkered"></i> Ya, Selesai
                </button>
            </div>
        </form>
    </div>
</dialog>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hargaPokokHidden = document.getElementById('hargaPokokHidden');
    const diskonPersenInput = document.getElementById('diskonPersen');
    const diskonNominalInput = document.getElementById('diskonNominal');
    const hargaFinalDisplay = document.getElementById('hargaFinalDisplay');
    const approvalForm = document.getElementById('approvalForm');
    const modalHargaFinal = document.getElementById('modalHargaFinal');
    const modalHargaFinalText = document.getElementById('modalHargaFinalText');

    if (!hargaPokokHidden || !diskonPersenInput || !diskonNominalInput || !hargaFinalDisplay) {
        return;
    }

    let lastDiscountField = null;

    function formatNumber(value) {
        return Math.floor(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function recalculatePrice() {
        const hargaPokok = parseInt(hargaPokokHidden.value, 10) || 0;
        let diskonPersen = parseFloat(diskonPersenInput.value) || 0;
        let diskonNominal = parseInt(diskonNominalInput.value, 10) || 0;

        if (lastDiscountField === 'persen') {
            diskonNominal = Math.floor(hargaPokok * diskonPersen / 100);
            diskonNominalInput.value = diskonNominal;
        } else if (lastDiscountField === 'nominal') {
            diskonPersen = hargaPokok > 0 ? (diskonNominal * 100 / hargaPokok) : 0;
            diskonPersenInput.value = diskonPersen.toFixed(2);
        }

        const hargaFinal = Math.max(0, hargaPokok - diskonNominal);
        hargaFinalDisplay.textContent = formatNumber(hargaFinal);
        if (modalHargaFinal) {
            modalHargaFinal.textContent = formatNumber(hargaFinal);
        }
        if (modalHargaFinalText) {
            modalHargaFinalText.textContent = formatNumber(hargaFinal);
        }
    }

    window.submitApproval = function () {
        if (!approvalForm) {
            return;
        }

        approvalForm.submit();
    };

    diskonPersenInput.addEventListener('focus', () => lastDiscountField = 'persen');
    diskonPersenInput.addEventListener('input', recalculatePrice);
    diskonNominalInput.addEventListener('focus', () => lastDiscountField = 'nominal');
    diskonNominalInput.addEventListener('input', recalculatePrice);

    recalculatePrice();
});
</script>
@endsection
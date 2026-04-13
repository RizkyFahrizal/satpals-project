@extends('layouts.app')

@section('title', 'Checkout - Satya Palapa')

@section('content')
<!-- Header Section with Gradient -->
<div class="bg-gradient-to-br from-yellow-400 via-amber-400 to-orange-400 py-12 md:py-16">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-credit-card text-3xl text-gray-800"></i>
            <h1 class="text-4xl font-bold text-gray-800">Checkout Pesanan</h1>
        </div>
        <p class="text-gray-700 text-lg">Lengkapi informasi untuk menyelesaikan pesanan Anda</p>
    </div>
</div>

<div class="container mx-auto px-4 py-8">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-400">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-user text-yellow-600"></i> Informasi Penyewa
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                            <input type="text" name="renter_name" class="input input-bordered w-full @error('renter_name') input-error @enderror" 
                                   value="{{ old('renter_name') }}" required>
                            @error('renter_name')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NPM / NIK *</label>
                            <input type="text" name="renter_npm_nik" class="input input-bordered w-full @error('renter_npm_nik') input-error @enderror" 
                                   value="{{ old('renter_npm_nik') }}" required>
                            @error('renter_npm_nik')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon *</label>
                            <input type="tel" name="renter_phone" class="input input-bordered w-full @error('renter_phone') input-error @enderror" 
                                   value="{{ old('renter_phone') }}" required>
                            @error('renter_phone')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="renter_email" class="input input-bordered w-full @error('renter_email') input-error @enderror" 
                                   value="{{ old('renter_email') }}" required>
                            @error('renter_email')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- KTP/KTM -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-400">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-id-card text-yellow-600"></i> Dokumentasi
                    </h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">Upload KTP / KTM (Foto) *</label>
                        <div class="border-2 border-dashed border-yellow-200 rounded-lg p-6 text-center hover:border-yellow-400 hover:bg-yellow-50 transition cursor-pointer" 
                             onclick="document.getElementById('ktp_input').click()">
                            <i class="fas fa-cloud-upload-alt text-4xl text-yellow-300 mb-2"></i>
                            <p class="text-gray-600">Klik untuk upload atau drag & drop</p>
                            <p class="text-xs text-gray-500 mt-2">Max 2MB (JPEG, PNG, JPG, GIF)</p>
                        </div>
                        <input type="file" id="ktp_input" name="renter_ktp_ktm" class="hidden" accept="image/*" required onchange="showPreview(this)">
                        <div id="ktp_preview" class="mt-4 hidden">
                            <img id="preview_img" src="" alt="Preview" class="max-w-xs rounded-lg shadow">
                        </div>
                        @error('renter_ktp_ktm')
                        <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Rental Information -->
                <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-400">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-yellow-600"></i> Informasi Penyewaan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai *</label>
                            <input type="date" name="start_date" class="input input-bordered w-full @error('start_date') input-error @enderror" 
                                   value="{{ old('start_date') }}" min="{{ now()->format('Y-m-d') }}" required onchange="calculateDuration()">
                            @error('start_date')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai *</label>
                            <input type="date" name="end_date" class="input input-bordered w-full @error('end_date') input-error @enderror" 
                                   value="{{ old('end_date') }}" min="{{ now()->addDay()->format('Y-m-d') }}" required onchange="calculateDuration()">
                            @error('end_date')
                            <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="duration_display" class="md:col-span-2 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-gray-700">Durasi Penyewaan: <span class="font-bold text-yellow-600" id="duration_text">-</span></p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Penyewaan *</label>
                        <input type="text" name="rental_location" class="input input-bordered w-full @error('rental_location') input-error @enderror" 
                               placeholder="Alamat / Tempat acara" value="{{ old('rental_location') }}" required>
                        @error('rental_location')
                        <p class="text-error text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                        <textarea name="notes" class="textarea textarea-bordered w-full" rows="3" placeholder="Tambahkan informasi penting lainnya...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline flex-1 border-yellow-300 text-yellow-700 hover:bg-yellow-50">
                        <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                    </a>
                    <button type="submit" class="btn from-yellow-400 to-orange-500 btn-primary flex-1 btn-lg gap-2 text-white font-semibold">
                        <i class="fas fa-check"></i> Lanjutkan ke Pembayaran
                    </button>
                </div>
            </form>
        </div>

        <!-- Summary -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-lg p-6 border border-yellow-200 sticky top-20">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Ringkasan Pesanan</h2>

                <!-- Items -->
                <div class="space-y-3 mb-6 pb-6 border-b border-yellow-100 max-h-64 overflow-y-auto">
                    @foreach($cart as $item)
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-600">Qty: {{ $item['quantity'] }}</p>
                        </div>
                        <p class="font-semibold text-gray-800 text-sm">
                            Rp {{ number_format($item['price_per_day'] * $item['quantity'], 0, ',', '.') }}
                        </p>
                    </div>
                    @endforeach
                </div>

                <!-- Total (1 day) -->
                <div class="mb-6 pb-6 border-b border-yellow-100">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-700">Harga Per Hari</p>
                        <p class="font-bold text-lg text-gray-800">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>
                    </div>
                    <div id="total_display" class="flex justify-between items-center text-lg">
                        <p class="font-bold text-gray-800">Total</p>
                        <p class="font-bold text-2xl text-orange-600" id="grand_total">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Info -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-xs text-orange-800 space-y-2">
                    <p><i class="fas fa-info-circle"></i> Setelah booking dikonfirmasi admin akan menghubungi Anda melalui WhatsApp</p>
                    <p><i class="fas fa-money-bill-wave"></i> Lakukan pembayaran sesuai instruksi yang diberikan admin</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showPreview(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview_img').src = e.target.result;
            document.getElementById('ktp_preview').classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function calculateDuration() {
    const startDate = new Date(document.querySelector('input[name="start_date"]').value);
    const endDate = new Date(document.querySelector('input[name="end_date"]').value);
    
    if (startDate && endDate && endDate > startDate) {
        const duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
        const durationText = duration + ' hari';
        document.getElementById('duration_text').textContent = durationText;
        
        // Calculate grand total
        const pricePerDay = {{ $total }};
        const grandTotal = pricePerDay * duration;
        const grandTotalFormatted = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(grandTotal).replace('IDR', 'Rp').trim();
        
        document.getElementById('grand_total').textContent = grandTotalFormatted;
    }
}

// Calculate on load if dates are filled
window.addEventListener('load', calculateDuration);
</script>
@endsection

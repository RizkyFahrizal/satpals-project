@extends('layouts.app')

@section('title', 'Form Sewa - ' . $band->band_name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-4">
            <h1 class="text-3xl font-bold text-gray-800">Formulir Sewa Band</h1>
            <p class="text-gray-600 mt-2">{{ $band->band_name }}</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Band Summary -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-blue-500">
            <div class="flex items-start gap-4">
                @if($band->photo)
                <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->band_name }}" 
                     class="w-24 h-24 rounded-lg object-cover">
                @else
                <div class="w-24 h-24 rounded-lg bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-music text-gray-400 text-3xl"></i>
                </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $band->band_name }}</h2>
                    <p class="text-gray-600 mt-2">{{ Str::limit($band->description, 100) }}</p>
                    <div class="flex gap-4 mt-3">
                        <div>
                            <p class="text-gray-600 text-sm">Per Jam</p>
                            <p class="font-bold text-green-600">Rp {{ number_format($band->price_per_hour, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Per Event</p>
                            <p class="font-bold text-green-600">Rp {{ number_format($band->price_per_event, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-6">Isi Data Penyewa</h3>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="alert alert-error mb-6 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-bold">Ada kesalahan!</h3>
                    <div class="text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('public.bands.rental-store', $band) }}" method="POST">
                @csrf

                <!-- Renter Name -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nama Penyewa *</span>
                    </label>
                    <input 
                        type="text" 
                        name="renter_name" 
                        value="{{ old('renter_name', auth()->user()->name ?? '') }}"
                        placeholder="Nama lengkap Anda..." 
                        class="input input-bordered @error('renter_name') input-error @enderror"
                        required
                    >
                    @error('renter_name')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Renter Phone -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nomor Telepon *</span>
                    </label>
                    <input 
                        type="tel" 
                        name="renter_phone" 
                        value="{{ old('renter_phone', auth()->user()->phone ?? '') }}"
                        placeholder="08xxxxxxxxxx" 
                        class="input input-bordered @error('renter_phone') input-error @enderror"
                        required
                    >
                    @error('renter_phone')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Rental Purpose -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Tujuan Penyewaan *</span>
                    </label>
                    <textarea 
                        name="rental_purpose" 
                        placeholder="Untuk acara apa? (Pernikahan, Acara Kantor, Konser, dll)" 
                        rows="3"
                        class="textarea textarea-bordered @error('rental_purpose') textarea-error @enderror"
                        required
                    >{{ old('rental_purpose') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Jelaskan jenis acara dan detail singkat</p>
                    @error('rental_purpose')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Performance Date -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Tanggal & Waktu Pertunjukan *</span>
                    </label>
                    <input 
                        type="datetime-local" 
                        name="performance_date" 
                        value="{{ old('performance_date') }}"
                        class="input input-bordered @error('performance_date') input-error @enderror"
                        required
                    >
                    <p class="text-sm text-gray-500 mt-1">Pilih tanggal dan waktu ketika band akan tampil</p>
                    @error('performance_date')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Permohonan sewa Anda akan ditinjau oleh admin. Kami akan menghubungi Anda untuk konfirmasi dan detail lebih lanjut.</span>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="fas fa-check mr-2"></i> Kirim Permohonan
                    </button>
                    <a href="{{ route('public.bands.show', $band) }}" class="btn btn-ghost flex-1">Batal</a>
                </div>
            </form>
        </div>

        <!-- Info Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i> Prosesnya Mudah
                </h4>
                <ol class="text-sm text-gray-600 space-y-2 list-decimal list-inside">
                    <li>Isi formulir dengan data Anda</li>
                    <li>Admin akan meninjaunya</li>
                    <li>Kami akan menghubungi Anda</li>
                    <li>Konfirmasi dan finalisasi</li>
                </ol>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="fas fa-headset text-blue-600"></i> Butuh Bantuan?
                </h4>
                @if($band->whatsapp_number)
                <p class="text-sm text-gray-600 mb-2">Hubungi kami via WhatsApp untuk informasi lebih lanjut</p>
                <a href="https://wa.me/{{ $band->whatsapp_number }}" target="_blank" class="btn btn-sm btn-success">
                    <i class="fas fa-whatsapp"></i> Chat WhatsApp
                </a>
                @else
                <p class="text-sm text-gray-600">Hubungi admin melalui form ini untuk pertanyaan Anda</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

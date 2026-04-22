@extends('layouts.app')

@section('title', 'Form Sewa - ' . $band->band_name)

@section('content')
<div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white">
    <!-- Header -->
    <div class="bg-yellow-400 shadow-md">
        <div class="container mx-auto px-4 py-6 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">📝 Formulir Sewa Band</h1>
                <p class="text-gray-700 mt-2">{{ $band->band_name }}</p>
            </div>
            <a href="{{ route('public.bands.show', $band) }}" class="btn btn-sm btn-ghost text-gray-700 hover:text-gray-900">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Band Summary -->
        <div class="bg-white rounded-2xl shadow-md p-6 mb-6 border-l-4 border-yellow-400">
            <div class="flex items-start gap-4">
                @if($band->photo)
                <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->band_name }}" 
                     class="w-24 h-24 rounded-xl object-cover shadow-md">
                @else
                <div class="w-24 h-24 rounded-xl bg-yellow-100 flex items-center justify-center shadow-md">
                    <i class="fas fa-music text-yellow-400 text-3xl"></i>
                </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $band->band_name }}</h2>
                    <p class="text-gray-600 mt-2">{{ Str::limit($band->description, 100) }}</p>
                    <div class="flex gap-4 mt-3 flex-wrap">
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Per Jam</p>
                            <p class="font-bold text-green-600">Rp {{ number_format($band->price_per_hour, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Per Event</p>
                            <p class="font-bold text-green-600">Rp {{ number_format($band->price_per_event, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm font-semibold">Anggota Band</p>
                            <p class="font-bold text-blue-600">{{ $band->members->count() }} Personil</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="text-yellow-500">✓</span> Isi Data Penyewa
            </h3>

            <!-- Error Messages -->
            @if ($errors->any())
            <div class="alert alert-error mb-6 shadow-lg border-l-4 border-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-bold">❌ Ada kesalahan!</h3>
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

                <!-- Renter Email -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Email *</span>
                    </label>
                    <input 
                        type="email" 
                        name="renter_email" 
                        value="{{ old('renter_email', auth()->user()->email ?? '') }}"
                        placeholder="email@example.com" 
                        class="input input-bordered @error('renter_email') input-error @enderror"
                        required
                    >
                    @error('renter_email')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Rental Type -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Tipe Sewa *</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer transition {{ old('rental_type', 'hourly') === 'hourly' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 bg-white' }}">
                            <input type="radio" name="rental_type" value="hourly" class="radio radio-warning mt-1" {{ old('rental_type', 'hourly') === 'hourly' ? 'checked' : '' }}>
                            <div>
                                <p class="font-bold text-gray-800">Per Jam</p>
                                <p class="text-sm text-gray-500">Menampilkan jam mulai, jam selesai, dan jam break</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer transition {{ old('rental_type') === 'event' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 bg-white' }}">
                            <input type="radio" name="rental_type" value="event" class="radio radio-warning mt-1" {{ old('rental_type') === 'event' ? 'checked' : '' }}>
                            <div>
                                <p class="font-bold text-gray-800">Event</p>
                                <p class="text-sm text-gray-500">Satu harga paket, tanpa jam dan break</p>
                            </div>
                        </label>
                    </div>
                    @error('rental_type')
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

                <!-- Venue Address -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Alamat Lengkap Tempat Persewaan *</span>
                    </label>
                    <textarea 
                        name="venue_address" 
                        placeholder="Masukkan alamat lengkap tempat pertunjukan (jalan, kelurahan, kecamatan, kota)" 
                        rows="3"
                        class="textarea textarea-bordered @error('venue_address') textarea-error @enderror"
                        required
                    >{{ old('venue_address') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Pastikan alamat lengkap dan mudah ditemukan</p>
                    @error('venue_address')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Performance Date -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Tanggal Pertunjukan *</span>
                    </label>
                    <input 
                        type="date" 
                        name="performance_date" 
                        id="performanceDate"
                        value="{{ old('performance_date') }}"
                        class="input input-bordered @error('performance_date') input-error @enderror"
                        required
                    >
                    @error('performance_date')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Performance Start Time -->
                <div class="form-control mb-4" id="hourlyFields">
                    <label class="label">
                        <span class="label-text font-semibold">Waktu Mulai *</span>
                    </label>
                    <input 
                        type="time" 
                        name="performance_start_time" 
                        value="{{ old('performance_start_time') }}"
                        class="input input-bordered @error('performance_start_time') input-error @enderror"
                        required
                    >
                    <p class="text-sm text-gray-500 mt-1">Jam berapa band mulai tampil?</p>
                    @error('performance_start_time')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Performance End Time -->
                <div class="form-control mb-6" id="hourlyFieldsEnd">
                    <label class="label">
                        <span class="label-text font-semibold">Waktu Berakhir *</span>
                    </label>
                    <input 
                        type="time" 
                        name="performance_end_time" 
                        value="{{ old('performance_end_time') }}"
                        class="input input-bordered @error('performance_end_time') input-error @enderror"
                        required
                    >
                    <p class="text-sm text-gray-500 mt-1">Jam berapa band selesai tampil?</p>
                    @error('performance_end_time')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Performance Duration (Band Main Duration) -->
                <div class="bg-blue-50 border-l-4 border-blue-400 rounded p-4 mb-4" id="durationSection">
                    <h4 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-clock"></i> Durasi Acara & Break
                    </h4>
                    <p class="text-sm text-blue-700 mb-4">
                        ⏱️ Masukkan jam break, durasi band main akan dihitung otomatis
                    </p>
                    
                    <!-- Break Duration -->
                    <div class="mb-4">
                        <label class="text-sm font-semibold text-blue-900 mb-2 block">Total Jam Break</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold text-sm">Jam</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="break_duration_hours" 
                                    id="breakHours"
                                    value="{{ old('break_duration_hours', 0) }}"
                                    min="0" 
                                    max="23"
                                    placeholder="0" 
                                    class="input input-bordered input-sm @error('break_duration_hours') input-error @enderror"
                                    required
                                >
                                @error('break_duration_hours')
                                    <label class="label">
                                        <span class="label-text-alt text-error text-xs">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold text-sm">Menit</span>
                                </label>
                                <input 
                                    type="number" 
                                    name="break_duration_minutes" 
                                    id="breakMinutes"
                                    value="{{ old('break_duration_minutes', 0) }}"
                                    min="0" 
                                    max="59"
                                    placeholder="0" 
                                    class="input input-bordered input-sm @error('break_duration_minutes') input-error @enderror"
                                    required
                                >
                                @error('break_duration_minutes')
                                    <label class="label">
                                        <span class="label-text-alt text-error text-xs">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Performance Duration (Auto-calculated, disabled) -->
                    <div>
                        <label class="text-sm font-semibold text-blue-900 mb-2 block">Durasi Band Main (Otomatis)</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold text-sm">Jam</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="performanceHours"
                                    value="{{ old('performance_duration_hours', 0) }}"
                                    readonly
                                    class="input input-bordered input-sm bg-gray-100"
                                >
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold text-sm">Menit</span>
                                </label>
                                <input 
                                    type="number" 
                                    id="performanceMinutes"
                                    value="{{ old('performance_duration_minutes', 0) }}"
                                    readonly
                                    class="input input-bordered input-sm bg-gray-100"
                                >
                            </div>
                        </div>
                        <p class="text-xs text-blue-600 mt-2">
                            = Waktu Acara - Jam Break
                        </p>
                    </div>
                </div>

                <!-- Hidden inputs for form submission -->
                <input type="hidden" id="performanceDurationHoursHidden" name="performance_duration_hours" value="{{ old('performance_duration_hours', 0) }}">
                <input type="hidden" id="performanceDurationMinutesHidden" name="performance_duration_minutes" value="{{ old('performance_duration_minutes', 0) }}">

                <!-- Price Summary -->
                <div id="priceSummaryCard" class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 mb-6">
                    <div class="flex items-center justify-between mb-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-600" id="priceLabel">Harga Per Jam</p>
                            <p class="text-lg font-bold text-gray-900" id="priceUnit">Rp {{ number_format($band->price_per_hour, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Total Harga</p>
                            <p id="total-price" class="text-2xl font-bold text-yellow-700">Rp 0</p>
                        </div>
                    </div>
                    <p id="priceSummaryNote" class="text-xs text-gray-500">Pilih tipe sewa untuk melihat perhitungan harga.</p>
                </div>

                <!-- Info Alert -->
                <div class="alert alert-info mb-6 bg-blue-50 border-l-4 border-blue-400 text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>ℹ️ Permohonan sewa Anda akan ditinjau oleh admin. Kami akan menghubungi Anda untuk konfirmasi dan detail lebih lanjut.</span>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" class="btn btn-sm flex-1 bg-yellow-400 hover:bg-yellow-500 border-0 text-gray-900 font-bold">
                        <i class="fas fa-check mr-2"></i> Kirim Permohonan
                    </button>
                    <a href="{{ route('public.bands.show', $band) }}" class="btn btn-sm flex-1 border-2 border-gray-300 hover:bg-gray-100 hover:border-gray-400 text-gray-800">Batal</a>
                </div>
            </form>
        </div>

        <!-- Info Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-green-100">
                <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2 text-lg">
                    <span class="text-2xl">✅</span> Prosesnya Mudah
                </h4>
                <ol class="text-sm text-gray-600 space-y-2 list-decimal list-inside">
                    <li>Isi formulir dengan data Anda</li>
                    <li>Admin akan meninjaunya</li>
                    <li>Kami akan menghubungi Anda</li>
                    <li>Konfirmasi dan finalisasi</li>
                </ol>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 border border-yellow-100">
                <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2 text-lg">
                    <span class="text-2xl">💬</span> Butuh Bantuan?
                </h4>
                @if($band->whatsapp_number)
                <p class="text-sm text-gray-600 mb-3">Hubungi kami via WhatsApp untuk informasi lebih lanjut</p>
                <a href="https://wa.me/{{ $band->whatsapp_number }}" target="_blank" class="btn btn-sm bg-green-500 hover:bg-green-600 border-0 text-white w-full">
                    <i class="fas fa-whatsapp"></i> Chat WhatsApp
                </a>
                @else
                <p class="text-sm text-gray-600">Hubungi admin melalui form ini untuk pertanyaan Anda</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.querySelector('input[name="performance_start_time"]');
    const endTimeInput = document.querySelector('input[name="performance_end_time"]');
    const breakHoursInput = document.getElementById('breakHours');
    const breakMinutesInput = document.getElementById('breakMinutes');
    const performanceHoursInput = document.getElementById('performanceHours');
    const performanceMinutesInput = document.getElementById('performanceMinutes');
    const performanceHoursHidden = document.getElementById('performanceDurationHoursHidden');
    const performanceMinutesHidden = document.getElementById('performanceDurationMinutesHidden');
    const rentalTypeInputs = document.querySelectorAll('input[name="rental_type"]');
    const hourlyFields = document.getElementById('hourlyFields');
    const hourlyFieldsEnd = document.getElementById('hourlyFieldsEnd');
    const durationSection = document.getElementById('durationSection');
    const priceLabel = document.getElementById('priceLabel');
    const priceUnit = document.getElementById('priceUnit');
    const totalPrice = document.getElementById('total-price');
    const priceSummaryNote = document.getElementById('priceSummaryNote');

    const pricePerHour = {{ (int) $band->price_per_hour }};
    const pricePerEvent = {{ (int) $band->price_per_event }};

    function formatRupiah(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
    }

    function getRentalType() {
        const checked = document.querySelector('input[name="rental_type"]:checked');
        return checked ? checked.value : 'hourly';
    }

    function toggleRentalType() {
        const isEvent = getRentalType() === 'event';

        hourlyFields.classList.toggle('hidden', isEvent);
        hourlyFieldsEnd.classList.toggle('hidden', isEvent);
        durationSection.classList.toggle('hidden', isEvent);

        startTimeInput.required = !isEvent;
        endTimeInput.required = !isEvent;
        breakHoursInput.required = !isEvent;
        breakMinutesInput.required = !isEvent;

        if (isEvent) {
            startTimeInput.value = '';
            endTimeInput.value = '';
            breakHoursInput.value = 0;
            breakMinutesInput.value = 0;
            performanceHoursInput.value = 0;
            performanceMinutesInput.value = 0;
            performanceHoursHidden.value = 0;
            performanceMinutesHidden.value = 0;

            priceLabel.textContent = 'Harga Per Event';
            priceUnit.textContent = formatRupiah(pricePerEvent);
            priceSummaryNote.textContent = 'Mode event memakai satu harga paket, tanpa jam dan break.';
            totalPrice.textContent = formatRupiah(pricePerEvent);
        } else {
            priceLabel.textContent = 'Harga Per Jam';
            priceUnit.textContent = formatRupiah(pricePerHour);
            priceSummaryNote.textContent = 'Total dihitung dari durasi band main × harga per jam.';
            calculateDuration();
        }
    }

    function calculateDuration() {
        if (getRentalType() === 'event') {
            return;
        }

        if (!startTimeInput.value || !endTimeInput.value) {
            totalPrice.textContent = formatRupiah(0);
            return;
        }

        // Calculate event duration
        const [startH, startM] = startTimeInput.value.split(':').map(Number);
        const [endH, endM] = endTimeInput.value.split(':').map(Number);

        let eventMinutes = (endH * 60 + endM) - (startH * 60 + startM);
        
        if (eventMinutes < 0) {
            eventMinutes += 24 * 60; // Next day
        }

        // Calculate break duration
        const breakMinutes = (parseInt(breakHoursInput.value || 0) * 60) + parseInt(breakMinutesInput.value || 0);

        // Calculate performance duration (event duration - break)
        const mainMinutes = Math.max(0, eventMinutes - breakMinutes);

        // Convert to hours and minutes
        const mainHours = Math.floor(mainMinutes / 60);
        const remainingMinutes = mainMinutes % 60;

        performanceHoursInput.value = mainHours;
        performanceMinutesInput.value = remainingMinutes;
        
        // Update hidden inputs for form submission
        performanceHoursHidden.value = mainHours;
        performanceMinutesHidden.value = remainingMinutes;

        const totalHours = Math.max(1, Math.ceil(mainMinutes / 60));
        totalPrice.textContent = formatRupiah(totalHours * pricePerHour);
    }

    // Event listeners
    startTimeInput.addEventListener('change', calculateDuration);
    startTimeInput.addEventListener('input', calculateDuration);
    endTimeInput.addEventListener('change', calculateDuration);
    endTimeInput.addEventListener('input', calculateDuration);
    breakHoursInput.addEventListener('input', calculateDuration);
    breakMinutesInput.addEventListener('input', calculateDuration);
    rentalTypeInputs.forEach((input) => input.addEventListener('change', toggleRentalType));

    // Calculate on page load if values exist
    toggleRentalType();
    calculateDuration();
});
</script>

@endsection

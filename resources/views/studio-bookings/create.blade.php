@extends('layouts.app')

@section('title', 'Booking Studio')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-yellow-50 to-orange-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Booking Studio Satpal</h1>
            <p class="text-gray-600">Ajukan permohonan untuk menggunakan Studio Satpal</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Form Section -->
            <div class="lg:col-span-2">
                <!-- Alert Messages -->
                @if ($errors->any())
                    <div class="alert alert-error shadow-lg mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h3 class="font-bold">Terdapat Kesalahan</h3>
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

                <!-- Form Card -->
                <div class="card bg-white shadow-xl">
                    <div class="card-body p-8">
                        <form action="{{ route('studio-bookings.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- NPM Input -->
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold text-gray-800">NPM</span>
                                    <span class="label-text-alt text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="npm" 
                                       class="input input-bordered w-full @error('npm') input-error @enderror"
                                       placeholder="Masukkan NPM Anda"
                                       value="{{ old('npm') }}"
                                       required>
                                <label class="label">
                                    <span class="label-text-alt text-gray-500">NPM harus sesuai dengan data yang terdaftar</span>
                                </label>
                                @error('npm')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Nama Lengkap Input -->
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold text-gray-800">Nama Lengkap</span>
                                    <span class="label-text-alt text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="nama_lengkap" 
                                       class="input input-bordered w-full @error('nama_lengkap') input-error @enderror"
                                       placeholder="Masukkan nama lengkap Anda"
                                       value="{{ old('nama_lengkap') }}"
                                       required>
                                <label class="label">
                                    <span class="label-text-alt text-gray-500">Nama harus sesuai dengan data yang terdaftar</span>
                                </label>
                                @error('nama_lengkap')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Email Input -->
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold text-gray-800">Email</span>
                                    <span class="label-text-alt text-red-500">*</span>
                                </label>
                                <input type="email"
                                       name="renter_email"
                                       class="input input-bordered w-full @error('renter_email') input-error @enderror"
                                       placeholder="nama@email.com"
                                       value="{{ old('renter_email', auth()->user()->email ?? '') }}"
                                       required>
                                @error('renter_email')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Phone Input -->
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold text-gray-800">No. Telepon</span>
                                    <span class="label-text-alt text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="renter_phone"
                                       class="input input-bordered w-full @error('renter_phone') input-error @enderror"
                                       placeholder="08xxxxxxxxxx"
                                       value="{{ old('renter_phone', auth()->user()->phone ?? '') }}"
                                       required>
                                @error('renter_phone')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Booking Type -->
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold text-gray-800">Tipe Peserta</span>
                                    <span class="label-text-alt text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition {{ old('booking_scope', 'ukm_all') === 'ukm_all' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 bg-white' }}">
                                        <input type="radio" name="booking_scope" value="ukm_all" class="radio radio-warning" {{ old('booking_scope', 'ukm_all') === 'ukm_all' ? 'checked' : '' }} onchange="toggleParticipantFields()">
                                        <div>
                                            <p class="font-semibold text-gray-800">UKM semua</p>
                                            <p class="text-sm text-gray-500">Tidak ada peserta non-UKM, jadi tidak ada biaya</p>
                                        </div>
                                    </label>
                                    <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer transition {{ old('booking_scope') === 'non_ukm' ? 'border-yellow-500 bg-yellow-50' : 'border-gray-200 bg-white' }}">
                                        <input type="radio" name="booking_scope" value="non_ukm" class="radio radio-warning" {{ old('booking_scope') === 'non_ukm' ? 'checked' : '' }} onchange="toggleParticipantFields()">
                                        <div>
                                            <p class="font-semibold text-gray-800">Ada peserta non-UKM</p>
                                            <p class="text-sm text-gray-500">Masukkan jumlah peserta non-UKM yang ikut</p>
                                        </div>
                                    </label>
                                </div>
                                @error('booking_scope')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Tanggal Booking -->
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold text-gray-800">Tanggal Booking</span>
                                    <span class="label-text-alt text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal_booking" 
                                       id="tanggal_booking"
                                       class="input input-bordered w-full @error('tanggal_booking') input-error @enderror"
                                       value="{{ old('tanggal_booking') }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       required
                                       onchange="updateAvailability()">
                                <label class="label">
                                    <span class="label-text-alt text-gray-500">Pilih tanggal mulai dari hari ini</span>
                                </label>
                                @error('tanggal_booking')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Sesi Selection -->
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold text-gray-800">Pilih Sesi</span>
                                    <span class="label-text-alt text-red-500">*</span>
                                </label>
                                <select name="sesi" 
                                        id="sesi"
                                        class="select select-bordered w-full @error('sesi') select-error @enderror"
                                        required>
                                    <option value="" disabled selected>-- Pilih Sesi --</option>
                                    @foreach (\App\Models\StudioBooking::SESI_TIMES as $sesiNum => $sesiInfo)
                                        <option value="{{ $sesiNum }}" {{ old('sesi') == $sesiNum ? 'selected' : '' }}>
                                            {{ $sesiInfo['label'] }} ({{ $sesiInfo['start'] }} - {{ $sesiInfo['end'] }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('sesi')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Keperluan -->
                            <div>
                                <label class="label">
                                    <span class="label-text font-semibold text-gray-800">Keperluan / Tujuan Penggunaan</span>
                                    <span class="label-text-alt text-red-500">*</span>
                                </label>
                                <textarea name="keperluan" 
                                          class="textarea textarea-bordered w-full @error('keperluan') textarea-error @enderror"
                                          rows="4"
                                          placeholder="Jelaskan untuk apa Anda ingin menggunakan studio..."
                                          required>{{ old('keperluan') }}</textarea>
                                <label class="label">
                                    <span class="label-text-alt text-gray-500">Minimal 10 karakter, maksimal 500 karakter</span>
                                </label>
                                @error('keperluan')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <!-- Non UKM Count -->
                            <div id="non-ukm-wrapper">
                                <label class="label">
                                    <span class="label-text font-semibold text-gray-800">Jumlah Non-UKM</span>
                                    <span class="label-text-alt text-gray-500">Opsional jika UKM semua</span>
                                </label>
                                <input type="number"
                                       name="jumlah_non_ukm"
                                       id="jumlah_non_ukm"
                                       min="0"
                                       value="{{ old('jumlah_non_ukm', 0) }}"
                                       class="input input-bordered w-full @error('jumlah_non_ukm') input-error @enderror"
                                       onchange="updatePriceSummary()"
                                       oninput="updatePriceSummary()"
                                       >
                                <label class="label">
                                    <span class="label-text-alt text-gray-500">Harga per orang non-UKM saat ini: Rp {{ number_format($pricePerPerson, 0, ',', '.') }}</span>
                                </label>
                                @error('jumlah_non_ukm')
                                    <label class="label">
                                        <span class="label-text-alt text-error">{{ $message }}</span>
                                    </label>
                                @enderror
                            </div>

                            <div id="ukm-all-message" class="hidden alert alert-info shadow-sm">
                                <span>Mode UKM semua aktif. Tidak ada biaya yang dihitung.</span>
                            </div>

                            <!-- Price Summary -->
                            <div id="price-summary-card" class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
                                <div class="flex items-center justify-between mb-3 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Harga per Orang</p>
                                        <p class="text-lg font-bold text-gray-900">Rp {{ number_format($pricePerPerson, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">Total Harga</p>
                                        <p id="total-price" class="text-2xl font-bold text-yellow-700">Rp 0</p>
                                    </div>
                                </div>
                                <p id="price-summary-note" class="text-xs text-gray-500">Total dihitung dari jumlah non-UKM × harga per orang.</p>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex gap-3 pt-4">
                                <button type="submit" class="btn btn-warning flex-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" />
                                    </svg>
                                    Ajukan Booking
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                    Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Availability Calendar Sidebar -->
            <div class="lg:col-span-1">
                <!-- Info Card -->
                <div class="card bg-blue-50 border-l-4 border-blue-400 shadow-lg mb-6">
                    <div class="card-body p-6">
                        <h3 class="font-bold text-blue-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                            </svg>
                            Jadwal Sesi Studio
                        </h3>
                        <div class="space-y-2">
                            @foreach (\App\Models\StudioBooking::SESI_TIMES as $sesiNum => $sesiInfo)
                                <div class="flex justify-between items-center p-3 bg-white rounded-lg border border-blue-200">
                                    <span class="font-medium text-gray-700">{{ $sesiInfo['label'] }}</span>
                                    <span class="text-blue-600 font-semibold text-sm">{{ $sesiInfo['start'] }} - {{ $sesiInfo['end'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Availability Card -->
                <div class="card bg-white shadow-lg border-t-4 border-yellow-400">
                    <div class="card-body p-6">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H6a6 6 0 100 12H4a2 2 0 01-2-2v-4a2 2 0 012-2zm6 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                            Ketersediaan
                        </h3>

                        <div id="availability-container" class="space-y-3 max-h-96 overflow-y-auto">
                            <p class="text-gray-500 text-sm text-center py-8">Pilih tanggal untuk melihat ketersediaan sesi</p>
                        </div>

                        <!-- Legend -->
                        <div class="mt-4 pt-4 border-t border-gray-200 space-y-2 text-xs">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-gray-600">Tersedia</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-gray-600">Terpesan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Availability Data (Hidden) -->
    <div id="bookings-data" style="display: none;">
        @json($bookings)
    </div>

    <script>
        const pricePerPerson = {{ (int) $pricePerPerson }};

        function formatRupiah(value) {
            return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
        }

        function getSelectedBookingScope() {
            const checked = document.querySelector('input[name="booking_scope"]:checked');
            return checked ? checked.value : 'ukm_all';
        }

        function toggleParticipantFields() {
            const scope = getSelectedBookingScope();
            const wrapper = document.getElementById('non-ukm-wrapper');
            const ukmAllMessage = document.getElementById('ukm-all-message');
            const qtyInput = document.getElementById('jumlah_non_ukm');
            const priceCard = document.getElementById('price-summary-card');
            const priceNote = document.getElementById('price-summary-note');

            if (!wrapper || !ukmAllMessage || !qtyInput || !priceCard || !priceNote) {
                return;
            }

            const isNonUkm = scope === 'non_ukm';

            wrapper.classList.toggle('hidden', !isNonUkm);
            ukmAllMessage.classList.toggle('hidden', isNonUkm);
            qtyInput.required = isNonUkm;
            qtyInput.min = isNonUkm ? '1' : '0';

            if (!isNonUkm) {
                qtyInput.value = '0';
                priceCard.classList.add('hidden');
                priceNote.textContent = 'Pilih "Ada peserta non-UKM" jika ada biaya per orang.';
                document.getElementById('total-price').textContent = formatRupiah(0);
            } else {
                priceCard.classList.remove('hidden');
                priceCard.classList.remove('opacity-75');
                priceNote.textContent = 'Total dihitung dari jumlah non-UKM × harga per orang.';
                updatePriceSummary();
            }
        }

        function updatePriceSummary() {
            const qtyInput = document.getElementById('jumlah_non_ukm');
            const totalPriceElement = document.getElementById('total-price');
            if (getSelectedBookingScope() !== 'non_ukm') {
                totalPriceElement.textContent = formatRupiah(0);
                return;
            }

            if (!qtyInput || !totalPriceElement) {
                return;
            }

            const quantity = parseInt(qtyInput.value || '0', 10);
            const total = Math.max(0, quantity * pricePerPerson);

            totalPriceElement.textContent = formatRupiah(total);
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleParticipantFields();
            updatePriceSummary();
        });
    </script>

    <script>
        function updateAvailability() {
            const dateInput = document.getElementById('tanggal_booking');
            const selectedDate = dateInput.value;
            const container = document.getElementById('availability-container');
            const bookingsData = JSON.parse(document.getElementById('bookings-data').textContent);

            console.log('Selected Date:', selectedDate);
            console.log('Bookings Data:', bookingsData);
            console.log('Keys in Bookings:', Object.keys(bookingsData));

            if (!selectedDate) {
                container.innerHTML = '<p class="text-gray-500 text-sm text-center py-8">Pilih tanggal untuk melihat ketersediaan sesi</p>';
                return;
            }

            let html = '';
            const sesiTimes = {
                1: { label: 'Sesi 1', start: '08:00', end: '11:00' },
                2: { label: 'Sesi 2', start: '11:00', end: '14:00' },
                3: { label: 'Sesi 3', start: '14:00', end: '17:00' },
                4: { label: 'Sesi 4', start: '17:00', end: '20:00' }
            };

            for (let sesiNum = 1; sesiNum <= 4; sesiNum++) {
                const sesiInfo = sesiTimes[sesiNum];
                const bookingKey = selectedDate + '-' + sesiNum;
                const isBooked = bookingKey in bookingsData;

                console.log(`Checking key: ${bookingKey}, isBooked:`, isBooked);

                const bgColor = isBooked ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200';
                const statusColor = isBooked ? 'text-red-600' : 'text-green-600';
                const dotColor = isBooked ? 'bg-red-500' : 'bg-green-500';
                const statusText = isBooked ? 'Terpesan' : 'Tersedia';

                html += `
                    <div class="p-3 rounded-lg border ${bgColor}">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-800">${sesiInfo.label}</p>
                                <p class="text-xs text-gray-600">${sesiInfo.start} - ${sesiInfo.end}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full ${dotColor}"></div>
                                <span class="font-medium text-xs ${statusColor}">${statusText}</span>
                            </div>
                        </div>
                    </div>
                `;
            }

            container.innerHTML = html;
        }

        // Initialize on page load
        window.addEventListener('DOMContentLoaded', function() {
            toggleParticipantFields();
            if (document.getElementById('tanggal_booking').value) {
                updateAvailability();
            }
        });

        // Update when date input changes
        document.getElementById('tanggal_booking').addEventListener('change', updateAvailability);
    </script>
</div>
@endsection

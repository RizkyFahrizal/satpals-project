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
            if (document.getElementById('tanggal_booking').value) {
                updateAvailability();
            }
        });

        // Update when date input changes
        document.getElementById('tanggal_booking').addEventListener('change', updateAvailability);
    </script>
</div>
@endsection

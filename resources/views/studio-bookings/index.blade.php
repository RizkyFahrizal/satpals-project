@extends('layouts.app')

@section('title', 'Booking Studio - Satya Palapa')

@section('content')
<!-- Hero Section -->
<div class="relative py-16 overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-32 h-32 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-20 w-48 h-48 bg-orange-400/20 rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-6xl mx-auto px-4 relative z-10 text-center">
        <span class="inline-block px-4 py-2 bg-yellow-400/20 backdrop-blur-sm rounded-full text-yellow-400 text-sm font-semibold mb-4 border border-yellow-400/30">
            🎵 Booking Studio Satya Palapa
        </span>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Pesan Studio dengan Mudah</h1>
        <p class="text-xl text-gray-300 max-w-3xl mx-auto">Lihat ketersediaan studio, periksa jadwal booking yang sudah ada, dan ajukan permohonan booking Anda sekarang.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <!-- My Bookings Section (if logged in) -->
    @if (auth()->check() && $myBookings && $myBookings->count() > 0)
    <div class="mb-12">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border border-blue-200 p-6 md:p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Booking Saya
            </h2>
            <div class="space-y-3">
                @foreach ($myBookings as $booking)
                <div class="bg-white rounded-lg p-4 border-l-4 {{ $booking->status === 'approved' ? 'border-green-500' : ($booking->status === 'pending' ? 'border-yellow-500' : 'border-red-500') }}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_booking)->locale('id')->translatedFormat('d F Y') }} - {{ $booking->sesi_label }}</p>
                                <span class="text-sm text-gray-600">({{ $booking->sesi_time }})</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-1"><strong>Keperluan:</strong> {{ $booking->keperluan }}</p>
                            <p class="text-sm text-gray-600"><strong>Nama:</strong> {{ $booking->nama_pemohon }}</p>
                        </div>
                        <div class="text-right">
                            @if ($booking->status === 'approved')
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-green-100 text-green-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Diterima
                                </span>
                            @elseif ($booking->status === 'pending')
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-yellow-100 text-yellow-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Menunggu
                                </span>
                            @elseif ($booking->status === 'rejected')
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-red-100 text-red-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Ditolak
                                </span>
                            @elseif ($booking->status === 'completed')
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Selesai
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Calendar & Booking Section -->
    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Calendar -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 md:p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Jadwal Ketersediaan
                </h2>

                <!-- Mini Calendar -->
                <div id="calendar" class="space-y-6"></div>

                <!-- Legend -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="font-semibold text-gray-800 mb-3">Keterangan:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-green-100 border-2 border-green-500 rounded"></div>
                            <span class="text-sm text-gray-600">Tersedia (Ada slot kosong)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-4 h-4 bg-red-100 border-2 border-red-500 rounded"></div>
                            <span class="text-sm text-gray-600">Terpesan (Penuh)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Button Section -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl border border-yellow-200 p-6 md:p-8 sticky top-24">
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Ajukan Booking</h3>
                    <p class="text-sm text-gray-600 mb-6">Klik tombol di bawah untuk mengajukan permohonan booking studio. Admin akan memproses dalam waktu singkat.</p>
                    
                    <a href="{{ route('studio-bookings.create') }}" class="btn btn-lg btn-warning gap-2 w-full justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        Booking Studio
                    </a>

                    <p class="text-xs text-gray-500 mt-4 leading-relaxed">
                        Syarat & Ketentuan:
                        <br>✓ Member UKM Satya Palapa
                        <br>✓ Booking min 2 sesi
                        <br>✓ Konfirmasi ke admin UKM
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Data bookings dari server
    const bookingsByDate = @json($bookings);

    function generateCalendar() {
        const calendar = document.getElementById('calendar');
        const today = new Date();
        const endDate = new Date(today);
        endDate.setDate(endDate.getDate() + 60);

        const monthsData = {};
        
        // Group dates by month
        let currentDate = new Date(today);
        while (currentDate <= endDate) {
            const month = currentDate.toLocaleDateString('id-ID', { year: 'numeric', month: 'long' });
            if (!monthsData[month]) {
                monthsData[month] = [];
            }
            monthsData[month].push(new Date(currentDate));
            currentDate.setDate(currentDate.getDate() + 1);
        }

        // Generate calendar for each month
        for (const [month, dates] of Object.entries(monthsData)) {
            const monthDiv = document.createElement('div');
            monthDiv.className = 'mb-8 pb-8 border-b border-gray-200 last:border-b-0';
            
            const monthTitle = document.createElement('h3');
            monthTitle.className = 'font-bold text-gray-800 mb-4 text-lg';
            monthTitle.textContent = month;
            monthDiv.appendChild(monthTitle);

            const datesGrid = document.createElement('div');
            datesGrid.className = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2';

            for (const date of dates) {
                const dateStr = date.toISOString().split('T')[0];
                const hasBooking = bookingsByDate[dateStr];
                const bookedSessions = hasBooking ? Object.keys(hasBooking) : [];
                const allSessionsBooked = bookedSessions.length === 4;

                const dateBtn = document.createElement('button');
                dateBtn.className = `p-3 rounded-lg text-sm font-semibold transition-all border-2 ${
                    allSessionsBooked 
                        ? 'bg-red-100 border-red-400 text-red-700 cursor-not-allowed' 
                        : 'bg-green-100 border-green-400 text-green-700 hover:shadow-lg hover:scale-105'
                }`;
                dateBtn.textContent = date.getDate();
                dateBtn.title = allSessionsBooked ? 'Semua sesi penuh' : 'Ada slot tersedia';

                if (!allSessionsBooked) {
                    dateBtn.addEventListener('click', () => showSessionDetail(dateStr, hasBooking));
                }

                datesGrid.appendChild(dateBtn);
            }

            monthDiv.appendChild(datesGrid);
            calendar.appendChild(monthDiv);
        }
    }

    function showSessionDetail(dateStr, bookings) {
        const dateObj = new Date(dateStr);
        const dateFormatted = dateObj.toLocaleDateString('id-ID', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });

        const sesiTimes = {
            1: { label: 'Sesi 1', time: '08:00 - 11:00' },
            2: { label: 'Sesi 2', time: '11:00 - 14:00' },
            3: { label: 'Sesi 3', time: '14:00 - 17:00' },
            4: { label: 'Sesi 4', time: '17:00 - 20:00' }
        };

        let html = `
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl max-w-lg w-full p-6 md:p-8 max-h-96 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Ketersediaan ${dateFormatted}</h3>
                    <div class="space-y-3 mb-6">
        `;

        for (let sesi = 1; sesi <= 4; sesi++) {
            const isBooked = bookings && bookings[sesi];
            const sesiInfo = sesiTimes[sesi];
            
            html += `
                <div class="p-4 rounded-lg border-2 ${
                    isBooked 
                        ? 'bg-red-50 border-red-300' 
                        : 'bg-green-50 border-green-300'
                }">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">${sesiInfo.label}</p>
                            <p class="text-sm text-gray-600">${sesiInfo.time}</p>
                        </div>
                        <span class="text-sm font-bold ${
                            isBooked 
                                ? 'text-red-600' 
                                : 'text-green-600'
                        }">
                            ${isBooked ? '❌ Terpesan' : '✅ Tersedia'}
                        </span>
                    </div>
                    ${isBooked ? `<p class="text-xs text-gray-500 mt-2">Dipesan oleh: ${isBooked.nama}</p>` : ''}
                </div>
            `;
        }

        html += `
                    </div>
                    <button onclick="this.closest('.fixed').remove()" class="w-full btn btn-ghost">Tutup</button>
                </div>
            </div>
        `;

        const container = document.createElement('div');
        container.innerHTML = html;
        document.body.appendChild(container);
    }

    // Generate calendar on page load
    window.addEventListener('DOMContentLoaded', generateCalendar);
</script>

@endsection

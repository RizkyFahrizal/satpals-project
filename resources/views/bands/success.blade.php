@extends('layouts.app')

@section('title', 'Sewa Band Berhasil - ' . $band->band_name)

@section('content')
@php
    $rentalType = $rentalType ?? ($rental->rental_type ?? session('band_rental_type'));
    if (!$rentalType) {
        $rentalType = ((int) ($rental->harga_pokok ?? 0) === (int) ($band->price_per_event ?? 0)) ? 'event' : 'hourly';
    }
    $isEventRental = $rentalType === 'event';
    $bandPrice = $isEventRental ? ($band->price_per_event ?? 0) : ($band->price_per_hour ?? 0);
    $jumlahSatuan = $isEventRental ? '1 event' : ((($rental->performance_duration_hours ?? 0) > 0 || ($rental->performance_duration_minutes ?? 0) > 0)
        ? trim(($rental->performance_duration_hours ?? 0) . ' jam ' . ($rental->performance_duration_minutes ?? 0) . ' menit')
        : '-');
    $jumlahBreak = (($rental->break_duration_hours ?? 0) > 0 || ($rental->break_duration_minutes ?? 0) > 0)
        ? trim(($rental->break_duration_hours ?? 0) . ' jam ' . ($rental->break_duration_minutes ?? 0) . ' menit')
        : 'Tidak ada';
    $priceLabel = $isEventRental ? 'Harga Event' : 'Harga per Jam';
    $rentalTypeLabel = $isEventRental ? 'Event' : 'Per Jam';
@endphp
<div class="min-h-screen bg-gradient-to-br from-yellow-50 to-amber-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-center mb-6">
            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-4 text-center">Permintaan Sewa Band Diterima!</h1>

        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-6 shadow-sm">
            <p class="text-gray-700 text-center">
                <span class="font-semibold text-gray-900">Permintaan sewa band untuk</span>
                <span class="font-bold text-blue-600">{{ $band->band_name }}</span>
                <span class="font-semibold text-gray-900">telah diterima dan sedang menunggu proses admin.</span>
            </p>
            <p class="text-gray-700 text-center mt-3">
                Silakan simpan halaman ini sebagai bukti pengajuan sewa Anda.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6 mb-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 text-center">Detail Permintaan Anda</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                <div class="border-l-4 border-yellow-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Kode Order</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $rental->kode_order ?? '-' }}</p>
                </div>
                <div class="border-l-4 border-blue-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Nama Penyewa</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $rental->renter_name ?? '-' }}</p>
                </div>
                <div class="border-l-4 border-emerald-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Email</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $rental->renter_email ?? '-' }}</p>
                </div>
                <div class="border-l-4 border-teal-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">No. Telepon</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $rental->renter_phone ?? '-' }}</p>
                </div>
                <div class="border-l-4 border-purple-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Tanggal Pertunjukan</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $rental->performance_date ? $rental->performance_date->translatedFormat('d F Y') : '-' }}</p>
                </div>
                <div class="border-l-4 border-orange-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Tipe Penyewaan</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $rentalTypeLabel }}</p>
                </div>
                <div class="border-l-4 border-indigo-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Harga Per Jam</p>
                    <p class="text-lg text-gray-900 font-semibold">Rp {{ number_format($band->price_per_hour ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="border-l-4 border-rose-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Harga Per Event</p>
                    <p class="text-lg text-gray-900 font-semibold">Rp {{ number_format($band->price_per_event ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="border-l-4 border-amber-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Tujuan Sewa</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $rental->rental_purpose ?? '-' }}</p>
                </div>
                <div class="border-l-4 border-pink-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Status Permohonan</p>
                    <p class="text-lg text-gray-900 font-semibold"><span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Menunggu Diproses</span></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6 mb-6 border border-gray-100">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 text-center">Rincian Sewa</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4">
                    <p class="text-sm text-gray-600 font-semibold">{{ $priceLabel }}</p>
                    <p class="text-lg text-gray-900 font-semibold">Rp {{ number_format($bandPrice, 0, ',', '.') }}</p>
                </div>
                <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                    <p class="text-sm text-gray-600 font-semibold">Durasi Band Main</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $jumlahSatuan }}</p>
                </div>
                <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
                    <p class="text-sm text-gray-600 font-semibold">Total Harga</p>
                    <p class="text-lg text-gray-900 font-semibold">Rp {{ number_format($rental->harga_final ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                <p class="text-sm text-gray-600 font-semibold">Ringkasan Tipe Sewa</p>
                <p class="text-gray-900 font-medium">{{ $isEventRental ? 'Event' : 'Per Jam' }}</p>
            </div>

            @if(!$isEventRental)
            <div class="mt-4 bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-600 font-semibold mb-1">Waktu Mulai</p>
                <p class="text-gray-900">{{ $rental->performance_start_time ?? '-' }}</p>
                <p class="text-sm text-gray-600 font-semibold mt-3 mb-1">Waktu Berakhir</p>
                <p class="text-gray-900">{{ $rental->performance_end_time ?? '-' }}</p>
                <p class="text-sm text-gray-600 font-semibold mt-3 mb-1">Durasi Break</p>
                <p class="text-gray-900">{{ $jumlahBreak }}</p>
            </div>
            @endif
        </div>

        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-6 mb-6 shadow-sm">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 shrink-0 mt-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div class="text-left w-full">
                    <h3 class="font-semibold text-gray-900 mb-2">📸 Langkah Penting</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        Silakan lakukan <span class="font-semibold">screenshot halaman ini</span> sebagai bukti permohonan sewa band Anda telah diterima.
                    </p>
                    <p class="text-sm text-gray-700">
                        Admin akan meninjau permintaan Anda dan menghubungi bila diperlukan.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6 mb-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Hubungi Band</h3>
            @if($band->whatsapp_number)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $band->whatsapp_number) }}" target="_blank" class="flex items-center gap-4 p-4 border-2 border-green-500 rounded-lg hover:bg-green-50 transition-all">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-5.031 1.378c-3.055 2.116-4.687 5.351-4.687 8.905 0 3.554 1.632 6.789 4.686 8.905 3.056 2.116 7.699 2.134 10.749.031 3.051-2.105 4.633-5.355 4.633-8.905 0-3.55-1.646-6.804-4.649-8.95a9.865 9.865 0 00-5.031-1.378c-.108 0-.216 0-.324.006-.108-.001-.216-.006-.324-.006z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-600">WhatsApp Band</p>
                    <p class="font-semibold text-gray-900 text-sm">{{ $band->whatsapp_number }}</p>
                    <p class="text-xs text-green-600 mt-1">Klik untuk chat</p>
                </div>
            </a>
            @else
            <p class="text-sm text-gray-600 text-center">Band ini belum memiliki nomor WhatsApp yang tercantum.</p>
            @endif
        </div>

        <div class="flex flex-col gap-3">
            <a href="{{ route('public.bands.show', $band) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Kembali ke Detail Band
            </a>
            <a href="{{ route('public.bands.index') }}" class="btn btn-outline btn-warning">
                Lihat Band Lain
            </a>
        </div>
    </div>
</div>
@endsection
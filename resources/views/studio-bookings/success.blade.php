@extends('layouts.app')

@section('title', 'Booking Berhasil')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Success Icon -->
        <div class="flex justify-center mb-6">
            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Success Message -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4 text-center">Permohonan Booking Studio Diterima!</h1>

        <!-- Info Message -->
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-6">
            <p class="text-gray-700 text-center">
                <span class="font-semibold text-gray-900">Permohonan booking studio Anda pada</span> 
                <span class="font-bold text-blue-600">{{ $booking->tanggal_booking ? $booking->tanggal_booking->translatedFormat('d F Y') : 'tanggal booking' }} - {{ $booking->sesi_label ?? 'sesi booking' }}</span>
                <span class="font-semibold text-gray-900">telah diterima.</span>
            </p>
            <p class="text-gray-700 text-center mt-3">
                Admin akan memproses permohonan Anda dan Anda akan menerima notifikasi status permohonan Anda melalui sistem.
            </p>
        </div>
        
        <!-- Booking Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 text-center">Detail Permohonan Anda</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                <div class="border-l-4 border-yellow-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Kode Booking</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $booking->booking_code ?? '-' }}</p>
                </div>
                <div class="border-l-4 border-blue-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Nama Pemohon</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $booking->nama_pemohon ?? 'N/A' }}</p>
                </div>
                <div class="border-l-4 border-indigo-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">NPM</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $booking->nomor_identitas ?? 'N/A' }}</p>
                </div>
                <div class="border-l-4 border-emerald-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Email</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $booking->renter_email ?? 'N/A' }}</p>
                </div>
                <div class="border-l-4 border-teal-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">No. Telepon</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $booking->renter_phone ?? 'N/A' }}</p>
                </div>
                <div class="border-l-4 border-green-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Tanggal Booking</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $booking->tanggal_booking ? $booking->tanggal_booking->translatedFormat('d F Y') : 'N/A' }}</p>
                </div>
                <div class="border-l-4 border-purple-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Sesi Booking</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $booking->sesi_label ?? 'N/A' }}</p>
                </div>
                <div class="border-l-4 border-orange-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Jumlah Non-UKM</p>
                    <p class="text-lg text-gray-900 font-semibold">{{ $booking->jumlah_non_ukm ?? 0 }} Orang</p>
                </div>
                <div class="border-l-4 border-yellow-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Total Harga</p>
                    <p class="text-lg text-gray-900 font-semibold">Rp {{ number_format($booking->harga_final ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="border-l-4 border-orange-500 pl-4">
                    <p class="text-sm text-gray-600 font-semibold">Status Permohonan</p>
                    <p class="text-lg text-gray-900 font-semibold"><span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Menunggu Diproses</span></p>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-6 mb-6">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 shrink-0 mt-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div class="text-left w-full">
                    <h3 class="font-semibold text-gray-900 mb-2">📸 Langkah Penting</h3>
                    <p class="text-sm text-gray-700 mb-3">
                        Silakan lakukan <span class="font-semibold">screenshot halaman ini</span> sebagai bukti permohonan booking Anda telah diterima.
                    </p>
                    <p class="text-sm text-gray-700">
                        Jika ada pertanyaan atau butuh bantuan, silakan hubungi CP Band kami di bawah ini.
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Hubungi CP Subsie Band</h3>
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('contact.cp_band', '6281234567890')) }}" target="_blank" class="flex items-center gap-4 p-4 border-2 border-green-500 rounded-lg hover:bg-green-50 transition-all">
                <div class="flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.67-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.076 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421-7.403h-.004a9.87 9.87 0 00-5.031 1.378c-3.055 2.116-4.687 5.351-4.687 8.905 0 3.554 1.632 6.789 4.686 8.905 3.056 2.116 7.699 2.134 10.749.031 3.051-2.105 4.633-5.355 4.633-8.905 0-3.55-1.646-6.804-4.649-8.95a9.865 9.865 0 00-5.031-1.378c-.108 0-.216 0-.324.006-.108-.001-.216-.006-.324-.006z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-600">CP Band (Subsie Band) - WhatsApp</p>
                    <p class="font-semibold text-gray-900 text-sm">{{ config('contact.cp_band', '0812 3456 7890') }}</p>
                    <p class="text-xs text-green-600 mt-1">Klik untuk chat</p>
                </div>
            </a>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Kembali ke Home
            </a>
        </div>
    </div>
</div>
@endsection

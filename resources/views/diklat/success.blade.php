@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil - Satya Palapa')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-gradient-to-b from-gray-50 to-white py-20">
    <div class="max-w-lg mx-auto px-4 text-center">
        <!-- Success Animation -->
        <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full mx-auto mb-8 flex items-center justify-center shadow-xl shadow-green-400/30 animate-bounce">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Pendaftaran Berhasil!</h1>
        <p class="text-gray-600 text-lg mb-8">
            Terima kasih telah mendaftar diklat di UKM Satya Palapa. 
            Data pendaftaranmu akan diverifikasi oleh pengurus kami.
        </p>

        <!-- Info Box -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-8 text-left">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 mb-1">Langkah Selanjutnya</h3>
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li>• Tunggu konfirmasi dari pengurus melalui WhatsApp</li>
                        <li>• Pastikan nomor telepon yang didaftarkan aktif</li>
                        <li>• Persiapkan diri untuk jadwal diklat</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" 
                class="px-8 py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-full hover:shadow-lg transition-all duration-300">
                Kembali ke Beranda
            </a>
            <a href="{{ route('diklat.register') }}" 
                class="px-8 py-4 bg-white text-gray-700 font-semibold rounded-full border-2 border-gray-200 hover:border-gray-300 transition-all duration-300">
                Daftar Lagi
            </a>
        </div>
    </div>
</div>
@endsection

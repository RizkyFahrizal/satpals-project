@extends('layouts.app')

@section('title', 'Booking Berhasil')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <!-- Success Icon -->
        <div class="flex justify-center mb-6">
            <div class="flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <!-- Success Message -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Booking Berhasil!</h1>
        <p class="text-gray-600 mb-8">
            Permohonan booking studio Anda telah diterima. Admin akan memproses permohonan Anda dan Anda akan menerima notifikasi status permohonan Anda.
        </p>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 shrink-0 mt-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 100-2 1 1 0 000 2zm5 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
                <div class="text-left">
                    <h3 class="font-semibold text-gray-900">Apa Selanjutnya?</h3>
                    <ul class="mt-2 text-sm text-gray-600 space-y-1">
                        <li>✓ Admin akan mereview permohonan Anda</li>
                        <li>✓ Anda akan diberitahu via sistem</li>
                        <li>✓ Tunggu notifikasi status approval</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Kembali ke Home
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline">
                Jelajahi Halaman Lain
            </a>
        </div>
    </div>
</div>
@endsection

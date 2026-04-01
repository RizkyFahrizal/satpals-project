@extends('layouts.admin')

@section('title', 'Tambah Booking Studio')
@section('header', 'Tambah Booking Studio')
@section('breadcrumb', 'Form Pemesanan Sesi Studio')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Form -->
    <div class="lg:col-span-2">
        <div class="card bg-white shadow-lg border border-yellow-200">
            <div class="card-body p-8">
                <form action="{{ route('admin.studio-bookings.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- NPM/Username Input -->
                    <div>
                        <label class="label">
                            <span class="label-text font-semibold text-gray-800">NPM / Username Anggota</span>
                            <span class="label-text-alt text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="user_npm"
                               name="user_npm" 
                               class="input input-bordered w-full @error('user_npm') input-error @enderror"
                               placeholder="Ketik NPM atau nama anggota..."
                               value="{{ old('user_npm') }}"
                               list="users_list"
                               required>
                        <label class="label">
                            <span class="label-text-alt text-gray-500">Cari berdasarkan NPM atau nama anggota Satpal</span>
                        </label>
                        @error('user_npm')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Datalist for autocomplete -->
                    <datalist id="users_list">
                        @foreach (\App\Models\User::with('member')->whereNotNull('member_id')->get() as $user)
                            <option value="{{ $user->member?->npm }}">{{ $user->member?->nama_lengkap }} ({{ $user->member?->npm }})</option>
                        @endforeach
                    </datalist>

                    <!-- Tanggal Booking -->
                    <div>
                        <label class="label">
                            <span class="label-text font-semibold text-gray-800">Tanggal Booking</span>
                            <span class="label-text-alt text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="tanggal_booking"
                               name="tanggal_booking" 
                               class="input input-bordered w-full @error('tanggal_booking') input-error @enderror"
                               value="{{ old('tanggal_booking') }}"
                               min="{{ now()->format('Y-m-d') }}"
                               required>
                        <label class="label">
                            <span class="label-text-alt text-gray-500">Pilih tanggal dari hari ini atau lebih</span>
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
                        <select id="sesi" 
                                name="sesi" 
                                class="select select-bordered w-full @error('sesi') select-error @enderror"
                                required>
                            <option value="" disabled selected>-- Pilih Sesi --</option>
                            @foreach (\App\Models\StudioBooking::SESI_TIMES as $sesiNum => $sesiInfo)
                                <option value="{{ $sesiNum }}" 
                                        {{ old('sesi') == $sesiNum ? 'selected' : '' }}>
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
                        <textarea id="keperluan"
                                  name="keperluan" 
                                  class="textarea textarea-bordered w-full @error('keperluan') textarea-error @enderror"
                                  rows="4"
                                  placeholder="Jelaskan untuk apa studio ini digunakan..."
                                  required>{{ old('keperluan') }}</textarea>
                        <label class="label">
                            <span class="label-text-alt text-gray-500">Minimum 10 karakter, maksimal 500 karakter</span>
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
                            Simpan Booking
                        </button>
                        <a href="{{ route('admin.studio-bookings.index') }}" class="btn btn-outline">
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

    <!-- Info Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Jadwal Sesi -->
        <div class="card bg-blue-50 border-l-4 border-blue-400 shadow-lg">
            <div class="card-body p-6">
                <h3 class="font-bold text-blue-900 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd" />
                    </svg>
                    Jadwal Sesi Studio
                </h3>
                <ul class="space-y-2 text-sm">
                    @foreach (\App\Models\StudioBooking::SESI_TIMES as $sesiNum => $sesiInfo)
                        <li class="flex justify-between items-center p-2 bg-white rounded border border-blue-200">
                            <span class="font-medium text-gray-700">{{ $sesiInfo['label'] }}</span>
                            <span class="text-blue-600 font-semibold">{{ $sesiInfo['start'] }} - {{ $sesiInfo['end'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Tips -->
        <div class="card bg-green-50 border-l-4 border-green-400 shadow-lg">
            <div class="card-body p-6">
                <h3 class="font-bold text-green-900 mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd" />
                    </svg>
                    Tips
                </h3>
                <ul class="space-y-2 text-sm text-gray-700 list-disc list-inside">
                    <li>Pastikan NPM sudah terdaftar</li>
                    <li>Pilih tanggal minimal hari ini</li>
                    <li>Satu sesi hanya bisa satu booking</li>
                    <li>Deskripsi harus jelas dan detail</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection


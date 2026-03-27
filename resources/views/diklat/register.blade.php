@extends('layouts.app')

@section('title', 'Pendaftaran Diklat - Satya Palapa')

@section('content')
<!-- Hero Section -->
<div class="relative py-20 overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-32 h-32 bg-yellow-400/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-20 w-48 h-48 bg-orange-400/20 rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
        <span class="inline-block px-4 py-2 bg-yellow-400/20 backdrop-blur-sm rounded-full text-yellow-400 text-sm font-semibold mb-4 border border-yellow-400/30">
            🎵 Pendaftaran Diklat
        </span>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Bergabung Bersama Kami</h1>
        <p class="text-gray-300 text-lg max-w-2xl mx-auto">
            Daftarkan dirimu untuk mengikuti pelatihan musik di UKM Satya Palapa. 
            Kembangkan bakatmu bersama kami!
        </p>
    </div>
</div>

<!-- Registration Form -->
<div class="bg-gradient-to-b from-gray-50 to-white py-16">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-8 py-6">
                <h2 class="text-2xl font-bold text-gray-800">Form Pendaftaran Diklat</h2>
                <p class="text-gray-700">Lengkapi data berikut dengan benar</p>
            </div>

            <!-- Status Alert -->
            @if(!$isOpen)
                <div class="bg-red-50 border-l-4 border-red-500 p-6 m-8 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-red-800">Pendaftaran Ditutup</h3>
                            <p class="text-red-700 mt-1">Maaf, pendaftaran diklat sedang tidak dibuka. Silahkan hubungi pengurus untuk informasi lebih lanjut.</p>
                        </div>
                    </div>
                </div>
            @elseif($isOpen && $activePeriod)
                <div class="bg-green-50 border-l-4 border-green-500 p-6 m-8 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="ml-4">
                            <h3 class="text-lg font-bold text-green-800">Pendaftaran Dibuka</h3>
                            <p class="text-green-700 mt-1"><strong>Periode:</strong> {{ $activePeriod->nama_periode }} (Angkatan {{ $activePeriod->tahun_masuk }})</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form Body -->
            <form action="{{ route('diklat.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6" {{ !$isOpen ? 'style=pointer-events:none;opacity:0.5' : '' }}>
                @csrf

                <!-- Data Pribadi Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Data Pribadi</h3>
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all"
                            placeholder="Masukkan nama lengkap">
                        @error('nama_lengkap')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-4">
                            <label class="relative cursor-pointer flex-1">
                                <input type="radio" name="jenis_kelamin" value="laki-laki" {{ old('jenis_kelamin') === 'laki-laki' ? 'checked' : '' }} required class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-gray-200 bg-white transition-all duration-200
                                    peer-checked:border-blue-500 peer-checked:bg-blue-50
                                    hover:border-blue-300 hover:bg-blue-50/50 text-center">
                                    <span class="text-2xl mb-2 block">👨</span>
                                    <span class="font-medium text-gray-800">Laki-laki</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer flex-1">
                                <input type="radio" name="jenis_kelamin" value="perempuan" {{ old('jenis_kelamin') === 'perempuan' ? 'checked' : '' }} required class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-gray-200 bg-white transition-all duration-200
                                    peer-checked:border-pink-500 peer-checked:bg-pink-50
                                    hover:border-pink-300 hover:bg-pink-50/50 text-center">
                                    <span class="text-2xl mb-2 block">👩</span>
                                    <span class="font-medium text-gray-800">Perempuan</span>
                                </div>
                            </label>
                        </div>
                        @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Telepon -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="no_telepon_pribadi" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Telepon Pribadi <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="no_telepon_pribadi" name="no_telepon_pribadi" value="{{ old('no_telepon_pribadi') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all"
                                placeholder="08xxxxxxxxxx">
                            @error('no_telepon_pribadi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="no_telepon_ortu" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Telepon Orang Tua/Wali <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="no_telepon_ortu" name="no_telepon_ortu" value="{{ old('no_telepon_ortu') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all"
                                placeholder="08xxxxxxxxxx">
                            @error('no_telepon_ortu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Data Akademik Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Data Akademik</h3>
                    </div>

                    <!-- NPM -->
                    <div>
                        <label for="npm" class="block text-sm font-semibold text-gray-700 mb-2">
                            NPM (Nomor Pokok Mahasiswa) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="npm" name="npm" value="{{ old('npm') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all"
                            placeholder="Contoh: 2024110001">
                        @error('npm')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fakultas & Prodi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fakultas" class="block text-sm font-semibold text-gray-700 mb-2">
                                Fakultas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="fakultas" name="fakultas" value="{{ old('fakultas') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all"
                                placeholder="Contoh: Fakultas Teknik">
                            @error('fakultas')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="prodi" class="block text-sm font-semibold text-gray-700 mb-2">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="prodi" name="prodi" value="{{ old('prodi') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all"
                                placeholder="Contoh: Teknik Informatika">
                            @error('prodi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Tahun Masuk & Tahun Daftar Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Tahun Masuk (Angkatan)</h3>
                    </div>

                    @if($isOpen && $activePeriod)
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <p class="text-blue-800 font-semibold">Tahun Masuk: <span class="text-lg">{{ $activePeriod->tahun_masuk }}</span></p>
                        <p class="text-blue-700 text-sm mt-1">Sesuai dengan periode pendaftaran yang sedang dibuka</p>
                    </div>

                    <div>
                        <label for="tahun_daftar" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Daftar
                        </label>
                        <input type="hidden" name="tahun_daftar" value="{{ $activePeriod->tahun_masuk ?? 2022 }}">
                        <input type="text" id="tahun_daftar" readonly value="{{ $activePeriod->tahun_masuk ?? 2022 }}" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-100 text-gray-700 font-semibold cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-2">Tahun daftar otomatis sesuai dengan periode pendaftaran</p>
                    </div>
                    @endif
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Spesifikasi Musik & Custom Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Spesifikasi Musik</h3>
                    </div>

                    <p class="text-sm text-gray-500 -mt-2">Pilih satu atau lebih instrumen yang ingin dipelajari</p>

                    <!-- Spesifikasi Checkboxes -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        @foreach($spesifikasiOptions as $value => $label)
                        <label class="relative cursor-pointer">
                            <input type="checkbox" name="spesifikasi[]" value="{{ $value }}" 
                                {{ is_array(old('spesifikasi')) && in_array($value, old('spesifikasi')) ? 'checked' : '' }}
                                class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-gray-200 bg-white transition-all duration-200 text-center
                                peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:shadow-lg
                                hover:border-purple-300 hover:bg-purple-50/50">
                                <span class="text-3xl mb-2 block">
                                    @if($value === 'drum') 🥁
                                    @elseif($value === 'keyboard') 🎹
                                    @elseif($value === 'vocal') 🎤
                                    @elseif($value === 'bass') 🎸
                                    @elseif($value === 'guitar') 🎸
                                    @endif
                                </span>
                                <span class="font-medium text-gray-800 text-sm">{{ $label }}</span>
                                <!-- Checkmark -->
                                <div class="absolute top-2 right-2 w-5 h-5 rounded-full bg-purple-500 text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('spesifikasi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Spesifikasi Lainnya (Custom) -->
                    <div class="mt-6 p-4 rounded-xl bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200">
                        <h4 class="text-sm font-semibold text-purple-900 mb-3">🎵 Spesifikasi Lainnya (Opsional)</h4>
                        <p class="text-xs text-purple-700 mb-3">Tambahkan instrumen lain seperti DJ, Violin, Harmonica, Saxophone, dll</p>
                        
                        <!-- Custom Instrument Inputs -->
                        <div id="spesifikasi-lainnya-container" class="space-y-2">
                            @if(old('spesifikasi_lainnya'))
                                @foreach(old('spesifikasi_lainnya') as $index => $spec)
                                    @if(!empty($spec))
                                    <div class="flex gap-2 items-center">
                                        <input type="text" name="spesifikasi_lainnya[]" value="{{ $spec }}"
                                            class="flex-1 px-3 py-2 rounded-lg border border-purple-300 bg-white text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                                            placeholder="Misal: DJ, Violin, Harmonica">
                                        <button type="button" onclick="this.parentElement.remove()"
                                            class="px-3 py-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 text-sm font-medium transition-colors">
                                            Hapus
                                        </button>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                            <div class="flex gap-2 items-center">
                                <input type="text" name="spesifikasi_lainnya[]" 
                                    class="flex-1 px-3 py-2 rounded-lg border border-purple-300 bg-white text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                                    placeholder="Misal: DJ, Violin, Harmonica">
                                <button type="button" onclick="this.parentElement.remove()"
                                    class="px-3 py-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 text-sm font-medium transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </div>

                        <!-- Add More Button -->
                        <button type="button" id="tambah-spesifikasi-btn" 
                            class="mt-3 w-full px-4 py-2 rounded-lg border-2 border-dashed border-purple-300 text-purple-600 hover:bg-purple-50 font-medium text-sm transition-colors">
                            + Tambah Spesifikasi Lainnya
                        </button>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Bukti Pembayaran Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Bukti Pembayaran</h3>
                    </div>

                    @if($isOpen && $activePeriod)
                    <!-- Bank Account Info -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mb-4">
                        <h4 class="font-bold text-blue-900 mb-2">📱 Informasi Rekening Pembayaran:</h4>
                        <div class="text-blue-800 text-sm whitespace-pre-wrap font-mono">{{ $activePeriod->rekening_info }}</div>
                        <p class="text-blue-700 text-xs mt-3">💡 Setelah melakukan transfer, upload bukti pembayaran di bawah ini</p>
                    </div>

                    <!-- Tahun Masuk Display (Auto-filled) -->
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Masuk (Angkatan)
                        </label>
                        <input type="text" readonly value="{{ $activePeriod->tahun_masuk }}" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-300 bg-gray-100 text-gray-700 font-semibold cursor-not-allowed">
                        <p class="text-xs text-gray-500 mt-1">Tahun masuk otomatis sesuai periode yang dibuka</p>
                    </div>
                    @endif

                    <!-- Upload Bukti Pembayaran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Upload Bukti Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" required accept="image/*"
                                class="hidden" onchange="previewImage(this)" {{ !$isOpen ? 'disabled' : '' }}>
                            <label for="bukti_pembayaran" 
                                class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors {{ !$isOpen ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <div id="upload-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span></p>
                                    <p class="text-xs text-gray-400">PNG, JPG, JPEG (Max. 2MB)</p>
                                </div>
                                <img id="image-preview" class="hidden w-full h-full object-contain rounded-xl p-2" alt="Preview">
                            </label>
                        </div>
                        @error('bukti_pembayaran')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Riwayat Kesehatan Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Riwayat Kesehatan</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="riwayat_penyakit" class="block text-sm font-semibold text-gray-700 mb-2">
                                Riwayat Penyakit
                            </label>
                            <textarea id="riwayat_penyakit" name="riwayat_penyakit" rows="3"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all resize-none"
                                placeholder="Kosongkan jika tidak ada">{{ old('riwayat_penyakit') }}</textarea>
                            @error('riwayat_penyakit')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="riwayat_alergi" class="block text-sm font-semibold text-gray-700 mb-2">
                                Riwayat Alergi
                            </label>
                            <textarea id="riwayat_alergi" name="riwayat_alergi" rows="3"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all resize-none"
                                placeholder="Kosongkan jika tidak ada">{{ old('riwayat_alergi') }}</textarea>
                            @error('riwayat_alergi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6">
                    <button type="submit" 
                        class="w-full py-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-xl hover:shadow-lg hover:shadow-yellow-400/30 transition-all duration-300 hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const placeholder = document.getElementById('upload-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Tambah Spesifikasi Lainnya functionality
document.getElementById('tambah-spesifikasi-btn').addEventListener('click', function() {
    const container = document.getElementById('spesifikasi-lainnya-container');
    const newInput = document.createElement('div');
    newInput.className = 'flex gap-2 items-center';
    newInput.innerHTML = `
        <input type="text" name="spesifikasi_lainnya[]" 
            class="flex-1 px-3 py-2 rounded-lg border border-purple-300 bg-white text-sm focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
            placeholder="Misal: DJ, Violin, Harmonica">
        <button type="button" onclick="this.parentElement.remove()"
            class="px-3 py-2 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 text-sm font-medium transition-colors">
            Hapus
        </button>
    `;
    container.insertBefore(newInput, this);
});
</script>
@endsection

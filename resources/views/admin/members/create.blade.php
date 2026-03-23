@extends('layouts.admin')

@section('title', 'Tambah Anggota Baru - Admin Satya Palapa')

@section('header', 'Tambah Anggota Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.members.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-yellow-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Anggota
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-400 to-orange-400 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Tambah Anggota Manual
            </h2>
            <p class="text-yellow-100 text-sm mt-1">Untuk menambahkan anggota tanpa melalui pendaftaran diklat</p>
        </div>

        <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Foto -->
                <div class="md:col-span-2">
                    <label for="foto" class="block text-sm font-semibold text-gray-700 mb-2">
                        Foto
                    </label>
                    <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/jpg"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks: 2MB. Opsional.</p>
                    @error('foto')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" 
                        value="{{ old('nama_lengkap') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('nama_lengkap') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap">
                    @error('nama_lengkap')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_kelamin" id="jenis_kelamin" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('jenis_kelamin') border-red-500 @enderror">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="laki-laki" {{ old('jenis_kelamin') === 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('jenis_kelamin') === 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No Telepon -->
                <div>
                    <label for="no_telepon" class="block text-sm font-semibold text-gray-700 mb-2">
                        No. Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_telepon" id="no_telepon" 
                        value="{{ old('no_telepon') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('no_telepon') border-red-500 @enderror"
                        placeholder="08xxxxxxxxxx">
                    @error('no_telepon')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NPM -->
                <div>
                    <label for="npm" class="block text-sm font-semibold text-gray-700 mb-2">
                        NPM <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="npm" id="npm" 
                        value="{{ old('npm') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all font-mono @error('npm') border-red-500 @enderror"
                        placeholder="2024xxxxxxxx">
                    @error('npm')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fakultas -->
                <div>
                    <label for="fakultas" class="block text-sm font-semibold text-gray-700 mb-2">
                        Fakultas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="fakultas" id="fakultas" 
                        value="{{ old('fakultas') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('fakultas') border-red-500 @enderror"
                        placeholder="Fakultas Teknik">
                    @error('fakultas')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Program Studi -->
                <div>
                    <label for="prodi" class="block text-sm font-semibold text-gray-700 mb-2">
                        Program Studi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="prodi" id="prodi" 
                        value="{{ old('prodi') }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('prodi') border-red-500 @enderror"
                        placeholder="Teknik Informatika">
                    @error('prodi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun Daftar -->
                <div>
                    <label for="tahun_daftar" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tahun Daftar <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="tahun_daftar" id="tahun_daftar" 
                        value="{{ old('tahun_daftar', now()->year) }}" required
                        min="2000" max="{{ now()->year + 1 }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('tahun_daftar') border-red-500 @enderror">
                    @error('tahun_daftar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Angkatan -->
                <div>
                    <label for="angkatan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Angkatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="angkatan" id="angkatan" 
                        value="{{ old('angkatan', now()->year) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('angkatan') border-red-500 @enderror"
                        placeholder="2024">
                    @error('angkatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('status') border-red-500 @enderror">
                        <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>✅ Aktif</option>
                        <option value="alumni" {{ old('status') === 'alumni' ? 'selected' : '' }}>🎓 Alumni</option>
                    </select>
                    @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Spesifikasi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Spesifikasi <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500 mb-3">Pilih minimal satu spesifikasi musik</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                        @foreach($spesifikasiOptions as $value => $label)
                        <label class="relative cursor-pointer">
                            <input type="checkbox" name="spesifikasi[]" value="{{ $value }}" 
                                class="peer sr-only"
                                {{ in_array($value, old('spesifikasi', [])) ? 'checked' : '' }}>
                            <div class="px-4 py-3 rounded-xl border-2 border-gray-200 bg-white text-center transition-all peer-checked:border-yellow-400 peer-checked:bg-yellow-50 peer-checked:text-yellow-700 hover:border-gray-300">
                                <span class="text-2xl block mb-1">
                                    @switch($value)
                                        @case('drum') 🥁 @break
                                        @case('keyboard') 🎹 @break
                                        @case('vocal') 🎤 @break
                                        @case('bass') 🎸 @break
                                        @case('guitar') 🎸 @break
                                    @endswitch
                                </span>
                                <span class="text-sm font-medium">{{ $label }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('spesifikasi')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Spesifikasi Lainnya -->
                <div class="md:col-span-2">
                    <label for="spesifikasi_lainnya" class="block text-sm font-semibold text-gray-700 mb-2">
                        Spesifikasi Lainnya
                    </label>
                    <p class="text-xs text-gray-500 mb-3">Tambahkan spesifikasi tambahan jika ada (pisahkan dengan koma)</p>
                    <input type="text" name="spesifikasi_lainnya" id="spesifikasi_lainnya" 
                        value="{{ old('spesifikasi_lainnya', '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('spesifikasi_lainnya') border-red-500 @enderror"
                        placeholder="Contoh: Biola, Kecapi, atau instrumen lainnya">
                    @error('spesifikasi_lainnya')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.members.index') }}" 
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold rounded-xl transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Simpan Anggota
                </button>
            </div>
        </form>
    </div>

    <!-- Info Box -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-4">
        <div class="flex gap-3">
            <svg class="w-6 h-6 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-700">
                <p class="font-semibold mb-1">Catatan:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Anggota juga bisa ditambahkan otomatis dari pendaftaran diklat yang disetujui</li>
                    <li>NPM harus unik dan tidak boleh sama dengan anggota lain</li>
                    <li>Foto bersifat opsional dan bisa ditambahkan nanti</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

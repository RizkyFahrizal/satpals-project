@extends('layouts.admin')

@section('title', 'Edit Prestasi - ' . $achievement->judul_lomba)

@section('header', 'Edit Prestasi')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.achievements.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-yellow-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Prestasi
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-400 to-orange-400 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit: {{ $achievement->judul_lomba }}
            </h2>
        </div>

        <form action="{{ route('admin.achievements.update', $achievement) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Lomba -->
                <div class="md:col-span-2">
                    <label for="judul_lomba" class="block text-sm font-semibold text-gray-700 mb-2">
                        Judul/Nama Lomba <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul_lomba" id="judul_lomba" 
                        value="{{ old('judul_lomba', $achievement->judul_lomba) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('judul_lomba') border-red-500 @enderror">
                    @error('judul_lomba')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Juara -->
                <div>
                    <label for="juara" class="block text-sm font-semibold text-gray-700 mb-2">
                        Juara/Penghargaan <span class="text-red-500">*</span>
                    </label>
                    <select name="juara" id="juara" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('juara') border-red-500 @enderror">
                        <option value="">Pilih Juara</option>
                        @foreach($juaraOptions as $value => $label)
                        <option value="{{ $value }}" {{ old('juara', $achievement->juara) === $value ? 'selected' : '' }}>🏆 {{ $label }}</option>
                        @endforeach
                    </select>
                    @error('juara')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Band -->
                <div>
                    <label for="nama_band" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Band
                    </label>
                    <input type="text" name="nama_band" id="nama_band" 
                        value="{{ old('nama_band', $achievement->nama_band) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('nama_band') border-red-500 @enderror">
                    @error('nama_band')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Anggota -->
                <div class="md:col-span-2">
                    <label for="anggota" class="block text-sm font-semibold text-gray-700 mb-2">
                        Anggota Band/Peserta
                    </label>
                    <input type="text" name="anggota" id="anggota" 
                        value="{{ old('anggota', $achievement->anggota_string !== '-' ? $achievement->anggota_string : '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('anggota') border-red-500 @enderror"
                        placeholder="Pisahkan dengan koma">
                    <p class="text-xs text-gray-500 mt-1">Pisahkan nama anggota dengan tanda koma (,)</p>
                    @error('anggota')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Lomba -->
                <div>
                    <label for="tanggal_lomba" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Lomba <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_lomba" id="tanggal_lomba" 
                        value="{{ old('tanggal_lomba', $achievement->tanggal_lomba->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('tanggal_lomba') border-red-500 @enderror">
                    @error('tanggal_lomba')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempat Lomba -->
                <div>
                    <label for="tempat_lomba" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tempat Lomba <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tempat_lomba" id="tempat_lomba" 
                        value="{{ old('tempat_lomba', $achievement->tempat_lomba) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('tempat_lomba') border-red-500 @enderror">
                    @error('tempat_lomba')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penyelenggara -->
                <div class="md:col-span-2">
                    <label for="penyelenggara" class="block text-sm font-semibold text-gray-700 mb-2">
                        Penyelenggara
                    </label>
                    <input type="text" name="penyelenggara" id="penyelenggara" 
                        value="{{ old('penyelenggara', $achievement->penyelenggara) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('penyelenggara') border-red-500 @enderror">
                    @error('penyelenggara')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $achievement->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto Dokumentasi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Foto Dokumentasi (Maksimal 3 foto)
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach(['foto_1', 'foto_2', 'foto_3'] as $index => $field)
                        <div class="relative">
                            <label for="{{ $field }}" class="block cursor-pointer">
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-yellow-400 transition-colors bg-gray-50 hover:bg-yellow-50">
                                    @if($achievement->$field)
                                    <div class="current-{{ $field }}">
                                        <img src="{{ asset('storage/' . $achievement->$field) }}" alt="Foto {{ $index + 1 }}" class="w-full h-32 object-cover rounded-lg mb-2">
                                        <p class="text-xs text-gray-500">Klik untuk ganti</p>
                                    </div>
                                    @endif
                                    <div class="preview-{{ $field }} {{ $achievement->$field ? 'hidden' : '' }}">
                                        <img src="" alt="Preview" class="w-full h-32 object-cover rounded-lg mb-2 {{ !$achievement->$field ? 'hidden' : '' }}">
                                    </div>
                                    <div class="placeholder-{{ $field }} {{ $achievement->$field ? 'hidden' : '' }}">
                                        <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm text-gray-500">Foto {{ $index + 1 }}</p>
                                        <p class="text-xs text-gray-400">Klik untuk upload</p>
                                    </div>
                                </div>
                                <input type="file" name="{{ $field }}" id="{{ $field }}" accept="image/jpeg,image/png,image/jpg" class="hidden"
                                    onchange="previewImage(this, '{{ $field }}')">
                            </label>
                            @if($achievement->$field)
                            <div class="mt-2">
                                <label class="flex items-center gap-2 text-xs text-red-600 cursor-pointer">
                                    <input type="checkbox" name="remove_{{ $field }}" value="1" class="rounded border-gray-300 text-red-500 focus:ring-red-400">
                                    Hapus foto ini
                                </label>
                            </div>
                            @endif
                            @error($field)
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maks: 2MB per foto.</p>
                </div>

                <!-- Is Published -->
                <div class="md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" class="w-5 h-5 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400"
                            {{ old('is_published', $achievement->is_published) ? 'checked' : '' }}>
                        <span class="text-sm font-semibold text-gray-700">Publikasikan ke galeri publik</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Jika tidak dicentang, prestasi hanya akan tersimpan sebagai draft.</p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.achievements.index') }}" 
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" 
                    class="px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold rounded-xl transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input, field) {
    const preview = document.querySelector('.preview-' + field);
    const placeholder = document.querySelector('.placeholder-' + field);
    const current = document.querySelector('.current-' + field);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (preview.querySelector('img')) {
                preview.querySelector('img').src = e.target.result;
                preview.querySelector('img').classList.remove('hidden');
            }
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
            if (current) current.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

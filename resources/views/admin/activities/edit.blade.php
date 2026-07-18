@extends('layouts.admin')

@section('title', 'Edit Kegiatan - ' . $activity->judul_kegiatan)
@section('header', 'Edit Kegiatan')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.activities.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-yellow-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Kegiatan
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-400 to-orange-400 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit: {{ $activity->judul_kegiatan }}
            </h2>
        </div>

        <form action="{{ route('admin.activities.update', $activity) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Judul Kegiatan -->
                <div class="md:col-span-2">
                    <label for="judul_kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Judul Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul_kegiatan" id="judul_kegiatan" 
                        value="{{ old('judul_kegiatan', $activity->judul_kegiatan) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('judul_kegiatan') border-red-500 @enderror">
                    @error('judul_kegiatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tujuan Kegiatan -->
                <div class="md:col-span-2">
                    <label for="tujuan_kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tujuan Kegiatan
                    </label>
                    <input type="text" name="tujuan_kegiatan" id="tujuan_kegiatan" 
                        value="{{ old('tujuan_kegiatan', $activity->tujuan_kegiatan) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('tujuan_kegiatan') border-red-500 @enderror">
                    @error('tujuan_kegiatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi Kegiatan -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi Kegiatan
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $activity->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Kegiatan -->
                <div>
                    <label for="tanggal_kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" 
                        value="{{ old('tanggal_kegiatan', $activity->tanggal_kegiatan->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('tanggal_kegiatan') border-red-500 @enderror">
                    @error('tanggal_kegiatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Mulai -->
                <div>
                    <label for="waktu_mulai" class="block text-sm font-semibold text-gray-700 mb-2">
                        Waktu Mulai
                    </label>
                    <input type="time" name="waktu_mulai" id="waktu_mulai" 
                        value="{{ old('waktu_mulai', $activity->waktu_mulai ? substr($activity->waktu_mulai, 0, 5) : '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('waktu_mulai') border-red-500 @enderror">
                    @error('waktu_mulai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu Selesai -->
                <div>
                    <label for="waktu_selesai" class="block text-sm font-semibold text-gray-700 mb-2">
                        Waktu Selesai
                    </label>
                    <input type="time" name="waktu_selesai" id="waktu_selesai" 
                        value="{{ old('waktu_selesai', $activity->waktu_selesai ? substr($activity->waktu_selesai, 0, 5) : '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('waktu_selesai') border-red-500 @enderror">
                    @error('waktu_selesai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tempat Kegiatan -->
                <div class="md:col-span-2">
                    <label for="tempat_kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tempat Kegiatan
                    </label>
                    <input type="text" name="tempat_kegiatan" id="tempat_kegiatan" 
                        value="{{ old('tempat_kegiatan', $activity->tempat_kegiatan) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('tempat_kegiatan') border-red-500 @enderror">
                    @error('tempat_kegiatan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ketua Pelaksana -->
                <div>
                    <label for="ketua_pelaksana" class="block text-sm font-semibold text-gray-700 mb-2">
                        Ketua Pelaksana
                    </label>
                    <input type="text" name="ketua_pelaksana" id="ketua_pelaksana" 
                        value="{{ old('ketua_pelaksana', $activity->ketua_pelaksana) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all @error('ketua_pelaksana') border-red-500 @enderror"
                        placeholder="Nama Ketua Pelaksana">
                    @error('ketua_pelaksana')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Divisi Kepanitiaan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Divisi Kepanitiaan
                    </label>
                    <div id="divisiContainer" class="space-y-3">
                        @php
                            $divisiData = old('divisi', $activity->divisi ?? []);
                            // Ensure it's an array, not a string
                            if (is_string($divisiData)) {
                                $divisiList = json_decode($divisiData, true) ?? [];
                            } else {
                                $divisiList = $divisiData ?? [];
                            }
                            // Make sure it's not empty before iterating
                            if (!is_array($divisiList)) {
                                $divisiList = [];
                            }
                        @endphp
                        @forelse($divisiList as $index => $divisi)
                        <div class="divisi-row flex gap-3 items-start">
                            <div class="flex-1">
                                <input type="text" name="divisi[{{ $index }}][nama_divisi]" value="{{ $divisi['nama_divisi'] ?? '' }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all"
                                    placeholder="Nama Divisi (cth: Acara, Perkap, Humas)">
                            </div>
                            <div class="flex-1">
                                <input type="text" name="divisi[{{ $index }}][ketua_divisi]" value="{{ $divisi['ketua_divisi'] ?? '' }}"
                                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all"
                                    placeholder="Ketua Divisi">
                            </div>
                            <button type="button" onclick="removeDivisi(this)" class="px-3 py-2.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        @empty
                        <!-- Jika tidak ada divisi, tampilkan placeholder text -->
                        <p class="text-gray-500 text-sm italic">Belum ada divisi. Klik tombol "Tambah Divisi" untuk menambahkan.</p>
                        @endforelse
                    </div>
                    <button type="button" onclick="addDivisi()" class="mt-3 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors flex items-center gap-2 font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Divisi
                    </button>
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
                                    @if($activity->$field)
                                    <div class="current-{{ $field }}">
                                        <img src="{{ asset('storage/' . $activity->$field) }}" alt="Foto {{ $index + 1 }}" class="w-full h-32 object-cover rounded-lg mb-2">
                                        <p class="text-xs text-gray-500">Klik untuk ganti</p>
                                    </div>
                                    @endif
                                    <div class="preview-{{ $field }} {{ $activity->$field ? 'hidden' : '' }}">
                                        <img src="" alt="Preview" class="w-full h-32 object-cover rounded-lg mb-2 {{ !$activity->$field ? 'hidden' : '' }}">
                                    </div>
                                    <div class="placeholder-{{ $field }} {{ $activity->$field ? 'hidden' : '' }}">
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
                            @if($activity->$field)
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
                            {{ old('is_published', $activity->is_published) ? 'checked' : '' }}>
                        <span class="text-sm font-semibold text-gray-700">Publikasikan ke galeri publik</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Jika tidak dicentang, kegiatan hanya akan tersimpan sebagai draft.</p>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex justify-end gap-4">
                <a href="{{ route('admin.activities.index') }}" 
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

let divisiIndex = {{ count($divisiList) }};

function addDivisi() {
    const container = document.getElementById('divisiContainer');
    
    // Hapus placeholder text jika ada
    const placeholder = container.querySelector('p');
    if (placeholder) {
        placeholder.remove();
    }
    
    const newRow = document.createElement('div');
    newRow.className = 'divisi-row flex gap-3 items-start';
    newRow.innerHTML = `
        <div class="flex-1">
            <input type="text" name="divisi[${divisiIndex}][nama_divisi]" 
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all"
                placeholder="Nama Divisi (cth: Acara, Perkap, Humas)">
        </div>
        <div class="flex-1">
            <input type="text" name="divisi[${divisiIndex}][ketua_divisi]" 
                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all"
                placeholder="Ketua Divisi">
        </div>
        <button type="button" onclick="removeDivisi(this)" class="px-3 py-2.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    `;
    container.appendChild(newRow);
    divisiIndex++;
}

function removeDivisi(btn) {
    const container = document.getElementById('divisiContainer');
    const rows = document.querySelectorAll('.divisi-row');
    if (rows.length > 1) {
        btn.closest('.divisi-row').remove();
    } else {
        alert('Minimal harus ada 1 baris divisi. Untuk menghapus semua divisi, kosongkan fieldnya.');
    }
}
</script>
@endsection

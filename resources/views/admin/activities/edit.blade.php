@extends('layouts.admin')

@section('title', 'Edit Kegiatan')
@section('header', 'Edit Kegiatan')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('admin.activities.update', $activity) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Informasi Kegiatan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul_kegiatan" value="{{ old('judul_kegiatan', $activity->judul_kegiatan) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('judul_kegiatan') border-red-500 @enderror" required>
                    @error('judul_kegiatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan Kegiatan</label>
                    <textarea name="tujuan_kegiatan" rows="2" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('tujuan_kegiatan', $activity->tujuan_kegiatan) }}</textarea>
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kegiatan</label>
                    <textarea name="deskripsi" rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi', $activity->deskripsi) }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Waktu & Tempat -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Waktu & Tempat
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan', $activity->tanggal_kegiatan->format('Y-m-d')) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    @error('tanggal_kegiatan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" value="{{ old('waktu_mulai', $activity->waktu_mulai ? substr($activity->waktu_mulai, 0, 5) : '') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" value="{{ old('waktu_selesai', $activity->waktu_selesai ? substr($activity->waktu_selesai, 0, 5) : '') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Kegiatan</label>
                    <input type="text" name="tempat_kegiatan" value="{{ old('tempat_kegiatan', $activity->tempat_kegiatan) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>
        
        <!-- Kepanitiaan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Kepanitiaan
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ketua Pelaksana</label>
                    <input type="text" name="ketua_pelaksana" value="{{ old('ketua_pelaksana', $activity->ketua_pelaksana) }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Nama Ketua Pelaksana">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Divisi Kepanitiaan</label>
                    <div id="divisiContainer" class="space-y-3">
                        @php
                            $divisiList = old('divisi', $activity->divisi ?? []);
                            if (empty($divisiList)) {
                                $divisiList = [['nama_divisi' => '', 'ketua_divisi' => '']];
                            }
                        @endphp
                        @foreach($divisiList as $index => $divisi)
                        <div class="divisi-row flex gap-3 items-start">
                            <div class="flex-1">
                                <input type="text" name="divisi[{{ $index }}][nama_divisi]" value="{{ $divisi['nama_divisi'] ?? '' }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Nama Divisi (cth: Acara, Perkap, Humas)">
                            </div>
                            <div class="flex-1">
                                <input type="text" name="divisi[{{ $index }}][ketua_divisi]" value="{{ $divisi['ketua_divisi'] ?? '' }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ketua Divisi">
                            </div>
                            <button type="button" onclick="removeDivisi(this)" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" onclick="addDivisi()" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Divisi
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Foto Dokumentasi -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Foto Dokumentasi (Maks. 3 Foto)
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @for($i = 1; $i <= 3; $i++)
                @php $fotoField = "foto_$i"; @endphp
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto {{ $i }} {{ $i === 1 ? '(Utama)' : '' }}</label>
                    <div class="relative">
                        <input type="file" name="foto_{{ $i }}" id="foto_{{ $i }}" accept="image/*" class="hidden" onchange="previewImage(this, 'preview_{{ $i }}', 'placeholder_{{ $i }}')">
                        <label for="foto_{{ $i }}" class="block w-full aspect-video border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-colors overflow-hidden">
                            @if($activity->$fotoField)
                                <img id="preview_{{ $i }}" src="{{ asset('storage/' . $activity->$fotoField) }}" alt="" class="w-full h-full object-cover">
                                <div id="placeholder_{{ $i }}" class="hidden w-full h-full flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <span class="text-sm">Upload Foto</span>
                                </div>
                            @else
                                <img id="preview_{{ $i }}" src="" alt="" class="w-full h-full object-cover hidden">
                                <div id="placeholder_{{ $i }}" class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <span class="text-sm">Upload Foto</span>
                                </div>
                            @endif
                        </label>
                        @if($activity->$fotoField)
                        <div class="mt-2">
                            <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer">
                                <input type="checkbox" name="remove_foto_{{ $i }}" value="1" class="rounded text-red-600">
                                Hapus foto ini
                            </label>
                        </div>
                        @endif
                    </div>
                    @error("foto_$i")
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endfor
            </div>
            <p class="text-gray-500 text-sm mt-2">Format: JPG, PNG, WebP. Maksimal 2MB per foto.</p>
        </div>
        
        <!-- Publish Option -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $activity->is_published) ? 'checked' : '' }} 
                    class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <div>
                    <span class="font-medium text-gray-800">Publikasikan</span>
                    <p class="text-sm text-gray-500">Kegiatan akan tampil di halaman publik</p>
                </div>
            </label>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-between">
            <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" 
                onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-100 text-red-600 font-medium rounded-lg hover:bg-red-200 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus
                </button>
            </form>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.activities.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
let divisiIndex = {{ count($divisiList) }};

function addDivisi() {
    const container = document.getElementById('divisiContainer');
    const newRow = document.createElement('div');
    newRow.className = 'divisi-row flex gap-3 items-start';
    newRow.innerHTML = `
        <div class="flex-1">
            <input type="text" name="divisi[${divisiIndex}][nama_divisi]" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Nama Divisi (cth: Acara, Perkap, Humas)">
        </div>
        <div class="flex-1">
            <input type="text" name="divisi[${divisiIndex}][ketua_divisi]" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Ketua Divisi">
        </div>
        <button type="button" onclick="removeDivisi(this)" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
        </button>
    `;
    container.appendChild(newRow);
    divisiIndex++;
}

function removeDivisi(btn) {
    const rows = document.querySelectorAll('.divisi-row');
    if (rows.length > 1) {
        btn.closest('.divisi-row').remove();
    }
}

function previewImage(input, previewId, placeholderId) {
    const preview = document.getElementById(previewId);
    const placeholder = document.getElementById(placeholderId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection

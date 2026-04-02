@extends('layouts.admin')

@section('title', 'Tambah Pengeluaran - Admin')
@section('header', 'Tambah Pengeluaran')
@section('breadcrumb', 'Tambah Pengeluaran')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Type Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Jenis Pengeluaran</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="barang" class="radio radio-warning" {{ old('type') === 'barang' ? 'checked' : '' }} required>
                        <span class="text-gray-700">Barang</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="type" value="kegiatan" class="radio radio-warning" {{ old('type') === 'kegiatan' ? 'checked' : '' }} required>
                        <span class="text-gray-700">Kegiatan</span>
                    </label>
                </div>
                @error('type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Pengeluaran</label>
                <input type="text" name="title" class="input input-bordered w-full" 
                       placeholder="Contoh: Beli Sound System" value="{{ old('title') }}" required>
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" class="textarea textarea-bordered w-full" rows="5" 
                          placeholder="Jelaskan apa yang dibeli dan tujuannya..." required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nominal -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal (Rp)</label>
                <input type="number" name="nominal" class="input input-bordered w-full" 
                       placeholder="100000" value="{{ old('nominal') }}" min="1000" required>
                @error('nominal')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Documents Upload -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Dokumen</label>
                <p class="text-xs text-gray-500 mb-3">Untuk Barang: SPD, BTPD, Foto barang, Nota</p>
                <p class="text-xs text-gray-500 mb-3">Untuk Kegiatan: LPJ</p>
                <input type="file" name="documents[]" multiple class="file-input file-input-bordered w-full" 
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                @error('documents.*')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan
                </button>
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

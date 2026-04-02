@extends('layouts.admin')

@section('title', 'Tambah Pemasukan - Admin')
@section('header', 'Tambah Pemasukan')
@section('breadcrumb', 'Tambah Pemasukan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.income.store') }}" method="POST">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Pemasukan</label>
                <input type="text" name="title" class="input input-bordered w-full" 
                       placeholder="Contoh: Hasil Persewaan Studio" value="{{ old('title') }}" required>
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Source -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sumber Pemasukan</label>
                <input type="text" name="source" class="input input-bordered w-full" 
                       placeholder="Contoh: Booking Studio, Persewaan Alat, dll" value="{{ old('source') }}">
                @error('source')
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

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" class="textarea textarea-bordered w-full" rows="4" 
                          placeholder="Keterangan tambahan tentang pemasukan ini...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan
                </button>
                <a href="{{ route('admin.income.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

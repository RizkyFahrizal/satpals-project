@extends('layouts.admin')

@section('title', 'Edit Pemasukan - Admin')
@section('header', 'Edit Pemasukan')
@section('breadcrumb', 'Edit Pemasukan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.income.update', $income) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Pemasukan</label>
                <input type="text" name="title" class="input input-bordered w-full" 
                       value="{{ old('title', $income->title) }}" required>
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Source -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sumber Pemasukan</label>
                <input type="text" name="source" class="input input-bordered w-full" 
                       value="{{ old('source', $income->source) }}">
                @error('source')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nominal -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal (Rp)</label>
                <input type="number" name="nominal" class="input input-bordered w-full" 
                       value="{{ old('nominal', $income->nominal) }}" min="1000" required>
                @error('nominal')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="description" class="textarea textarea-bordered w-full" rows="4">{{ old('description', $income->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                <a href="{{ route('admin.income.show', $income) }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Edit Pengeluaran - Admin')
@section('header', 'Edit Pengeluaran')
@section('breadcrumb', 'Edit Pengeluaran')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.expenses.update', $expense) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Pengeluaran</label>
                <input type="text" name="title" class="input input-bordered w-full" 
                       value="{{ old('title', $expense->title) }}" required>
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" class="textarea textarea-bordered w-full" rows="5" required>{{ old('description', $expense->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nominal -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal (Rp)</label>
                <input type="number" name="nominal" class="input input-bordered w-full" 
                       value="{{ old('nominal', $expense->nominal) }}" min="1000" required>
                @error('nominal')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Documents -->
            @if($expense->documents->count() > 0)
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Dokumen Saat Ini</label>
                    <div class="space-y-2">
                        @foreach($expense->documents as $doc)
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <p class="text-sm text-gray-700">{{ $doc->original_name }}</p>
                                <form action="{{ route('admin.expenses.delete-document', $doc) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-ghost text-error">Hapus</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add New Documents -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tambah Dokumen Baru</label>
                <input type="file" name="documents[]" multiple class="file-input file-input-bordered w-full" 
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                @error('documents.*')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                <a href="{{ route('admin.expenses.show', $expense) }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

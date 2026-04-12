@extends('layouts.admin')

@section('title', 'Tambah Pemasukan - Admin')
@section('header', 'Tambah Pemasukan')
@section('breadcrumb', 'Tambah Pemasukan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.income.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Pemasukan</label>
                <input type="text" name="title" class="input input-bordered w-full" 
                       placeholder="Contoh: Persewaan Alat, Turunan Kampus, atau Pendaftaran Expo" value="{{ old('title') }}" required>
                @error('title')
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

            <!-- Nominal -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal (Rp)</label>
                <input type="number" name="nominal" class="input input-bordered w-full" 
                       placeholder="100000" value="{{ old('nominal') }}" min="1000" required>
                @error('nominal')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Income Date -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pemasukan</label>
                <input type="date" name="income_date" class="input input-bordered w-full" 
                       value="{{ old('income_date', date('Y-m-d')) }}" required>
                @error('income_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Documents Upload -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Bukti & Dokumen Pendukung (Opsional)</label>
                <p class="text-xs text-gray-500 mb-3">Contoh: Bukti transfer ke bank UKM, Invoice, Proposal pengajuan dana, atau dokumen pendukung lainnya</p>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6" id="dropZone">
                    <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                           class="hidden" id="fileInput">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <p class="text-gray-600">Klik untuk memilih file atau drag & drop</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG (Max 10MB per file)</p>
                    </div>
                </div>
                <div id="fileList" class="mt-4"></div>
                @error('documents')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('documents.*')
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
                <a href="{{ route('admin.financial.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');

    // Click to select files
    dropZone.addEventListener('click', () => fileInput.click());

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    }

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        updateFileList();
    }

    // Update file list when files are selected
    fileInput.addEventListener('change', updateFileList);

    function updateFileList() {
        fileList.innerHTML = '';
        const files = fileInput.files;
        
        if (files.length === 0) return;

        const ul = document.createElement('ul');
        ul.className = 'space-y-2 mt-4';

        for (let i = 0; i < files.length; i++) {
            const li = document.createElement('li');
            li.className = 'flex items-center justify-between p-2 bg-gray-50 rounded border border-gray-200';
            
            const fileInfo = document.createElement('div');
            fileInfo.className = 'flex-1';
            fileInfo.innerHTML = `
                <p class="text-sm font-medium text-gray-700">${files[i].name}</p>
                <p class="text-xs text-gray-500">${(files[i].size / 1024).toFixed(2)} KB</p>
            `;
            
            const documentType = document.createElement('div');
            documentType.className = 'flex items-center gap-2';
            documentType.innerHTML = `
                <input type="text" name="document_types[${i}]" placeholder="Tipe dokumen" 
                       class="input input-sm input-bordered w-32" value="Bukti">
            `;
            
            li.appendChild(fileInfo);
            li.appendChild(documentType);
            ul.appendChild(li);
        }

        fileList.appendChild(ul);
    }
});
</script>
@endsection


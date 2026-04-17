@extends('layouts.admin')

@section('title', 'Tambah Pengeluaran - Admin')
@section('header', 'Tambah Pengeluaran')
@section('breadcrumb', 'Tambah Pengeluaran')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.expenses.store') }}" method="POST" enctype="multipart/form-data" id="expenseForm">
            @csrf

            <!-- Category Selection -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Pengeluaran</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="category" value="goods" class="radio radio-primary" 
                               onchange="updateFormFields()" checked>
                        <span class="text-sm">Pengeluaran Barang</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="category" value="activity" class="radio radio-primary"
                               onchange="updateFormFields()">
                        <span class="text-sm">Pengeluaran Kegiatan</span>
                    </label>
                </div>
                @error('category')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2" id="titleLabel">Judul Pengeluaran Barang</label>
                <input type="text" name="title" class="input input-bordered w-full" 
                       placeholder="Contoh: Pembelian Peralatan Studio" value="{{ old('title') }}" required>
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2" id="descLabel">Deskripsi - Daftar Barang & Tujuan Pengeluaran</label>
                <textarea name="description" class="textarea textarea-bordered w-full" rows="5" 
                          placeholder="Contoh: 
- Microphone Condenser (2x) - untuk recording
- Pop Filter (2x) - untuk recording
- XLR Cable (5x) - untuk koneksi peralatan

Tujuan: Meningkatkan kualitas recording studio" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Expense Date -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pengeluaran</label>
                <input type="date" name="expense_date" class="input input-bordered w-full" 
                       value="{{ old('expense_date', date('Y-m-d')) }}" required>
                @error('expense_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nominal -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Total Nominal (Rp)</label>
                <input type="number" name="nominal" class="input input-bordered w-full" 
                       placeholder="500000" value="{{ old('nominal') }}" min="1000" required>
                @error('nominal')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Documents Section - For Goods -->
            <div id="goodsDocuments" class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Dokumen Pengeluaran Barang</label>
                
                <!-- SPD Document -->
                <div class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h4 class="font-semibold text-gray-700 mb-2">1. SPD (Surat Pengajuan Dana - Sudah TTD Lengkap)</h4>
                    <p class="text-xs text-gray-500 mb-2">Upload dokumen SPD yang sudah ditandatangani lengkap</p>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4" id="dropZoneSPD">
                        <input type="file" name="spd_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                               class="hidden" id="fileSPD">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk memilih atau drag & drop</p>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                        </div>
                    </div>
                    <div id="fileSPDList" class="mt-2"></div>
                    @error('spd_file')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- BTPD Document -->
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h4 class="font-semibold text-gray-700 mb-2">2. BTPD (Bukti Transaksi Pengajuan Dana - TTD Lengkap)</h4>
                    <p class="text-xs text-gray-500 mb-2">Upload dokumen BTPD yang sudah ditandatangani lengkap</p>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4" id="dropZoneBTPD">
                        <input type="file" name="btpd_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                               class="hidden" id="fileBTPD">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk memilih atau drag & drop</p>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                        </div>
                    </div>
                    <div id="fileBTPDList" class="mt-2"></div>
                    @error('btpd_file')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Documents Section - For Activity -->
            <div id="activityDocuments" class="mb-6" style="display: none;">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Dokumen Pengeluaran Kegiatan</label>
                
                <!-- LPJ Document -->
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h4 class="font-semibold text-gray-700 mb-2">LPJ (Laporan Pertanggungjawaban Kegiatan)</h4>
                    <p class="text-xs text-gray-500 mb-2">Upload dokumen LPJ lengkap dengan bukti pengeluaran</p>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4" id="dropZoneLPJ">
                        <input type="file" name="lpj_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                               class="hidden" id="fileLPJ">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk memilih atau drag & drop</p>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                        </div>
                    </div>
                    <div id="fileLPJList" class="mt-2"></div>
                    @error('lpj_file')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
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
    setupDropZone('dropZoneSPD', 'fileSPD', 'fileSPDList', 'SPD');
    setupDropZone('dropZoneBTPD', 'fileBTPD', 'fileBTPDList', 'BTPD');
    setupDropZone('dropZoneLPJ', 'fileLPJ', 'fileLPJList', 'LPJ');
});

function setupDropZone(dropZoneId, inputId, listId, docType) {
    const dropZone = document.getElementById(dropZoneId);
    const fileInput = document.getElementById(inputId);
    const fileList = document.getElementById(listId);

    dropZone.addEventListener('click', () => fileInput.click());

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        updateFileDisplay(fileInput, fileList, docType);
    }, false);

    fileInput.addEventListener('change', () => {
        updateFileDisplay(fileInput, fileList, docType);
    });
}

function updateFileDisplay(fileInput, fileList, docType) {
    fileList.innerHTML = '';
    
    if (!fileInput.files || fileInput.files.length === 0) {
        return;
    }

    const ul = document.createElement('ul');
    ul.className = 'space-y-2 mt-2';

    for (let i = 0; i < fileInput.files.length; i++) {
        const li = document.createElement('li');
        li.className = 'flex items-center justify-between p-2 bg-white rounded border border-gray-200';
        li.innerHTML = `
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-700">${fileInput.files[i].name}</p>
                <p class="text-xs text-gray-500">${(fileInput.files[i].size / 1024).toFixed(2)} KB</p>
            </div>
            <span class="badge badge-sm badge-primary">${docType}</span>
        `;
        ul.appendChild(li);
    }

    fileList.appendChild(ul);
}

function updateFormFields() {
    const category = document.querySelector('input[name="category"]:checked').value;
    const goodsSection = document.getElementById('goodsDocuments');
    const activitySection = document.getElementById('activityDocuments');
    const titleLabel = document.getElementById('titleLabel');
    const descLabel = document.getElementById('descLabel');

    if (category === 'goods') {
        goodsSection.style.display = 'block';
        activitySection.style.display = 'none';
        titleLabel.textContent = 'Judul Pengeluaran Barang';
        descLabel.textContent = 'Deskripsi - Daftar Barang & Tujuan Pengeluaran';
    } else {
        goodsSection.style.display = 'none';
        activitySection.style.display = 'block';
        titleLabel.textContent = 'Judul Pengeluaran Kegiatan';
        descLabel.textContent = 'Deskripsi Kegiatan';
    }
}

// Form submission validation
document.getElementById('expenseForm').addEventListener('submit', function(e) {
    const category = document.querySelector('input[name="category"]:checked').value;
    
    if (category === 'goods') {
        const spd = document.getElementById('fileSPD').files.length;
        const btpd = document.getElementById('fileBTPD').files.length;
        
        if (spd === 0 || btpd === 0) {
            e.preventDefault();
            alert('Mohon upload kedua dokumen: SPD dan BTPD');
            return false;
        }
    } else {
        const lpj = document.getElementById('fileLPJ').files.length;
        
        if (lpj === 0) {
            e.preventDefault();
            alert('Mohon upload dokumen LPJ');
            return false;
        }
    }
});
</script>
@endsection


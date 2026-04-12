@extends('layouts.admin')

@section('title', 'Edit Pengeluaran - Admin')
@section('header', 'Edit Pengeluaran')
@section('breadcrumb', 'Edit Pengeluaran')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.expenses.update', $expense) }}" method="POST" enctype="multipart/form-data" id="expenseForm">
            @csrf
            @method('PUT')

            <!-- Category (Read-only) -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Pengeluaran</label>
                <p class="text-gray-800 font-medium">
                    @if($expense->category === 'goods')
                        Pengeluaran Barang
                    @else
                        Pengeluaran Kegiatan
                    @endif
                </p>
            </div>

            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2" id="titleLabel">Judul Pengeluaran</label>
                <input type="text" name="title" class="input input-bordered w-full" 
                       value="{{ old('title', $expense->title) }}" required>
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2" id="descLabel">Deskripsi</label>
                <textarea name="description" class="textarea textarea-bordered w-full" rows="5" required>{{ old('description', $expense->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Expense Date -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pengeluaran</label>
                <input type="date" name="expense_date" class="input input-bordered w-full" 
                       value="{{ old('expense_date', $expense->expense_date ? $expense->expense_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                @error('expense_date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nominal -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Total Nominal (Rp)</label>
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
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-700">{{ $doc->original_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $doc->document_type ?? 'Dokumen' }} • {{ $doc->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-xs btn-ghost">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.expenses.delete-document', $doc) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-ghost text-red-600 hover:bg-red-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add New Documents - For Goods -->
            @if($expense->category === 'goods')
            <div id="goodsDocuments" class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Tambah Dokumen Pengeluaran Barang</label>
                
                <!-- SPD Document -->
                <div class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h4 class="font-semibold text-gray-700 mb-2">SPD (Surat Pengajuan Dana)</h4>
                    <p class="text-xs text-gray-500 mb-2">Upload dokumen SPD yang sudah ditandatangani lengkap</p>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4" id="dropZoneSPD">
                        <input type="file" name="spd_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" id="fileSPD">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk memilih atau drag & drop</p>
                        </div>
                    </div>
                    <div id="fileSPDList" class="mt-2"></div>
                </div>

                <!-- BTPD Document -->
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h4 class="font-semibold text-gray-700 mb-2">BTPD (Bukti Transaksi Pengajuan Dana)</h4>
                    <p class="text-xs text-gray-500 mb-2">Upload dokumen BTPD yang sudah ditandatangani lengkap</p>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4" id="dropZoneBTPD">
                        <input type="file" name="btpd_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" id="fileBTPD">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk memilih atau drag & drop</p>
                        </div>
                    </div>
                    <div id="fileBTPDList" class="mt-2"></div>
                </div>
            </div>
            @else
            <!-- Add New Documents - For Activity -->
            <div id="activityDocuments" class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Tambah Dokumen Pengeluaran Kegiatan</label>
                
                <!-- LPJ Document -->
                <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                    <h4 class="font-semibold text-gray-700 mb-2">LPJ (Laporan Pertanggungjawaban Kegiatan)</h4>
                    <p class="text-xs text-gray-500 mb-2">Upload dokumen LPJ lengkap dengan bukti pengeluaran</p>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4" id="dropZoneLPJ">
                        <input type="file" name="lpj_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden" id="fileLPJ">
                        <div class="text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <p class="text-sm text-gray-600">Klik untuk memilih atau drag & drop</p>
                        </div>
                    </div>
                    <div id="fileLPJList" class="mt-2"></div>
                </div>
            </div>
            @endif

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                <a href="{{ route('admin.financial.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($expense->category === 'goods')
        setupDropZone('dropZoneSPD', 'fileSPD', 'fileSPDList', 'SPD');
        setupDropZone('dropZoneBTPD', 'fileBTPD', 'fileBTPDList', 'BTPD');
    @else
        setupDropZone('dropZoneLPJ', 'fileLPJ', 'fileLPJList', 'LPJ');
    @endif
});

function setupDropZone(dropZoneId, inputId, listId, docType) {
    const dropZone = document.getElementById(dropZoneId);
    const fileInput = document.getElementById(inputId);
    const fileList = document.getElementById(listId);

    if (!dropZone || !fileInput) return;

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
</script>
@endsection


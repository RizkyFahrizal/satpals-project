@extends('layouts.admin')

@section('title', 'Detail Arsip Surat - Admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.letters.index') }}" class="btn btn-ghost btn-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Arsip Surat</h1>
        </div>
    </div>

    <!-- Letter Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- File Icon -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                        <span class="text-5xl">{{ $letter->file_icon }}</span>
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        <span class="badge {{ $letter->jenis_badge_class }} badge-lg gap-1">
                            {{ $letter->jenis_icon }} {{ $letter->jenis_label }}
                        </span>
                        <span class="badge badge-ghost badge-lg uppercase">{{ $letter->file_type }}</span>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $letter->nama_surat }}</h2>
                    
                    @if($letter->perihal)
                    <p class="text-gray-600 mb-4">{{ $letter->perihal }}</p>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Tanggal Surat</p>
                            <p class="font-medium text-gray-800">{{ $letter->tanggal_surat->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Nomor Surat</p>
                            <p class="font-medium text-gray-800">{{ $letter->nomor_surat ?? '-' }}</p>
                        </div>
                        @if($letter->jenis === 'masuk')
                        <div>
                            <p class="text-gray-500">Pengirim</p>
                            <p class="font-medium text-gray-800">{{ $letter->pengirim ?? '-' }}</p>
                        </div>
                        @else
                        <div>
                            <p class="text-gray-500">Penerima</p>
                            <p class="font-medium text-gray-800">{{ $letter->penerima ?? '-' }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-gray-500">Ukuran File</p>
                            <p class="font-medium text-gray-800">{{ $letter->file_size_formatted }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Nama File</p>
                        <p class="font-medium text-gray-800">{{ $letter->file_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Diarsipkan</p>
                        <p class="font-medium text-gray-800">{{ $letter->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($letter->keterangan)
                    <div class="md:col-span-2">
                        <p class="text-gray-500">Keterangan</p>
                        <p class="font-medium text-gray-800">{{ $letter->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-gray-50 px-6 py-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.letters.download', $letter) }}" class="btn bg-green-500 hover:bg-green-600 text-white border-0">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download File
            </a>
            
            @if($letter->file_type === 'pdf')
            <a href="{{ route('admin.letters.preview', $letter) }}" target="_blank" class="btn bg-blue-500 hover:bg-blue-600 text-white border-0">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                Buka di Tab Baru
            </a>
            @endif

            <button onclick="document.getElementById('deleteModal').showModal()" class="btn btn-error text-white">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Hapus Arsip
            </button>
        </div>

        <!-- Preview Section for PDF -->
        @if($letter->file_type === 'pdf')
        <div class="border-t border-gray-200">
            <div class="p-4 bg-gray-100">
                <h3 class="font-semibold text-gray-700 mb-2">Preview Dokumen</h3>
            </div>
            <div class="bg-gray-800 p-4">
                <iframe src="{{ asset('storage/' . $letter->file_path) }}" class="w-full h-[600px] rounded-lg"></iframe>
            </div>
        </div>
        @else
        <!-- Info for non-PDF files -->
        <div class="border-t border-gray-200 p-6">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-yellow-800">Preview tidak tersedia untuk file {{ strtoupper($letter->file_type) }}</h4>
                    <p class="text-yellow-700 text-sm mt-1">
                        File {{ strtoupper($letter->file_type) }} tidak dapat ditampilkan langsung di browser. 
                        Silakan download file untuk melihat isinya.
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<dialog id="deleteModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg text-red-600">Hapus Arsip Surat</h3>
        <p class="py-4">Apakah Anda yakin ingin menghapus arsip surat <strong>"{{ $letter->nama_surat }}"</strong>? File akan dihapus permanen.</p>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn btn-ghost">Batal</button>
            </form>
            <form action="{{ route('admin.letters.destroy', $letter) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error text-white">Hapus</button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endsection

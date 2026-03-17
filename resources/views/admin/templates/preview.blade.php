@extends('layouts.admin')

@section('title', 'Preview Template - Admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.templates.index') }}" class="btn btn-ghost btn-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Preview Template</h1>
        </div>
    </div>

    <!-- Template Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-6">
                <!-- File Icon -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center">
                        <span class="text-5xl">{{ $template->file_icon }}</span>
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $template->nama_template }}</h2>
                    
                    <div class="flex flex-wrap gap-3 mb-4">
                        <span class="badge badge-lg">{{ $template->kategori_label }}</span>
                        <span class="badge badge-lg badge-ghost uppercase">{{ $template->file_type }}</span>
                    </div>

                    @if($template->deskripsi)
                    <p class="text-gray-600 mb-4">{{ $template->deskripsi }}</p>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Nama File</p>
                            <p class="font-medium text-gray-800">{{ $template->file_name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Ukuran</p>
                            <p class="font-medium text-gray-800">{{ $template->file_size_formatted }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Tanggal Upload</p>
                            <p class="font-medium text-gray-800">{{ $template->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Terakhir Diupdate</p>
                            <p class="font-medium text-gray-800">{{ $template->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-gray-50 px-6 py-4 flex flex-wrap gap-3">
            <a href="{{ route('admin.templates.download', $template) }}" class="btn bg-green-500 hover:bg-green-600 text-white border-0">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download File
            </a>
            
            @if($template->file_type === 'pdf')
            <a href="{{ asset('storage/' . $template->file_path) }}" target="_blank" class="btn bg-blue-500 hover:bg-blue-600 text-white border-0">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                Buka di Tab Baru
            </a>
            @endif
        </div>

        <!-- Preview Section for PDF -->
        @if($template->file_type === 'pdf')
        <div class="border-t border-gray-200">
            <div class="p-4 bg-gray-100">
                <h3 class="font-semibold text-gray-700 mb-2">Preview Dokumen</h3>
            </div>
            <div class="bg-gray-800 p-4">
                <iframe src="{{ asset('storage/' . $template->file_path) }}" class="w-full h-[600px] rounded-lg"></iframe>
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
                    <h4 class="font-semibold text-yellow-800">Preview tidak tersedia untuk file {{ strtoupper($template->file_type) }}</h4>
                    <p class="text-yellow-700 text-sm mt-1">
                        File {{ strtoupper($template->file_type) }} tidak dapat ditampilkan langsung di browser. 
                        Silakan download file untuk melihat isinya menggunakan aplikasi yang sesuai 
                        (Microsoft Word untuk .doc/.docx, Microsoft Excel untuk .xls/.xlsx).
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

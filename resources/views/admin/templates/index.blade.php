@extends('layouts.admin')

@section('title', 'Kelola Template Surat - Admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Template Surat</h1>
            <p class="text-gray-500 mt-1">Upload dan kelola template dokumen (surat, RAB, proposal, dll)</p>
        </div>
        <button onclick="document.getElementById('addModal').showModal()" class="btn bg-yellow-400 hover:bg-yellow-500 text-gray-800 border-0">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Upload Template
        </button>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success mb-6">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error mb-6">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama template..." 
                       class="input input-bordered w-full">
            </div>
            <div>
                <select name="kategori" class="select select-bordered w-full md:w-48">
                    <option value="">Semua Kategori</option>
                    @foreach(App\Models\DocumentTemplate::KATEGORI_OPTIONS as $key => $label)
                        <option value="{{ $key }}" {{ request('kategori') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn bg-gray-800 text-white hover:bg-gray-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari
            </button>
            @if(request('search') || request('kategori'))
            <a href="{{ route('admin.templates.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </form>
    </div>

    <!-- Templates List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($templates->isEmpty())
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📄</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Template</h3>
                <p class="text-gray-500 mb-4">Mulai upload template dokumen pertama Anda</p>
                <button onclick="document.getElementById('addModal').showModal()" class="btn bg-yellow-400 hover:bg-yellow-500 text-gray-800 border-0">
                    Upload Template
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="font-semibold text-gray-700">Template</th>
                            <th class="font-semibold text-gray-700">Kategori</th>
                            <th class="font-semibold text-gray-700">File</th>
                            <th class="font-semibold text-gray-700">Ukuran</th>
                            <th class="font-semibold text-gray-700">Tanggal Upload</th>
                            <th class="font-semibold text-gray-700 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $template)
                        <tr class="hover:bg-gray-50">
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="text-3xl">{{ $template->file_icon }}</span>
                                    <div>
                                        <div class="font-semibold text-gray-800">{{ $template->nama_template }}</div>
                                        @if($template->deskripsi)
                                        <div class="text-sm text-gray-500 line-clamp-1">{{ $template->deskripsi }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-ghost">{{ $template->kategori_label }}</span>
                            </td>
                            <td>
                                <div class="text-sm">
                                    <div class="font-medium text-gray-700">{{ Str::limit($template->file_name, 25) }}</div>
                                    <div class="text-gray-400 uppercase text-xs">{{ $template->file_type }}</div>
                                </div>
                            </td>
                            <td class="text-gray-600 text-sm">{{ $template->file_size_formatted }}</td>
                            <td class="text-gray-600 text-sm">{{ $template->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Preview Button -->
                                    @if($template->file_type === 'pdf')
                                    <a href="{{ route('admin.templates.preview', $template) }}" target="_blank" 
                                       class="btn btn-sm btn-ghost text-blue-600" title="Lihat">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    @else
                                    <a href="{{ route('admin.templates.preview', $template) }}" 
                                       class="btn btn-sm btn-ghost text-blue-600" title="Preview Info">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </a>
                                    @endif

                                    <!-- Download Button -->
                                    <a href="{{ route('admin.templates.download', $template) }}" 
                                       class="btn btn-sm btn-ghost text-green-600" title="Download">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>

                                    <!-- Edit Button -->
                                    <button onclick="document.getElementById('editModal{{ $template->id }}').showModal()" 
                                            class="btn btn-sm btn-ghost text-yellow-600" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <button onclick="document.getElementById('deleteModal{{ $template->id }}').showModal()" 
                                            class="btn btn-sm btn-ghost text-red-600" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <dialog id="editModal{{ $template->id }}" class="modal">
                            <div class="modal-box max-w-lg">
                                <form method="dialog">
                                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                </form>
                                <h3 class="font-bold text-lg mb-4">Edit Template</h3>
                                <form action="{{ route('admin.templates.update', $template) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="label"><span class="label-text font-medium">Nama Template <span class="text-red-500">*</span></span></label>
                                            <input type="text" name="nama_template" value="{{ $template->nama_template }}" class="input input-bordered w-full" required>
                                        </div>
                                        <div>
                                            <label class="label"><span class="label-text font-medium">Kategori <span class="text-red-500">*</span></span></label>
                                            <select name="kategori" class="select select-bordered w-full" required>
                                                @foreach(App\Models\DocumentTemplate::KATEGORI_OPTIONS as $key => $label)
                                                    <option value="{{ $key }}" {{ $template->kategori == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="label"><span class="label-text font-medium">File Saat Ini</span></label>
                                            <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-lg">
                                                <span class="text-2xl">{{ $template->file_icon }}</span>
                                                <div>
                                                    <p class="font-medium text-sm">{{ $template->file_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $template->file_size_formatted }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="label"><span class="label-text font-medium">Ganti File (opsional)</span></label>
                                            <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" class="file-input file-input-bordered w-full">
                                            <p class="text-xs text-gray-500 mt-1">Format: PDF, Word, Excel. Max: 10MB</p>
                                        </div>
                                        <div>
                                            <label class="label"><span class="label-text font-medium">Deskripsi</span></label>
                                            <textarea name="deskripsi" rows="3" class="textarea textarea-bordered w-full" placeholder="Deskripsi singkat template...">{{ $template->deskripsi }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-action">
                                        <button type="button" onclick="document.getElementById('editModal{{ $template->id }}').close()" class="btn btn-ghost">Batal</button>
                                        <button type="submit" class="btn bg-yellow-400 hover:bg-yellow-500 text-gray-800 border-0">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                            <form method="dialog" class="modal-backdrop">
                                <button>close</button>
                            </form>
                        </dialog>

                        <!-- Delete Modal -->
                        <dialog id="deleteModal{{ $template->id }}" class="modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg text-red-600">Hapus Template</h3>
                                <p class="py-4">Apakah Anda yakin ingin menghapus template <strong>"{{ $template->nama_template }}"</strong>? File akan dihapus permanen.</p>
                                <div class="modal-action">
                                    <form method="dialog">
                                        <button class="btn btn-ghost">Batal</button>
                                    </form>
                                    <form action="{{ route('admin.templates.destroy', $template) }}" method="POST">
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($templates->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $templates->withQueryString()->links() }}
            </div>
            @endif
        @endif
    </div>
</div>

<!-- Add Modal -->
<dialog id="addModal" class="modal">
    <div class="modal-box max-w-lg">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Upload Template Baru</h3>
        <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="label"><span class="label-text font-medium">Nama Template <span class="text-red-500">*</span></span></label>
                    <input type="text" name="nama_template" class="input input-bordered w-full" placeholder="Contoh: Template Surat Undangan" required>
                </div>
                <div>
                    <label class="label"><span class="label-text font-medium">Kategori <span class="text-red-500">*</span></span></label>
                    <select name="kategori" class="select select-bordered w-full" required>
                        <option value="">Pilih Kategori</option>
                        @foreach(App\Models\DocumentTemplate::KATEGORI_OPTIONS as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label"><span class="label-text font-medium">Upload File <span class="text-red-500">*</span></span></label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" class="file-input file-input-bordered w-full" required>
                    <p class="text-xs text-gray-500 mt-1">Format yang didukung: PDF, Word (doc/docx), Excel (xls/xlsx). Maksimal 10MB</p>
                </div>
                <div>
                    <label class="label"><span class="label-text font-medium">Deskripsi</span></label>
                    <textarea name="deskripsi" rows="3" class="textarea textarea-bordered w-full" placeholder="Deskripsi singkat tentang template ini..."></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('addModal').close()" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn bg-yellow-400 hover:bg-yellow-500 text-gray-800 border-0">Upload Template</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addModal').showModal();
    });
</script>
@endif
@endsection

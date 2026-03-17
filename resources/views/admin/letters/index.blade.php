@extends('layouts.admin')

@section('title', 'Arsip Surat - Admin')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Arsip Surat</h1>
            <p class="text-gray-500 mt-1">Kelola surat masuk dan surat keluar UKM</p>
        </div>
        <button onclick="document.getElementById('addModal').showModal()" class="btn bg-yellow-400 hover:bg-yellow-500 text-gray-800 border-0">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Arsip Surat
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📋</span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Arsip</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📥</span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Surat Masuk</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['masuk'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📤</span>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Surat Keluar</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['keluar'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari nama surat, nomor, atau perihal..." 
                       class="input input-bordered w-full">
            </div>
            <div>
                <select name="jenis" class="select select-bordered w-full md:w-40">
                    <option value="">Semua Jenis</option>
                    <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>📥 Surat Masuk</option>
                    <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>📤 Surat Keluar</option>
                </select>
            </div>
            <div>
                <select name="tahun" class="select select-bordered w-full md:w-32">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                    @if(!$years->contains(date('Y')))
                        <option value="{{ date('Y') }}" {{ request('tahun') == date('Y') ? 'selected' : '' }}>{{ date('Y') }}</option>
                    @endif
                </select>
            </div>
            <div>
                <select name="bulan" class="select select-bordered w-full md:w-40">
                    <option value="">Semua Bulan</option>
                    @php
                        $bulanList = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn bg-gray-800 text-white hover:bg-gray-700">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Filter
            </button>
            @if(request('search') || request('jenis') || request('tahun') || request('bulan'))
            <a href="{{ route('admin.letters.index') }}" class="btn btn-ghost">Reset</a>
            @endif
        </form>
    </div>

    <!-- Letters List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @if($letters->isEmpty())
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📬</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Arsip Surat</h3>
                <p class="text-gray-500 mb-4">Mulai arsipkan surat masuk dan keluar UKM</p>
                <button onclick="document.getElementById('addModal').showModal()" class="btn bg-yellow-400 hover:bg-yellow-500 text-gray-800 border-0">
                    Tambah Arsip Surat
                </button>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="font-semibold text-gray-700">Tanggal</th>
                            <th class="font-semibold text-gray-700">Jenis</th>
                            <th class="font-semibold text-gray-700">Surat</th>
                            <th class="font-semibold text-gray-700">Nomor Surat</th>
                            <th class="font-semibold text-gray-700">Pengirim/Penerima</th>
                            <th class="font-semibold text-gray-700">File</th>
                            <th class="font-semibold text-gray-700 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($letters as $letter)
                        <tr class="hover:bg-gray-50">
                            <td class="text-gray-600">
                                <div class="text-sm font-medium">{{ $letter->tanggal_surat->format('d M Y') }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $letter->jenis_badge_class }} gap-1">
                                    {{ $letter->jenis_icon }} {{ $letter->jenis_label }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl">{{ $letter->file_icon }}</span>
                                    <div>
                                        <div class="font-semibold text-gray-800">{{ $letter->nama_surat }}</div>
                                        @if($letter->perihal)
                                        <div class="text-sm text-gray-500 line-clamp-1">{{ $letter->perihal }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-gray-600 text-sm">{{ $letter->nomor_surat ?? '-' }}</td>
                            <td class="text-gray-600 text-sm">
                                @if($letter->jenis === 'masuk')
                                    {{ $letter->pengirim ?? '-' }}
                                @else
                                    {{ $letter->penerima ?? '-' }}
                                @endif
                            </td>
                            <td>
                                <div class="text-sm">
                                    <div class="font-medium text-gray-700">{{ Str::limit($letter->file_name, 20) }}</div>
                                    <div class="text-gray-400 text-xs">{{ $letter->file_size_formatted }}</div>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-2">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.letters.show', $letter) }}" 
                                       class="btn btn-sm btn-ghost text-blue-600" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    <!-- Download Button -->
                                    <a href="{{ route('admin.letters.download', $letter) }}" 
                                       class="btn btn-sm btn-ghost text-green-600" title="Download">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <button onclick="document.getElementById('deleteModal{{ $letter->id }}').showModal()" 
                                            class="btn btn-sm btn-ghost text-red-600" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Delete Modal -->
                        <dialog id="deleteModal{{ $letter->id }}" class="modal">
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($letters->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $letters->withQueryString()->links() }}
            </div>
            @endif
        @endif
    </div>
</div>

<!-- Add Modal -->
<dialog id="addModal" class="modal">
    <div class="modal-box max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Tambah Arsip Surat</h3>
        <form action="{{ route('admin.letters.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="label"><span class="label-text font-medium">Nama Surat <span class="text-red-500">*</span></span></label>
                    <input type="text" name="nama_surat" class="input input-bordered w-full" placeholder="Contoh: Surat Undangan Rapat" required>
                </div>
                <div>
                    <label class="label"><span class="label-text font-medium">Jenis Surat <span class="text-red-500">*</span></span></label>
                    <select name="jenis" id="jenisSelect" class="select select-bordered w-full" required onchange="togglePengirimPenerima()">
                        <option value="">Pilih Jenis</option>
                        <option value="masuk">📥 Surat Masuk</option>
                        <option value="keluar">📤 Surat Keluar</option>
                    </select>
                </div>
                <div>
                    <label class="label"><span class="label-text font-medium">Tanggal Surat <span class="text-red-500">*</span></span></label>
                    <input type="date" name="tanggal_surat" class="input input-bordered w-full" value="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="label"><span class="label-text font-medium">Nomor Surat</span></label>
                    <input type="text" name="nomor_surat" class="input input-bordered w-full" placeholder="Contoh: 001/SP/I/2026">
                </div>
                <div id="pengirimField">
                    <label class="label"><span class="label-text font-medium">Pengirim</span></label>
                    <input type="text" name="pengirim" class="input input-bordered w-full" placeholder="Nama pengirim surat">
                </div>
                <div id="penerimaField" style="display: none;">
                    <label class="label"><span class="label-text font-medium">Penerima</span></label>
                    <input type="text" name="penerima" class="input input-bordered w-full" placeholder="Nama penerima surat">
                </div>
                <div class="md:col-span-2">
                    <label class="label"><span class="label-text font-medium">Perihal</span></label>
                    <input type="text" name="perihal" class="input input-bordered w-full" placeholder="Perihal surat">
                </div>
                <div class="md:col-span-2">
                    <label class="label"><span class="label-text font-medium">Upload File Surat <span class="text-red-500">*</span></span></label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" class="file-input file-input-bordered w-full" required>
                    <p class="text-xs text-gray-500 mt-1">Format: PDF, Word (doc/docx), Excel (xls/xlsx). Maksimal 10MB</p>
                </div>
                <div class="md:col-span-2">
                    <label class="label"><span class="label-text font-medium">Keterangan</span></label>
                    <textarea name="keterangan" rows="2" class="textarea textarea-bordered w-full" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" onclick="document.getElementById('addModal').close()" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn bg-yellow-400 hover:bg-yellow-500 text-gray-800 border-0">Simpan Arsip</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
function togglePengirimPenerima() {
    const jenis = document.getElementById('jenisSelect').value;
    const pengirimField = document.getElementById('pengirimField');
    const penerimaField = document.getElementById('penerimaField');
    
    if (jenis === 'masuk') {
        pengirimField.style.display = 'block';
        penerimaField.style.display = 'none';
    } else if (jenis === 'keluar') {
        pengirimField.style.display = 'none';
        penerimaField.style.display = 'block';
    } else {
        pengirimField.style.display = 'block';
        penerimaField.style.display = 'none';
    }
}
</script>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('addModal').showModal();
    });
</script>
@endif
@endsection

@extends('layouts.admin')

@section('title', 'Struktur Pengurus - Admin Satya Palapa')

@section('header', 'Struktur Pengurus')

@section('content')
<div class="space-y-6">
    <!-- Period Selector & Add Button -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <label class="text-sm font-medium text-gray-700">Periode:</label>
            <form action="{{ route('admin.board.index') }}" method="GET" class="inline">
                <select name="periode" onchange="this.form.submit()" 
                    class="px-4 py-2 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    @foreach($periodeList as $periode)
                    <option value="{{ $periode }}" {{ $selectedPeriode === $periode ? 'selected' : '' }}>
                        {{ $periode }}
                        @if($periode === $currentPeriode) (Aktif) @endif
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
        
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
            class="bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold px-6 py-2 rounded-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Pengurus
        </button>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
        {{ session('error') }}
    </div>
    @endif

    <!-- Empty State -->
    @if($boardMembers->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum ada data pengurus</h3>
        <p class="text-gray-500 mb-6">Periode {{ $selectedPeriode }} belum memiliki struktur pengurus</p>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
            class="inline-flex items-center gap-2 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold px-6 py-3 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Pengurus Pertama
        </button>
    </div>
    @else

    <!-- Pimpinan Section -->
    @if($grouped['pimpinan']->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                Pimpinan Inti
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($grouped['pimpinan'] as $board)
                <div class="group relative bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 p-5 hover:shadow-lg transition-all duration-300">
                    <!-- Status Badge -->
                    @if(!$board->is_active)
                    <span class="absolute top-3 right-3 px-2 py-1 bg-red-100 text-red-600 text-xs font-medium rounded-full">Nonaktif</span>
                    @endif
                    
                    <div class="flex items-start gap-4">
                        <!-- Avatar -->
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white text-xl font-bold shadow-lg flex-shrink-0 overflow-hidden">
                            @if($board->foto)
                            <img src="{{ asset('storage/' . $board->foto) }}" alt="{{ $board->member->nama_lengkap }}" class="w-full h-full object-cover">
                            @elseif($board->member->foto)
                            <img src="{{ asset('storage/' . $board->member->foto) }}" alt="{{ $board->member->nama_lengkap }}" class="w-full h-full object-cover">
                            @else
                            {{ strtoupper(substr($board->member->nama_lengkap, 0, 1)) }}
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 truncate">{{ $board->member->nama_lengkap }}</h4>
                            <p class="text-sm font-semibold text-yellow-600">{{ $board->jabatan_label }}</p>
                            <p class="text-xs text-gray-500">{{ $board->member->npm }}</p>
                            
                            <!-- Login Account Badge -->
                            @if($board->user)
                            <span class="inline-flex items-center gap-1 mt-2 px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Punya Akun
                            </span>
                            @else
                            <form action="{{ route('admin.board.create-account', $board) }}" method="POST" class="inline mt-2">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-full transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Buat Akun
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end gap-2">
                        <button onclick="document.getElementById('editModal{{ $board->id }}').classList.remove('hidden')" 
                            class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <form action="{{ route('admin.board.toggle-status', $board) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors" title="{{ $board->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                @if($board->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @endif
                            </button>
                        </form>
                        <form action="{{ route('admin.board.destroy', $board) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pengurus ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Subsie Section -->
    @if($grouped['subsie']->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Sub Seksi
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                @foreach($grouped['subsie'] as $board)
                <div class="group relative bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 p-4 hover:shadow-lg transition-all duration-300">
                    @if(!$board->is_active)
                    <span class="absolute top-2 right-2 px-2 py-0.5 bg-red-100 text-red-600 text-xs font-medium rounded-full">Nonaktif</span>
                    @endif
                    
                    <div class="flex flex-col items-center text-center gap-3">
                        <div class="w-14 h-14 rounded-lg bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-bold flex-shrink-0 overflow-hidden">
                            @if($board->foto)
                            <img src="{{ asset('storage/' . $board->foto) }}" alt="{{ $board->member->nama_lengkap }}" class="w-full h-full object-cover">
                            @elseif($board->member->foto)
                            <img src="{{ asset('storage/' . $board->member->foto) }}" alt="{{ $board->member->nama_lengkap }}" class="w-full h-full object-cover">
                            @else
                            {{ strtoupper(substr($board->member->nama_lengkap, 0, 1)) }}
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-purple-600 font-semibold mb-1">{{ $board->jabatan_label }}</p>
                            <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $board->member->nama_lengkap }}</h4>
                        </div>
                    </div>
                    
                    <div class="mt-3 flex justify-center gap-1">
                        <button onclick="document.getElementById('editModal{{ $board->id }}').classList.remove('hidden')" 
                            class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        @if(!$board->user)
                        <form action="{{ route('admin.board.create-account', $board) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors" title="Buat Akun">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.board.toggle-status', $board) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded transition-colors" title="{{ $board->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                @if($board->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                @endif
                            </button>
                        </form>
                        <form action="{{ route('admin.board.destroy', $board) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @endif
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Tambah Pengurus</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-800 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.board.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            
            <!-- Periode Selection -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Periode <span class="text-red-500">*</span>
                </label>
                <select name="periode" id="periodeSelect" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <option value="">-- Pilih Periode --</option>
                    @foreach($periodeList as $periode)
                    <option value="{{ $periode }}" {{ $selectedPeriode === $periode ? 'selected' : '' }}>
                        {{ $periode }}
                        @if($periode === $currentPeriode) (Aktif) @endif
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Hanya periode dengan anggota terdaftar</p>
            </div>
            
            <!-- Member Selection (Searchable) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pilih Anggota <span class="text-red-500">*</span>
                </label>
                @if($availableMembers->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-700">
                    <p>Tidak ada anggota aktif yang tersedia.</p>
                    <a href="{{ route('admin.members.index') }}" class="text-yellow-800 font-semibold hover:underline">Kelola Data Anggota →</a>
                </div>
                @else
                <div class="relative">
                    <input type="text" id="searchMember" placeholder="Cari anggota (nama/npm)..." 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <select name="member_id" id="memberSelect" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all mt-2">
                        <option value="">-- Pilih Dari Hasil Pencarian --</option>
                    </select>
                    <div id="searchResults" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-10 max-h-48 overflow-y-auto"></div>
                </div>
                @endif
            </div>
            
            <!-- Diklat Period Selection (untuk timestamp) -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Periode Diklat
                </label>
                <select name="diklat_period_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <option value="">-- Tidak Ada (Tanpa Periode) --</option>
                    @foreach($diklatPeriods as $period)
                    <option value="{{ $period->id }}">{{ $period->nama_periode }} ({{ $period->tahun_masuk }})</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih periode untuk otomatis set tanggal dibuka/ditutup</p>
            </div>
            
            <!-- Jabatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jabatan <span class="text-red-500">*</span>
                </label>
                <select name="jabatan" id="jabatanSelect" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <option value="">-- Pilih Jabatan --</option>
                    <optgroup label="Badan Pengurus Harian">
                        @foreach(['ketua_umum', 'wakil_ketua_umum', 'sekretaris', 'bendahara', 'mpa'] as $key)
                        <option value="{{ $key }}">{{ $jabatanOptions[$key] }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Sub Seksi">
                        @foreach(['subsie_band', 'subsie_peralatan', 'subsie_humas', 'subsie_pdd', 'subsie_kesekretariatan'] as $key)
                        <option value="{{ $key }}">{{ $jabatanOptions[$key] }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            
            <!-- Foto Upload -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Foto Pengurus
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-yellow-400 transition-colors">
                    <input type="file" name="foto" id="fotoInput" accept="image/*" class="hidden" onchange="previewFoto(this, 'fotoPreview')">
                    <label for="fotoInput" class="cursor-pointer">
                        <div id="fotoPreview" class="hidden mb-3">
                            <img src="" alt="Preview" class="w-32 h-32 object-cover rounded-xl mx-auto">
                        </div>
                        <div id="fotoPlaceholder" class="text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm">Klik untuk upload foto</p>
                            <p class="text-xs text-gray-400">Max 2MB (JPG, PNG, WebP)</p>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Create Account -->
            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="create_account" value="1" class="w-5 h-5 rounded border-gray-300 text-yellow-500 focus:ring-yellow-400">
                    <div>
                        <span class="font-medium text-gray-800">Buat Akun Login</span>
                        <p class="text-xs text-gray-500">Pengurus akan bisa login ke admin panel</p>
                    </div>
                </label>
            </div>
            
            <!-- Submit -->
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" 
                    class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Batal
                </button>
                <button type="submit" @if($availableMembers->isEmpty()) disabled @endif
                    class="flex-1 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 font-bold rounded-xl hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modals for each board member -->
@foreach($boardMembers as $board)
<div id="editModal{{ $board->id }}" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 px-6 py-4 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white">Edit Pengurus</h3>
            <button onclick="document.getElementById('editModal{{ $board->id }}').classList.add('hidden')" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.board.update', $board) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @method('PUT')
            
            <!-- Info Anggota -->
            <div class="bg-gray-50 rounded-xl p-4 flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white text-xl font-bold flex-shrink-0 overflow-hidden">
                    @if($board->foto)
                        <img src="{{ asset('storage/' . $board->foto) }}" alt="{{ $board->member->nama_lengkap }}" class="w-full h-full object-cover">
                    @elseif($board->member->foto)
                        <img src="{{ asset('storage/' . $board->member->foto) }}" alt="{{ $board->member->nama_lengkap }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($board->member->nama_lengkap, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $board->member->nama_lengkap }}</p>
                    <p class="text-sm text-gray-500">{{ $board->member->npm }}</p>
                </div>
            </div>
            
            <!-- Jabatan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Jabatan <span class="text-red-500">*</span>
                </label>
                <select name="jabatan" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition-all">
                    <optgroup label="Badan Pengurus Harian">
                        @foreach(['ketua_umum', 'wakil_ketua_umum', 'sekretaris', 'bendahara', 'mpa'] as $key)
                        <option value="{{ $key }}" {{ $board->jabatan === $key ? 'selected' : '' }}>{{ $jabatanOptions[$key] }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Sub Seksi">
                        @foreach(['subsie_band', 'subsie_peralatan', 'subsie_humas', 'subsie_pdd', 'subsie_kesekretariatan'] as $key)
                        <option value="{{ $key }}" {{ $board->jabatan === $key ? 'selected' : '' }}>{{ $jabatanOptions[$key] }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            
            <!-- Urutan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Urutan Tampil
                </label>
                <input type="number" name="urutan" value="{{ $board->urutan }}" min="0" 
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition-all">
            </div>
            
            <!-- Foto Upload -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Foto Pengurus
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:border-blue-400 transition-colors">
                    <input type="file" name="foto" id="editFotoInput{{ $board->id }}" accept="image/*" class="hidden" onchange="previewFoto(this, 'editFotoPreview{{ $board->id }}')">
                    <label for="editFotoInput{{ $board->id }}" class="cursor-pointer">
                        <div id="editFotoPreview{{ $board->id }}" class="{{ $board->foto ? '' : 'hidden' }} mb-3">
                            <img src="{{ $board->foto ? asset('storage/' . $board->foto) : '' }}" alt="Preview" class="w-32 h-32 object-cover rounded-xl mx-auto">
                        </div>
                        <div id="editFotoPlaceholder{{ $board->id }}" class="{{ $board->foto ? 'hidden' : '' }} text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="mt-2 text-sm">Klik untuk upload foto</p>
                            <p class="text-xs text-gray-400">Max 2MB (JPG, PNG, WebP)</p>
                        </div>
                    </label>
                </div>
                @if($board->foto)
                <label class="flex items-center gap-2 mt-2 text-sm text-red-600 cursor-pointer">
                    <input type="checkbox" name="hapus_foto" value="1" class="rounded text-red-500 focus:ring-red-400">
                    <span>Hapus foto saat ini</span>
                </label>
                @endif
            </div>
            
            <!-- Submit -->
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="document.getElementById('editModal{{ $board->id }}').classList.add('hidden')" 
                    class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Batal
                </button>
                <button type="submit" 
                    class="flex-1 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

@section('scripts')
<script>
// Search Members Functionality
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchMember');
    const resultsDiv = document.getElementById('searchResults');
    const memberSelect = document.getElementById('memberSelect');
    const periodeSelect = document.getElementById('periodeSelect');
    const periodSelect = document.querySelector('select[name="diklat_period_id"]');

    if (!searchInput) return;

    let searchTimeout;

    // Trigger search when periode changes
    if (periodeSelect) {
        periodeSelect.addEventListener('change', () => {
            if (searchInput.value.length >= 2) {
                searchInput.dispatchEvent(new Event('input'));
            }
            memberSelect.value = ''; // Clear selection
        });
    }

    searchInput.addEventListener('input', async (e) => {
        clearTimeout(searchTimeout);
        const search = e.target.value.trim();

        if (search.length < 2) {
            resultsDiv.classList.add('hidden');
            memberSelect.value = '';
            return;
        }

        // Get selected period from form (not diklat period)
        const periode = periodeSelect?.value || '';

        searchTimeout = setTimeout(async () => {
            try {
                const params = new URLSearchParams();
                params.append('search', search);
                if (periode) params.append('periode', periode);

                const response = await fetch(`/admin/board/search-members?${params.toString()}`);
                const members = await response.json();

                if (members.length === 0) {
                    resultsDiv.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">Tidak ada anggota ditemukan</div>';
                } else {
                    resultsDiv.innerHTML = members.map(member => `
                        <div class="px-4 py-2 hover:bg-yellow-50 cursor-pointer border-b last:border-b-0 transition-colors" 
                             onclick="selectMember(${member.id}, '${member.nama_lengkap.replace(/'/g, "\\'")}')">
                            <div class="text-sm font-semibold text-gray-700">${member.nama_lengkap}</div>
                            <div class="text-xs text-gray-500">${member.npm}</div>
                        </div>
                    `).join('');
                }

                resultsDiv.classList.remove('hidden');
            } catch (error) {
                console.error('Search error:', error);
                resultsDiv.innerHTML = '<div class="px-4 py-2 text-sm text-red-500">Error saat mencari</div>';
                resultsDiv.classList.remove('hidden');
            }
        }, 300);
    });

    // Close results when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#searchMember') && !e.target.closest('#searchResults')) {
            resultsDiv.classList.add('hidden');
        }
    });

    // Global function to select member
    window.selectMember = (id, nama) => {
        memberSelect.value = id;
        searchInput.value = nama;
        resultsDiv.classList.add('hidden');
    };
});
</script>
<script>
function previewFoto(input, previewId) {
    const preview = document.getElementById(previewId);
    const placeholder = preview.nextElementSibling;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
@endsection

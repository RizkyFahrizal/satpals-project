@extends('layouts.admin')

@section('title', 'Data Anggota UKM - Admin Satya Palapa')

@section('header', 'Data Anggota UKM')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Anggota</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <!-- Aktif -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Aktif</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['aktif'] }}</p>
                </div>
            </div>
        </div>

        <!-- Alumni -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Alumni</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['alumni'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pengurus -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Pengurus Aktif</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pengurus'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <form action="{{ route('admin.members.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Cari nama, NPM, fakultas, prodi..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-40">
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>✅ Aktif</option>
                    <option value="alumni" {{ request('status') === 'alumni' ? 'selected' : '' }}>🎓 Alumni</option>
                    <option value="keluar" {{ request('status') === 'keluar' ? 'selected' : '' }}>❌ Keluar</option>
                </select>
            </div>

            <!-- Angkatan Filter -->
            <div class="w-full md:w-40">
                <select name="angkatan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <option value="">Semua Angkatan</option>
                    @foreach($angkatanList as $angkatan)
                    <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>{{ $angkatan }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold rounded-xl transition-all">
                    Filter
                </button>
                @if(request('search') || request('status') || request('angkatan'))
                <a href="{{ route('admin.members.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar Anggota</h3>
            <p class="text-sm text-gray-500">Anggota UKM yang sudah terdaftar</p>
        </div>
        <a href="{{ route('admin.members.create') }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold px-6 py-2 rounded-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Manual
        </a>
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

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-yellow-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">NPM</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Fakultas/Prodi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Angkatan</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($members as $index => $member)
                    <tr class="hover:bg-yellow-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $members->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold overflow-hidden">
                                    @if($member->foto)
                                    <img src="{{ asset('storage/' . $member->foto) }}" alt="{{ $member->nama_lengkap }}" class="w-full h-full object-cover">
                                    @else
                                    {{ strtoupper(substr($member->nama_lengkap, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <span class="font-medium text-gray-800">{{ $member->nama_lengkap }}</span>
                                    @if($member->isPengurus())
                                    <span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">Pengurus</span>
                                    @endif
                                    <p class="text-xs text-gray-500">{{ $member->jenis_kelamin === 'laki-laki' ? '👨' : '👩' }} {{ $member->no_telepon }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $member->npm }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">{{ $member->fakultas }}</p>
                                <p class="text-gray-500">{{ $member->prodi }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-full">
                                {{ $member->angkatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.members.update-status', $member) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <div class="relative inline-block">
                                    <select name="status" onchange="this.form.submit()" 
                                        class="appearance-none cursor-pointer pl-4 pr-8 py-1.5 rounded-full text-xs font-medium border-2 transition-all duration-200 focus:outline-none
                                        @if($member->status === 'aktif') 
                                            bg-green-100 text-green-700 border-green-200
                                        @elseif($member->status === 'alumni')
                                            bg-blue-100 text-blue-700 border-blue-200
                                        @else
                                            bg-red-100 text-red-700 border-red-200
                                        @endif">
                                        <option value="aktif" {{ $member->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="alumni" {{ $member->status === 'alumni' ? 'selected' : '' }}>Alumni</option>
                                        <option value="keluar" {{ $member->status === 'keluar' ? 'selected' : '' }}>Keluar</option>
                                    </select>
                                </div>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.members.show', $member) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.members.edit', $member) }}" 
                                   class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.members.destroy', $member) }}" method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-gray-500 text-lg">Belum ada data anggota</p>
                                <p class="text-gray-400 text-sm mb-4">Anggota akan otomatis ditambahkan saat pendaftaran diklat disetujui</p>
                                <a href="{{ route('admin.diklat.index') }}" class="text-yellow-600 hover:text-yellow-700 font-semibold">
                                    Kelola Pendaftaran Diklat →
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($members->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $members->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

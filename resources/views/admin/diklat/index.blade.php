@extends('layouts.admin')

@section('title', 'Kelola Anggota Diklat - Admin Satya Palapa')

@section('header', 'Kelola Anggota Diklat')

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
                    <p class="text-sm text-gray-500">Total Pendaftar</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Menunggu</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Diterima</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-rose-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ditolak</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Period & Filter Bar -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.diklat.periods.index') }}" class="btn btn-outline btn-sm gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Kelola Periode
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <form action="{{ route('admin.diklat.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Period Filter -->
            <div class="w-full md:w-56">
                <select name="period_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <option value="">Semua Periode</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                            {{ $period->nama_periode }} ({{ $period->tahun_masuk }})
                        </option>
                    @endforeach
                </select>
            </div>

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
            <div class="w-full md:w-48">
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✅ Diterima</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold rounded-xl transition-all">
                    Filter
                </button>
                @if(request('search') || request('status') || request('period_id'))
                <a href="{{ route('admin.diklat.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Accept All Button (if filtering by period) -->
    @if(request('period_id') && $stats['pending'] > 0)
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-blue-900">Terima Semua Pendaftaran Menunggu</h3>
                <p class="text-sm text-blue-700 mt-1">{{ $stats['pending'] }} pendaftaran menunggu untuk diterima di periode ini</p>
            </div>
            <form action="{{ route('admin.diklat.accept-all', \App\Models\DiklatPeriod::find(request('period_id'))) }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="confirm" value="yes">
                <button type="submit" class="btn btn-sm btn-success gap-2" onclick="return confirm('Terima semua {{ $stats['pending'] }} pendaftaran? Data anggota akan otomatis ditambahkan.')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Terima Semua
                </button>
            </form>
        </div>
    </div>
    @endif

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
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Lengkap</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">NPM</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Fakultas/Prodi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Spesifikasi</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($registrations as $index => $reg)
                    <tr class="hover:bg-yellow-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $registrations->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($reg->nama_lengkap, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-medium text-gray-800">{{ $reg->nama_lengkap }}</span>
                                    <p class="text-xs text-gray-500">{{ $reg->jenis_kelamin === 'laki-laki' ? '👨 Laki-laki' : '👩 Perempuan' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $reg->npm }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-medium text-gray-800">{{ $reg->fakultas }}</p>
                                <p class="text-gray-500">{{ $reg->prodi }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($reg->spesifikasi as $spec)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    @if($spec === 'drum') 🥁
                                    @elseif($spec === 'keyboard') 🎹
                                    @elseif($spec === 'vocal') 🎤
                                    @elseif($spec === 'bass') 🎸
                                    @elseif($spec === 'guitar') 🎸
                                    @endif
                                    {{ ucfirst($spec) }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.diklat.update-status', $reg) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <div class="relative inline-block">
                                    <select name="status" onchange="this.form.submit()" 
                                        class="appearance-none cursor-pointer pl-4 pr-10 py-2 rounded-full text-sm font-medium border-2 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2
                                        @if($reg->status === 'pending') 
                                            bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-800 border-yellow-300 focus:ring-yellow-400
                                        @elseif($reg->status === 'approved')
                                            bg-gradient-to-r from-green-400 to-emerald-500 text-white border-green-400 focus:ring-green-400
                                        @else
                                            bg-gradient-to-r from-red-400 to-rose-500 text-white border-red-400 focus:ring-red-400
                                        @endif">
                                        <option value="pending" {{ $reg->status === 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                                        <option value="approved" {{ $reg->status === 'approved' ? 'selected' : '' }}>✅ Diterima</option>
                                        <option value="rejected" {{ $reg->status === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <svg class="w-4 h-4 text-current opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.diklat.show', $reg) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.diklat.destroy', $reg) }}" method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus data pendaftaran ini?')">
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
                                <p class="text-gray-500 text-lg">Belum ada data pendaftaran</p>
                                <p class="text-gray-400 text-sm">Data pendaftaran diklat akan muncul di sini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($registrations->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $registrations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

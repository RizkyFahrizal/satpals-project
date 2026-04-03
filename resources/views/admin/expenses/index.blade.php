@extends('layouts.admin')

@section('title', 'Kelola Pengeluaran - Admin')
@section('header', 'Kelola Pengeluaran')
@section('breadcrumb', 'Pengeluaran')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Total Pengeluaran</p>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Pengeluaran Barang</p>
            <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($barangExpense, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Pengeluaran Kegiatan</p>
            <p class="text-2xl font-bold text-yellow-600">Rp {{ number_format($kegiatanExpense, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Menunggu Approval</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($pendingExpense, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filter & Create Button -->
    <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
        <div class="flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Pengeluaran</h2>
            <a href="{{ route('admin.expenses.create') }}" class="btn btn-sm btn-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pengeluaran
            </a>
        </div>

        <!-- Search & Filter -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <form method="GET" action="{{ route('admin.expenses.index') }}" class="flex gap-2">
                <input type="text" name="search" placeholder="Cari judul, deskripsi..." value="{{ request('search') }}" 
                       class="input input-bordered input-sm flex-1">
                <button type="submit" class="btn btn-sm btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>

            <form method="GET" action="{{ route('admin.expenses.index') }}" class="flex gap-2">
                <select name="type" onchange="this.form.submit()" class="select select-bordered select-sm flex-1">
                    <option value="all">Semua Tipe</option>
                    <option value="barang" {{ request('type') === 'barang' ? 'selected' : '' }}>Barang</option>
                    <option value="kegiatan" {{ request('type') === 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                </select>
            </form>

            <form method="GET" action="{{ route('admin.expenses.index') }}" class="flex gap-2">
                <select name="status" onchange="this.form.submit()" class="select select-bordered select-sm flex-1">
                    <option value="all">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b-2 border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Judul</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tipe</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Nominal</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Dibuat oleh</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.expenses.show', $expense) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $expense->title }}
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $expense->type === 'barang' ? 'badge-warning' : 'badge-info' }}">
                                    {{ ucfirst($expense->type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-semibold">
                                Rp {{ number_format($expense->nominal, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge 
                                    @if($expense->status === 'pending') badge-warning
                                    @elseif($expense->status === 'approved') badge-success
                                    @elseif($expense->status === 'rejected') badge-error
                                    @elseif($expense->status === 'archived') badge-ghost
                                    @endif">
                                    {{ ucfirst($expense->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">{{ $expense->creator->name }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.expenses.show', $expense) }}" class="btn btn-xs btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">Tidak ada data pengeluaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $expenses->links() }}
        </div>
    </div>
</div>
@endsection

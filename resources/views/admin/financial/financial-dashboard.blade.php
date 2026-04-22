@extends('layouts.admin')

@section('title', 'Kelola Keuangan - Admin')
@section('header', 'Kelola Keuangan')
@section('breadcrumb', 'Keuangan')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-md hover:shadow-lg transition">
            <p class="text-sm text-gray-600 mb-2 font-semibold">💰 Total Sisa</p>
            <p class="text-3xl font-bold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-md hover:shadow-lg transition">
            <p class="text-sm text-gray-600 mb-2 font-semibold">📥 Total Pemasukan</p>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-md hover:shadow-lg transition">
            <p class="text-sm text-gray-600 mb-2 font-semibold">📤 Total Pengeluaran</p>
            <p class="text-3xl font-bold text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-md hover:shadow-lg transition">
            <p class="text-sm text-gray-600 mb-2 font-semibold">⏳ Menunggu Approval</p>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Pemasukan:</span>
                    <span class="text-2xl font-bold text-green-600">{{ $pendingIncomeCount }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Pengeluaran:</span>
                    <span class="text-2xl font-bold text-red-600">{{ $pendingExpenseCount }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Period -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6">
        <form method="GET" action="{{ route('admin.financial.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📅 Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="input input-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📅 Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="input input-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📋 Tipe</label>
                    <select name="filter_type" class="select select-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20">
                        <option value="all" {{ request('filter_type') === 'all' || !request('filter_type') ? 'selected' : '' }}>Semua Tipe</option>
                        <option value="income" {{ request('filter_type') === 'income' ? 'selected' : '' }}>📥 Pemasukan</option>
                        <option value="expense" {{ request('filter_type') === 'expense' ? 'selected' : '' }}>📤 Pengeluaran</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">✓ Status</label>
                    <select name="filter_status" class="select select-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20">
                        <option value="all" {{ request('filter_status') === 'all' || !request('filter_status') ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('filter_status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="approved" {{ request('filter_status') === 'approved' ? 'selected' : '' }}>✓ Approved</option>
                        <option value="rejected" {{ request('filter_status') === 'rejected' ? 'selected' : '' }}>✕ Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📌 Jenis Pengeluaran</label>
                    <select name="filter_expense_type" class="select select-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20">
                        <option value="all" {{ request('filter_expense_type') === 'all' || !request('filter_expense_type') ? 'selected' : '' }}>Semua Jenis</option>
                        <option value="barang" {{ request('filter_expense_type') === 'barang' ? 'selected' : '' }}>📦 Barang</option>
                        <option value="kegiatan" {{ request('filter_expense_type') === 'kegiatan' ? 'selected' : '' }}>🎯 Kegiatan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔎 Cari Berdasarkan</label>
                    <select name="search_by" class="select select-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20">
                        <option value="all" {{ ($searchBy ?? request('search_by')) === 'all' || !($searchBy ?? request('search_by')) ? 'selected' : '' }}>Semua Kolom</option>
                        <option value="title" {{ ($searchBy ?? request('search_by')) === 'title' ? 'selected' : '' }}>Judul Transaksi</option>
                        <option value="description" {{ ($searchBy ?? request('search_by')) === 'description' ? 'selected' : '' }}>Keterangan</option>
                        <option value="creator" {{ ($searchBy ?? request('search_by')) === 'creator' ? 'selected' : '' }}>Dibuat Oleh</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔤 Kata Kunci</label>
                    <input type="text" name="search" value="{{ $searchTerm ?? request('search') }}" class="input input-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20" placeholder="Cari transaksi...">
                </div>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="submit" class="btn btn-sm bg-yellow-400 hover:bg-yellow-500 border-0 text-gray-900 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.financial.index') }}" class="btn btn-sm border border-gray-300 hover:bg-gray-100 text-gray-800">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Combined Transactions Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">📊 Semua Transaksi</h2>
            <div class="flex gap-3">
                <a href="{{ route('admin.income.create') }}" class="btn btn-sm bg-green-500 hover:bg-green-600 border-0 text-white font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pemasukan
                </a>
                <a href="{{ route('admin.expenses.create') }}" class="btn btn-sm bg-red-500 hover:bg-red-600 border-0 text-white font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pengeluaran
                </a>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm" style="table-layout: fixed;">
                <thead class="border-b-2 border-yellow-200 bg-yellow-50">
                    <tr>
                        <th class="text-left py-3 px-4 font-bold text-gray-800" style="width: 60px;">Tipe</th>
                        <th class="text-left py-3 px-4 font-bold text-gray-800" style="width: 220px;">Judul</th>
                        <th class="text-left py-3 px-4 font-bold text-gray-800" style="width: 160px;">Keterangan</th>
                        <th class="text-center py-3 px-4 font-bold text-gray-800" style="width: 100px;">Status</th>
                        <th class="text-right py-3 px-4 font-bold text-gray-800" style="width: 150px;">Nominal</th>
                        <th class="text-left py-3 px-4 font-bold text-gray-800" style="width: 110px;">Dibuat oleh</th>
                        <th class="text-left py-3 px-4 font-bold text-gray-800" style="width: 130px;">Tanggal</th>
                        <th class="text-center py-3 px-4 font-bold text-gray-800">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allTransactions as $transaction)
                        <tr class="border-b border-gray-100 {{ $transaction['type'] === 'income' ? 'bg-green-50 hover:bg-green-100' : 'bg-red-50 hover:bg-red-100' }} transition-colors">
                            <td class="py-3 px-4 text-center" style="width: 60px;">
                                @if($transaction['type'] === 'income')
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-green-200 text-green-800 font-bold" title="Pemasukan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3-3m0 0l3 3m-3-3v12" />
                                        </svg>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-200 text-red-800 font-bold" title="Pengeluaran">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 19l-3 3m0 0l-3-3m3 3V7" />
                                        </svg>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-semibold {{ $transaction['type'] === 'income' ? 'text-green-800' : 'text-red-800' }}" style="width: 220px; word-break: break-word; overflow-wrap: break-word;">
                                {{ $transaction['title'] }}
                            </td>
                            <td class="py-3 px-4 text-gray-600" style="width: 160px;">
                                <span class="badge badge-ghost text-gray-700">
                                    {{ $transaction['type'] === 'income' ? ($transaction['source'] ?? 'Lainnya') : ucfirst($transaction['expense_type'] ?? 'Lainnya') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center" style="width: 100px;">
                                @php
                                    $status = $transaction['status'] ?? 'pending';
                                    $statusColor = $status === 'approved' ? 'badge-success' : ($status === 'rejected' ? 'badge-error' : 'badge-warning');
                                    $statusIcon = $status === 'approved' ? '✓' : ($status === 'rejected' ? '✕' : '⏳');
                                @endphp
                                <span class="badge {{ $statusColor }} text-white font-semibold" title="{{ ucfirst($status) }}">
                                    {{ $statusIcon }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-bold {{ $transaction['type'] === 'income' ? 'text-green-700' : 'text-red-700' }}" style="width: 160px;">
                                {{ $transaction['type'] === 'income' ? '+' : '-' }} Rp {{ number_format($transaction['nominal'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-gray-600 text-xs truncate" style="width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $transaction['creator_name'] }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600" style="width: 120px;">{{ $transaction['date']->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ $transaction['route'] }}" class="btn btn-xs btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                Belum ada transaksi di periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

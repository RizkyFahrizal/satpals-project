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
            <p class="text-3xl font-bold text-yellow-600">{{ $pendingCount }}</p>
            <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($pendingExpense, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filter Period -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6">
        <form method="GET" action="{{ route('admin.financial.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
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
            <table class="w-full text-sm">
                <thead class="border-b-2 border-yellow-200 bg-yellow-50">
                    <tr>
                        <th class="text-left py-3 px-4 font-bold text-gray-800">Tipe</th>
                        <th class="text-left py-3 px-4 font-bold text-gray-800">Judul</th>
                        <th class="text-left py-3 px-4 font-bold text-gray-800">Keterangan</th>
                        <th class="text-center py-3 px-4 font-bold text-gray-800">Status</th>
                        <th class="text-right py-3 px-4 font-bold text-gray-800">Nominal</th>
                        <th class="text-left py-3 px-4 font-bold text-gray-800">Dibuat oleh</th>
                        <th class="text-left py-3 px-4 font-bold text-gray-800">Tanggal</th>
                        <th class="text-center py-3 px-4 font-bold text-gray-800">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allTransactions as $transaction)
                        <tr class="border-b border-gray-100 {{ $transaction['type'] === 'income' ? 'bg-green-50 hover:bg-green-100' : 'bg-red-50 hover:bg-red-100' }} transition-colors">
                            <td class="py-3 px-4">
                                @if($transaction['type'] === 'income')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-200 text-green-800 font-bold text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
                                        </svg>
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-200 text-red-800 font-bold text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c-2.33 0-4.31-1.46-5.11-3.5h10.22c-.8 2.04-2.78 3.5-5.11 3.5z" />
                                        </svg>
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-semibold {{ $transaction['type'] === 'income' ? 'text-green-800' : 'text-red-800' }}">
                                {{ $transaction['title'] }}
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                @if($transaction['type'] !== 'income')
                                    <span class="badge {{ $transaction['expense_type'] === 'barang' ? 'badge-warning' : 'badge-info' }}">
                                        {{ ucfirst($transaction['expense_type']) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $status = $transaction['status'] ?? 'pending';
                                    $statusColor = $status === 'approved' ? 'badge-success' : ($status === 'rejected' ? 'badge-error' : 'badge-warning');
                                    $statusIcon = $status === 'approved' ? '✓' : ($status === 'rejected' ? '✕' : '⏳');
                                @endphp
                                <span class="badge {{ $statusColor }} text-white font-semibold" title="{{ ucfirst($status) }}">
                                    {{ $statusIcon }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-bold {{ $transaction['type'] === 'income' ? 'text-green-700' : 'text-red-700' }}">
                                {{ $transaction['type'] === 'income' ? '+' : '-' }} Rp {{ number_format($transaction['nominal'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $transaction['creator']->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $transaction['date']->format('d M Y') }}</td>
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

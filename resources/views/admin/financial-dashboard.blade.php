@extends('layouts.admin')

@section('title', 'Kelola Keuangan - Admin')
@section('header', 'Kelola Keuangan')
@section('breadcrumb', 'Keuangan')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-2">Total Sisa</p>
            <p class="text-3xl font-bold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-2">Total Pemasukan</p>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-2">Total Pengeluaran</p>
            <p class="text-3xl font-bold text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-2">Menunggu Approval</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $pendingCount }}</p>
            <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($pendingExpense, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filter Period -->
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <form method="GET" action="{{ route('admin.financial.index') }}" class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="input input-bordered w-full">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="input input-bordered w-full">
            </div>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Filter
            </button>
            <a href="{{ route('admin.financial.index') }}" class="btn btn-ghost">
                Reset
            </a>
        </form>
    </div>

    <!-- Combined Transactions Table -->
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Semua Transaksi</h2>
            <div class="flex gap-3">
                <a href="{{ route('admin.income.create') }}" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pemasukan
                </a>
                <a href="{{ route('admin.expenses.create') }}" class="btn btn-error">
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
                <thead class="border-b-2 border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tipe</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Judul</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Keterangan</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Nominal</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Dibuat oleh</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tanggal</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allTransactions as $transaction)
                        <tr class="border-b border-gray-100 {{ $transaction['type'] === 'income' ? 'bg-green-50 hover:bg-green-100' : 'bg-red-50 hover:bg-red-100' }} transition-colors">
                            <td class="py-3 px-4">
                                @if($transaction['type'] === 'income')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-200 text-green-800 font-semibold text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z" />
                                        </svg>
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-200 text-red-800 font-semibold text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c-2.33 0-4.31-1.46-5.11-3.5h10.22c-.8 2.04-2.78 3.5-5.11 3.5z" />
                                        </svg>
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-medium {{ $transaction['type'] === 'income' ? 'text-green-800' : 'text-red-800' }}">
                                {{ $transaction['title'] }}
                            </td>
                            <td class="py-3 px-4 text-gray-600">
                                @if($transaction['type'] === 'income')
                                    {{ $transaction['source'] ?? '-' }}
                                @else
                                    <span class="badge {{ $transaction['expense_type'] === 'barang' ? 'badge-warning' : 'badge-info' }}">
                                        {{ ucfirst($transaction['expense_type']) }}
                                    </span>
                                    @if($transaction['status'] !== 'approved')
                                        <span class="badge badge-ghost">{{ ucfirst($transaction['status']) }}</span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right font-semibold {{ $transaction['type'] === 'income' ? 'text-green-700' : 'text-red-700' }}">
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
            <p class="text-3xl font-bold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-2">Total Pemasukan</p>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-2">Total Pengeluaran</p>
            <p class="text-3xl font-bold text-red-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-2">Menunggu Approval</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $pendingCount }} Item</p>
            <p class="text-xs text-gray-500 mt-1">Rp {{ number_format($pendingExpense, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Pengeluaran Section -->
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Pengeluaran</h2>
                <p class="text-sm text-gray-600 mt-1">Barang: Rp {{ number_format($barangExpense, 0, ',', '.') }} | Kegiatan: Rp {{ number_format($kegiatanExpense, 0, ',', '.') }}</p>
            </div>
            <a href="{{ route('admin.expenses.create') }}" class="btn btn-warning">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pengeluaran
            </a>
        </div>

        <!-- Expenses Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b-2 border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Judul</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tipe</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Nominal</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Dibuat oleh</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tanggal</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses->slice(0, 10) as $expense)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 font-medium text-gray-800">{{ $expense->title }}</td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $expense->type === 'barang' ? 'badge-warning' : 'badge-info' }}">
                                    {{ ucfirst($expense->type) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                <span class="badge {{ $expense->status === 'pending' ? 'badge-warning' : ($expense->status === 'approved' ? 'badge-success' : 'badge-error') }}">
                                    {{ ucfirst($expense->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $expense->creator->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-500">{{ $expense->created_at->format('d M Y') }}</td>
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
                            <td colspan="7" class="text-center py-8 text-gray-500">Belum ada data pengeluaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-right">
            <a href="{{ route('admin.expenses.index') }}" class="link text-blue-600 hover:underline">
                Lihat semua pengeluaran →
            </a>
        </div>
    </div>

    <!-- Pemasukan Section -->
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Pemasukan</h2>
            <a href="{{ route('admin.income.create') }}" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pemasukan
            </a>
        </div>

        <!-- Income Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b-2 border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Judul</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Sumber</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Nominal</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Dibuat oleh</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tanggal</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomes->slice(0, 10) as $income)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 font-medium text-gray-800">{{ $income->title }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $income->source ?? '-' }}</td>
                            <td class="py-3 px-4 text-right font-semibold text-green-600">Rp {{ number_format($income->nominal, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-gray-600">{{ $income->creator->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-500">{{ $income->created_at->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.income.show', $income) }}" class="btn btn-xs btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">Belum ada data pemasukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-right">
            <a href="{{ route('admin.income.index') }}" class="link text-blue-600 hover:underline">
                Lihat semua pemasukan →
            </a>
        </div>
    </div>

    <!-- Monthly Summary Chart -->
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan 6 Bulan Terakhir</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b-2 border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Bulan</th>
                        <th class="text-right py-3 px-4 font-semibold text-green-600">Pemasukan</th>
                        <th class="text-right py-3 px-4 font-semibold text-red-600">Pengeluaran</th>
                        <th class="text-right py-3 px-4 font-semibold text-blue-600">Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyData as $data)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 font-medium text-gray-800">{{ $data['month'] }}</td>
                            <td class="py-3 px-4 text-right font-semibold text-green-600">Rp {{ number_format($data['income'], 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-semibold text-red-600">Rp {{ number_format($data['expense'], 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right font-semibold {{ $data['balance'] >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                Rp {{ number_format($data['balance'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

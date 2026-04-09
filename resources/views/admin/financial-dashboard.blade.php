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

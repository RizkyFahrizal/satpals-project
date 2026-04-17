@extends('layouts.admin')

@section('title', 'Kelola Pemasukan - Admin')
@section('header', 'Kelola Pemasukan')
@section('breadcrumb', 'Pemasukan')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Total Pemasukan</p>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-100 shadow-sm">
            <p class="text-sm text-gray-600 mb-1">Pemasukan Bulan Ini</p>
            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($thisMonthIncome, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filter & Create Button -->
    <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
        <div class="flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center mb-6">
            <h2 class="text-lg font-semibold text-gray-800">Daftar Pemasukan</h2>
            <a href="{{ route('admin.income.create') }}" class="btn btn-sm btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pemasukan
            </a>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <form method="GET" action="{{ route('admin.income.index') }}" class="space-y-4">
                <div class="flex flex-col lg:flex-row gap-4">
                    <input type="text" name="search" placeholder="Cari judul, deskripsi..." value="{{ request('search') }}" 
                           class="input input-bordered input-sm flex-1">
                    <select name="status" class="select select-bordered select-sm w-full lg:w-48">
                        <option value="all" {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>✓ Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>✕ Rejected</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.income.index') }}" class="btn btn-sm btn-ghost">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b-2 border-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Judul</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Status</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Nominal</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Dibuat oleh</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Tanggal</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomes as $income)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.income.show', $income) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $income->title }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @php
                                    $status = $income->status ?? 'pending';
                                    $statusColor = $status === 'approved' ? 'badge-success' : ($status === 'rejected' ? 'badge-error' : 'badge-warning');
                                    $statusIcon = $status === 'approved' ? '✓' : ($status === 'rejected' ? '✕' : '⏳');
                                @endphp
                                <span class="badge {{ $statusColor }} text-white font-semibold" title="{{ ucfirst($status) }}">
                                    {{ $statusIcon }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-semibold text-green-600">
                                Rp {{ number_format($income->nominal, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4">{{ $income->creator->name }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $income->created_at->format('d M Y') }}</td>
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
                            <td colspan="6" class="text-center py-8 text-gray-500">Tidak ada data pemasukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $incomes->links() }}
        </div>
    </div>
</div>
@endsection

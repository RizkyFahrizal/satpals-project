@extends('layouts.admin')

@section('title', 'Dashboard - Admin Satya Palapa')

@section('header', 'Dashboard')

@section('breadcrumb', 'Home')

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl p-6 lg:p-8 text-white shadow-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-yellow-50 text-sm lg:text-base">Kelola operasional UKM Musik Satya Palapa dengan mudah</p>
            </div>
            <div class="text-right hidden lg:block">
                <p class="text-yellow-100 text-sm">Role: <span class="font-semibold">{{ Auth::user()->role_label }}</span></p>
                <p class="text-yellow-100 text-sm">Tanggal: <span class="font-semibold">{{ now()->format('d M Y') }}</span></p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">🔍 Filter Data</h3>
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Year Filter -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select name="year" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quarter Filter -->
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Triwulan</label>
                <select name="quarter" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    <option value="1" {{ $selectedQuarter == '1' ? 'selected' : '' }}>Triwulan 1 (Jan - Mar)</option>
                    <option value="2" {{ $selectedQuarter == '2' ? 'selected' : '' }}>Triwulan 2 (Apr - Jun)</option>
                    <option value="3" {{ $selectedQuarter == '3' ? 'selected' : '' }}>Triwulan 3 (Jul - Sep)</option>
                    <option value="4" {{ $selectedQuarter == '4' ? 'selected' : '' }}>Triwulan 4 (Okt - Des)</option>
                </select>
            </div>

            <!-- Period Info -->
            <div class="flex-1 flex items-end">
                <div class="w-full p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold">Periode:</span> 
                        {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                    </p>
                </div>
            </div>
        </form>
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Sisa Uang UKM -->
        <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Sisa Uang UKM</p>
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-800">Rp {{ number_format($totalBalance ?? 12000000, 0, ',', '.') }}</h2>
                    <p class="text-xs text-gray-400 mt-1">Total Masuk - Total Keluar</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Pemasukan -->
        <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Pemasukan</p>
                    <h3 class="text-2xl lg:text-3xl font-bold text-green-600">Rp {{ number_format($totalIncome ?? 175000, 0, ',', '.') }}</h3>
                    <p class="text-xs text-gray-400 mt-1">Semua sumber pemasukan</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818l.879.659c1.469 1.469 3.662 1.469 5.146 0l5.148-5.147c1.54-1.54 1.54-4.051 0-5.591-1.54-1.54-4.051-1.54-5.591 0L12.91 9.09"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Pengeluaran -->
        <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Total Pengeluaran</p>
                    <h3 class="text-2xl lg:text-3xl font-bold text-red-600">Rp {{ number_format($totalExpense ?? 65000, 0, ',', '.') }}</h3>
                    <p class="text-xs text-gray-400 mt-1">Barang + Kegiatan</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-rose-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m3 2.818l-.879-.659c-1.469-1.469-3.662-1.469-5.146 0l-5.148 5.147c-1.54 1.54-1.54 4.051 0 5.591 1.54 1.54 4.051 1.54 5.591 0L12.09 14.91"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart: Pemasukan vs Pengeluaran -->
        <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">💰 Pemasukan vs Pengeluaran</h3>
            <div class="bg-yellow-50 rounded-xl p-4" style="height: 280px;">
                <canvas id="chartIncomeExpense"></canvas>
            </div>
        </div>

        <!-- Chart: Pengeluaran per Kategori -->
        <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Pengeluaran: Barang vs Kegiatan</h3>
            <div class="bg-yellow-50 rounded-xl p-4" style="height: 280px;">
                <canvas id="chartExpenseCategory"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center hover:shadow-md transition-shadow">
            <p class="text-gray-500 text-sm mb-2">Pengeluaran Barang</p>
            <p class="text-2xl font-bold text-gray-800">{{ $expenseItemCount ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Total transaksi</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center hover:shadow-md transition-shadow">
            <p class="text-gray-500 text-sm mb-2">Pengeluaran Kegiatan</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $expenseEventCount ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Total transaksi</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center hover:shadow-md transition-shadow">
            <p class="text-gray-500 text-sm mb-2">Pemasukan</p>
            <p class="text-2xl font-bold text-green-600">{{ $incomeCount ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Total transaksi</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center hover:shadow-md transition-shadow">
            <p class="text-gray-500 text-sm mb-2">Menunggu Approval</p>
            <p class="text-2xl font-bold text-orange-600">{{ $pendingApproval ?? 0 }}</p>
            <p class="text-xs text-gray-400 mt-1">Transaksi</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Data dari controller (backend)
    const chartMonths = {!! json_encode($monthlyData['months']) !!};
    const chartIncomeData = {!! json_encode($monthlyData['income']) !!};
    const chartExpenseData = {!! json_encode($monthlyData['expense']) !!};
    const expenseCategoryData = {!! json_encode($expenseByCategory) !!};

    // Chart: Pemasukan vs Pengeluaran
    const ctxIncomeExpense = document.getElementById('chartIncomeExpense').getContext('2d');
    new Chart(ctxIncomeExpense, {
        type: 'bar',
        data: {
            labels: chartMonths,
            datasets: [
                {
                    label: 'Pemasukan (Rp)',
                    data: chartIncomeData,
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 0,
                    borderRadius: 8
                },
                {
                    label: 'Pengeluaran (Rp)',
                    data: chartExpenseData,
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 0,
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Grafik Pemasukan vs Pengeluaran (6 bulan terakhir)',
                    color: '#374151',
                    font: {
                        size: 14,
                        weight: '500'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Chart: Pengeluaran per Kategori
    const ctxExpenseCategory = document.getElementById('chartExpenseCategory').getContext('2d');
    new Chart(ctxExpenseCategory, {
        type: 'doughnut',
        data: {
            labels: ['Pengeluaran Barang', 'Pengeluaran Kegiatan'],
            datasets: [{
                label: 'Pengeluaran (Rp)',
                data: [
                    expenseCategoryData.barang || 0,
                    expenseCategoryData.kegiatan || 0
                ],
                backgroundColor: [
                    'rgba(251, 146, 60, 0.8)',
                    'rgba(251, 191, 36, 0.8)'
                ],
                borderColor: [
                    'rgba(251, 146, 60, 1)',
                    'rgba(251, 191, 36, 1)'
                ],
                borderWidth: 0,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Distribusi Pengeluaran (Barang vs Kegiatan)',
                    color: '#374151',
                    font: {
                        size: 14,
                        weight: '500'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection

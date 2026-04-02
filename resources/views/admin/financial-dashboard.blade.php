@extends('layouts.admin')

@section('title', 'Kelola Keuangan - Admin')
@section('header', 'Kelola Keuangan')
@section('breadcrumb', 'Keuangan')

@section('content')
<div class="space-y-6">
    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('admin.financial.index') }}" class="flex flex-col md:flex-row gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" class="input input-bordered input-sm w-full">
            </div>
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" class="input input-bordered input-sm w-full">
            </div>
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            <a href="{{ route('admin.financial.index') }}" class="btn btn-sm btn-ghost">Reset</a>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Balance -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-5 text-white shadow-lg">
            <p class="text-sm opacity-90 mb-1">Sisa Keuangan</p>
            <p class="text-3xl font-bold">Rp {{ number_format($totalBalance, 0, ',', '.') }}</p>
            <p class="text-xs mt-2 opacity-75">Total Masuk - Total Keluar</p>
        </div>

        <!-- Total Income -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-5 text-white shadow-lg">
            <p class="text-sm opacity-90 mb-1">Total Pemasukan</p>
            <p class="text-3xl font-bold">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
            <p class="text-xs mt-2 opacity-75">Periode yang dipilih</p>
        </div>

        <!-- Total Expense -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg p-5 text-white shadow-lg">
            <p class="text-sm opacity-90 mb-1">Total Pengeluaran</p>
            <p class="text-3xl font-bold">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
            <p class="text-xs mt-2 opacity-75">Approved only</p>
        </div>

        <!-- Pending -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-5 text-white shadow-lg">
            <p class="text-sm opacity-90 mb-1">Menunggu Approval</p>
            <p class="text-3xl font-bold">Rp {{ number_format($pendingExpense, 0, ',', '.') }}</p>
            <p class="text-xs mt-2 opacity-75">{{ $pendingCount }} transaksi</p>
        </div>
    </div>

    <!-- Expense Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pengeluaran Breakdown -->
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">💰 Breakdown Pengeluaran</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-700">Pengeluaran Barang</span>
                        <span class="font-semibold text-orange-600">Rp {{ number_format($barangExpense, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $totalExpense > 0 ? ($barangExpense / $totalExpense * 100) : 0 }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-700">Pengeluaran Kegiatan</span>
                        <span class="font-semibold text-yellow-600">Rp {{ number_format($kegiatanExpense, 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $totalExpense > 0 ? ($kegiatanExpense / $totalExpense * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">⚡ Aksi Cepat</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.expenses.create') }}" class="btn btn-sm btn-block btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pengeluaran
                </a>
                <a href="{{ route('admin.income.create') }}" class="btn btn-sm btn-block btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pemasukan
                </a>
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-sm btn-block btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Kelola Pengeluaran
                </a>
                <a href="{{ route('admin.income.index') }}" class="btn btn-sm btn-block btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Kelola Pemasukan
                </a>
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Ringkasan Bulanan</h3>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @foreach($monthlyData as $data)
                    <div class="pb-3 border-b border-gray-100 last:border-b-0">
                        <p class="text-sm font-medium text-gray-800">{{ $data['month'] }}</p>
                        <div class="text-xs text-gray-600 mt-1 space-y-0.5">
                            <p>📥 <span class="text-green-600 font-semibold">Rp {{ number_format($data['income'], 0, ',', '.') }}</span></p>
                            <p>📤 <span class="text-red-600 font-semibold">Rp {{ number_format($data['expense'], 0, ',', '.') }}</span></p>
                            <p>Sisa: <span class="font-semibold {{ $data['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($data['balance'], 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Expenses -->
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">📤 Pengeluaran Terbaru</h3>
                <a href="{{ route('admin.expenses.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse($recentExpenses as $expense)
                    <div class="flex justify-between items-start p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <a href="{{ route('admin.expenses.show', $expense) }}" class="text-sm font-medium text-blue-600 hover:underline">
                                {{ Str::limit($expense->title, 30) }}
                            </a>
                            <p class="text-xs text-gray-500 mt-1">{{ $expense->creator->name }}</p>
                        </div>
                        <span class="text-sm font-semibold text-red-600">-Rp {{ number_format($expense->nominal, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">Tidak ada pengeluaran</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Income -->
        <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">📥 Pemasukan Terbaru</h3>
                <a href="{{ route('admin.income.index') }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
            </div>
            <div class="space-y-3">
                @forelse($recentIncomes as $income)
                    <div class="flex justify-between items-start p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <a href="{{ route('admin.income.show', $income) }}" class="text-sm font-medium text-blue-600 hover:underline">
                                {{ Str::limit($income->title, 30) }}
                            </a>
                            <p class="text-xs text-gray-500 mt-1">{{ $income->creator->name }}</p>
                        </div>
                        <span class="text-sm font-semibold text-green-600">+Rp {{ number_format($income->nominal, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">Tidak ada pemasukan</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Chart: Monthly Trends -->
    <div class="bg-white rounded-lg p-6 border border-gray-100 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 Tren Keuangan (6 Bulan)</h3>
        <div style="height: 300px;">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Chart: Monthly Trends
    const monthlyData = @json($monthlyData);
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [
                {
                    label: 'Pemasukan',
                    data: monthlyData.map(d => d.income),
                    borderColor: 'rgba(34, 197, 94, 1)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                },
                {
                    label: 'Pengeluaran',
                    data: monthlyData.map(d => d.expense),
                    borderColor: 'rgba(239, 68, 68, 1)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                },
                {
                    label: 'Sisa',
                    data: monthlyData.map(d => d.balance),
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
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
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection

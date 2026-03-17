@extends('layouts.admin')

@section('title', 'Dashboard - Admin Satya Palapa')

@section('header', 'Dashboard UKM Musik Satya Palapa')

@section('content')
<div class="space-y-6">
    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Total Uang UKM -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-yellow-100">
            <p class="text-gray-500 text-sm mb-1">Uang UKM saat ini</p>
            <h2 class="text-3xl font-bold text-gray-800">Rp 12.000.000</h2>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border-l-4 border-yellow-400">
            <p class="text-gray-500 text-sm mb-1">Pemasukan Januari</p>
            <h3 class="text-2xl font-bold text-green-600">Rp 175.000</h3>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border-l-4 border-orange-400">
            <p class="text-gray-500 text-sm mb-1">Pengeluaran Januari</p>
            <h3 class="text-2xl font-bold text-red-500">Rp 65.000</h3>
        </div>
    </div>

    <!-- Chart: Persewaan Alat -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-yellow-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pemasukan Persewaan Alat</h3>
        <div class="bg-yellow-50 rounded-xl p-4" style="height: 280px;">
            <canvas id="chartPersewaanAlat"></canvas>
        </div>
    </div>

    <!-- Chart: Booking Studio -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-yellow-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Pemasukan Booking Studio</h3>
        <div class="bg-yellow-50 rounded-xl p-4" style="height: 280px;">
            <canvas id="chartBookingStudio"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Chart Persewaan Alat
    const ctxAlat = document.getElementById('chartPersewaanAlat').getContext('2d');
    new Chart(ctxAlat, {
        type: 'bar',
        data: {
            labels: ['Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari'],
            datasets: [{
                label: 'Pemasukan (Rp)',
                data: [150000, 200000, 180000, 220000, 190000, 175000],
                backgroundColor: 'rgba(251, 191, 36, 0.8)',
                borderColor: 'rgba(251, 191, 36, 1)',
                borderWidth: 0,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Grafik persewaan alat dalam 6 bulan',
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

    // Chart Booking Studio
    const ctxStudio = document.getElementById('chartBookingStudio').getContext('2d');
    new Chart(ctxStudio, {
        type: 'bar',
        data: {
            labels: ['Agustus', 'September', 'Oktober', 'November', 'Desember', 'Januari'],
            datasets: [{
                label: 'Pemasukan (Rp)',
                data: [120000, 180000, 160000, 200000, 170000, 150000],
                backgroundColor: 'rgba(251, 146, 60, 0.8)',
                borderColor: 'rgba(251, 146, 60, 1)',
                borderWidth: 0,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Grafik Booking studio dalam 6 bulan',
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
</script>
@endsection

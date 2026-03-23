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

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Total Uang UKM -->
        <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Uang UKM Saat Ini</p>
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-800">Rp 12.000.000</h2>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Pemasukan {{ now()->format('F') }}</p>
                    <h3 class="text-2xl lg:text-3xl font-bold text-green-600">Rp 175.000</h3>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-3-2.818l.879.659c1.469 1.469 3.662 1.469 5.146 0l5.148-5.147c1.54-1.54 1.54-4.051 0-5.591-1.54-1.54-4.051-1.54-5.591 0L12.91 9.09"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium mb-1">Pengeluaran {{ now()->format('F') }}</p>
                    <h3 class="text-2xl lg:text-3xl font-bold text-red-600">Rp 65.000</h3>
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
        <!-- Chart: Persewaan Alat -->
        <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Pemasukan Persewaan Alat</h3>
            <div class="bg-yellow-50 rounded-xl p-4" style="height: 280px;">
                <canvas id="chartPersewaanAlat"></canvas>
            </div>
        </div>

        <!-- Chart: Booking Studio -->
        <div class="bg-white rounded-2xl p-5 lg:p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">🎵 Pemasukan Booking Studio</h3>
            <div class="bg-yellow-50 rounded-xl p-4" style="height: 280px;">
                <canvas id="chartBookingStudio"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center hover:shadow-md transition-shadow">
            <p class="text-gray-500 text-sm mb-2">Total Anggota</p>
            <p class="text-2xl font-bold text-gray-800">42</p>
            <p class="text-xs text-gray-400 mt-1">Aktif</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center hover:shadow-md transition-shadow">
            <p class="text-gray-500 text-sm mb-2">Pendaftaran Diklat</p>
            <p class="text-2xl font-bold text-yellow-600">15</p>
            <p class="text-xs text-gray-400 mt-1">Menunggu</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center hover:shadow-md transition-shadow">
            <p class="text-gray-500 text-sm mb-2">Surat Keluar</p>
            <p class="text-2xl font-bold text-gray-800">28</p>
            <p class="text-xs text-gray-400 mt-1">Bulan ini</p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-100 text-center hover:shadow-md transition-shadow">
            <p class="text-gray-500 text-sm mb-2">Galeri Kegiatan</p>
            <p class="text-2xl font-bold text-gray-800">12</p>
            <p class="text-xs text-gray-400 mt-1">Foto</p>
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

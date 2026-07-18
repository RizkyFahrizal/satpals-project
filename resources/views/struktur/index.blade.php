@extends('layouts.app')

@section('title', 'Struktur Kepengurusan - UKM Satya Palapa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-yellow-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-yellow-400 via-amber-400 to-orange-400 text-gray-800 py-16 relative overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-1/4 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        </div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <span class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-gray-800 text-sm font-semibold mb-4">👥 Kepengurusan</span>
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Struktur Kepengurusan</h1>
            <p class="text-lg md:text-xl opacity-90">UKM Satya Palapa Universitas Pembangunan Nasional "Veteran" Jawa Timur</p>
            
            <!-- Periode Selector -->
            <div class="mt-8 flex justify-center">
                <form method="GET" class="flex items-center gap-3 bg-white/20 backdrop-blur-sm rounded-full px-6 py-3">
                    <label class="text-gray-800 font-medium">Periode:</label>
                    <select name="periode" onchange="this.form.submit()" 
                            class="bg-white/90 text-gray-800 rounded-full px-4 py-2 border-0 focus:ring-2 focus:ring-gray-800 cursor-pointer">
                        @foreach($periodeList as $periode)
                            <option value="{{ $periode }}" {{ $selectedPeriode == $periode ? 'selected' : '' }}>
                                {{ $periode }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-16">
        @if($boardMembers->isEmpty())
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🎵</div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Data Pengurus</h3>
                <p class="text-gray-500">Data struktur kepengurusan periode {{ $selectedPeriode }} belum tersedia.</p>
            </div>
        @else
            <!-- Pimpinan Section -->
            @if($pimpinan->isNotEmpty())
            <div class="mb-16">
                @php
                    $mpaMembers = $pimpinan->where('jabatan', 'mpa');
                @endphp

                @if($mpaMembers->isNotEmpty())
                <div class="text-center mb-10">
                    <span class="inline-block px-4 py-2 bg-purple-100 rounded-full text-purple-700 text-sm font-semibold mb-4">🏛️ Majelis Perwakilan Anggota</span>
                    <h2 class="text-2xl font-bold text-gray-800">Majelis Perwakilan Anggota</h2>
                </div>
                <div class="flex justify-center mb-12">
                    <div class="flex flex-wrap justify-center gap-8">
                        @foreach($mpaMembers as $mpaMember)
                        <div class="group text-center">
                            <div class="w-56 h-72 rounded-2xl overflow-hidden bg-gradient-to-br from-purple-400 to-pink-500 shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
                                @php
                                    $foto = $mpaMember->foto ?? ($mpaMember->member->foto ?? null);
                                @endphp
                                @if($foto)
                                    <img src="{{ asset('storage/' . $foto) }}" 
                                         alt="{{ $mpaMember->member->nama_lengkap }}" 
                                         class="w-full h-full object-cover object-top">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-6xl text-white/60">👤</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 w-56">
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-full text-xs font-bold mb-2 w-full">
                                    Majelis Perwakilan Anggota
                                </span>
                                <h3 class="font-bold text-gray-800 text-sm line-clamp-2 leading-tight">{{ $mpaMember->member->nama_lengkap ?? 'Belum Ditentukan' }}</h3>
                                @if($mpaMember->member)
                                    <p class="text-gray-500 text-xs mt-2 line-clamp-1">{{ $mpaMember->member->prodi }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="text-center mb-10">
                    <span class="inline-block px-4 py-2 bg-yellow-100 rounded-full text-yellow-700 text-sm font-semibold mb-4">⭐ Pimpinan Inti</span>
                    <h2 class="text-3xl font-bold text-gray-800">Badan Pengurus Harian</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 place-items-center">
                    @php
                        $jabatanOrder = ['ketua_umum', 'wakil_ketua_umum', 'sekretaris', 'bendahara'];
                    @endphp
                    @foreach($jabatanOrder as $jabatan)
                        @php $member = $pimpinan->where('jabatan', $jabatan)->first(); @endphp
                        @if($member)
                        <div class="group text-center">
                            <div class="w-56 h-72 rounded-2xl overflow-hidden bg-gradient-to-br from-yellow-400 to-orange-500 shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
                                @php
                                    $foto = $member->foto ?? ($member->member->foto ?? null);
                                @endphp
                                @if($foto)
                                    <img src="{{ asset('storage/' . $foto) }}" 
                                         alt="{{ $member->member->nama_lengkap }}" 
                                         class="w-full h-full object-cover object-top">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-6xl text-white/50">👤</span>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 w-56">
                                <span class="inline-flex items-center justify-center px-3 py-1 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-full text-xs font-bold mb-2 w-full">
                                    {{ $member->jabatan_label }}
                                </span>
                                <h3 class="font-bold text-gray-800 text-sm line-clamp-2 leading-tight">{{ $member->member->nama_lengkap ?? 'Belum Ditentukan' }}</h3>
                                @if($member->member)
                                    <p class="text-gray-500 text-xs mt-2 line-clamp-1">{{ $member->member->prodi }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Subsie Section -->
            @if($subsie->isNotEmpty())
            <div>
                <div class="text-center mb-12">
                    <span class="inline-block px-4 py-2 bg-blue-100 rounded-full text-blue-700 text-sm font-semibold mb-4">🎯 Bidang Kerja</span>
                    <h2 class="text-3xl font-bold text-gray-800">Sub Seksi</h2>
                </div>

                @php
                    $subsieOrder = ['subsie_band', 'subsie_peralatan', 'subsie_humas', 'subsie_pdd', 'subsie_kesekretariatan'];
                    $subsieIcons = [
                        'subsie_band' => '🎸',
                        'subsie_peralatan' => '🔧',
                        'subsie_humas' => '📢',
                        'subsie_pdd' => '📷',
                        'subsie_kesekretariatan' => '📝',
                    ];
                    $subsieColors = [
                        'subsie_band' => ['from-red-400 to-orange-500', 'from-red-500 to-orange-600'],
                        'subsie_peralatan' => ['from-blue-400 to-cyan-500', 'from-blue-500 to-cyan-600'],
                        'subsie_humas' => ['from-green-400 to-emerald-500', 'from-green-500 to-emerald-600'],
                        'subsie_pdd' => ['from-purple-400 to-pink-500', 'from-purple-500 to-pink-600'],
                        'subsie_kesekretariatan' => ['from-amber-400 to-yellow-500', 'from-amber-500 to-yellow-600'],
                    ];
                @endphp

                <div class="space-y-8">
                    @foreach($subsieOrder as $jabatan)
                        @php $members = $subsie->where('jabatan', $jabatan); @endphp
                        @if($members->isNotEmpty())
                        <div class="mb-12">
                            <div class="text-center mb-8">
                                <span class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white rounded-full text-sm font-semibold shadow-sm border border-gray-100">
                                    <span class="text-xl">{{ $subsieIcons[$jabatan] ?? '🎵' }}</span>
                                    <span class="text-gray-800">{{ App\Models\BoardMember::JABATAN_OPTIONS[$jabatan] ?? $jabatan }}</span>
                                    <span class="ml-2 px-3 py-0.5 rounded-full text-xs font-semibold text-white bg-gradient-to-r {{ $subsieColors[$jabatan][1] ?? 'from-gray-500 to-gray-600' }}">
                                        {{ $members->count() }} Anggota
                                    </span>
                                </span>
                            </div>

                            <div class="flex justify-center">
                                <div class="flex flex-wrap justify-center gap-8">
                                    @foreach($members as $member)
                                    <div class="group text-center">
                                        <div class="w-56 h-72 rounded-2xl overflow-hidden bg-gradient-to-br {{ $subsieColors[$jabatan][0] ?? 'from-gray-400 to-gray-500' }} shadow-lg group-hover:shadow-xl group-hover:scale-105 transition-all duration-300">
                                            @php
                                                $foto = $member->foto ?? ($member->member->foto ?? null);
                                            @endphp
                                            @if($foto)
                                                <img src="{{ asset('storage/' . $foto) }}" 
                                                     alt="{{ $member->member->nama_lengkap }}" 
                                                     class="w-full h-full object-cover object-center">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <span class="text-6xl text-white/60">👤</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="mt-4 w-56">
                                            <span class="inline-flex items-center justify-center px-3 py-1 bg-gradient-to-r {{ $subsieColors[$jabatan][1] ?? 'from-gray-500 to-gray-600' }} text-white rounded-full text-xs font-bold mb-2 w-full">
                                                {{ App\Models\BoardMember::JABATAN_OPTIONS[$jabatan] ?? $jabatan }}
                                            </span>
                                            <h4 class="font-bold text-gray-800 text-sm line-clamp-2 leading-tight">{{ $member->member->nama_lengkap }}</h4>
                                            <p class="text-gray-500 text-xs mt-2 line-clamp-1">{{ $member->member->prodi }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection

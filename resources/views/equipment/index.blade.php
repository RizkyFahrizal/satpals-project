@extends('layouts.app')

@section('title', 'Sewa Peralatan - Satya Palapa')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-yellow-400 via-amber-400 to-orange-400 py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4 text-gray-800">Selamat Datang Di Satpal Rent</h1>
        <p class="text-xl text-gray-700">Dimana kami akan memfasilitasi alat musik untuk menunjang event yang kalian buat</p>
    </div>
</div>

<!-- Alerts -->
<div class="container mx-auto px-4 mt-6">
    @if(session('success'))
    <div class="alert alert-success mb-4 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error mb-4 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m8-8l-2 2m0 0l-2-2m2 2l2-2m-2 2l-2 2" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif
</div>

<!-- Filter & Search Section -->
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row gap-4 mb-8">
        <!-- Filter Button -->
        <button class="btn btn-outline border-yellow-300 text-yellow-700 hover:bg-yellow-50 flex-1 md:flex-none gap-2" onclick="document.getElementById('filter_modal').showModal()">
            <i class="fas fa-filter"></i> Filter
        </button>

        <!-- Search with placeholder text -->
        <form method="GET" action="{{ route('equipment.index') }}" class="flex-1 flex gap-2">
            <input type="text" name="search" placeholder="Cari nama alat atau paket..." class="input input-bordered w-full" value="{{ $searchQuery ?? '' }}">
            <button type="submit" class="btn from-yellow-400 to-orange-500 btn-primary gap-2 text-white font-semibold">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <!-- Contact Us Button -->
        <a href="https://wa.me/628123456789" target="_blank" class="btn from-yellow-400 to-orange-500 btn-primary flex-1 md:flex-none gap-2 text-white font-semibold">
            <i class="fab fa-whatsapp"></i> Hubungi Kami
        </a>

        <!-- Cart Button -->
        <a href="{{ route('cart.index') }}" class="btn btn-outline border-yellow-300 text-yellow-700 hover:bg-yellow-50 flex-1 md:flex-none relative">
            <i class="fas fa-shopping-cart"></i> 
            Keranjang
            @if(session('cart') && count(session('cart', [])) > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                {{ count(session('cart', [])) }}
            </span>
            @endif
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-4 pb-16">
    @forelse($equipments->groupBy('category')->sortBy(function($item, $key) { return $key === 'paket' ? 0 : 1; }) as $category => $items)
    <div class="mb-12">
        <!-- Category Title -->
        <h2 class="text-3xl font-bold text-gray-800 mb-6 capitalize">
            {{ $category === 'paket' ? 'Paket Sewaan' : 'Alat Musik Satuan' }}
        </h2>

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 {{ $category === 'satuan' ? 'lg:grid-cols-4' : '' }} gap-6">
            @foreach($items as $equipment)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                <!-- Image -->
                <div class="bg-gray-300 h-48 overflow-hidden">
                    @if($equipment->photo)
                    <img src="{{ asset('storage/' . $equipment->photo) }}" alt="{{ $equipment->name }}" class="w-full h-full object-cover hover:scale-105 transition">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                    </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-4">
                    <h3 class="font-bold text-gray-800 text-lg truncate">{{ $equipment->name }}</h3>
                    
                    <div class="mt-3 flex justify-between items-center">
                        <span class="badge {{ $equipment->category === 'paket' ? 'bg-amber-100 text-amber-800' : 'bg-orange-100 text-orange-800' }}">
                            {{ ucfirst($equipment->category) }}
                        </span>
                        <span class="text-sm text-gray-600">
                            @if($equipment->requestItems->count() > 0)
                            {{ $equipment->requestItems->count() }} disewa
                            @else
                            Baru
                            @endif
                        </span>
                    </div>

                    <!-- Price Badge -->
                    <div class="mt-4 p-3 bg-gradient-to-br from-yellow-50 to-orange-50 rounded border border-yellow-200 text-center">
                        <p class="text-xs text-gray-600">Harga Per Hari</p>
                        <p class="text-lg font-bold text-orange-600">Rp {{ number_format($equipment->price_per_day, 0, ',', '.') }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('equipment.show', $equipment) }}" class="btn btn-sm btn-ghost flex-1">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <form action="{{ route('equipment.add-to-cart', $equipment) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-sm from-yellow-400 to-orange-500 btn-primary w-full text-white font-semibold">
                                <i class="fas fa-shopping-cart"></i> Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="text-center py-12">
        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg">Tidak ada peralatan yang tersedia</p>
    </div>
    @endforelse

    <!-- Pagination -->
    <div class="flex justify-center mt-8">
        {{ $equipments->links() }}
    </div>
</div>

<!-- Filter Modal -->
<dialog id="filter_modal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Filter Peralatan</h3>
        <form method="GET" action="{{ route('equipment.index') }}" class="space-y-4">
            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select name="category" class="select select-bordered w-full">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $selectedCategory === $cat ? 'selected' : '' }}>
                        {{ ucfirst($cat) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Price Range Filter -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Minimal</label>
                    <input type="number" name="price_min" placeholder="0" class="input input-bordered w-full" value="{{ $priceMin ?? '' }}" min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimal</label>
                    <input type="number" name="price_max" placeholder="10000000" class="input input-bordered w-full" value="{{ $priceMax ?? '' }}" min="0">
                </div>
            </div>

            <!-- Current Filters Display -->
            @if($selectedCategory || $searchQuery || $priceMin || $priceMax)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mt-4">
                <p class="text-sm text-yellow-800 font-semibold mb-2">Filter Aktif:</p>
                <div class="flex flex-wrap gap-2">
                    @if($selectedCategory)
                    <span class="badge bg-amber-100 text-amber-800">{{ ucfirst($selectedCategory) }}</span>
                    @endif
                    @if($searchQuery)
                    <span class="badge bg-amber-100 text-amber-800">Search: {{ $searchQuery }}</span>
                    @endif
                    @if($priceMin || $priceMax)
                    <span class="badge bg-amber-100 text-amber-800">
                        Rp {{ number_format($priceMin ?? 0, 0, ',', '.') }} - Rp {{ number_format($priceMax ?? 999999999, 0, ',', '.') }}
                    </span>
                    @endif
                </div>
            </div>
            @endif

            <div class="modal-action">
                <button type="submit" class="btn from-yellow-400 to-orange-500 btn-primary text-white font-semibold">Terapkan Filter</button>
                <a href="{{ route('equipment.index') }}" class="btn btn-ghost">Reset</a>
                <button type="button" class="btn" onclick="document.getElementById('filter_modal').close()">Tutup</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>
@endsection

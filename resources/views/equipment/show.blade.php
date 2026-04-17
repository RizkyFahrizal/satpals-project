@extends('layouts.app')

@section('title', $equipment->name . ' - Satya Palapa')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Back Button -->
    <a href="{{ route('equipment.index') }}" class="btn btn-outline border-yellow-300 text-yellow-700 hover:bg-yellow-50 mb-6">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Photo -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                @if($equipment->photo)
                <img src="{{ asset('storage/' . $equipment->photo) }}" alt="{{ $equipment->name }}" class="w-full h-96 object-cover">
                @else
                <div class="w-full h-96 bg-gray-300 flex items-center justify-center">
                    <i class="fas fa-image text-gray-400 text-6xl"></i>
                </div>
                @endif
            </div>

            <!-- Description -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Deskripsi Produk</h2>
                <p class="text-gray-700 leading-relaxed">
                    {{ $equipment->description ?? 'Tidak ada deskripsi tersedia' }}
                </p>
            </div>

            <!-- Notes -->
            @if($equipment->notes)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-6 mt-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-yellow-600"></i> Catatan Penting
                </h2>
                <p class="text-gray-700 whitespace-pre-line">{{ $equipment->notes }}</p>
            </div>
            @endif

            <!-- Units for Paket -->
            @if($equipment->category === 'paket' && $units->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6 border-l-4 border-yellow-400">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Apa Saja yang Disertakan ({{ $units->count() }} Item)</h2>
                <div class="space-y-3">
                    @foreach($units as $unit)
                    <div class="flex items-start gap-4 pb-4 border-b last:border-b-0">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-md bg-gradient-to-br from-yellow-400 to-orange-500 text-white">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $unit->unit_name }}</p>
                            <p class="text-sm text-gray-600">Jumlah: <span class="font-bold">{{ $unit->quantity }} {{ $unit->quantity > 1 ? 'unit' : 'unit' }}</span></p>
                            @if($unit->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $unit->description }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right: Order Info -->
        <div class="lg:col-span-1">
            <!-- Price Card -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-lg p-6 border border-yellow-200 sticky top-20">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Informasi Harga</h3>

                <!-- Price -->
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-2">Harga Per Hari</p>
                    <p class="text-4xl font-bold text-orange-600">Rp {{ number_format($equipment->price_per_day, 0, ',', '.') }}</p>
                </div>

                <!-- Operator Price for Paket -->
                @if($equipment->category === 'paket' && $equipment->operator_crew_price)
                <div class="bg-white rounded-lg p-4 mb-6 border border-yellow-200">
                    <p class="text-sm text-gray-600 mb-2">Operator + Crew (Optional)</p>
                    <p class="text-2xl font-bold text-orange-600">Rp {{ number_format($equipment->operator_crew_price, 0, ',', '.') }}/hari</p>
                </div>
                @endif

                <!-- Category Badge -->
                <div class="mb-6">
                    <span class="badge badge-lg {{ $equipment->category === 'paket' ? 'bg-amber-100 text-amber-800' : 'bg-orange-100 text-orange-800' }}">
                        {{ ucfirst($equipment->category) }}
                    </span>
                </div>

                <!-- Add to Cart Form -->
                <form action="{{ route('equipment.add-to-cart', $equipment) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    @if($equipment->category === 'satuan')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button type="button" onclick="decreaseQty()" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-100">-</button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="w-full text-center border-none focus:outline-none">
                            <button type="button" onclick="increaseQty()" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-100">+</button>
                        </div>
                    </div>
                    @else
                    <input type="hidden" name="quantity" value="1">
                    @endif

                    <button type="submit" class="btn from-yellow-400 to-orange-500 btn-primary w-full btn-lg gap-2 text-white font-semibold">
                        <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                    </button>
                </form>

                <a href="{{ route('cart.index') }}" class="btn btn-outline border-yellow-300 text-yellow-700 hover:bg-yellow-50 w-full mt-3">
                    Lihat Keranjang
                </a>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Penting</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-500 text-lg mt-1"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Garansi Kualitas</p>
                            <p class="text-sm text-gray-600">Semua alat dalam kondisi prima dan teruji</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-truck text-blue-500 text-lg mt-1"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Pengiriman Tersedia</p>
                            <p class="text-sm text-gray-600">Kirim ke lokasi acara Anda</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-headset text-orange-500 text-lg mt-1"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Support 24/7</p>
                            <p class="text-sm text-gray-600">Tim siap membantu Anda kapan saja</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function increaseQty() {
    const input = document.getElementById('quantity');
    input.value = parseInt(input.value) + 1;
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>
@endsection

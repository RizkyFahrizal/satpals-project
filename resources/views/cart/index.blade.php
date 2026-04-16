@extends('layouts.app')

@section('title', 'Keranjang - Satya Palapa')

@section('content')
<!-- Header Section with Gradient -->
<div class="bg-gradient-to-br from-yellow-400 via-amber-400 to-orange-400 py-12 md:py-16">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-shopping-cart text-3xl text-gray-800"></i>
            <h1 class="text-4xl font-bold text-gray-800">Keranjang Saya</h1>
        </div>
        <p class="text-gray-700 text-lg">Tinjau dan sesuaikan pesanan Anda sebelum checkout</p>
    </div>
</div>

<div class="container mx-auto px-4 py-8">

    @if(empty($cart))
    <!-- Empty Cart -->
    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg border-2 border-dashed border-yellow-200 p-12 text-center">
        <i class="fas fa-shopping-bag text-yellow-300 text-6xl mb-4"></i>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Keranjang Anda Kosong</h2>
        <p class="text-gray-600 mb-6">Mari mulai pilih peralatan yang Anda butuhkan</p>
        <a href="{{ route('equipment.index') }}" class="btn btn-lg from-yellow-400 to-orange-500 btn-primary gap-2 text-white font-semibold">
            <i class="fas fa-arrow-left"></i> Lanjut Belanja
        </a>
    </div>
    @else
    <!-- Cart Items -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Items List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Produk</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Harga/Hari</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Qty</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Subtotal</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $equipment_id => $item)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @if($item['photo'])
                                        <img src="{{ asset('storage/' . $item['photo']) }}" alt="{{ $item['name'] }}" class="w-12 h-12 object-cover rounded">
                                        @else
                                        <div class="w-12 h-12 bg-gray-300 rounded flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $item['name'] }}</p>
                                            <span class="badge badge-sm {{ $item['category'] === 'paket' ? 'bg-amber-100 text-amber-800' : 'bg-orange-100 text-orange-800' }}">
                                                {{ ucfirst($item['category']) }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-800 font-medium">
                                    Rp {{ number_format($item['price_per_day'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($item['category'] === 'satuan')
                                    <form action="{{ route('cart.update', $equipment_id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <div class="flex items-center border border-gray-300 rounded">
                                            <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100" onclick="decreaseQty(this)">-</button>
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-12 text-center border-none focus:outline-none" onchange="this.form.submit()">
                                            <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100" onclick="increaseQty(this)">+</button>
                                        </div>
                                    </form>
                                    @else
                                    <div class="text-center font-semibold text-gray-800">
                                        {{ $item['quantity'] }}
                                    </div>
                                    @endif
                                </td>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <p class="font-bold text-gray-800">
                                        Rp {{ number_format($item['price_per_day'] * $item['quantity'], 0, ',', '.') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('cart.remove', $equipment_id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-ghost text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Continue Shopping -->
                <div class="bg-gray-50 px-6 py-4 border-t">
                    <a href="{{ route('equipment.index') }}" class="text-yellow-600 hover:text-orange-600 font-medium flex items-center gap-2">
                        <i class="fas fa-arrow-left"></i> Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="lg:col-span-1">
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-lg p-6 border border-yellow-200 sticky top-20">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Ringkasan Pesanan</h2>

                <!-- Items Count -->
                <div class="flex justify-between items-center mb-4 pb-4 border-b border-yellow-100">
                    <p class="text-gray-700">{{ count($cart) }} Item</p>
                    <p class="font-semibold text-gray-800">
                        {{ array_sum(array_map(function($item) { return $item['quantity']; }, $cart)) }} Qty
                    </p>
                </div>

                <!-- Subtotal -->
                <div class="flex justify-between items-center mb-4 pb-4 border-b border-yellow-100">
                    <p class="text-gray-700">Subtotal (1 Hari)</p>
                    <p class="font-semibold text-gray-800">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Note -->
                <div class="bg-orange-50 border border-orange-200 rounded p-3 mb-6">
                    <p class="text-xs text-orange-800">
                        <i class="fas fa-info-circle"></i> Harga ini untuk 1 hari penyewaan. Durasi akan dipilih saat checkout.
                    </p>
                </div>

                <!-- Checkout Button -->
                <a href="{{ route('checkout.index') }}" class="btn from-yellow-400 to-orange-500 btn-primary w-full btn-lg gap-2 mb-3 text-white font-semibold">
                    <i class="fas fa-arrow-right"></i> Lanjut ke Checkout
                </a>

                <!-- Clear Cart -->
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline w-full border-yellow-300 text-yellow-700 hover:bg-yellow-50" onclick="return confirm('Hapus semua item dari keranjang?')">
                        <i class="fas fa-trash"></i> Kosongkan Keranjang
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function increaseQty(btn) {
    const input = btn.parentElement.querySelector('input[type="number"]');
    input.value = parseInt(input.value) + 1;
}

function decreaseQty(btn) {
    const input = btn.parentElement.querySelector('input[type="number"]');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>
@endsection

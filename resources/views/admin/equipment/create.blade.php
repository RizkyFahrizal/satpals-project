@extends('layouts.admin')

@section('title', 'Tambah Peralatan Sewa')

@section('header', 'Tambah Peralatan Sewa')

@section('breadcrumb', 'Manajemen Persewaan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">📦 Tambah Peralatan</h2>
            <a href="{{ route('admin.equipment.index') }}" class="btn btn-sm border border-gray-300 hover:bg-gray-100 text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('admin.equipment.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 border-yellow-200">📋 Informasi Dasar</h3>

                    <div class="space-y-4">
                        <!-- Nama -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Peralatan <span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="Nama peralatan atau paket" 
                                   value="{{ old('name') }}" 
                                   class="input input-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20 @error('name') input-error @enderror" />
                            @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                            <select name="category" id="category" 
                                    class="select select-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20 @error('category') select-error @enderror">
                                <option value="">Pilih Kategori</option>
                                <option value="paket" {{ old('category') == 'paket' ? 'selected' : '' }}>📦 Paket</option>
                                <option value="satuan" {{ old('category') == 'satuan' ? 'selected' : '' }}>🎁 Satuan</option>
                            </select>
                            @error('category')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                            <textarea name="description" placeholder="Deskripsi peralatan atau paket" rows="4"
                                      class="textarea textarea-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20 @error('description') textarea-error @enderror">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Produk</label>
                            <textarea name="notes" placeholder="Catatan khusus untuk produk ini" rows="3"
                                      class="textarea textarea-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20 @error('notes') textarea-error @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Harga -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 border-yellow-200">💰 Harga</h3>

                    <div class="space-y-4">
                        <!-- Harga Per Hari -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Per Hari (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="price_per_day" placeholder="0" 
                                   value="{{ old('price_per_day') }}" 
                                   class="input input-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20 @error('price_per_day') input-error @enderror" 
                                   min="0" step="1000" />
                            @error('price_per_day')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga Operator (Paket Only) -->
                        <div id="operator-price-section" style="display: none;">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Operator + Crew Per Hari (Rp)</label>
                            <input type="number" name="operator_crew_price" placeholder="0" 
                                   value="{{ old('operator_crew_price') }}" 
                                   class="input input-bordered w-full rounded-lg border-gray-300 focus:border-yellow-400 focus:ring-yellow-400/20 @error('operator_crew_price') input-error @enderror" 
                                   min="0" step="1000" />
                            @error('operator_crew_price')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Unit List (Paket Only) -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6" id="units-section" style="display: none;">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 border-yellow-200">📦 Unit yang Disewakan</h3>

                    <div id="units-container" class="space-y-4 mb-4">
                        <!-- Units akan ditambah di sini via JavaScript -->
                    </div>

                    <button type="button" onclick="addUnit()" class="btn btn-sm bg-yellow-400 hover:bg-yellow-500 border-0 text-gray-900 font-semibold w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Unit
                    </button>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Foto -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 border-yellow-200">📸 Foto Peralatan</h3>

                    <div class="space-y-4">
                        <div id="photo-preview" class="w-full h-40 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>

                        <input type="file" name="photo" accept="image/*" id="photo-input"
                               class="file-input file-input-bordered w-full rounded-lg border-gray-300 @error('photo') file-input-error @enderror" />
                        
                        <p class="text-xs text-gray-600">Maksimal 2MB. Format: JPEG, PNG, JPG, GIF</p>

                        @error('photo')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-3 border-b-2 border-yellow-200">✓ Status</h3>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-4 p-0 h-auto">
                            <input type="checkbox" name="is_available" value="1" 
                                   {{ old('is_available') ? 'checked' : 'checked' }}
                                   class="checkbox checkbox-sm checkbox-warning" />
                            <span class="label-text font-semibold">Tersedia untuk Disewa</span>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex gap-2 mt-6">
                    <button type="submit" class="btn btn-sm bg-yellow-400 hover:bg-yellow-500 border-0 text-gray-900 font-semibold flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan
                    </button>
                    <a href="{{ route('admin.equipment.index') }}" class="btn btn-sm border border-gray-300 hover:bg-gray-100 text-gray-800">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const categorySelect = document.getElementById('category');
    const unitSection = document.getElementById('units-section');
    const operatorPriceSection = document.getElementById('operator-price-section');
    const photoInput = document.getElementById('photo-input');
    const photoPreview = document.getElementById('photo-preview');

    // Show/hide sections based on category
    categorySelect.addEventListener('change', function() {
        if (this.value === 'paket') {
            unitSection.style.display = 'block';
            operatorPriceSection.style.display = 'block';
            addUnit();
        } else {
            unitSection.style.display = 'none';
            operatorPriceSection.style.display = 'none';
            document.getElementById('units-container').innerHTML = '';
        }
    });

    // Trigger on load
    if (categorySelect.value === 'paket') {
        unitSection.style.display = 'block';
        operatorPriceSection.style.display = 'block';
    }

    // Photo preview
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                photoPreview.innerHTML = `<img src="${event.target.result}" class="w-full h-40 object-cover rounded-lg" />`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Add unit function
    let unitCount = 0;
    function addUnit() {
        const container = document.getElementById('units-container');
        unitCount++;
        const unitHTML = `
            <div class="border border-gray-200 rounded-lg p-4 space-y-3 unit-item bg-gray-50">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="font-semibold text-gray-700">Unit ${unitCount}</h4>
                    <button type="button" onclick="this.closest('.unit-item').remove()" class="btn btn-sm btn-ghost text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Unit</label>
                    <input type="text" name="units[${unitCount}][unit_name]" placeholder="Nama unit" 
                           class="input input-bordered input-sm w-full rounded-lg" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah</label>
                    <input type="number" name="units[${unitCount}][quantity]" placeholder="1" 
                           class="input input-bordered input-sm w-full rounded-lg" value="1" min="1" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <input type="text" name="units[${unitCount}][description]" placeholder="Deskripsi unit (opsional)" 
                           class="input input-bordered input-sm w-full rounded-lg" />
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', unitHTML);
    }
</script>
@endsection

@extends('layouts.admin')

@section('title', 'Edit Peralatan Sewa')

@section('header', 'Edit Peralatan Sewa')

@section('breadcrumb', 'Manajemen Persewaan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Peralatan: {{ $equipment->name }}</h1>
        <a href="{{ route('admin.equipment.index') }}" class="btn btn-ghost gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.equipment.update', $equipment) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Dasar -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Dasar</h2>

                    <div class="space-y-4">
                        <!-- Nama -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Nama Peralatan <span class="text-red-500">*</span></span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $equipment->name) }}" 
                                   class="input input-bordered @error('name') input-error @enderror" />
                            @error('name')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </div>

                        <!-- Kategori -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Kategori <span class="text-red-500">*</span></span>
                            </label>
                            <select name="category" id="category" 
                                    class="select select-bordered @error('category') select-error @enderror">
                                <option value="paket" {{ old('category', $equipment->category) == 'paket' ? 'selected' : '' }}>Paket</option>
                                <option value="satuan" {{ old('category', $equipment->category) == 'satuan' ? 'selected' : '' }}>Satuan</option>
                            </select>
                            @error('category')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Deskripsi</span>
                            </label>
                            <textarea name="description" rows="4"
                                      class="textarea textarea-bordered @error('description') textarea-error @enderror">{{ old('description', $equipment->description) }}</textarea>
                            @error('description')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Catatan Produk</span>
                            </label>
                            <textarea name="notes" rows="3"
                                      class="textarea textarea-bordered @error('notes') textarea-error @enderror">{{ old('notes', $equipment->notes) }}</textarea>
                            @error('notes')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Harga -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Harga</h2>

                    <div class="space-y-4">
                        <!-- Harga Per Hari -->
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Harga Per Hari (Rp) <span class="text-red-500">*</span></span>
                            </label>
                            <input type="number" name="price_per_day" value="{{ old('price_per_day', $equipment->price_per_day) }}" 
                                   class="input input-bordered @error('price_per_day') input-error @enderror" 
                                   min="0" step="1000" />
                            @error('price_per_day')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </div>

                        <!-- Harga Operator (Paket Only) -->
                        <div class="form-control" id="operator-price-section" style="{{ $equipment->category === 'paket' ? '' : 'display: none;' }}">
                            <label class="label">
                                <span class="label-text font-semibold">Harga Operator + Crew Per Hari (Rp)</span>
                            </label>
                            <input type="number" name="operator_crew_price" value="{{ old('operator_crew_price', $equipment->operator_crew_price) }}" 
                                   class="input input-bordered @error('operator_crew_price') input-error @enderror" 
                                   min="0" step="1000" />
                            @error('operator_crew_price')
                            <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Unit List (Paket Only) -->
                <div class="bg-white rounded-lg shadow p-6" id="units-section" style="{{ $equipment->category === 'paket' ? '' : 'display: none;' }}">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Unit yang Disewakan</h2>

                    <div id="units-container" class="space-y-4 mb-4">
                        @foreach($equipment->units as $unit)
                        <div class="border rounded p-4 space-y-3 unit-item">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-semibold text-gray-700">Unit {{ $loop->iteration }}</h4>
                                <button type="button" onclick="this.closest('.unit-item').remove()" class="btn btn-sm btn-ghost">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text text-sm">Nama Unit</span>
                                </label>
                                <input type="text" name="units[{{ $loop->index }}][unit_name]" value="{{ $unit->unit_name }}" 
                                       class="input input-bordered input-sm" />
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text text-sm">Jumlah</span>
                                </label>
                                <input type="number" name="units[{{ $loop->index }}][quantity]" value="{{ $unit->quantity }}" 
                                       class="input input-bordered input-sm" min="1" />
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text text-sm">Deskripsi</span>
                                </label>
                                <input type="text" name="units[{{ $loop->index }}][description]" value="{{ $unit->description }}" 
                                       class="input input-bordered input-sm" />
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" onclick="addUnit()" class="btn btn-sm btn-outline">
                        <i class="fas fa-plus"></i> Tambah Unit
                    </button>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Foto -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Foto Peralatan</h2>

                    <div class="space-y-4">
                        <div id="photo-preview" class="w-full h-40 bg-gray-200 rounded border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                            @if($equipment->photo)
                            <img src="{{ asset('storage/' . $equipment->photo) }}" class="w-full h-40 object-cover" />
                            @else
                            <i class="fas fa-image text-gray-400 text-3xl"></i>
                            @endif
                        </div>

                        <input type="file" name="photo" accept="image/*" id="photo-input"
                               class="file-input file-input-bordered w-full @error('photo') file-input-error @enderror" />
                        
                        <p class="text-xs text-gray-500">Maksimal 2MB. Format: JPEG, PNG, JPG, GIF</p>

                        @error('photo')
                        <label class="label"><span class="label-text-alt text-error">{{ $message }}</span></label>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Status</h2>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-4">
                            <input type="checkbox" name="is_available" value="1" 
                                   {{ old('is_available', $equipment->is_available) ? 'checked' : '' }}
                                   class="checkbox checkbox-primary" />
                            <span class="label-text font-semibold">Tersedia untuk Disewa</span>
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="space-y-2 mt-6">
                    <button type="submit" class="btn btn-primary w-full gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.equipment.index') }}" class="btn btn-ghost w-full">
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

    categorySelect.addEventListener('change', function() {
        if (this.value === 'paket') {
            unitSection.style.display = 'block';
            operatorPriceSection.style.display = 'block';
        } else {
            unitSection.style.display = 'none';
            operatorPriceSection.style.display = 'none';
        }
    });

    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                photoPreview.innerHTML = `<img src="${event.target.result}" class="w-full h-40 object-cover" />`;
            };
            reader.readAsDataURL(file);
        }
    });

    let unitCount = {{ $equipment->units->count() }};
    function addUnit() {
        const container = document.getElementById('units-container');
        unitCount++;
        const unitHTML = `
            <div class="border rounded p-4 space-y-3 unit-item">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="font-semibold text-gray-700">Unit ${unitCount}</h4>
                    <button type="button" onclick="this.closest('.unit-item').remove()" class="btn btn-sm btn-ghost">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-sm">Nama Unit</span>
                    </label>
                    <input type="text" name="units[${unitCount}][unit_name]" placeholder="Nama unit" 
                           class="input input-bordered input-sm" />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-sm">Jumlah</span>
                    </label>
                    <input type="number" name="units[${unitCount}][quantity]" placeholder="1" 
                           class="input input-bordered input-sm" value="1" min="1" />
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-sm">Deskripsi</span>
                    </label>
                    <input type="text" name="units[${unitCount}][description]" placeholder="Deskripsi unit (opsional)" 
                           class="input input-bordered input-sm" />
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', unitHTML);
    }
</script>
@endsection

@extends('layouts.admin')

@section('title', isset($band) ? 'Edit Band' : 'Tambah Band Baru')

@section('header', isset($band) ? 'Edit Band' : 'Tambah Band Baru')

@section('breadcrumb', 'Persewaan Band')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            {{ isset($band) ? 'Edit Band' : 'Tambah Band Baru' }}
        </h1>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
    <div class="alert alert-error mb-6 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <h3 class="font-bold">Ada kesalahan!</h3>
            <div class="text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ isset($band) ? route('admin.bands.update', $band) : route('admin.bands.store') }}" 
              method="POST" 
              enctype="multipart/form-data">
            @csrf
            @if(isset($band))
                @method('PUT')
            @endif

            <!-- Band Name -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Band *</span>
                </label>
                <input 
                    type="text" 
                    name="band_name" 
                    value="{{ old('band_name', $band->band_name ?? '') }}"
                    placeholder="Nama band..." 
                    class="input input-bordered @error('band_name') input-error @enderror"
                    required
                >
                @error('band_name')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Deskripsi *</span>
                </label>
                <textarea 
                    name="description" 
                    placeholder="Deskripsi band..." 
                    rows="4"
                    class="textarea textarea-bordered @error('description') textarea-error @enderror"
                    required
                >{{ old('description', $band->description ?? '') }}</textarea>
                @error('description')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <!-- Price Section -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Price Per Hour -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Harga Per Jam (Rp) *</span>
                    </label>
                    <input 
                        type="number" 
                        name="price_per_hour" 
                        value="{{ old('price_per_hour', $band->price_per_hour ?? '') }}"
                        placeholder="0" 
                        class="input input-bordered @error('price_per_hour') input-error @enderror"
                        required
                        min="0"
                        step="1000"
                    >
                    @error('price_per_hour')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Price Per Event -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Harga Per Event (Rp) *</span>
                    </label>
                    <input 
                        type="number" 
                        name="price_per_event" 
                        value="{{ old('price_per_event', $band->price_per_event ?? '') }}"
                        placeholder="0" 
                        class="input input-bordered @error('price_per_event') input-error @enderror"
                        required
                        min="0"
                        step="1000"
                    >
                    @error('price_per_event')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>
            </div>

            <!-- Photo -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Foto Band</span>
                </label>
                <div class="flex gap-4">
                    @if(isset($band) && $band->photo)
                    <div>
                        <img src="{{ asset('storage/' . $band->photo) }}" alt="{{ $band->band_name }}" 
                             class="w-32 h-32 rounded-lg object-cover">
                        <p class="text-sm text-gray-500 mt-2">Foto saat ini</p>
                    </div>
                    @endif
                    <div class="flex-1">
                        <input 
                            type="file" 
                            name="photo" 
                            accept="image/*"
                            class="file-input file-input-bordered w-full @error('photo') file-input-error @enderror"
                        >
                        <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG, GIF. Max: 2MB</p>
                        @error('photo')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Availability -->
            <div class="form-control mb-6">
                <label class="cursor-pointer label">
                    <span class="label-text font-semibold">Tersedia untuk disewa</span>
                    <input 
                        type="hidden" 
                        name="is_available" 
                        value="0"
                    >
                    <input 
                        type="checkbox" 
                        name="is_available" 
                        value="1"
                        class="checkbox"
                        {{ old('is_available', $band->is_available ?? true) ? 'checked' : '' }}
                    >
                </label>
            </div>

            <!-- Social Media Section -->
            <div class="divider my-4">Media Sosial (Opsional)</div>

            <!-- WhatsApp Number -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nomor WhatsApp</span>
                </label>
                <input 
                    type="tel" 
                    name="whatsapp_number" 
                    value="{{ old('whatsapp_number', $band->whatsapp_number ?? '') }}"
                    placeholder="62812345678 (format tanpa +)" 
                    class="input input-bordered @error('whatsapp_number') input-error @enderror"
                >
                <p class="text-sm text-gray-500 mt-1">Contoh: 62812345678</p>
                @error('whatsapp_number')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <!-- Instagram Username -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Username Instagram</span>
                </label>
                <div class="input-group">
                    <span class="bg-gray-100">@</span>
                    <input 
                        type="text" 
                        name="instagram_username" 
                        value="{{ old('instagram_username', $band->instagram_username ?? '') }}"
                        placeholder="nama_band" 
                        class="input input-bordered flex-1 @error('instagram_username') input-error @enderror"
                    >
                </div>
                @error('instagram_username')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <!-- TikTok Username -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Username TikTok</span>
                </label>
                <div class="input-group">
                    <span class="bg-gray-100">@</span>
                    <input 
                        type="text" 
                        name="tiktok_username" 
                        value="{{ old('tiktok_username', $band->tiktok_username ?? '') }}"
                        placeholder="nama_band" 
                        class="input input-bordered flex-1 @error('tiktok_username') input-error @enderror"
                    >
                </div>
                @error('tiktok_username')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <!-- YouTube URL -->
            <div class="form-control mb-6">
                <label class="label">
                    <span class="label-text font-semibold">URL Channel YouTube</span>
                </label>
                <input 
                    type="url" 
                    name="youtube_url" 
                    value="{{ old('youtube_url', $band->youtube_url ?? '') }}"
                    placeholder="https://www.youtube.com/@nama_band" 
                    class="input input-bordered @error('youtube_url') input-error @enderror"
                >
                <p class="text-sm text-gray-500 mt-1">Contoh: https://www.youtube.com/@nama_band</p>
                @error('youtube_url')
                    <label class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </label>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> 
                    {{ isset($band) ? 'Simpan Perubahan' : 'Tambah Band' }}
                </button>
                <a href="{{ route('admin.bands.index') }}" class="btn btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

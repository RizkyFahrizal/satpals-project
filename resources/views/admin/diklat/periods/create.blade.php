@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.diklat.periods.index') }}" class="btn btn-ghost">← Kembali</a>
    </div>

    <h1 class="text-3xl font-bold mb-6">Buat Periode Diklat Baru</h1>

    <div class="card bg-base-100 shadow-xl max-w-2xl">
        <div class="card-body">
            <form action="{{ route('admin.diklat.periods.store') }}" method="POST">
                @csrf

                <!-- Nama Periode -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nama Periode</span>
                    </label>
                    <input type="text" name="nama_periode" class="input input-bordered" 
                           placeholder="Contoh: Diklat Kesenian 2024/2025" 
                           value="{{ old('nama_periode') }}" required>
                    @error('nama_periode')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Tahun Masuk -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Tahun Masuk (Angkatan)</span>
                    </label>
                    <input type="number" name="tahun_masuk" class="input input-bordered" 
                           placeholder="Contoh: 2024" min="2020" max="{{ date('Y') + 10 }}"
                           value="{{ old('tahun_masuk', date('Y')) }}" required>
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Tahun masuk peserta yang boleh mendaftar</span>
                    </label>
                    @error('tahun_masuk')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Nomor Rekening -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nomor Rekening</span>
                    </label>
                    <input type="text" name="rekening_number" class="input input-bordered" 
                           placeholder="Contoh: 1234567890123456" 
                           value="{{ old('rekening_number') }}" required>
                    @error('rekening_number')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Info Rekening -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Informasi Rekening</span>
                    </label>
                    <textarea name="rekening_info" class="textarea textarea-bordered h-24" 
                              placeholder="Contoh: Bank: BCA&#10;Atas Nama: Satya Palapa&#10;Nomor: 1234567890" 
                              required>{{ old('rekening_info') }}</textarea>
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Informasi lengkap yang akan ditampilkan di form publik</span>
                    </label>
                    @error('rekening_info')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="form-control mb-6">
                    <label class="label">
                        <span class="label-text font-semibold">Keterangan (Opsional)</span>
                    </label>
                    <textarea name="keterangan" class="textarea textarea-bordered h-20" 
                              placeholder="Deskripsi periode atau catatan penting">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="form-control flex-row gap-2 justify-end">
                    <a href="{{ route('admin.diklat.periods.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Periode</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.diklat.periods.index') }}" class="btn btn-ghost">← Kembali</a>
    </div>

    <h1 class="text-3xl font-bold mb-6">Edit Periode Diklat</h1>

    <div class="card bg-base-100 shadow-xl max-w-2xl">
        <div class="card-body">
            <form action="{{ route('admin.diklat.periods.update', $period) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Nama Periode -->
                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text font-semibold">Nama Periode</span>
                    </label>
                    <input type="text" name="nama_periode" class="input input-bordered" 
                           value="{{ old('nama_periode', $period->nama_periode) }}" required>
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
                           value="{{ old('tahun_masuk', $period->tahun_masuk) }}" 
                           min="2020" max="{{ date('Y') + 10 }}" required>
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
                           value="{{ old('rekening_number', $period->rekening_number) }}" required>
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
                    <textarea name="rekening_info" class="textarea textarea-bordered h-24" required>{{ old('rekening_info', $period->rekening_info) }}</textarea>
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
                    <textarea name="keterangan" class="textarea textarea-bordered h-20">{{ old('keterangan', $period->keterangan) }}</textarea>
                    @error('keterangan')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Status Info -->
                <div class="alert mb-6">
                    <div>
                        <svg class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>
                            Status saat ini: <span class="badge {{ $period->status_badge }}">{{ $period->status_label }}</span>
                            <br>
                            <small>Gunakan tombol "Buka/Tutup" di halaman daftar periode untuk mengubah status</small>
                        </span>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="form-control flex-row gap-2 justify-end">
                    <a href="{{ route('admin.diklat.periods.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

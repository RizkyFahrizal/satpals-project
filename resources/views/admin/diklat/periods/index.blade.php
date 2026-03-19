@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Kelola Periode Diklat</h1>
        <a href="{{ route('admin.diklat.periods.create') }}" class="btn btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Buat Periode Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-lg mb-6">
            <div>
                <svg class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error shadow-lg mb-6">
            <div>
                <svg class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2"></path>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Nama Periode</th>
                    <th>Tahun Masuk</th>
                    <th>Nomor Rekening</th>
                    <th>Status</th>
                    <th>Tanggal Buka</th>
                    <th>Tanggal Tutup</th>
                    <th>Peserta</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($periods as $period)
                    <tr>
                        <td class="font-semibold">{{ $period->nama_periode }}</td>
                        <td>{{ $period->tahun_masuk }}</td>
                        <td class="font-mono text-sm">{{ $period->rekening_number }}</td>
                        <td>
                            <span class="badge {{ $period->status_badge }}">
                                {{ $period->status_label }}
                            </span>
                        </td>
                        <td>{{ $period->tanggal_buka?->format('d M Y') ?? '-' }}</td>
                        <td>{{ $period->tanggal_tutup?->format('d M Y') ?? '-' }}</td>
                        <td>
                            <span class="badge badge-info">
                                {{ $period->registrations()->count() }}
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                <!-- Toggle Button -->
                                <form action="{{ route('admin.diklat.periods.toggle', $period) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-xs {{ $period->is_open ? 'btn-warning' : 'btn-success' }}" 
                                            onclick="return confirm('{{ $period->is_open ? 'Tutup' : 'Buka' }} periode ini?')">
                                        {{ $period->is_open ? '🔒' : '🔓' }}
                                    </button>
                                </form>

                                <!-- Edit Button -->
                                <a href="{{ route('admin.diklat.periods.edit', $period) }}" class="btn btn-xs btn-info">
                                    ✏️
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.diklat.periods.destroy', $period) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-error" 
                                            onclick="return confirm('Hapus periode ini? Data registrasi tidak akan terhapus.')">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500">
                            Belum ada periode diklat. <a href="{{ route('admin.diklat.periods.create') }}" class="link">Buat sekarang</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $periods->links() }}
    </div>
</div>
@endsection

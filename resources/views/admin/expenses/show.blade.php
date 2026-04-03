@extends('layouts.admin')

@section('title', 'Detail Pengeluaran - Admin')
@section('header', 'Detail Pengeluaran')
@section('breadcrumb', 'Detail Pengeluaran')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Status & Actions -->
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $expense->title }}</h1>
                <p class="text-gray-600 text-sm mt-1">Dibuat oleh: {{ $expense->creator->name }}</p>
            </div>
            <span class="badge badge-lg {{ $expense->status === 'pending' ? 'badge-warning' : ($expense->status === 'approved' ? 'badge-success' : 'badge-error') }}">
                {{ ucfirst($expense->status) }}
            </span>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-600">Jenis Pengeluaran</p>
                <p class="text-lg font-semibold text-gray-800">{{ ucfirst($expense->type) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Nominal</p>
                <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Dibuat</p>
                <p class="text-lg font-semibold text-gray-800">{{ $expense->created_at->format('d M Y H:i') }}</p>
            </div>
            @if($expense->approved_at)
                <div>
                    <p class="text-sm text-gray-600">Tanggal Disetujui</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $expense->approved_at->format('d M Y H:i') }}</p>
                </div>
            @endif
        </div>

        <!-- Description -->
        <div class="mb-6 pb-6 border-b border-gray-200">
            <p class="text-sm text-gray-600 mb-2">Deskripsi</p>
            <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $expense->description }}</p>
        </div>

        <!-- Action Buttons -->
        @if($expense->status === 'pending')
            <div class="flex flex-wrap gap-2">
                <button class="btn btn-sm btn-success" onclick="approveModal.showModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Setujui
                </button>
                <button class="btn btn-sm btn-error" onclick="rejectModal.showModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Tolak
                </button>
                <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-sm btn-outline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-ghost text-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        @elseif($expense->status === 'approved')
            <div class="flex flex-wrap gap-2">
                <form action="{{ route('admin.expenses.archive', $expense) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9-4v4m0 0v4" />
                        </svg>
                        Arsipkan
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Documents -->
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Dokumen Pendukung</h3>
        
        @if($expense->documents->count() > 0)
            <div class="space-y-3">
                @foreach($expense->documents as $doc)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="font-medium text-gray-800">{{ $doc->original_name }}</p>
                                <p class="text-xs text-gray-500">{{ $doc->document_type }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-xs btn-ghost">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @if($expense->status === 'pending')
                                <form action="{{ route('admin.expenses.delete-document', $doc) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus dokumen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-ghost text-error">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">Tidak ada dokumen pendukung</p>
        @endif
    </div>

    <!-- Approvals History -->
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Approval</h3>
        
        @if($expense->approvals->count() > 0)
            <div class="space-y-3">
                @foreach($expense->approvals as $approval)
                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-medium text-gray-800">{{ $approval->approver->name }}</p>
                            <span class="badge {{ $approval->approval_status === 'approved' ? 'badge-success' : 'badge-error' }}">
                                {{ ucfirst($approval->approval_status) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mb-2">{{ $approval->created_at->format('d M Y H:i') }}</p>
                        @if($approval->notes)
                            <p class="text-sm text-gray-700">{{ $approval->notes }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-8">Belum ada approval</p>
        @endif
    </div>

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline">Kembali</a>
    </div>
</div>

<!-- Approve Modal -->
<dialog id="approveModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Setujui Pengeluaran</h3>
        <form action="{{ route('admin.expenses.approve', $expense) }}" method="POST">
            @csrf
            <div class="py-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                <textarea name="notes" class="textarea textarea-bordered w-full" rows="3"></textarea>
            </div>
            <div class="modal-action">
                <button type="submit" class="btn btn-success">Setujui</button>
                <button type="button" class="btn" onclick="approveModal.close()">Batal</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Reject Modal -->
<dialog id="rejectModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Tolak Pengeluaran</h3>
        <form action="{{ route('admin.expenses.reject', $expense) }}" method="POST">
            @csrf
            <div class="py-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan</label>
                <textarea name="rejection_reason" class="textarea textarea-bordered w-full" rows="3" required></textarea>
            </div>
            <div class="modal-action">
                <button type="submit" class="btn btn-error">Tolak</button>
                <button type="button" class="btn" onclick="rejectModal.close()">Batal</button>
            </div>
        </form>
    </div>
</dialog>
@endsection

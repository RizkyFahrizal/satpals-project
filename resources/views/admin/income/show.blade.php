@extends('layouts.admin')

@section('title', 'Detail Pemasukan - Admin')
@section('header', 'Detail Pemasukan')
@section('breadcrumb', 'Detail Pemasukan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
        <!-- Header -->
        <div class="mb-8 pb-6 border-b border-gray-200">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $income->title }}</h1>
                    <p class="text-gray-600 text-sm mt-1">Dibuat oleh: {{ $income->creator->name }}</p>
                </div>
                <span class="badge badge-lg {{ $income->status === 'pending' ? 'badge-warning' : ($income->status === 'approved' ? 'badge-success' : 'badge-error') }}">
                    {{ ucfirst($income->status) }}
                </span>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div>
                <p class="text-sm text-gray-600">Sumber Pemasukan</p>
                <p class="text-lg font-semibold text-gray-800">{{ $income->source ?? 'Tidak ada' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal Pemasukan</p>
                <p class="text-lg font-semibold text-gray-800">{{ $income->income_date?->format('d M Y') ?? 'Tidak ada' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Nominal</p>
                <p class="text-lg font-semibold text-gray-800">Rp {{ number_format($income->nominal, 0, ',', '.') }}</p>
            </div>
            @if($income->approved_at)
                <div>
                    <p class="text-sm text-gray-600">Tanggal Disetujui</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $income->approved_at->format('d M Y H:i') }}</p>
                </div>
            @endif
        </div>

        <!-- Description -->
        @if($income->description)
            <div class="mb-8 pb-6 border-b border-gray-200">
                <p class="text-sm text-gray-600 mb-2">Deskripsi</p>
                <p class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $income->description }}</p>
            </div>
        @endif

        <!-- Documents Section -->
        @if($income->documents && $income->documents->count() > 0)
        <div class="mb-8 pb-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Dokumen ({{ $income->documents->count() }})
            </h3>
            <div class="space-y-2">
                @foreach($income->documents as $doc)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-200 hover:bg-gray-100 transition">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700">{{ $doc->original_name }}</p>
                        <p class="text-xs text-gray-500">{{ $doc->document_type ?? 'Dokumen' }} • {{ $doc->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" 
                           class="btn btn-xs btn-ghost" title="Download">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                        </a>
                        @if($canDelete)
                            <form action="{{ route('admin.income.delete-document', $doc) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-ghost text-red-600 hover:bg-red-50"
                                        onclick="return confirm('Hapus dokumen ini?')" title="Hapus">
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
        </div>
        @endif

        <!-- Action Buttons - Top -->
        @if($income->status === 'pending')
            @if(!$canManage)
                <div class="alert alert-warning mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-12a9 9 0 110 18 9 9 0 010-18z" />
                    </svg>
                    <span>Anda tidak memiliki otorisasi untuk melakukan aksi pada pemasukan ini. Hanya Admin, Ketua Umum, Wakil Ketua Umum, atau Bendahara yang dapat melakukan aksi.</span>
                </div>
            @else
                <div class="flex flex-wrap gap-2 mb-6">
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
                    <a href="{{ route('admin.income.edit', $income) }}" class="btn btn-sm btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('admin.income.destroy', $income) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
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
            @endif
        @elseif($income->status === 'approved')
            <div class="flex flex-wrap gap-2 mb-6">
                @if($canManage)
                    <div class="alert alert-info">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Pemasukan ini telah disetujui.</span>
                    </div>
                @endif
            </div>
        @endif

        <!-- Approval History -->
        <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Approval</h3>
            
            @if($income->approvals && $income->approvals->count() > 0)
                <div class="space-y-3">
                    @foreach($income->approvals as $approval)
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

        <!-- Action Buttons - Bottom -->
            <a href="{{ route('admin.financial.index') }}" class="btn btn-sm btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<dialog id="approveModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Setujui Pemasukan</h3>
        <form action="{{ route('admin.income.approve', $income) }}" method="POST">
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
        <h3 class="font-bold text-lg">Tolak Pemasukan</h3>
        <form action="{{ route('admin.income.reject', $income) }}" method="POST">
            @csrf
            <div class="py-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea id="incomRejectionReason" name="rejection_reason" class="textarea textarea-bordered w-full" rows="3" required placeholder="Tulis alasan penolakan minimal 10 karakter..." oninput="validateIncomeRejectLength()"></textarea>
                
                <!-- Character count alert -->
                <div id="incomeRejectAlert" class="alert alert-warning mt-3 hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-12a9 9 0 110 18 9 9 0 010-18z" />
                    </svg>
                    <span>Alasan penolakan harus minimal 10 karakter. Saat ini: <strong id="incomeCharCount">0</strong>/10</span>
                </div>
            </div>
            <div class="modal-action">
                <button id="incomeRejectBtn" type="submit" class="btn btn-error" disabled>Tolak</button>
                <button type="button" class="btn" onclick="rejectModal.close()">Batal</button>
            </div>
        </form>
    </div>
</dialog>

<script>
function validateIncomeRejectLength() {
    const textarea = document.getElementById('incomRejectionReason');
    const alert = document.getElementById('incomeRejectAlert');
    const charCount = document.getElementById('incomeCharCount');
    const submitBtn = document.getElementById('incomeRejectBtn');
    const length = textarea.value.trim().length;
    
    charCount.textContent = length;
    
    if (length < 10) {
        alert.classList.remove('hidden');
        submitBtn.disabled = true;
    } else {
        alert.classList.add('hidden');
        submitBtn.disabled = false;
    }
}
</script>
@endsection

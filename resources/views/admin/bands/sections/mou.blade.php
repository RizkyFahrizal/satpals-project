<!-- MoU Section -->
<div class="space-y-6">
    <h3 class="text-lg font-bold text-gray-900">Memorandum of Understanding (MoU)</h3>

    @if($band->mou)
    <!-- Existing MoU -->
    <div class="bg-white rounded-2xl shadow-md border-l-4 border-yellow-400 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- MoU Info -->
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-yellow-600"></i>
                    Informasi MoU
                </h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Status</p>
                        <div class="flex gap-2 items-center">
                            @if($band->mou->status === 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-green-100 text-green-700 text-xs font-semibold border border-green-300">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-semibold border border-red-300">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Tidak Aktif
                                </span>
                            @endif
                            <form action="{{ route('admin.bands.mou.toggle', $band) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="flex items-center justify-center w-7 h-7 text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition">
                                    <i class="fas fa-sync text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @if($band->mou->effective_date)
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Tanggal Berlaku</p>
                        <p class="text-gray-900 font-medium">{{ $band->mou->effective_date->format('d M Y') }}</p>
                    </div>
                    @endif
                    @if($band->mou->expiry_date)
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase mb-1">Tanggal Kadaluarsa</p>
                        <p class="text-gray-900 font-medium">{{ $band->mou->expiry_date->format('d M Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- MoU Document -->
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                <h4 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-file text-yellow-600"></i>
                    Dokumen
                </h4>
                @if($band->mou->mou_document)
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center border border-red-200">
                            <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-gray-900 text-sm truncate">{{ basename($band->mou->mou_document) }}</p>
                            <p class="text-xs text-gray-500">File MoU</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $band->mou->mou_document) }}" 
                       target="_blank"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-yellow-400 text-gray-900 rounded-lg hover:bg-yellow-500 transition font-semibold text-sm">
                        <i class="fas fa-download"></i>
                        Download
                    </a>
                </div>
                @else
                <div class="bg-white rounded-lg p-6 text-center border border-gray-200">
                    <i class="fas fa-file-alt text-gray-300 text-3xl mb-2 block"></i>
                    <p class="text-sm text-gray-500">Belum ada dokumen</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Description -->
        @if($band->mou->mou_description)
        <div class="bg-blue-50 rounded-xl p-5 border border-blue-200 mb-6">
            <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="fas fa-sticky-note text-blue-600"></i>
                Catatan MoU
            </h4>
            <p class="text-gray-700 text-sm whitespace-pre-line">{{ $band->mou->mou_description }}</p>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-2 pt-6 border-t border-gray-200">
            <button onclick="showMOUModal()" class="flex items-center gap-2 px-4 py-2.5 bg-yellow-50 text-yellow-600 rounded-xl border border-yellow-200 hover:bg-yellow-100 transition font-semibold text-sm">
                <i class="fas fa-edit"></i>
                Edit/Upload
            </button>
            <form action="{{ route('admin.bands.mou.delete', $band) }}" method="POST" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus MoU ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 rounded-xl border border-red-200 hover:bg-red-100 transition font-semibold text-sm">
                    <i class="fas fa-trash"></i>
                    Hapus
                </button>
            </form>
        </div>
    </div>
    @else
    <!-- No MoU -->
    <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
        <i class="fas fa-file-contract text-5xl text-yellow-200 mb-4 block"></i>
        <p class="text-gray-600 font-medium mb-6">Belum ada MoU</p>
        <button onclick="showMOUModal()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition">
            <i class="fas fa-plus"></i>
            Upload MoU
        </button>
    </div>
    @endif
</div>

<!-- MoU Modal -->
<dialog id="mouModal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-6">{{ $band->mou ? 'Edit/Upload' : 'Upload' }} MoU</h3>
        
        <form action="{{ route('admin.bands.mou.store', $band) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Status -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 bg-white focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition">
                    <option value="active" {{ old('status', $band->mou?->status ?? 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $band->mou?->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <!-- Effective Date -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Berlaku</label>
                <input 
                    type="date" 
                    name="effective_date" 
                    value="{{ old('effective_date', $band->mou?->effective_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition"
                >
            </div>

            <!-- Expiry Date -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kadaluarsa</label>
                <input 
                    type="date" 
                    name="expiry_date" 
                    value="{{ old('expiry_date', $band->mou?->expiry_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition"
                >
            </div>

            <!-- Document Upload -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">File MoU (PDF, DOC, DOCX)</label>
                <input 
                    type="file" 
                    name="mou_document" 
                    accept=".pdf,.doc,.docx"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 file:bg-yellow-50 file:border-none file:rounded-lg file:px-3 file:py-1 file:text-yellow-700 file:font-semibold focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition"
                >
                <p class="text-xs text-gray-500 mt-2">Max: 5MB</p>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan MoU</label>
                <textarea 
                    name="mou_description" 
                    placeholder="Catatan atau deskripsi MoU..." 
                    rows="4"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition"
                >{{ old('mou_description', $band->mou?->mou_description) }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-2 justify-end">
                <form method="dialog">
                    <button class="px-6 py-2.5 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition font-semibold">Batal</button>
                </form>
                <button type="submit" class="px-6 py-2.5 bg-yellow-400 text-gray-900 rounded-xl shadow-md hover:bg-yellow-500 transition font-semibold">Simpan MoU</button>
            </div>
        </form>
    </div>
</dialog>

<script>
function showMOUModal() {
    document.getElementById('mouModal').showModal();
}
</script>

<!-- MoU Section -->
<div class="space-y-4">
    <h3 class="text-xl font-bold text-gray-800">Memorandum of Understanding (MoU)</h3>

    @if($band->mou)
    <!-- Existing MoU -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-primary">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- MoU Info -->
            <div>
                <h4 class="font-semibold text-gray-800 mb-4">Informasi MoU</h4>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <div class="flex gap-2 items-center mt-1">
                            @if($band->mou->status === 'active')
                                <span class="badge badge-success badge-outline">Aktif</span>
                            @else
                                <span class="badge badge-error badge-outline">Tidak Aktif</span>
                            @endif
                            <form action="{{ route('admin.bands.mou.toggle', $band) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-xs btn-ghost">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @if($band->mou->effective_date)
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Berlaku</p>
                        <p class="text-gray-800 font-medium">{{ $band->mou->effective_date->format('d M Y') }}</p>
                    </div>
                    @endif
                    @if($band->mou->expiry_date)
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Kadaluarsa</p>
                        <p class="text-gray-800 font-medium">{{ $band->mou->expiry_date->format('d M Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- MoU Document -->
            <div>
                <h4 class="font-semibold text-gray-800 mb-4">Dokumen</h4>
                @if($band->mou->mou_document)
                <div class="bg-gray-50 rounded p-4 border border-gray-200">
                    <div class="flex items-center gap-3 mb-3">
                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                        <div>
                            <p class="font-medium text-gray-800">{{ basename($band->mou->mou_document) }}</p>
                            <p class="text-xs text-gray-500">File MoU</p>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $band->mou->mou_document) }}" 
                       target="_blank"
                       class="btn btn-sm btn-outline w-full">
                        <i class="fas fa-download mr-2"></i> Download
                    </a>
                </div>
                @else
                <div class="bg-gray-100 rounded p-4 text-center">
                    <i class="fas fa-file-alt text-gray-400 text-3xl mb-2 block"></i>
                    <p class="text-sm text-gray-500">Belum ada dokumen</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Description -->
        @if($band->mou->mou_description)
        <div class="mb-6 pb-6 border-b">
            <h4 class="font-semibold text-gray-800 mb-2">Catatan MoU</h4>
            <p class="text-gray-600 text-sm whitespace-pre-line">{{ $band->mou->mou_description }}</p>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex gap-2 pt-4">
            <button onclick="showMOUModal()" class="btn btn-warning btn-sm">
                <i class="fas fa-edit mr-2"></i> Edit/Upload MoU
            </button>
            <form action="{{ route('admin.bands.mou.delete', $band) }}" method="POST" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus MoU ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error btn-sm">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>
    @else
    <!-- No MoU -->
    <div class="bg-gray-100 rounded-lg p-8 text-center">
        <i class="fas fa-file-contract text-4xl text-gray-300 mb-3 block"></i>
        <p class="text-gray-500">Belum ada MoU</p>
        <button onclick="showMOUModal()" class="btn btn-primary btn-sm mt-4">
            <i class="fas fa-plus mr-2"></i> Upload MoU
        </button>
    </div>
    @endif
</div>

<!-- MoU Modal -->
<dialog id="mouModal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4">{{ $band->mou ? 'Edit/Upload' : 'Upload' }} MoU</h3>
        
        <form action="{{ route('admin.bands.mou.store', $band) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Status -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Status</span>
                </label>
                <select name="status" class="select select-bordered">
                    <option value="active" {{ old('status', $band->mou?->status ?? 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $band->mou?->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <!-- Effective Date -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Tanggal Berlaku</span>
                </label>
                <input 
                    type="date" 
                    name="effective_date" 
                    value="{{ old('effective_date', $band->mou?->effective_date?->format('Y-m-d')) }}"
                    class="input input-bordered"
                >
            </div>

            <!-- Expiry Date -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Tanggal Kadaluarsa</span>
                </label>
                <input 
                    type="date" 
                    name="expiry_date" 
                    value="{{ old('expiry_date', $band->mou?->expiry_date?->format('Y-m-d')) }}"
                    class="input input-bordered"
                >
            </div>

            <!-- Document Upload -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">File MoU (PDF, DOC, DOCX)</span>
                </label>
                <input 
                    type="file" 
                    name="mou_document" 
                    accept=".pdf,.doc,.docx"
                    class="file-input file-input-bordered w-full"
                >
                <p class="text-sm text-gray-500 mt-2">Max: 5MB</p>
            </div>

            <!-- Description -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Catatan MoU</span>
                </label>
                <textarea 
                    name="mou_description" 
                    placeholder="Catatan atau deskripsi MoU..." 
                    rows="4"
                    class="textarea textarea-bordered"
                >{{ old('mou_description', $band->mou?->mou_description) }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Batal</button>
                </form>
                <button type="submit" class="btn btn-primary">Simpan MoU</button>
            </div>
        </form>
    </div>
</dialog>

<script>
function showMOUModal() {
    document.getElementById('mouModal').showModal();
}
</script>

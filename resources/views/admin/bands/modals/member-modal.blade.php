<!-- Member Modal -->
<dialog id="memberModal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4"><span id="memberTitle">Tambah</span> Personil Band</h3>
        
        <form id="memberForm" action="{{ route('admin.bands.members.store', $band) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="memberId" name="member_id">
            <input type="hidden" id="memberMethod" name="_method" value="POST">
            
            <!-- Member Name -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Nama Personil *</span>
                </label>
                <input 
                    type="text" 
                    id="memberName"
                    name="member_name" 
                    placeholder="Nama personil..." 
                    class="input input-bordered"
                    required
                >
            </div>

            <!-- Role -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Posisi/Role *</span>
                </label>
                <input 
                    type="text" 
                    id="memberRole"
                    name="role" 
                    placeholder="Contoh: Drummer, Bassist, Vocalist, Guitarist..." 
                    class="input input-bordered"
                    required
                >
            </div>

            <!-- Bio -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Biodata</span>
                </label>
                <textarea 
                    id="memberBio"
                    name="bio" 
                    placeholder="Biodata singkat personil..." 
                    rows="3"
                    class="textarea textarea-bordered"
                ></textarea>
            </div>

            <!-- Photo -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Foto Personil</span>
                </label>
                <input 
                    type="file" 
                    id="memberPhoto"
                    name="photo" 
                    accept="image/*"
                    class="file-input file-input-bordered w-full"
                >
                <p class="text-sm text-gray-500 mt-2">Format: JPG, PNG, GIF. Max: 2MB</p>
            </div>

            <!-- Buttons -->
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Batal</button>
                </form>
                <button type="submit" class="btn btn-primary">Simpan Personil</button>
            </div>
        </form>
    </div>
</dialog>

<script>
function editMember(id, name, role, bio) {
    document.getElementById('memberTitle').textContent = 'Edit';
    document.getElementById('memberId').value = id;
    document.getElementById('memberName').value = name;
    document.getElementById('memberRole').value = role;
    document.getElementById('memberBio').value = bio || '';
    document.getElementById('memberMethod').value = 'PUT';
    
    const form = document.getElementById('memberForm');
    form.action = '{{ route("admin.bands.members.update", [$band, ":id"]) }}'.replace(':id', id);
    
    document.getElementById('memberModal').showModal();
}

// Reset form when opening for new member
function openMemberModal() {
    document.getElementById('memberTitle').textContent = 'Tambah';
    document.getElementById('memberForm').reset();
    document.getElementById('memberMethod').value = 'POST';
    document.getElementById('memberForm').action = '{{ route("admin.bands.members.store", $band) }}';
    document.getElementById('memberModal').showModal();
}
</script>

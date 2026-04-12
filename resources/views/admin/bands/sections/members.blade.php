<!-- Members Section -->
<div class="space-y-4">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-800">Daftar Personil Band</h3>
        <button onclick="openMemberModal()" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-2"></i> Tambah Personil
        </button>
    </div>

    @if($band->members->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($band->members as $member)
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-primary transition">
            <div class="flex gap-4">
                @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->member_name }}" 
                         class="w-20 h-20 rounded-lg object-cover">
                @else
                    <div class="w-20 h-20 rounded-lg bg-gray-300 flex items-center justify-center">
                        <i class="fas fa-user text-gray-500 text-2xl"></i>
                    </div>
                @endif
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-800">{{ $member->member_name }}</h4>
                    <p class="text-sm text-primary font-medium">{{ $member->role }}</p>
                    @if($member->bio)
                        <p class="text-sm text-gray-600 mt-2">{{ $member->bio }}</p>
                    @endif
                </div>
            </div>
            <div class="flex gap-2 mt-4 pt-4 border-t">
                <button onclick="editMember('{{ $member->id }}', '{{ $member->member_name }}', '{{ $member->role }}', '{{ $member->bio }}')" 
                        class="btn btn-sm btn-warning flex-1">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <form action="{{ route('admin.bands.members.delete', [$band, $member]) }}" method="POST" class="flex-1"
                      onsubmit="return confirm('Apakah Anda yakin?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-error w-full">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-gray-100 rounded-lg p-8 text-center">
        <i class="fas fa-user-slash text-4xl text-gray-300 mb-3 block"></i>
        <p class="text-gray-500">Belum ada personil</p>
        <button onclick="openMemberModal()" class="btn btn-primary btn-sm mt-4">
            Tambah Personil
        </button>
    </div>
    @endif
</div>

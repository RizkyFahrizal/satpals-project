<!-- Members Section -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Daftar Personil Band</h3>
        <button onclick="openMemberModal()" class="flex items-center gap-2 px-4 py-2 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition text-sm">
            <i class="fas fa-plus"></i>
            <span>Tambah Personil</span>
        </button>
    </div>

    @if($band->members->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($band->members as $member)
        <div class="bg-white rounded-2xl p-4 border border-gray-200 hover:border-yellow-300 hover:shadow-md transition">
            <div class="flex gap-4 mb-4">
                @if($member->photo)
                    <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->member_name }}" 
                         class="w-16 h-16 rounded-xl object-cover shadow-sm">
                @else
                    <div class="w-16 h-16 rounded-xl bg-yellow-50 flex items-center justify-center border border-yellow-200">
                        <i class="fas fa-user text-yellow-600 text-xl"></i>
                    </div>
                @endif
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-900">{{ $member->member_name }}</h4>
                    <p class="text-sm text-yellow-600 font-medium">{{ $member->role }}</p>
                    @if($member->bio)
                        <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ $member->bio }}</p>
                    @endif
                </div>
            </div>
            <div class="flex gap-2 pt-4 border-t border-gray-100">
                <button onclick="editMember('{{ $member->id }}', '{{ $member->member_name }}', '{{ $member->role }}', '{{ $member->bio }}')" 
                        class="flex items-center justify-center flex-1 px-3 py-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition font-medium text-sm border border-yellow-200">
                    <i class="fas fa-edit mr-1"></i>
                    Edit
                </button>
                <form action="{{ route('admin.bands.members.delete', [$band, $member]) }}" method="POST" class="flex-1"
                      onsubmit="return confirm('Apakah Anda yakin?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium text-sm border border-red-200">
                        <i class="fas fa-trash mr-1"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
        <i class="fas fa-user-slash text-5xl text-yellow-200 mb-4 block"></i>
        <p class="text-gray-600 font-medium mb-6">Belum ada personil</p>
        <button onclick="openMemberModal()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition">
            <i class="fas fa-plus"></i>
            Tambah Personil
        </button>
    </div>
    @endif
</div>

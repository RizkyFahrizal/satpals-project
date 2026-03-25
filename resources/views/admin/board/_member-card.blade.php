<div class="group relative bg-gradient-to-br from-gray-50 to-white rounded-xl border border-gray-200 p-4 hover:shadow-lg transition-all duration-300">
    <!-- Status Badge -->
    @if(!$board->is_active)
    <span class="absolute top-2 right-2 px-2 py-0.5 bg-red-100 text-red-600 text-xs font-medium rounded-full">Nonaktif</span>
    @endif
    
    <div class="flex flex-col items-center text-center gap-3">
        <!-- Avatar -->
        <div class="w-14 h-14 rounded-lg bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center text-white font-bold flex-shrink-0 overflow-hidden">
            @if($board->foto)
            <img src="{{ asset('storage/' . $board->foto) }}" alt="{{ $board->member->nama_lengkap }}" class="w-full h-full object-cover">
            @elseif($board->member->foto)
            <img src="{{ asset('storage/' . $board->member->foto) }}" alt="{{ $board->member->nama_lengkap }}" class="w-full h-full object-cover">
            @else
            {{ strtoupper(substr($board->member->nama_lengkap, 0, 1)) }}
            @endif
        </div>
        
        <div class="flex-1 min-w-0">
            <p class="text-xs text-purple-600 font-semibold mb-1">{{ $board->jabatan_label }}</p>
            <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $board->member->nama_lengkap }}</h4>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="mt-3 flex justify-center gap-1">
        <button onclick="document.getElementById('editModal{{ $board->id }}').classList.remove('hidden')" 
            class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors" title="Edit">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </button>
        @if(!$board->user)
        <form action="{{ route('admin.board.create-account', $board) }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors" title="Buat Akun">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </button>
        </form>
        @endif
        <form action="{{ route('admin.board.toggle-status', $board) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="p-1.5 text-gray-500 hover:bg-gray-100 rounded transition-colors" title="{{ $board->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                @if($board->is_active)
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                @else
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                @endif
            </button>
        </form>
        <form action="{{ route('admin.board.destroy', $board) }}" method="POST" class="inline" onsubmit="return confirm('Yakin?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors" title="Hapus">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </form>
    </div>
</div>

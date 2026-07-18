<!-- Genres Section -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Genre yang Dimainkan</h3>
    </div>

    <div class="bg-white rounded-xl p-4 border border-gray-200">
        <form action="{{ route('admin.bands.genres.store', $band) }}" method="POST" class="flex gap-3">
            @csrf
            <input 
                type="text" 
                name="genre_name" 
                placeholder="Contoh: Rock, Jazz, Pop..." 
                class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition @error('genre_name') border-red-400 @enderror"
                required
            >
            <button type="submit" class="px-6 py-2.5 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition">
                <i class="fas fa-plus mr-2"></i>Tambah
            </button>
        </form>
    </div>

    @if($band->genres->count())
    <div class="space-y-3">
        @foreach($band->genres as $genre)
        <div class="bg-white rounded-xl p-4 flex justify-between items-center border border-gray-200 hover:border-yellow-300 hover:shadow-md transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center border border-yellow-200">
                    <i class="fas fa-music text-yellow-600"></i>
                </div>
                <span class="font-medium text-gray-900">{{ $genre->genre_name }}</span>
            </div>
            <form action="{{ route('admin.bands.genres.delete', [$band, $genre]) }}" method="POST" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center justify-center w-9 h-9 text-red-600 hover:bg-red-50 rounded-lg transition border border-red-200">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </form>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
        <i class="fas fa-music text-5xl text-yellow-200 mb-4 block"></i>
        <p class="text-gray-600 font-medium">Belum ada genre</p>
    </div>
    @endif

    @error('genre_name')
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            <span class="text-red-700 font-medium">{{ $message }}</span>
        </div>
    @enderror
</div>

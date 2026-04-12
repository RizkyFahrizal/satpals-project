<!-- Genres Section -->
<div class="space-y-4">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-800">Genre yang Dimainkan</h3>
    </div>

    <form action="{{ route('admin.bands.genres.store', $band) }}" method="POST" class="flex gap-2 mb-6">
        @csrf
        <input 
            type="text" 
            name="genre_name" 
            placeholder="Tambah genre baru (contoh: Rock, Jazz, Pop)..." 
            class="input input-bordered flex-1 @error('genre_name') input-error @enderror"
            required
        >
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i> Tambah
        </button>
    </form>

    @if($band->genres->count())
    <div class="space-y-2">
        @foreach($band->genres as $genre)
        <div class="bg-primary bg-opacity-10 rounded-lg p-4 flex justify-between items-center border border-primary border-opacity-30">
            <span class="text-lg font-medium text-gray-800">{{ $genre->genre_name }}</span>
            <form action="{{ route('admin.bands.genres.delete', [$band, $genre]) }}" method="POST" class="inline"
                  onsubmit="return confirm('Apakah Anda yakin?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-error">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-gray-100 rounded-lg p-8 text-center">
        <i class="fas fa-music text-4xl text-gray-300 mb-3 block"></i>
        <p class="text-gray-500">Belum ada genre</p>
    </div>
    @endif

    @error('genre_name')
        <div class="alert alert-error mt-4">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ $message }}</span>
        </div>
    @enderror
</div>

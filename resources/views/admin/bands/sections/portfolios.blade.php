<!-- Portfolios/Videos Section -->
<div class="space-y-4">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-800">Portfolio / Sample Video</h3>
        <button onclick="openPortfolioModal()" class="btn btn-primary btn-sm">
            <i class="fas fa-plus mr-2"></i> Tambah Video
        </button>
    </div>

    @if($band->portfolios->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($band->portfolios as $portfolio)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <!-- YouTube Embed -->
            @php
                $embedUrl = $portfolio->getYoutubeEmbedUrl();
            @endphp
            @if($embedUrl)
            <div class="aspect-video bg-black">
                <iframe 
                    width="100%" 
                    height="100%" 
                    src="{{ $embedUrl }}" 
                    title="{{ $portfolio->title }}"
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
            @else
            <div class="aspect-video bg-gray-200 flex items-center justify-center">
                <i class="fas fa-video text-gray-400 text-4xl"></i>
            </div>
            @endif

            <!-- Info -->
            <div class="p-4">
                <h4 class="font-semibold text-gray-800 text-lg">{{ $portfolio->title }}</h4>
                @if($portfolio->description)
                    <p class="text-sm text-gray-600 mt-2">{{ $portfolio->description }}</p>
                @endif
                <p class="text-xs text-gray-400 mt-3 break-all">{{ $portfolio->youtube_url }}</p>
                
                <div class="flex gap-2 mt-4 pt-4 border-t">
                    <button onclick="editPortfolio('{{ $portfolio->id }}', '{{ $portfolio->title }}', '{{ $portfolio->youtube_url }}', '{{ $portfolio->description }}')" 
                            class="btn btn-sm btn-warning flex-1">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <form action="{{ route('admin.bands.portfolios.delete', [$band, $portfolio]) }}" method="POST" class="flex-1"
                          onsubmit="return confirm('Apakah Anda yakin?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-error w-full">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-gray-100 rounded-lg p-8 text-center">
        <i class="fas fa-video text-4xl text-gray-300 mb-3 block"></i>
        <p class="text-gray-500">Belum ada portfolio/video</p>
        <button onclick="openPortfolioModal()" class="btn btn-primary btn-sm mt-4">
            Tambah Video
        </button>
    </div>
    @endif
</div>

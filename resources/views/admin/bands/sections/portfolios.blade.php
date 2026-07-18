<!-- Portfolios/Videos Section -->
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Portfolio / Sample Video</h3>
        <button onclick="openPortfolioModal()" class="flex items-center gap-2 px-4 py-2 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition text-sm">
            <i class="fas fa-plus"></i>
            <span>Tambah Video</span>
        </button>
    </div>

    @if($band->portfolios->count())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($band->portfolios as $portfolio)
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg hover:border-yellow-300 transition">
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
            <div class="p-5">
                <h4 class="font-semibold text-gray-900">{{ $portfolio->title }}</h4>
                @if($portfolio->description)
                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $portfolio->description }}</p>
                @endif
                <p class="text-xs text-gray-400 mt-3 break-all">{{ $portfolio->youtube_url }}</p>
                
                <div class="flex gap-2 mt-5 pt-5 border-t border-gray-100">
                    <button onclick="editPortfolio('{{ $portfolio->id }}', '{{ $portfolio->title }}', '{{ $portfolio->youtube_url }}', '{{ $portfolio->description }}')" 
                            class="flex items-center justify-center flex-1 px-3 py-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition font-medium text-sm border border-yellow-200">
                        <i class="fas fa-edit mr-1"></i>
                        Edit
                    </button>
                    <form action="{{ route('admin.bands.portfolios.delete', [$band, $portfolio]) }}" method="POST" class="flex-1"
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
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl p-12 text-center border border-gray-100">
        <i class="fas fa-video text-5xl text-yellow-200 mb-4 block"></i>
        <p class="text-gray-600 font-medium mb-6">Belum ada portfolio/video</p>
        <button onclick="openPortfolioModal()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-yellow-400 text-gray-900 rounded-xl shadow-md font-semibold hover:bg-yellow-500 transition">
            <i class="fas fa-plus"></i>
            Tambah Video
        </button>
    </div>
    @endif
</div>

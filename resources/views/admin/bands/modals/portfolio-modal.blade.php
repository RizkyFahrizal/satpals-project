<!-- Portfolio Modal -->
<dialog id="portfolioModal" class="modal">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4"><span id="portfolioTitle">Tambah</span> Portfolio/Video</h3>
        
        <form id="portfolioForm" action="{{ route('admin.bands.portfolios.store', $band) }}" method="POST">
            @csrf
            <input type="hidden" id="portfolioId" name="portfolio_id">
            <input type="hidden" id="portfolioMethod" name="_method" value="POST">
            
            <!-- Title -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Judul Portfolio *</span>
                </label>
                <input 
                    type="text" 
                    id="portfolioTitle_input"
                    name="title" 
                    placeholder="Judul portfolio/performance..." 
                    class="input input-bordered"
                    required
                >
            </div>

            <!-- YouTube URL -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">YouTube URL *</span>
                </label>
                <input 
                    type="url" 
                    id="youtubeUrl"
                    name="youtube_url" 
                    placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." 
                    class="input input-bordered"
                    required
                >
                <p class="text-sm text-gray-500 mt-2">Contoh: https://www.youtube.com/watch?v=video_id atau https://youtu.be/video_id</p>
            </div>

            <!-- Description -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Deskripsi</span>
                </label>
                <textarea 
                    id="portfolioDescription"
                    name="description" 
                    placeholder="Deskripsi singkat portfolio..." 
                    rows="3"
                    class="textarea textarea-bordered"
                ></textarea>
            </div>

            <!-- Preview -->
            <div class="form-control mb-4">
                <label class="label">
                    <span class="label-text font-semibold">Preview</span>
                </label>
                <div id="youtubePreview" class="w-full aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                    <p class="text-gray-400">Preview akan muncul di sini</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Batal</button>
                </form>
                <button type="submit" class="btn btn-primary">Simpan Portfolio</button>
            </div>
        </form>
    </div>
</dialog>

<script>
function openPortfolioModal() {
    document.getElementById('portfolioTitle').textContent = 'Tambah';
    document.getElementById('portfolioId').value = '';
    document.getElementById('portfolioTitle_input').value = '';
    document.getElementById('youtubeUrl').value = '';
    document.getElementById('portfolioDescription').value = '';
    document.getElementById('portfolioMethod').value = 'POST';
    document.getElementById('youtubePreview').innerHTML = '<p class="text-gray-400">Preview akan muncul di sini</p>';
    
    const form = document.getElementById('portfolioForm');
    form.action = '{{ route("admin.bands.portfolios.store", $band) }}';
    
    document.getElementById('portfolioModal').showModal();
}

function editPortfolio(id, title, url, description) {
    document.getElementById('portfolioTitle').textContent = 'Edit';
    document.getElementById('portfolioId').value = id;
    document.getElementById('portfolioTitle_input').value = title;
    document.getElementById('youtubeUrl').value = url;
    document.getElementById('portfolioDescription').value = description || '';
    document.getElementById('portfolioMethod').value = 'PUT';
    
    const form = document.getElementById('portfolioForm');
    form.action = '{{ route("admin.bands.portfolios.update", [$band, ":id"]) }}'.replace(':id', id);
    
    updatePreview(url);
    document.getElementById('prototypeModal').showModal();
}

function extractVideoId(url) {
    // Match youtube.com/watch?v=
    let match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/);
    return match ? match[1] : null;
}

function updatePreview(url) {
    const videoId = extractVideoId(url);
    const preview = document.getElementById('youtubePreview');
    
    if (videoId) {
        preview.innerHTML = `<iframe width="100%" height="100%" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
    } else {
        preview.innerHTML = '<p class="text-gray-400">URL tidak valid atau belum ada pratinjau</p>';
    }
}

// YouTube URL preview
document.getElementById('youtubeUrl')?.addEventListener('change', function() {
    updatePreview(this.value);
});

document.getElementById('youtubeUrl')?.addEventListener('blur', function() {
    updatePreview(this.value);
});

// Reset form when opening new
document.getElementById('portfolioModal')?.addEventListener('close', () => {
    if (!document.getElementById('portfolioId').value) {
        document.getElementById('portfolioForm').reset();
        document.getElementById('portfolioTitle').textContent = 'Tambah';
        document.getElementById('youtubePreview').innerHTML = '<p class="text-gray-400">Preview akan muncul di sini</p>';
    }
});
</script>

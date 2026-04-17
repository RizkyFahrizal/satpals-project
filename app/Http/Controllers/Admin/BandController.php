<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Band;
use App\Models\BandMember;
use App\Models\BandGenre;
use App\Models\BandPrototype;
use App\Models\BandMOU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Band::withCount(['members', 'genres', 'portfolios']);

        // Search
        if ($request->search) {
            $query->where('band_name', 'like', "%{$request->search}%");
        }

        // Filter by availability
        if ($request->availability && $request->availability !== 'all') {
            $query->where('is_available', $request->availability === 'available');
        }

        $bands = $query->paginate(15);

        return view('admin.bands.index', compact('bands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bands.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Band create request received', $request->all());
            
            $validated = $request->validate([
                'band_name' => 'required|string|max:255|unique:bands,band_name',
                'description' => 'required|string',
                'price_per_hour' => 'required|numeric|min:0',
                'price_per_event' => 'required|numeric|min:0',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_available' => 'nullable|in:0,1',
                'whatsapp_number' => 'nullable|string|max:20',
                'instagram_username' => 'nullable|string|max:100',
                'tiktok_username' => 'nullable|string|max:100',
                'youtube_url' => 'nullable|url|max:255',
            ]);

            Log::info('Validation passed', $validated);

            // Handle photo upload
            if ($request->hasFile('photo')) {
                Log::info('Photo file detected');
                $validated['photo'] = $request->file('photo')->store('bands', 'public');
                Log::info('Photo stored at: ' . $validated['photo']);
            }

            // Ensure is_available is boolean
            $validated['is_available'] = (bool) ($validated['is_available'] ?? 0);
            
            Log::info('Creating band with data', $validated);
            $band = Band::create($validated);
            Log::info('Band created successfully', ['id' => $band->id]);
            
            return redirect()->route('admin.bands.index')
                ->with('success', 'Band berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed', $e->errors());
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error creating band', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Band $band)
    {
        $band->load(['members', 'genres', 'portfolios', 'mou']);
        
        return view('admin.bands.show', compact('band'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Band $band)
    {
        return view('admin.bands.form', compact('band'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Band $band)
    {
        try {
            Log::info('Band update request received', $request->all());
            
            $validated = $request->validate([
                'band_name' => 'required|string|max:255|unique:bands,band_name,' . $band->id,
                'description' => 'required|string',
                'price_per_hour' => 'required|numeric|min:0',
                'price_per_event' => 'required|numeric|min:0',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'is_available' => 'nullable|in:0,1',
                'whatsapp_number' => 'nullable|string|max:20',
                'instagram_username' => 'nullable|string|max:100',
                'tiktok_username' => 'nullable|string|max:100',
                'youtube_url' => 'nullable|url|max:255',
            ]);

            Log::info('Validation passed', $validated);

            // Handle photo upload
            if ($request->hasFile('photo')) {
                Log::info('Photo file detected');
                // Delete old photo if exists
                if ($band->photo && \Storage::disk('public')->exists($band->photo)) {
                    \Storage::disk('public')->delete($band->photo);
                    Log::info('Old photo deleted');
                }
                $validated['photo'] = $request->file('photo')->store('bands', 'public');
                Log::info('Photo stored at: ' . $validated['photo']);
            }

            // Ensure is_available is boolean
            $validated['is_available'] = (bool) ($validated['is_available'] ?? 0);

            Log::info('Updating band with data', $validated);
            $band->update($validated);
            Log::info('Band updated successfully', ['id' => $band->id]);

            return redirect()->route('admin.bands.show', $band)
                ->with('success', 'Band berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed', $e->errors());
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error updating band', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Band $band)
    {
        if ($band->photo) {
            Storage::disk('public')->delete($band->photo);
        }

        $band->delete();

        return redirect()->route('admin.bands.index')->with('success', 'Band berhasil dihapus');
    }

    /**
     * Toggle band availability
     */
    public function toggleAvailability(Band $band)
    {
        $band->update(['is_available' => !$band->is_available]);

        $status = $band->is_available ? 'Tersedia' : 'Tidak Tersedia';
        return redirect()->back()->with('success', "Status band diubah menjadi: $status");
    }

    // ===== BAND MEMBERS =====

    /**
     * Store a new band member
     */
    public function storeMember(Request $request, Band $band)
    {
        $validated = $request->validate([
            'member_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle member photo
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('band-members', 'public');
        }

        $validated['band_id'] = $band->id;
        
        BandMember::create($validated);

        return redirect()->back()->with('success', 'Personil berhasil ditambahkan');
    }

    /**
     * Update band member
     */
    public function updateMember(Request $request, Band $band, BandMember $member)
    {
        $validated = $request->validate([
            'member_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle member photo
        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store('band-members', 'public');
        }

        $member->update($validated);

        return redirect()->back()->with('success', 'Personil berhasil diperbarui');
    }

    /**
     * Delete band member
     */
    public function deleteMember(Band $band, BandMember $member)
    {
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->back()->with('success', 'Personil berhasil dihapus');
    }

    // ===== BAND GENRES =====

    /**
     * Add genre to band
     */
    public function storeGenre(Request $request, Band $band)
    {
        $validated = $request->validate([
            'genre_name' => 'required|string|max:255',
        ]);

        $validated['band_id'] = $band->id;
        
        BandGenre::create($validated);

        return redirect()->back()->with('success', 'Genre berhasil ditambahkan');
    }

    /**
     * Delete genre from band
     */
    public function deleteGenre(Band $band, BandGenre $genre)
    {
        $genre->delete();

        return redirect()->back()->with('success', 'Genre berhasil dihapus');
    }

    // ===== BAND PORTFOLIOS =====

    /**
     * Add portfolio/video to band
     */
    public function storePortfolio(Request $request, Band $band)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string|url',
            'description' => 'nullable|string',
        ]);

        $validated['band_id'] = $band->id;
        
        BandPrototype::create($validated);

        return redirect()->back()->with('success', 'Portfolio berhasil ditambahkan');
    }

    /**
     * Update portfolio/video
     */
    public function updatePortfolio(Request $request, Band $band, BandPrototype $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'youtube_url' => 'required|string|url',
            'description' => 'nullable|string',
        ]);

        $portfolio->update($validated);

        return redirect()->back()->with('success', 'Portfolio berhasil diperbarui');
    }

    /**
     * Delete portfolio/video
     */
    public function deletePortfolio(Band $band, BandPrototype $portfolio)
    {
        $portfolio->delete();

        return redirect()->back()->with('success', 'Portfolio berhasil dihapus');
    }

    // ===== BAND MOU =====

    /**
     * Upload or update MoU
     */
    public function storeMOU(Request $request, Band $band)
    {
        $validated = $request->validate([
            'mou_document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'mou_description' => 'nullable|string',
            'effective_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:effective_date',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle file upload
        if ($request->hasFile('mou_document')) {
            $validated['mou_document'] = $request->file('mou_document')->store('band-mous', 'public');
        }

        $mou = $band->mou;

        if ($mou) {
            // Update existing MoU
            if ($request->hasFile('mou_document') && $mou->mou_document) {
                Storage::disk('public')->delete($mou->mou_document);
            }
            $mou->update($validated);
        } else {
            // Create new MoU
            $validated['band_id'] = $band->id;
            BandMOU::create($validated);
        }

        return redirect()->back()->with('success', 'MoU berhasil disimpan');
    }

    /**
     * Toggle MoU status
     */
    public function toggleMOUStatus(Band $band)
    {
        $mou = $band->mou;

        if (!$mou) {
            return redirect()->back()->with('error', 'MoU belum tersedia');
        }

        $mou->update(['status' => $mou->status === 'active' ? 'inactive' : 'active']);

        $status = $mou->status === 'active' ? 'Aktif' : 'Tidak Aktif';
        return redirect()->back()->with('success', "Status MoU diubah menjadi: $status");
    }

    /**
     * Delete MoU
     */
    public function deleteMOU(Band $band)
    {
        $mou = $band->mou;

        if (!$mou) {
            return redirect()->back()->with('error', 'MoU belum tersedia');
        }

        if ($mou->mou_document) {
            Storage::disk('public')->delete($mou->mou_document);
        }

        $mou->delete();

        return redirect()->back()->with('success', 'MoU berhasil dihapus');
    }
}


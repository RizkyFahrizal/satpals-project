<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\BandRentalRequest;
use Illuminate\Http\Request;

class BandRentalController extends Controller
{
    /**
     * Display available bands for public browsing
     */
    public function index()
    {
        $bands = Band::where('is_available', true)
            ->with(['members', 'genres', 'portfolios'])
            ->withCount(['members', 'genres'])
            ->paginate(12);
        
        return view('bands.index', compact('bands'));
    }

    /**
     * Show band details with rental option
     */
    public function show(Band $band)
    {
        if (!$band->is_available) {
            return redirect()->route('public.bands.index')
                ->with('error', 'Band ini tidak tersedia untuk disewa');
        }

        $band->load(['members', 'genres', 'portfolios', 'mou']);
        
        return view('public.bands.show', compact('band'));
    }

    /**
     * Show rental request form
     */
    public function createRequest(Band $band)
    {
        if (!$band->is_available) {
            return redirect()->route('public.bands.index')
                ->with('error', 'Band ini tidak tersedia untuk disewa');
        }

        return view('public.bands.rental-form', compact('band'));
    }

    /**
     * Store rental request
     */
    public function storeRequest(Request $request, Band $band)
    {
        $validated = $request->validate([
            'renter_name' => 'required|string|max:255',
            'renter_phone' => 'required|string|max:20',
            'rental_purpose' => 'required|string',
            'performance_date' => 'required|date|after:today',
        ]);

        $validated['band_id'] = $band->id;
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        BandRentalRequest::create($validated);

        return redirect()->route('public.bands.index')
            ->with('success', 'Permintaan sewa band berhasil dikirim! Admin akan segera mengkonfirmasi.');
    }
}

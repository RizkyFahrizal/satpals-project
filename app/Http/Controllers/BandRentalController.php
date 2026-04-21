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
        
        return view('bands.show', compact('band'));
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

        return view('bands.rental-form', compact('band'));
    }

    /**
     * Store rental request
     */
    public function storeRequest(Request $request, Band $band)
    {
        $validated = $request->validate([
            'renter_name' => 'required|string|max:255',
            'renter_phone' => 'required|string|max:20',
            'renter_email' => 'required|email|max:255',
            'rental_purpose' => 'required|string',
            'venue_address' => 'required|string|max:500',
            'performance_date' => 'required|date|after:today',
            'performance_start_time' => 'required|date_format:H:i',
            'performance_end_time' => 'required|date_format:H:i|after:performance_start_time',
            'performance_duration_hours' => 'nullable|integer|min:0|max:23',
            'performance_duration_minutes' => 'nullable|integer|min:0|max:59',
            'break_duration_hours' => 'required|integer|min:0|max:23',
            'break_duration_minutes' => 'required|integer|min:0|max:59',
        ]);

        // Ensure performance_duration_hours & minutes have default values
        $validated['performance_duration_hours'] = $validated['performance_duration_hours'] ?? 0;
        $validated['performance_duration_minutes'] = $validated['performance_duration_minutes'] ?? 0;

        $validated['band_id'] = $band->id;
        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        BandRentalRequest::create($validated);

        return redirect()->route('public.bands.index')
            ->with('success', 'Permintaan sewa band berhasil dikirim! Admin akan segera mengkonfirmasi.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Band;
use App\Models\BandRentalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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
            'rental_type' => 'required|in:hourly,event',
            'rental_purpose' => 'required|string',
            'venue_address' => 'required|string|max:500',
            'performance_date' => 'required|date|after:today',
            'performance_start_time' => 'nullable|date_format:H:i',
            'performance_end_time' => 'nullable|date_format:H:i|after:performance_start_time',
            'performance_duration_hours' => 'nullable|integer|min:0|max:23',
            'performance_duration_minutes' => 'nullable|integer|min:0|max:59',
            'break_duration_hours' => 'nullable|integer|min:0|max:23',
            'break_duration_minutes' => 'nullable|integer|min:0|max:59',
        ]);

        $isEvent = $validated['rental_type'] === 'event';
        $pricePerHour = (float) $band->price_per_hour;
        $pricePerEvent = (float) $band->price_per_event;

        if ($isEvent) {
            $validated['performance_start_time'] = null;
            $validated['performance_end_time'] = null;
            $validated['performance_duration_hours'] = 0;
            $validated['performance_duration_minutes'] = 0;
            $validated['break_duration_hours'] = 0;
            $validated['break_duration_minutes'] = 0;
            $validated['harga_pokok'] = $pricePerEvent;
        } else {
            $validated['performance_duration_hours'] = (int) ($validated['performance_duration_hours'] ?? 0);
            $validated['performance_duration_minutes'] = (int) ($validated['performance_duration_minutes'] ?? 0);
            $validated['break_duration_hours'] = (int) ($validated['break_duration_hours'] ?? 0);
            $validated['break_duration_minutes'] = (int) ($validated['break_duration_minutes'] ?? 0);

            $totalMinutes = (($validated['performance_duration_hours'] * 60) + $validated['performance_duration_minutes']);
            $validated['harga_pokok'] = max(0, ceil($totalMinutes / 60) * $pricePerHour);
        }

        $validated['band_id'] = $band->id;
        $validated['status'] = 'pending';
        $validated['harga_final'] = $validated['harga_pokok'];
        $validated['diskon_persen'] = 0;
        $validated['diskon_nominal'] = 0;

        $hasRentalTypeColumn = Schema::hasColumn('band_rental_requests', 'rental_type');
        if (!$hasRentalTypeColumn) {
            unset($validated['rental_type']);
        }

        $rental = BandRentalRequest::create($validated);

        session()->flash('band_rental_id', $rental->id);
        session()->flash('band_rental_type', $validated['rental_type'] ?? 'hourly');

        return redirect()->route('public.bands.rental-success', $band)
            ->with('success', 'Permintaan sewa band berhasil dikirim! Admin akan segera mengkonfirmasi.');
    }

    /**
     * Show success page after rental request submission
     */
    public function success(Band $band)
    {
        if (!$band->is_available) {
            return redirect()->route('public.bands.index')
                ->with('error', 'Band ini tidak tersedia untuk disewa');
        }

        $rentalId = session('band_rental_id');

        if ($rentalId) {
            $rental = BandRentalRequest::with('band')->find($rentalId);
        } else {
            $rental = null;
        }

        if (!$rental || $rental->band_id !== $band->id) {
            return redirect()->route('public.bands.show', $band)
                ->with('error', 'Permintaan sewa band tidak ditemukan');
        }

        $rentalType = session('band_rental_type');

        if (!$rentalType) {
            $rentalType = $rental->rental_type
                ?? (((int) ($rental->harga_pokok ?? 0) === (int) ($band->price_per_event ?? 0)) ? 'event' : 'hourly');
        }

        $rental->setAttribute('rental_type', $rentalType);

        return view('bands.success', [
            'band' => $band,
            'rental' => $rental,
            'rentalType' => $rentalType,
        ]);
    }
}

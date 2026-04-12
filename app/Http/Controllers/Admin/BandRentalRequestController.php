<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BandRentalRequest;
use Illuminate\Http\Request;

class BandRentalRequestController extends Controller
{
    /**
     * Display all rental requests
     */
    public function index(Request $request)
    {
        $query = BandRentalRequest::with(['band', 'user'])
            ->orderBy('created_at', 'desc');
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $rentals = $query->paginate(15);
        
        return view('admin.band-rentals.index', compact('rentals'));
    }

    /**
     * Show details of a rental request
     */
    public function show(BandRentalRequest $rental)
    {
        $rental->load(['band', 'user']);
        
        return view('admin.band-rentals.show', compact('rental'));
    }

    /**
     * Approve rental request
     */
    public function approve(Request $request, BandRentalRequest $rental)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $rental->update([
            'status' => 'approved',
            'admin_notes' => $validated['admin_notes'] ?? $rental->admin_notes,
        ]);

        return redirect()->route('admin.band-rentals.show', $rental)
            ->with('success', 'Permintaan sewa band berhasil disetujui');
    }

    /**
     * Reject rental request
     */
    public function reject(Request $request, BandRentalRequest $rental)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $rental->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
        ]);

        return redirect()->route('admin.band-rentals.show', $rental)
            ->with('success', 'Permintaan sewa band berhasil ditolak');
    }

    /**
     * Mark as completed
     */
    public function complete(BandRentalRequest $rental)
    {
        $rental->update(['status' => 'completed']);

        return redirect()->route('admin.band-rentals.show', $rental)
            ->with('success', 'Status permintaan sewa band diubah menjadi selesai');
    }

    /**
     * Delete rental request
     */
    public function destroy(BandRentalRequest $rental)
    {
        $bandName = $rental->band->band_name;
        $rental->delete();

        return redirect()->route('admin.band-rentals.index')
            ->with('success', "Permintaan sewa untuk band $bandName berhasil dihapus");
    }
}

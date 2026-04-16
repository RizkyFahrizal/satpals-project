<?php

namespace App\Http\Controllers;

use App\Models\EquipmentRentalRequest;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display user's bookings
     */
    public function index(Request $request)
    {
        $query = EquipmentRentalRequest::query();

        // Filter by email for guest users or user_id for logged-in users
        if ($request->email) {
            $query->where('renter_email', $request->email);
        }

        // Filter by status
        if ($request->status && $request->status != '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);
        $statuses = ['pending', 'approved', 'rejected', 'in_progress', 'done'];

        return view('bookings.index', [
            'bookings' => $bookings,
            'statuses' => $statuses,
            'selectedStatus' => $request->status ?? null,
            'email' => $request->email ?? null,
        ]);
    }

    /**
     * Display booking detail
     */
    public function show($id)
    {
        $booking = EquipmentRentalRequest::with('items.equipment')->findOrFail($id);

        return view('bookings.show', [
            'booking' => $booking,
        ]);
    }
}

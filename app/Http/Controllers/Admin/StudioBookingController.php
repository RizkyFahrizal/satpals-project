<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudioBooking;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class StudioBookingController extends Controller
{
    /**
     * Display calendar view of studio bookings
     */
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', now()->format('Y-m-d'));
        $date = Carbon::parse($selectedDate);

        // Get bookings for selected date
        $bookingsForDate = StudioBooking::byDate($selectedDate)
            ->with(['user', 'approvedBy'])
            ->orderBy('sesi')
            ->get();

        // Get all bookings for the month for calendar view
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $bookingsForMonth = StudioBooking::whereBetween('tanggal_booking', [$startOfMonth, $endOfMonth])
            ->approved()
            ->get()
            ->groupBy('tanggal_booking');

        // Get ALL bookings for table view with search and filter
        $query = StudioBooking::with(['user', 'approvedBy']);

        // Search by nama_pemohon
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_pemohon', 'like', '%' . $search . '%')
                  ->orWhere('nomor_identitas', 'like', '%' . $search . '%');
            });
        }

        // Filter by tanggal_booking
        if ($request->filled('filter_tanggal')) {
            $filterTanggal = $request->input('filter_tanggal');
            $query->whereDate('tanggal_booking', '=', $filterTanggal);
        }

        // Filter by status
        if ($request->filled('filter_status')) {
            $filterStatus = $request->input('filter_status');
            $query->where('status', $filterStatus);
        }

        $allBookings = $query->orderBy('created_at', 'desc')->paginate(15);

        // Prepare sesi data
        $sesiData = [];
        for ($i = 1; $i <= 4; $i++) {
            $booking = $bookingsForDate->where('sesi', $i)->first();
            $sesiData[$i] = [
                'label' => StudioBooking::SESI_TIMES[$i]['label'],
                'time' => StudioBooking::SESI_TIMES[$i]['start'] . ' - ' . StudioBooking::SESI_TIMES[$i]['end'],
                'booking' => $booking,
                'available' => !$booking || !$booking->isApproved(),
            ];
        }

        // Get pending bookings
        $pendingBookings = StudioBooking::pending()
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.studio-bookings.index', [
            'selectedDate' => $date,
            'sesiData' => $sesiData,
            'bookingsForMonth' => $bookingsForMonth,
            'pendingBookings' => $pendingBookings,
            'allBookings' => $allBookings,
        ]);
    }

    /**
     * Show form for creating new booking
     */
    public function create(Request $request)
    {
        // Removed - booking harus dari public form, bukan dari admin
        return redirect()->route('admin.studio-bookings.index')
            ->with('info', 'Booking harus dilakukan melalui form public');
    }

    /**
     * Store new booking
     */
    public function store(Request $request)
    {
        // Removed - booking harus dari public form, bukan dari admin
        return redirect()->route('admin.studio-bookings.index')
            ->with('info', 'Booking harus dilakukan melalui form public');
    }

    /**
     * Show booking details
     */
    public function show(StudioBooking $booking)
    {
        $booking->load(['user', 'approvedBy']);
        return view('admin.studio-bookings.show', ['booking' => $booking]);
    }

    /**
     * Update booking (edit via API/modal)
     */
    public function update(Request $request, StudioBooking $booking)
    {
        // Hanya bisa edit jika pending atau rejected
        if (!in_array($booking->status, [StudioBooking::STATUS_PENDING, StudioBooking::STATUS_REJECTED])) {
            return back()->with('error', 'Hanya booking pending/rejected yang bisa diedit');
        }

        $validated = $request->validate([
            'keperluan' => 'required|string|min:10|max:500',
        ], [
            'keperluan.required' => 'Keperluan wajib diisi',
            'keperluan.min' => 'Keperluan minimal 10 karakter',
            'keperluan.max' => 'Keperluan maksimal 500 karakter',
        ]);

        $booking->update($validated);

        return back()->with('success', 'Booking berhasil diupdate');
    }

    /**
     * Approve booking
     *
     * @return void
     */
    public function approve(Request $request, StudioBooking $booking)
    {
        if ($booking->status !== StudioBooking::STATUS_PENDING) {
            return back()->with('error', 'Hanya booking pending yang bisa di-approve');
        }

        $validated = $request->validate([
            'catatan' => 'nullable|string|max:255',
        ]);

        $booking->update([
            'status' => StudioBooking::STATUS_APPROVED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'catatan_admin' => $validated['catatan'] ?? null,
        ]);

        return back()->with('success', 'Booking berhasil di-approve');
    }

    /**
     * Reject booking
     */
    public function reject(Request $request, StudioBooking $booking)
    {
        if ($booking->status !== StudioBooking::STATUS_PENDING) {
            return back()->with('error', 'Hanya booking pending yang bisa di-reject');
        }

        $booking->update([
            'status' => StudioBooking::STATUS_REJECTED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Booking berhasil di-reject');
    }

    /**
     * Delete booking
     */
    public function destroy(StudioBooking $booking)
    {
        // Hanya bisa delete jika pending atau rejected
        if (!in_array($booking->status, [StudioBooking::STATUS_PENDING, StudioBooking::STATUS_REJECTED])) {
            return back()->with('error', 'Hanya booking pending/rejected yang bisa dihapus');
        }

        $booking->delete();
        return back()->with('success', 'Booking berhasil dihapus');
    }
}

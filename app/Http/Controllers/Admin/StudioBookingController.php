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
        ]);
    }

    /**
     * Show form for creating new booking
     */
    public function create(Request $request)
    {
        $selectedDate = $request->input('date');
        $selectedSesi = $request->input('sesi');

        return view('admin.studio-bookings.create', [
            'selectedDate' => $selectedDate,
            'selectedSesi' => $selectedSesi,
        ]);
    }

    /**
     * Store new booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_npm' => 'required|string',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'sesi' => 'required|integer|in:1,2,3,4',
            'keperluan' => 'required|string|min:10|max:500',
        ], [
            'user_npm.required' => 'NPM/Nama anggota wajib diisi',
            'tanggal_booking.required' => 'Tanggal booking wajib diisi',
            'tanggal_booking.after_or_equal' => 'Tanggal tidak boleh di masa lalu',
            'sesi.required' => 'Sesi wajib dipilih',
            'sesi.in' => 'Sesi tidak valid',
            'keperluan.required' => 'Keperluan wajib diisi',
            'keperluan.min' => 'Keperluan minimal 10 karakter',
        ]);

        // Get user by NPM (from member) or name
        $member = Member::where('npm', $validated['user_npm'])
                         ->orWhere('nama_lengkap', 'like', '%' . $validated['user_npm'] . '%')
                         ->first();

        if (!$member || !$member->diklatRegistration || !$member->diklatRegistration->user) {
            return back()
                ->withInput()
                ->withErrors(['user_npm' => 'NPM/Nama anggota tidak ditemukan atau belum terdaftar sebagai user']);
        }

        $user = $member->diklatRegistration->user;
        $user = $member->diklatRegistration->user;

        // Check if sesi already booked
        if (!StudioBooking::isSesiAvailable($validated['tanggal_booking'], $validated['sesi'])) {
            return back()->with('error', 'Sesi ini sudah dipesan pada tanggal tersebut');
        }

        // Create booking
        StudioBooking::create([
            'user_id' => $user->id,
            'tanggal_booking' => $validated['tanggal_booking'],
            'sesi' => $validated['sesi'],
            'keperluan' => $validated['keperluan'],
            'status' => StudioBooking::STATUS_PENDING,
        ]);

        return redirect()->route('admin.studio-bookings.index')
            ->with('success', 'Booking berhasil dibuat dan menunggu approval');
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
     * Approve booking
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

        $validated = $request->validate([
            'catatan' => 'required|string|min:5|max:255',
        ], [
            'catatan.required' => 'Alasan rejection wajib diisi',
            'catatan.min' => 'Alasan minimal 5 karakter',
        ]);

        $booking->update([
            'status' => StudioBooking::STATUS_REJECTED,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'catatan_admin' => $validated['catatan'],
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

<?php

namespace App\Http\Controllers;

use App\Models\StudioBooking;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudioBookingController extends Controller
{
    /**
     * Display public studio bookings index/calendar
     */
    public function index(Request $request)
    {
        // Get bookings for the next 60 days
        $bookings = StudioBooking::where('status', '!=', StudioBooking::STATUS_REJECTED)
            ->where('status', '!=', StudioBooking::STATUS_CANCELLED)
            ->where('tanggal_booking', '>=', now()->toDateString())
            ->where('tanggal_booking', '<=', now()->addDays(60)->toDateString())
            ->get()
            ->groupBy(function($booking) {
                return $booking->tanggal_booking->format('Y-m-d');
            });

        // Get user's bookings if logged in
        $myBookings = null;
        if (auth()->check()) {
            $myBookings = StudioBooking::where(function($query) {
                $query->where('user_id', auth()->id())
                      ->orWhere('nomor_identitas', auth()->user()?->username ?? '');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        }

        // Group bookings by date for calendar
        $bookingsByDate = [];
        foreach ($bookings as $date => $dateBookings) {
            $bookingsByDate[$date] = [];
            foreach ($dateBookings as $booking) {
                $bookingsByDate[$date][$booking->sesi] = [
                    'status' => $booking->status,
                    'nama' => $booking->nama_pemohon,
                ];
            }
        }

        return view('studio-bookings.index', [
            'bookings' => $bookingsByDate,
            'myBookings' => $myBookings,
        ]);
    }

    /**
     * Display public form for booking studio
     */
    public function create(Request $request)
    {
        $selectedDate = $request->input('date');
        $selectedSesi = $request->input('sesi');

        // Get bookings for the next 30 days to show availability
        $bookings = StudioBooking::where('status', '!=', StudioBooking::STATUS_REJECTED)
            ->where('status', '!=', StudioBooking::STATUS_CANCELLED)
            ->where('tanggal_booking', '>=', now()->toDateString())
            ->where('tanggal_booking', '<=', now()->addDays(30)->toDateString())
            ->get()
            ->groupBy(function($booking) {
                return $booking->tanggal_booking->format('Y-m-d') . '-' . $booking->sesi;
            });

        return view('studio-bookings.create', [
            'selectedDate' => $selectedDate,
            'selectedSesi' => $selectedSesi,
            'bookings' => $bookings,
        ]);
    }

    /**
     * Store new booking from public
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'npm' => 'required|string',
            'nama_lengkap' => 'required|string',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'sesi' => 'required|integer|in:1,2,3,4',
            'keperluan' => 'required|string|min:10|max:500',
        ], [
            'npm.required' => 'NPM wajib diisi',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'tanggal_booking.required' => 'Tanggal booking wajib diisi',
            'tanggal_booking.after_or_equal' => 'Tanggal tidak boleh di masa lalu',
            'sesi.required' => 'Sesi wajib dipilih',
            'sesi.in' => 'Sesi tidak valid',
            'keperluan.required' => 'Keperluan wajib diisi',
            'keperluan.min' => 'Keperluan minimal 10 karakter',
        ]);

        // Validasi: cek npm dan nama di tabel members
        $member = Member::where('npm', $validated['npm'])
                         ->where('nama_lengkap', $validated['nama_lengkap'])
                         ->first();

        if (!$member) {
            return back()
                ->withInput()
                ->withErrors(['npm' => 'NPM dan Nama Lengkap tidak cocok di data members. Pastikan data Anda sudah terdaftar.']);
        }

        // Validasi: cek ketersediaan sesi
        if (!StudioBooking::isSesiAvailable($validated['tanggal_booking'], $validated['sesi'])) {
            return back()
                ->withInput()
                ->withErrors(['sesi' => 'Sesi ini sudah dipesan pada tanggal tersebut']);
        }

        // Cek apakah member sudah punya booking pada tanggal dan sesi yang sama
        $existingBooking = StudioBooking::whereHas('user.member', function ($query) use ($validated) {
            $query->where('npm', $validated['npm']);
        })
            ->where('tanggal_booking', $validated['tanggal_booking'])
            ->where('sesi', $validated['sesi'])
            ->first();

        if ($existingBooking) {
            return back()
                ->withInput()
                ->withErrors(['npm' => 'Anda sudah membuat booking untuk sesi ini pada tanggal tersebut']);
        }

        // Cari user dari member jika ada
        $user = null;
        if ($member->diklatRegistration && $member->diklatRegistration->user) {
            $user = $member->diklatRegistration->user;
        }

        // Create booking
        StudioBooking::create([
            'user_id' => $user?->id,
            'tanggal_booking' => $validated['tanggal_booking'],
            'sesi' => $validated['sesi'],
            'keperluan' => $validated['keperluan'],
            'status' => StudioBooking::STATUS_PENDING,
            'nomor_identitas' => $validated['npm'],
            'nama_pemohon' => $validated['nama_lengkap'],
        ]);

        return redirect()->route('studio-bookings.success')
            ->with('success', 'Booking berhasil dibuat! Admin akan segera memproses permohonan Anda.');
    }

    /**
     * Show success page
     */
    public function success()
    {
        return view('studio-bookings.success');
    }
}

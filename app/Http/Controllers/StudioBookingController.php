<?php

namespace App\Http\Controllers;

use App\Models\StudioBooking;
use App\Models\StudioBookingSetting;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class StudioBookingController extends Controller
{
    /**
     * Display public studio bookings index/calendar
     */
    public function index(Request $request)
    {
        // Get bookings for the next 60 days
        $bookings = StudioBooking::whereIn('status', [StudioBooking::STATUS_PENDING, StudioBooking::STATUS_APPROVED])
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
            'pricePerPerson' => StudioBookingSetting::currentPricePerPerson(),
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
        $bookings = StudioBooking::whereIn('status', [StudioBooking::STATUS_PENDING, StudioBooking::STATUS_APPROVED])
            ->whereDate('tanggal_booking', '>=', now()->toDateString())
            ->whereDate('tanggal_booking', '<=', now()->addDays(30)->toDateString())
            ->get()
            ->groupBy(function($booking) {
                return $booking->tanggal_booking->format('Y-m-d') . '-' . $booking->sesi;
            });

        return view('studio-bookings.create', [
            'selectedDate' => $selectedDate,
            'selectedSesi' => $selectedSesi,
            'bookings' => $bookings,
            'pricePerPerson' => StudioBookingSetting::currentPricePerPerson(),
        ]);
    }

    /**
     * Store new booking from public
     */
    public function store(Request $request)
    {
        // Log request data untuk debug
        \Log::info('StudioBooking store - request data:', $request->all());
        
        $validated = $request->validate([
            'npm' => 'required|string',
            'nama_lengkap' => 'required|string',
            'renter_email' => 'required|email',
            'renter_phone' => 'required|string|min:8|max:20',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'sesi' => 'required|integer|in:1,2,3,4',
            'keperluan' => 'required|string|min:10|max:500',
            'jumlah_non_ukm' => 'required|integer|min:0|max:999',
        ], [
            'npm.required' => 'NPM wajib diisi',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'renter_email.required' => 'Email wajib diisi',
            'renter_email.email' => 'Format email tidak valid',
            'renter_phone.required' => 'Nomor telepon wajib diisi',
            'tanggal_booking.required' => 'Tanggal booking wajib diisi',
            'tanggal_booking.after_or_equal' => 'Tanggal tidak boleh di masa lalu',
            'sesi.required' => 'Sesi wajib dipilih',
            'sesi.in' => 'Sesi tidak valid',
            'keperluan.required' => 'Keperluan wajib diisi',
            'keperluan.min' => 'Keperluan minimal 10 karakter',
            'jumlah_non_ukm.required' => 'Jumlah non-UKM wajib diisi',
            'jumlah_non_ukm.min' => 'Jumlah non-UKM minimal 0',
        ]);

        // Pastikan format date konsisten (YYYY-MM-DD) dan convert ke Carbon
        $validated['tanggal_booking'] = \Carbon\Carbon::createFromFormat('Y-m-d', $validated['tanggal_booking'])->toDateString();

        // Validasi: cek npm dan nama di tabel members
        $member = Member::where('npm', $validated['npm'])
                         ->where('nama_lengkap', $validated['nama_lengkap'])
                         ->first();

        if (!$member) {
            // Try to find member with npm to give better error message
            $memberByNpm = Member::where('npm', $validated['npm'])->first();
            if ($memberByNpm) {
                return back()
                    ->withInput()
                    ->withErrors(['npm' => 'Nama Lengkap tidak sesuai! NPM ' . $validated['npm'] . ' terdaftar dengan nama: ' . $memberByNpm->nama_lengkap]);
            } else {
                return back()
                    ->withInput()
                    ->withErrors(['npm' => 'NPM tidak ditemukan di sistem. Pastikan Anda sudah terdaftar sebagai member.']);
            }
        }

        // Validasi: cek ketersediaan sesi
        $sesiAvailable = StudioBooking::isSesiAvailable($validated['tanggal_booking'], $validated['sesi']);
        
        // Debug logging
        \Log::info('Booking validation', [
            'tanggal' => $validated['tanggal_booking'],
            'sesi' => $validated['sesi'],
            'sesiAvailable' => $sesiAvailable,
        ]);
        
        if (!$sesiAvailable) {
            return back()
                ->withInput()
                ->withErrors(['sesi' => 'Sesi ini sudah dipesan pada tanggal tersebut']);
        }

        // Cek apakah member sudah punya booking pada tanggal dan sesi yang sama
        $existingBooking = StudioBooking::whereHas('user.member', function ($query) use ($validated) {
            $query->where('npm', $validated['npm']);
        })
            ->whereDate('tanggal_booking', $validated['tanggal_booking'])
            ->where('sesi', $validated['sesi'])
            ->first();

        if ($existingBooking) {
            return back()
                ->withInput()
                ->withErrors(['npm' => 'Anda sudah membuat booking untuk sesi ini pada tanggal tersebut']);
        }

        $pricePerPerson = StudioBookingSetting::currentPricePerPerson();
        $hargaPokok = $pricePerPerson * (int) $validated['jumlah_non_ukm'];

        // Cari user dari member jika ada
        $user = null;
        if ($member->diklatRegistration && $member->diklatRegistration->user) {
            $user = $member->diklatRegistration->user;
        }

        // Create booking
        try {
            $booking = StudioBooking::create([
                'user_id' => $user?->id,
                'tanggal_booking' => $validated['tanggal_booking'],
                'sesi' => $validated['sesi'],
                'keperluan' => $validated['keperluan'],
                'renter_email' => $validated['renter_email'],
                'renter_phone' => $validated['renter_phone'],
                'jumlah_non_ukm' => $validated['jumlah_non_ukm'],
                'harga_satuan' => $pricePerPerson,
                'harga_pokok' => $hargaPokok,
                'diskon_persen' => 0,
                'diskon_nominal' => 0,
                'harga_final' => $hargaPokok,
                'status' => StudioBooking::STATUS_PENDING,
                'nomor_identitas' => $validated['npm'],
                'nama_pemohon' => $validated['nama_lengkap'],
            ]);
        } catch (QueryException $exception) {
            if ((string) $exception->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->withErrors(['sesi' => 'Sesi ini sudah dipesan pada tanggal tersebut. Silakan pilih sesi lain.']);
            }

            throw $exception;
        }

        // Flash booking ID to session for success page
        session()->flash('booking_id', $booking->id);

        return redirect()->route('studio-bookings.success')
            ->with('success', 'Booking berhasil dibuat! Admin akan segera memproses permohonan Anda.');
    }

    /**
     * Show success page
     */
    public function success()
    {
        // Get booking ID from session flash
        $bookingId = session('booking_id');

        // If no booking_id in session, try to get latest booking for current user
        if (!$bookingId && auth()->check()) {
            $booking = StudioBooking::where('user_id', auth()->id())
                ->latest()
                ->first();
        } else {
            $booking = $bookingId ? StudioBooking::find($bookingId) : null;
        }

        // If no booking found, redirect back to index
        if (!$booking) {
            return redirect()->route('studio-bookings.index')
                ->with('error', 'Booking tidak ditemukan');
        }

        return view('studio-bookings.success', compact('booking'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StudioBookingApprovedMail;
use App\Models\Income;
use App\Models\StudioBooking;
use App\Models\StudioBookingSetting;
use App\Models\User;
use App\Models\Member;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class StudioBookingController extends Controller
{
    /**
     * Display calendar view of studio bookings
     */
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', now()->format('Y-m-d'));
        $date = Carbon::parse($selectedDate);

        // Get active bookings for selected date
        $bookingsForDate = StudioBooking::byDate($selectedDate)
            ->whereIn('status', [StudioBooking::STATUS_PENDING, StudioBooking::STATUS_APPROVED])
            ->with(['user', 'approvedBy'])
            ->orderByDesc('created_at')
            ->orderBy('sesi')
            ->get();

        // Get all bookings for the month for calendar view
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $bookingsForMonth = StudioBooking::whereBetween('tanggal_booking', [$startOfMonth, $endOfMonth])
            ->whereIn('status', [StudioBooking::STATUS_PENDING, StudioBooking::STATUS_APPROVED])
            ->get()
            ->groupBy('tanggal_booking');

        // Get ALL bookings for table view with search and filter
        $query = StudioBooking::with(['user', 'approvedBy', 'income']);

        // Search by nama_pemohon
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_pemohon', 'like', '%' . $search . '%')
                                    ->orWhere('nomor_identitas', 'like', '%' . $search . '%')
                                    ->orWhere('booking_code', 'like', '%' . $search . '%')
                                    ->orWhere('renter_email', 'like', '%' . $search . '%')
                                    ->orWhere('renter_phone', 'like', '%' . $search . '%');
            });
        }

        // Filter by tanggal_booking
        if ($request->filled('filter_tanggal')) {
            $filterTanggal = $request->input('filter_tanggal');
            $query->whereDate('tanggal_booking', '=', $filterTanggal);
        }

        // Filter by status (support both old filter_status and new tab status param)
        $filterStatus = $request->input('status', $request->input('filter_status'));
        if ($filterStatus) {
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
            'pricePerPerson' => StudioBookingSetting::currentPricePerPerson(),
        ]);
    }

    /**
     * Update studio booking settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'price_per_person' => 'required|integer|min:0',
        ]);

        StudioBookingSetting::updatePricePerPerson((int) $validated['price_per_person']);

        return redirect()->route('admin.studio-bookings.index')
            ->with('success', 'Pengaturan harga studio berhasil diperbarui');
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
        $booking->load(['user', 'approvedBy', 'income']);
        return view('admin.studio-bookings.show', [
            'booking' => $booking,
            'pricePerPerson' => StudioBookingSetting::currentPricePerPerson(),
        ]);
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
            'harga_pokok' => 'required|numeric|min:0',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_nominal' => 'nullable|numeric|min:0',
            'catatan' => 'nullable|string|max:255',
        ]);

        $hargaPokok = (int) $validated['harga_pokok'];
        $diskonPersen = (int) ($validated['diskon_persen'] ?? 0);
        $diskonNominal = (int) ($validated['diskon_nominal'] ?? 0);

        if ($diskonPersen > 0) {
            $diskonNominal = (int) ($hargaPokok * $diskonPersen / 100);
        } elseif ($diskonNominal > 0) {
            $diskonPersen = $hargaPokok > 0 ? (int) ($diskonNominal * 100 / $hargaPokok) : 0;
        }

        $hargaFinal = max(0, $hargaPokok - $diskonNominal);

        $income = Income::create([
            'title' => 'Booking Studio - ' . $booking->booking_code,
            'description' => 'Booking Studio ' . $booking->booking_code,
            'nominal' => $hargaFinal,
            'source' => 'Booking Studio',
            'income_date' => now(),
            'created_by' => Auth::id(),
            'creator_name' => $booking->nama_pemohon,
            'status' => 'pending',
        ]);

        $booking->update([
            'status' => StudioBooking::STATUS_APPROVED,
            'harga_pokok' => $hargaPokok,
            'diskon_persen' => $diskonPersen,
            'diskon_nominal' => $diskonNominal,
            'harga_final' => $hargaFinal,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'catatan_admin' => $validated['catatan'] ?? null,
            'income_id' => $income->id,
        ]);

        $recipientEmail = $booking->renter_email ?: $booking->user?->email;

        if ($recipientEmail) {
            try {
                Mail::to($recipientEmail)->send(new StudioBookingApprovedMail($booking));
            } catch (\Throwable $exception) {
                Log::warning('Gagal mengirim email approval booking studio', [
                    'booking_id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'recipient_email' => $recipientEmail,
                    'error' => $exception->getMessage(),
                ]);

                return back()->with('success', 'Booking berhasil di-approve dan pemasukan telah dibuat')
                    ->with('warning', 'Booking sudah disetujui, tetapi email notifikasi gagal dikirim.');
            }
        }

        return back()->with('success', 'Booking berhasil di-approve dan pemasukan telah dibuat');
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
            'catatan' => 'required|string|min:10',
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
     * Cancel approved booking
     */
    public function cancel(Request $request, StudioBooking $booking)
    {
        if ($booking->status !== StudioBooking::STATUS_APPROVED) {
            return back()->with('error', 'Hanya booking yang sudah disetujui yang dapat dibatalkan');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|min:10',
        ]);

        if ($booking->income_id) {
            $income = Income::find($booking->income_id);
            if ($income) {
                $income->update([
                    'status' => 'rejected',
                    'description' => ($income->description ? $income->description . "\n\n" : '') . 'Pembatalan Booking Studio: ' . $validated['cancellation_reason'],
                ]);
            }
        }

        $booking->update([
            'status' => StudioBooking::STATUS_CANCELLED,
            'catatan_admin' => 'Pembatalan: ' . $validated['cancellation_reason'],
        ]);

        return back()->with('success', 'Booking berhasil dibatalkan');
    }

    /**
     * Mark booking as completed
     */
    public function complete(StudioBooking $booking)
    {
        if ($booking->status !== StudioBooking::STATUS_APPROVED) {
            return back()->with('error', 'Hanya booking approved yang bisa diselesaikan');
        }

        $booking->update(['status' => StudioBooking::STATUS_COMPLETED]);

        return back()->with('success', 'Status booking diubah menjadi selesai');
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

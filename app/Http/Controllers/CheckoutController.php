<?php

namespace App\Http\Controllers;

use App\Models\EquipmentRentalRequest;
use App\Models\EquipmentRentalRequestItem;
use App\Mail\BookingConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Show checkout form
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('equipment.index')
                ->with('error', 'Keranjang Anda kosong!');
        }

        // Calculate total and estimate duration (default 1 hari)
        $total = 0;
        $duration_days = 1; // Default
        foreach ($cart as $item) {
            $total += $item['price_per_day'] * $item['quantity'];
        }

        return view('checkout.index', [
            'cart' => $cart,
            'total' => $total,
            'duration_days' => $duration_days,
        ]);
    }

    /**
     * Process checkout and create booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'renter_name' => 'required|string|max:255',
            'renter_npm_nik' => 'required|string|max:255',
            'renter_phone' => 'required|string|max:20',
            'renter_email' => 'required|email',
            'renter_ktp_ktm' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'rental_location' => 'required|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('equipment.index')
                ->with('error', 'Keranjang Anda kosong!');
        }

        try {
            // Upload KTP/KTM photo
            $ktp_path = null;
            if ($request->hasFile('renter_ktp_ktm')) {
                $ktp_path = $request->file('renter_ktp_ktm')->store('ktp_ktm', 'public');
            }

            // Calculate duration (inclusive)
            $start = \Carbon\Carbon::parse($request->start_date);
            $end = \Carbon\Carbon::parse($request->end_date);
            $duration_days = $start->diffInDays($end) + 1;

            // Calculate total price
            $total_price = 0;
            foreach ($cart as $item) {
                $total_price += ($item['price_per_day'] * $duration_days * $item['quantity']);
            }

            // Create request
            $request_record = EquipmentRentalRequest::create([
                'renter_name' => $validated['renter_name'],
                'renter_npm_nik' => $validated['renter_npm_nik'],
                'renter_phone' => $validated['renter_phone'],
                'renter_email' => $validated['renter_email'],
                'renter_ktp_ktm' => $ktp_path,
                'rental_location' => $validated['rental_location'],
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'duration_days' => $duration_days,
                'total_price' => $total_price,
                'renter_notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            // Add items to request
            foreach ($cart as $item) {
                $subtotal = $item['price_per_day'] * $duration_days * $item['quantity'];
                
                EquipmentRentalRequestItem::create([
                    'equipment_rental_request_id' => $request_record->id,
                    'equipment_rental_id' => $item['equipment_id'],
                    'quantity' => $item['quantity'],
                    'price_per_day' => $item['price_per_day'],
                    'subtotal' => $subtotal,
                ]);
            }

            // Clear cart

                $order_number = $request_record->order_number;
            session()->forget('cart');
            session()->flash('booking_id', $request_record->id);

            // Send confirmation email to user (queued)
            $request_record->load('items.equipment');
            Mail::to($validated['renter_email'])->queue(new BookingConfirmationMail($request_record));

            return redirect()->route('bookings.success')
                ->with('success', 'Pesanan berhasil dibuat! Nomor pesanan Anda: ' . $order_number);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Show success page after checkout
     */
    public function success()
    {
        $bookingId = session('booking_id');

        if (!$bookingId) {
            return redirect()->route('checkout.index')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        $booking = EquipmentRentalRequest::with(['items.equipment'])->find($bookingId);

        if (!$booking) {
            return redirect()->route('checkout.index')
                ->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('bookings.success', compact('booking'));
    }
}

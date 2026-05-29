<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BandRentalRequest;
use App\Models\Income;
use App\Services\InvoiceService;
use App\Mail\InvoiceApprovedMail;
use App\Mail\BookingRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class BandRentalRequestController extends Controller
{
    /**
     * Display all rental requests
     */
    public function index(Request $request)
    {
        $query = BandRentalRequest::with(['band.members'])
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
        $rental->load(['band.members']);
        
        return view('admin.band-rentals.show', compact('rental'));
    }

    /**
     * Approve rental request
     */
    public function approve(Request $request, BandRentalRequest $rental)
    {
        $validated = $request->validate([
            'harga_pokok' => 'required|numeric|min:0',
            'diskon_persen' => 'nullable|numeric|min:0|max:100',
            'diskon_nominal' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string',
        ]);

        // Calculate discount values (harga_pokok - diskon = harga_final)
        $hargaPokok = (int) $validated['harga_pokok'];
        $diskonPersen = (int) ($validated['diskon_persen'] ?? 0);
        $diskonNominal = (int) ($validated['diskon_nominal'] ?? 0);

        // Calculate based on which discount was provided
        if ($diskonPersen > 0) {
            $diskonNominal = (int) ($hargaPokok * $diskonPersen / 100);
        } elseif ($diskonNominal > 0) {
            $diskonPersen = $hargaPokok > 0 ? (int) ($diskonNominal * 100 / $hargaPokok) : 0;
        }

        $hargaFinal = $hargaPokok - $diskonNominal;

        // Get the auto-generated kode_order (already generated in model boot)
        $kodeOrder = $rental->kode_order;

        // Create income record with title format: "Persewaan Band - SB..."
        $incomeData = [
            'title' => 'Persewaan Band - ' . $kodeOrder,
            'nominal' => $hargaFinal,
            'source' => 'Persewaan Band',
            'status' => 'pending',
            'income_date' => now(),
            'description' => 'Persewaan Band',
            'created_by' => auth()->id(),  // Admin yang approve
            'creator_name' => $rental->renter_name,  // Nama penyewa band
        ];
        $income = Income::create($incomeData);

        // Update rental request
        $rental->update([
            'status' => 'approved',
            'harga_pokok' => $hargaPokok,
            'diskon_persen' => $diskonPersen,
            'diskon_nominal' => $diskonNominal,
            'harga_final' => $hargaFinal,
            'kode_order' => $kodeOrder,
            'income_id' => $income->id,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'admin_notes' => $validated['admin_notes'] ?? $rental->admin_notes,
        ]);

        // Generate & Save PDF Invoice
        try {
            $pdf = InvoiceService::generate($rental);
            $pdfFilename = 'invoices/Invoice_' . $kodeOrder . '.pdf';
            Storage::disk('public')->put($pdfFilename, $pdf->output());
            $pdfPath = Storage::disk('public')->path($pdfFilename);

            // Send email to customer with PDF attachment
            if ($rental->renter_email) {
                \Log::info('Sending invoice email to: ' . $rental->renter_email);
                Mail::to($rental->renter_email)->queue(new InvoiceApprovedMail($rental, $pdfPath));
                \Log::info('Invoice email sent successfully');
            }
        } catch (\Exception $e) {
            \Log::error('Error generating/sending invoice: ' . $e->getMessage(), [
                'exception' => $e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            // Don't throw - just log so approval still succeeds
        }

        return redirect()->route('admin.band-rentals.show', $rental)
            ->with('success', 'Permintaan sewa band berhasil disetujui! Kode Pesanan: ' . $kodeOrder . ' | Harga Final: Rp ' . number_format($hargaFinal, 0, ',', '.') . ' | Alamat: ' . $rental->venue_address . '. Invoice telah dikirim ke email pelanggan.');

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

        // Send rejection email to renter (if email available)
        if ($rental->renter_email) {
            try {
                Mail::to($rental->renter_email)->queue(new BookingRejectedMail($rental, $validated['admin_notes']));
            } catch (\Exception $e) {
                \Log::warning('Gagal mengirim email penolakan sewa band', [
                    'rental_id' => $rental->id,
                    'email' => $rental->renter_email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

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
     * Cancel approved rental request
     */
    public function cancel(Request $request, BandRentalRequest $rental)
    {
        if ($rental->status !== 'approved') {
            return redirect()->route('admin.band-rentals.show', $rental)
                ->with('error', 'Hanya permintaan yang sudah disetujui yang dapat dibatalkan');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|min:10',
        ]);

        // Update the income record status to rejected (don't delete, just reject)
        if ($rental->income_id) {
            $income = Income::find($rental->income_id);
            if ($income) {
                $income->update([
                    'status' => 'rejected',
                    'description' => ($income->description ? $income->description . '\n\n' : '') . 
                                   'Pembatalan Sewa: ' . $validated['cancellation_reason'],
                ]);
            }
        }

        // Update rental status to cancelled with reason
        $rental->update([
            'status' => 'cancelled',
            'admin_notes' => 'Pembatalan: ' . $validated['cancellation_reason'],
        ]);

        return redirect()->route('admin.band-rentals.show', $rental)
            ->with('success', 'Permintaan sewa band berhasil dibatalkan. Status pemasukan telah diubah menjadi rejected dengan catatan pembatalan.');
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

    /**
     * Download invoice PDF
     */
    public function downloadInvoice(BandRentalRequest $rental)
    {
        if ($rental->status !== 'approved') {
            abort(403, 'Invoice hanya dapat diunduh untuk sewa yang sudah disetujui');
        }

        return InvoiceService::download($rental);
    }

    /**
     * View invoice in browser
     */
    public function viewInvoice(BandRentalRequest $rental)
    {
        if ($rental->status !== 'approved') {
            abort(403, 'Invoice hanya dapat dilihat untuk sewa yang sudah disetujui');
        }

        $rental->load(['band']);
        return InvoiceService::generate($rental)->stream();
    }
}

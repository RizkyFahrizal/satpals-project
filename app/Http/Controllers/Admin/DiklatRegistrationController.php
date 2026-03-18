<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiklatRegistration;
use App\Models\DiklatPeriod;
use App\Models\Member;
use Illuminate\Http\Request;

class DiklatRegistrationController extends Controller
{
    /**
     * Display a listing of the registrations with periods.
     */
    public function index(Request $request)
    {
        $query = DiklatRegistration::query();

        // Filter by period
        if ($request->filled('period_id')) {
            $query->where('diklat_period_id', $request->period_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('npm', 'like', "%{$search}%")
                  ->orWhere('fakultas', 'like', "%{$search}%")
                  ->orWhere('prodi', 'like', "%{$search}%");
            });
        }

        $registrations = $query->latest()->paginate(10)->withQueryString();

        // Statistics
        $stats = [
            'total' => DiklatRegistration::count(),
            'pending' => DiklatRegistration::where('status', 'pending')->count(),
            'approved' => DiklatRegistration::where('status', 'approved')->count(),
            'rejected' => DiklatRegistration::where('status', 'rejected')->count(),
        ];

        // Get all periods
        $periods = DiklatPeriod::all();

        return view('admin.diklat.index', compact('registrations', 'stats', 'periods'));
    }

    /**
     * Display the specified registration.
     */
    public function show(DiklatRegistration $registration)
    {
        return view('admin.diklat.show', compact('registration'));
    }

    /**
     * Update the status of registration.
     */
    public function updateStatus(Request $request, DiklatRegistration $registration)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $oldStatus = $registration->status;
        $newStatus = $request->status;

        $registration->update(['status' => $newStatus]);

        // If status changed to approved, create member
        if ($oldStatus !== 'approved' && $newStatus === 'approved') {
            // Check if member with this NPM doesn't exist
            if (!Member::where('npm', $registration->npm)->exists()) {
                Member::createFromDiklatRegistration($registration);
            }
        }

        $statusLabel = match($newStatus) {
            'approved' => 'diterima',
            'rejected' => 'ditolak',
            default => 'pending',
        };

        $message = "Status pendaftaran berhasil diubah menjadi {$statusLabel}.";
        if ($oldStatus !== 'approved' && $newStatus === 'approved') {
            $message .= " Data anggota berhasil ditambahkan.";
        }

        return back()->with('success', $message);
    }

    /**
     * Accept all pending registrations in a period
     */
    public function acceptAll(Request $request, DiklatPeriod $period)
    {
        $request->validate([
            'confirm' => 'required|in:yes',
        ]);

        $count = 0;
        $pending = $period->registrations()->where('status', 'pending')->get();

        foreach ($pending as $registration) {
            $registration->update(['status' => 'approved']);
            
            // Create member if doesn't exist
            if (!Member::where('npm', $registration->npm)->exists()) {
                Member::createFromDiklatRegistration($registration);
            }
            $count++;
        }

        return back()->with('success', "Berhasil menerima {$count} pendaftaran. Data anggota juga sudah ditambahkan.");
    }

    /**
     * Remove the specified registration.
     */
    public function destroy(DiklatRegistration $registration)
    {
        // Delete the bukti pembayaran file
        if ($registration->bukti_pembayaran) {
            \Storage::disk('public')->delete($registration->bukti_pembayaran);
        }

        $registration->delete();

        return redirect()->route('admin.diklat.index')->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}

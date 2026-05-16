<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiklatPeriod;
use Illuminate\Http\Request;

class DiklatPeriodController extends Controller
{
    /**
     * Display a listing of the periods.
     */
    public function index()
    {
        DiklatPeriod::syncAllStatusesFromDates();
        $periods = DiklatPeriod::latest()->paginate(10);
        return view('admin.diklat.periods.index', compact('periods'));
    }

    /**
     * Show the form for creating a new period.
     */
    public function create()
    {
        DiklatPeriod::syncAllStatusesFromDates();
        return view('admin.diklat.periods.create');
    }

    /**
     * Store a newly created period in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tahun_masuk' => 'required|integer|min:2020|max:' . (date('Y') + 10),
            'rekening_number' => 'required|string|max:50',
            'rekening_info' => 'required|string|max:500',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'nama_periode.required' => 'Nama periode wajib diisi',
            'tahun_masuk.required' => 'Tahun masuk wajib diisi',
            'rekening_number.required' => 'Nomor rekening wajib diisi',
            'rekening_info.required' => 'Info rekening wajib diisi',
            'tanggal_buka.required' => 'Tanggal buka wajib diisi',
            'tanggal_tutup.required' => 'Tanggal tutup wajib diisi',
            'tanggal_tutup.after_or_equal' => 'Tanggal tutup harus sama atau setelah tanggal buka',
        ]);

        DiklatPeriod::create($validated);

        DiklatPeriod::syncAllStatusesFromDates();

        return redirect()->route('admin.diklat.periods.index')
            ->with('success', 'Periode diklat berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified period.
     */
    public function edit(DiklatPeriod $period)
    {
        DiklatPeriod::syncAllStatusesFromDates();
        return view('admin.diklat.periods.edit', compact('period'));
    }

    /**
     * Update the specified period in database.
     */
    public function update(Request $request, DiklatPeriod $period)
    {
        $validated = $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tahun_masuk' => 'required|integer|min:2020|max:' . (date('Y') + 10),
            'rekening_number' => 'required|string|max:50',
            'rekening_info' => 'required|string|max:500',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $period->update($validated);

        DiklatPeriod::syncAllStatusesFromDates();

        return redirect()->route('admin.diklat.periods.index')
            ->with('success', 'Periode diklat berhasil diperbarui.');
    }

    /**
     * Toggle open/close status of period.
     */
    public function toggleOpen(DiklatPeriod $period)
    {
        $period->toggleOpen();
        $status = $period->is_open ? 'dibuka' : 'ditutup';
        
        return back()->with('success', "Periode diklat berhasil {$status}.");
    }

    /**
     * Delete the specified period.
     */
    public function destroy(DiklatPeriod $period)
    {
        // Check if there are registrations in this period
        if ($period->registrations()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus periode yang memiliki pendaftaran.');
        }

        $period->delete();

        return redirect()->route('admin.diklat.periods.index')
            ->with('success', 'Periode diklat berhasil dihapus.');
    }
}

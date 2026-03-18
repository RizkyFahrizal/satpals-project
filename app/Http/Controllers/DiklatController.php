<?php

namespace App\Http\Controllers;

use App\Models\DiklatRegistration;
use App\Models\DiklatPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiklatController extends Controller
{
    /**
     * Show the registration form
     */
    public function create()
    {
        // Get the active (open) period
        $activePeriod = DiklatPeriod::where('is_open', true)->first();
        
        if (!$activePeriod) {
            return view('diklat.register', [
                'activePeriod' => null,
                'isOpen' => false,
                'spesifikasiOptions' => DiklatRegistration::SPESIFIKASI_OPTIONS,
            ]);
        }

        return view('diklat.register', [
            'activePeriod' => $activePeriod,
            'isOpen' => true,
            'spesifikasiOptions' => DiklatRegistration::SPESIFIKASI_OPTIONS,
        ]);
    }

    /**
     * Store a new registration
     */
    public function store(Request $request)
    {
        // Check if there is an active period
        $activePeriod = DiklatPeriod::where('is_open', true)->first();
        
        if (!$activePeriod) {
            return redirect()->route('diklat.register')
                ->with('error', 'Pendaftaran diklat sedang ditutup.');
        }

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telepon_pribadi' => 'required|string|max:20',
            'no_telepon_ortu' => 'required|string|max:20',
            'npm' => 'required|string|max:20|unique:diklat_registrations,npm',
            'fakultas' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'spesifikasi' => 'required|array|min:1',
            'spesifikasi.*' => 'in:drum,keyboard,vocal,bass,guitar',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'riwayat_penyakit' => 'nullable|string',
            'riwayat_alergi' => 'nullable|string',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'no_telepon_pribadi.required' => 'Nomor telepon pribadi wajib diisi',
            'no_telepon_ortu.required' => 'Nomor telepon orang tua/wali wajib diisi',
            'npm.required' => 'NPM wajib diisi',
            'npm.unique' => 'NPM sudah terdaftar',
            'fakultas.required' => 'Fakultas wajib diisi',
            'prodi.required' => 'Program studi wajib diisi',
            'spesifikasi.required' => 'Pilih minimal satu spesifikasi',
            'spesifikasi.min' => 'Pilih minimal satu spesifikasi',
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diupload',
            'bukti_pembayaran.image' => 'File harus berupa gambar',
            'bukti_pembayaran.mimes' => 'Format gambar harus JPEG, PNG, atau JPG',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Handle file upload
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $validated['npm'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('bukti_pembayaran', $filename, 'public');
            $validated['bukti_pembayaran'] = $path;
        }

        // Add period ID and tahun masuk
        $validated['diklat_period_id'] = $activePeriod->id;
        $validated['tahun_masuk'] = $activePeriod->tahun_masuk;

        DiklatRegistration::create($validated);

        return redirect()->route('diklat.success')->with('success', 'Pendaftaran berhasil dikirim!');
    }

    /**
     * Show success page
     */
    public function success()
    {
        return view('diklat.success');
    }
}

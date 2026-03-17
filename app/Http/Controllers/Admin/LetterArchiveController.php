<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LetterArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LetterArchiveController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterArchive::query();

        // Filter by jenis (masuk/keluar)
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_surat', $request->tahun);
        }

        // Filter by month
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_surat', $request->bulan);
        }

        // Search by name or nomor surat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_surat', 'like', '%' . $search . '%')
                  ->orWhere('nomor_surat', 'like', '%' . $search . '%')
                  ->orWhere('perihal', 'like', '%' . $search . '%');
            });
        }

        $letters = $query->orderBy('tanggal_surat', 'desc')->paginate(15);

        // Get available years for filter
        $years = LetterArchive::selectRaw('YEAR(tanggal_surat) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Stats for current filter
        $stats = [
            'total' => $query->count(),
            'masuk' => (clone $query)->where('jenis', 'masuk')->count(),
            'keluar' => (clone $query)->where('jenis', 'keluar')->count(),
        ];

        return view('admin.letters.index', compact('letters', 'years', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_surat' => 'required|string|max:255',
            'nomor_surat' => 'nullable|string|max:100',
            'jenis' => 'required|in:masuk,keluar',
            'tanggal_surat' => 'required|date',
            'pengirim' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'perihal' => 'nullable|string|max:500',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'nama_surat.required' => 'Nama surat wajib diisi',
            'jenis.required' => 'Jenis surat wajib dipilih',
            'tanggal_surat.required' => 'Tanggal surat wajib diisi',
            'file.required' => 'File surat wajib diupload',
            'file.mimes' => 'Format file harus PDF, Word (doc/docx), atau Excel (xls/xlsx)',
            'file.max' => 'Ukuran file maksimal 10MB',
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileType = $file->getClientOriginalExtension();
        $fileSize = $file->getSize();
        
        // Store file
        $filePath = $file->store('letter-archives', 'public');

        LetterArchive::create([
            'nama_surat' => $request->nama_surat,
            'nomor_surat' => $request->nomor_surat,
            'jenis' => $request->jenis,
            'tanggal_surat' => $request->tanggal_surat,
            'pengirim' => $request->pengirim,
            'penerima' => $request->penerima,
            'perihal' => $request->perihal,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => strtolower($fileType),
            'file_size' => $fileSize,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.letters.index')->with('success', 'Arsip surat berhasil ditambahkan!');
    }

    public function show(LetterArchive $letter)
    {
        return view('admin.letters.show', compact('letter'));
    }

    public function destroy(LetterArchive $letter)
    {
        // Delete file from storage
        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            Storage::disk('public')->delete($letter->file_path);
        }

        $letter->delete();

        return redirect()->route('admin.letters.index')->with('success', 'Arsip surat berhasil dihapus!');
    }

    public function download(LetterArchive $letter)
    {
        if (!Storage::disk('public')->exists($letter->file_path)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        return Storage::disk('public')->download($letter->file_path, $letter->file_name);
    }

    public function preview(LetterArchive $letter)
    {
        if (!Storage::disk('public')->exists($letter->file_path)) {
            return back()->with('error', 'File tidak ditemukan!');
        }

        // For PDF, return file for browser preview
        if ($letter->file_type === 'pdf') {
            return response()->file(Storage::disk('public')->path($letter->file_path));
        }

        // For other files, redirect to show page
        return redirect()->route('admin.letters.show', $letter);
    }
}

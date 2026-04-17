<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities
     */
    public function index(Request $request): View
    {
        $query = Activity::query();

        // Search by judul
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_kegiatan', 'like', "%{$search}%")
                  ->orWhere('tempat_kegiatan', 'like', "%{$search}%")
                  ->orWhere('ketua_pelaksana', 'like', "%{$search}%");
            });
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_kegiatan', $request->tahun);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $activities = $query->orderBy('tanggal_kegiatan', 'desc')->paginate(12)->withQueryString();

        // Get years for filter
        $years = Activity::selectRaw('YEAR(tanggal_kegiatan) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        // Stats
        $totalActivities = Activity::count();
        $publishedCount = Activity::where('is_published', true)->count();

        return view('admin.activities.index', compact(
            'activities',
            'years',
            'totalActivities',
            'publishedCount'
        ));
    }

    /**
     * Show the form for creating a new activity
     */
    public function create(): View
    {
        return view('admin.activities.create');
    }

    /**
     * Store a newly created activity
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'tujuan_kegiatan' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'ketua_pelaksana' => 'nullable|string|max:255',
            'divisi' => 'nullable|array',
            'divisi.*.nama_divisi' => 'nullable|string|max:255',
            'divisi.*.ketua_divisi' => 'nullable|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'waktu_mulai' => 'nullable|date_format:H:i',
            'waktu_selesai' => 'nullable|date_format:H:i',
            'tempat_kegiatan' => 'nullable|string|max:255',
            'foto_1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'foto_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'boolean',
        ]);

        // Handle photo uploads
        foreach (['foto_1', 'foto_2', 'foto_3'] as $foto) {
            if ($request->hasFile($foto)) {
                $validated[$foto] = $request->file($foto)->store('activities', 'public');
            }
        }

        // Filter empty divisi entries
        if (isset($validated['divisi'])) {
            $validated['divisi'] = array_filter($validated['divisi'], function ($item) {
                return !empty($item['nama_divisi']) || !empty($item['ketua_divisi']);
            });
            $validated['divisi'] = array_values($validated['divisi']);
        }

        $validated['is_published'] = $request->boolean('is_published');

        Activity::create($validated);

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil ditambahkan!');
    }

    /**
     * Display the specified activity
     */
    public function show(Activity $activity): View
    {
        return view('admin.activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity
     */
    public function edit(Activity $activity): View
    {
        return view('admin.activities.edit', compact('activity'));
    }

    /**
     * Update the specified activity
     */
    public function update(Request $request, Activity $activity): RedirectResponse
    {
        try {
            // Get current activity data SEBELUM update
            $originalData = $activity->getAttributes();

            $validated = $request->validate([
                'judul_kegiatan' => 'required|string|max:255',
                'tujuan_kegiatan' => 'nullable|string',
                'deskripsi' => 'nullable|string',
                'ketua_pelaksana' => 'nullable|string|max:255',
                'divisi' => 'nullable|array',
                'divisi.*.nama_divisi' => 'nullable|string|max:255',
                'divisi.*.ketua_divisi' => 'nullable|string|max:255',
                'tanggal_kegiatan' => 'required|date',
                'waktu_mulai' => 'nullable|date_format:H:i',
                'waktu_selesai' => 'nullable|date_format:H:i',
                'tempat_kegiatan' => 'nullable|string|max:255',
                'foto_1' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'foto_2' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'foto_3' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'is_published' => 'nullable|boolean',
            ]);

            // Prepare update data - HANYA field yang benar-benar diubah
            $updateData = [];

            // Required fields - always include
            $updateData['judul_kegiatan'] = $validated['judul_kegiatan'];
            $updateData['tanggal_kegiatan'] = $validated['tanggal_kegiatan'];

            // Nullable text fields - PRESERVE original jika null/empty
            $textFields = ['tujuan_kegiatan', 'deskripsi', 'ketua_pelaksana', 'waktu_mulai', 'waktu_selesai', 'tempat_kegiatan'];
            foreach ($textFields as $field) {
                // Jika tidak null dari validation, include
                if ($validated[$field] !== null) {
                    $updateData[$field] = $validated[$field];
                } else {
                    // Jika null, preserve original value
                    $updateData[$field] = $originalData[$field] ?? null;
                }
            }

            // Handle photos
            foreach (['foto_1', 'foto_2', 'foto_3'] as $foto) {
                if ($request->boolean("remove_{$foto}")) {
                    if ($activity->$foto) {
                        Storage::disk('public')->delete($activity->$foto);
                    }
                    $updateData[$foto] = null;
                } elseif ($request->hasFile($foto)) {
                    if ($activity->$foto) {
                        Storage::disk('public')->delete($activity->$foto);
                    }
                    $updateData[$foto] = $request->file($foto)->store('activities', 'public');
                } else {
                    // Preserve original photo
                    $updateData[$foto] = $originalData[$foto] ?? null;
                }
            }

            // Handle divisi - SANGAT PENTING: preserve original jika tidak ada divisi baru
            if (isset($validated['divisi']) && is_array($validated['divisi']) && !empty($validated['divisi'])) {
                $filteredDivisi = array_filter($validated['divisi'], function ($item) {
                    return !empty($item['nama_divisi']) || !empty($item['ketua_divisi']);
                });
                
                if (!empty($filteredDivisi)) {
                    $updateData['divisi'] = array_values($filteredDivisi);
                } else {
                    // Jika semua divisi kosong, PRESERVE original
                    $updateData['divisi'] = $originalData['divisi'] ?? null;
                }
            } else {
                // Jika divisi field tidak ada di request, preserve original
                $updateData['divisi'] = $originalData['divisi'] ?? null;
            }

            // Handle is_published
            $updateData['is_published'] = $request->boolean('is_published', $originalData['is_published'] ?? false);

            $activity->update($updateData);

            return redirect()->route('admin.activities.index')
                ->with('success', 'Kegiatan berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan kegiatan: ' . $e->getMessage());
        }
    }

    /**
     * Toggle publish status
     */
    public function togglePublish(Activity $activity): RedirectResponse
    {
        $activity->update(['is_published' => !$activity->is_published]);

        $status = $activity->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Kegiatan berhasil {$status}!");
    }

    /**
     * Remove the specified activity
     */
    public function destroy(Activity $activity): RedirectResponse
    {
        // Delete photos
        foreach (['foto_1', 'foto_2', 'foto_3'] as $foto) {
            if ($activity->$foto) {
                Storage::disk('public')->delete($activity->$foto);
            }
        }

        $activity->delete();

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil dihapus!');
    }
}

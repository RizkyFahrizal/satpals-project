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

        // Handle photo uploads and removals
        foreach (['foto_1', 'foto_2', 'foto_3'] as $foto) {
            // Check if photo should be removed
            if ($request->boolean("remove_{$foto}")) {
                if ($activity->$foto) {
                    Storage::disk('public')->delete($activity->$foto);
                }
                $validated[$foto] = null;
            }
            // Check if new photo uploaded
            elseif ($request->hasFile($foto)) {
                // Delete old photo
                if ($activity->$foto) {
                    Storage::disk('public')->delete($activity->$foto);
                }
                $validated[$foto] = $request->file($foto)->store('activities', 'public');
            } else {
                // Keep existing photo
                unset($validated[$foto]);
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

        $activity->update($validated);

        return redirect()->route('admin.activities.index')
            ->with('success', 'Kegiatan berhasil diperbarui!');
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

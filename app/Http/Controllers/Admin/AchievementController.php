<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    /**
     * Display a listing of achievements.
     */
    public function index(Request $request)
    {
        $query = Achievement::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_lomba', 'like', "%{$search}%")
                  ->orWhere('nama_band', 'like', "%{$search}%")
                  ->orWhere('tempat_lomba', 'like', "%{$search}%")
                  ->orWhere('penyelenggara', 'like', "%{$search}%");
            });
        }

        // Filter by juara
        if ($request->filled('juara')) {
            $query->where('juara', $request->juara);
        }

        // Filter by year
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_lomba', $request->tahun);
        }

        $achievements = $query->orderBy('tanggal_lomba', 'desc')->paginate(10)->withQueryString();

        // Get years for filter
        $years = Achievement::selectRaw('YEAR(tanggal_lomba) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Statistics
        $stats = [
            'total' => Achievement::count(),
            'juara_1' => Achievement::where('juara', 'juara_1')->count(),
            'published' => Achievement::where('is_published', true)->count(),
        ];

        $juaraOptions = Achievement::JUARA_OPTIONS;

        return view('admin.achievements.index', compact('achievements', 'stats', 'years', 'juaraOptions'));
    }

    /**
     * Show the form for creating a new achievement.
     */
    public function create()
    {
        $juaraOptions = Achievement::JUARA_OPTIONS;
        return view('admin.achievements.create', compact('juaraOptions'));
    }

    /**
     * Store a newly created achievement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_lomba' => 'required|string|max:255',
            'juara' => 'required|string',
            'deskripsi' => 'nullable|string',
            'nama_band' => 'nullable|string|max:255',
            'anggota' => 'nullable|string', // Will be converted to array
            'tanggal_lomba' => 'required|date',
            'tempat_lomba' => 'required|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'foto_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_published' => 'nullable|boolean',
        ]);

        // Convert anggota string to array
        if (!empty($validated['anggota'])) {
            $validated['anggota'] = array_map('trim', explode(',', $validated['anggota']));
        } else {
            $validated['anggota'] = [];
        }

        // Handle photo uploads
        foreach (['foto_1', 'foto_2', 'foto_3'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('achievements', $filename, 'public');
                $validated[$field] = $path;
            }
        }

        $validated['is_published'] = $request->has('is_published');

        Achievement::create($validated);

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    /**
     * Display the specified achievement.
     */
    public function show(Achievement $achievement)
    {
        return view('admin.achievements.show', compact('achievement'));
    }

    /**
     * Show the form for editing the specified achievement.
     */
    public function edit(Achievement $achievement)
    {
        $juaraOptions = Achievement::JUARA_OPTIONS;
        return view('admin.achievements.edit', compact('achievement', 'juaraOptions'));
    }

    /**
     * Update the specified achievement.
     */
    public function update(Request $request, Achievement $achievement)
    {
        $validated = $request->validate([
            'judul_lomba' => 'required|string|max:255',
            'juara' => 'required|string',
            'deskripsi' => 'nullable|string',
            'nama_band' => 'nullable|string|max:255',
            'anggota' => 'nullable|string',
            'tanggal_lomba' => 'required|date',
            'tempat_lomba' => 'required|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'foto_1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_published' => 'nullable|boolean',
        ]);

        // Convert anggota string to array
        if (!empty($validated['anggota'])) {
            $validated['anggota'] = array_map('trim', explode(',', $validated['anggota']));
        } else {
            $validated['anggota'] = [];
        }

        // Handle photo uploads
        foreach (['foto_1', 'foto_2', 'foto_3'] as $field) {
            if ($request->hasFile($field)) {
                // Delete old photo
                if ($achievement->$field) {
                    Storage::disk('public')->delete($achievement->$field);
                }
                
                $file = $request->file($field);
                $filename = time() . '_' . $field . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('achievements', $filename, 'public');
                $validated[$field] = $path;
            } else {
                // Keep existing photo
                unset($validated[$field]);
            }
        }

        // Handle remove photo
        foreach (['foto_1', 'foto_2', 'foto_3'] as $field) {
            if ($request->has('remove_' . $field) && $achievement->$field) {
                Storage::disk('public')->delete($achievement->$field);
                $validated[$field] = null;
            }
        }

        $validated['is_published'] = $request->has('is_published');

        $achievement->update($validated);

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil diperbarui.');
    }

    /**
     * Toggle published status.
     */
    public function togglePublish(Achievement $achievement)
    {
        $achievement->update(['is_published' => !$achievement->is_published]);

        $status = $achievement->is_published ? 'dipublikasikan' : 'disembunyikan';
        return back()->with('success', "Prestasi berhasil {$status}.");
    }

    /**
     * Remove the specified achievement.
     */
    public function destroy(Achievement $achievement)
    {
        // Delete photos
        foreach (['foto_1', 'foto_2', 'foto_3'] as $field) {
            if ($achievement->$field) {
                Storage::disk('public')->delete($achievement->$field);
            }
        }

        $achievement->delete();

        return redirect()->route('admin.achievements.index')->with('success', 'Prestasi berhasil dihapus.');
    }
}

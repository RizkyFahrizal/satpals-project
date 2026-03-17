<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrestasiController extends Controller
{
    /**
     * Display public prestasi/achievements gallery page
     */
    public function index(Request $request): View
    {
        $query = Achievement::published()->latest();

        // Filter by year if provided
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_lomba', $request->tahun);
        }

        // Filter by juara if provided
        if ($request->filled('juara')) {
            $query->where('juara', $request->juara);
        }

        // Search by judul or nama_band
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul_lomba', 'like', "%{$search}%")
                  ->orWhere('nama_band', 'like', "%{$search}%")
                  ->orWhere('tempat_lomba', 'like', "%{$search}%");
            });
        }

        $achievements = $query->paginate(12)->withQueryString();

        // Get years for filter
        $years = Achievement::published()
            ->selectRaw('YEAR(tanggal_lomba) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        // Get stats
        $totalAchievements = Achievement::published()->count();
        $juaraCount = Achievement::published()->whereIn('juara', ['juara_1', 'juara_2', 'juara_3'])->count();

        return view('prestasi.index', compact(
            'achievements',
            'years',
            'totalAchievements',
            'juaraCount'
        ));
    }

    /**
     * Display a specific achievement
     */
    public function show(Achievement $achievement): View
    {
        // Only show published achievements
        if (!$achievement->is_published) {
            abort(404);
        }

        // Get related achievements (same year or similar juara)
        $relatedAchievements = Achievement::published()
            ->where('id', '!=', $achievement->id)
            ->where(function ($query) use ($achievement) {
                $query->whereYear('tanggal_lomba', $achievement->tanggal_lomba->year)
                      ->orWhere('juara', $achievement->juara);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('prestasi.show', compact('achievement', 'relatedAchievements'));
    }
}

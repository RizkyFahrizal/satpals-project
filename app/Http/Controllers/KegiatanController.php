<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    /**
     * Display public kegiatan/activities gallery page
     */
    public function index(Request $request): View
    {
        $query = Activity::published()->latest();

        // Filter by year if provided
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_kegiatan', $request->tahun);
        }

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul_kegiatan', 'like', "%{$search}%")
                  ->orWhere('tempat_kegiatan', 'like', "%{$search}%")
                  ->orWhere('ketua_pelaksana', 'like', "%{$search}%");
            });
        }

        $activities = $query->paginate(12)->withQueryString();

        // Get years for filter
        $years = Activity::published()
            ->selectRaw('YEAR(tanggal_kegiatan) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        // Get stats
        $totalActivities = Activity::published()->count();
        $thisYearCount = Activity::published()->whereYear('tanggal_kegiatan', date('Y'))->count();

        return view('kegiatan.index', compact(
            'activities',
            'years',
            'totalActivities',
            'thisYearCount'
        ));
    }

    /**
     * Display a specific activity
     */
    public function show(Activity $activity): View
    {
        // Only show published activities
        if (!$activity->is_published) {
            abort(404);
        }

        // Get related activities (same year)
        $relatedActivities = Activity::published()
            ->where('id', '!=', $activity->id)
            ->whereYear('tanggal_kegiatan', $activity->tanggal_kegiatan->year)
            ->latest()
            ->take(4)
            ->get();

        return view('kegiatan.show', compact('activity', 'relatedActivities'));
    }
}

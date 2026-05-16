<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Activity;
use App\Models\BoardMember;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display home page
     */
    public function index(): View
    {
        // Get latest published achievements for homepage preview
        $achievements = Achievement::published()
            ->latest()
            ->take(4)
            ->get();

        // Get latest published activities for homepage preview
        $activities = Activity::published()
            ->latest()
            ->take(4)
            ->get();

        // Get current active Ketua Umum (from BoardMember)
        $ketuaUmum = BoardMember::where('jabatan', 'ketua_umum')
            ->where('is_active', true)
            ->with(['member', 'diklatPeriod'])
            ->first();

        return view('home', compact('achievements', 'activities', 'ketuaUmum'));
    }
}

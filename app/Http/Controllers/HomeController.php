<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Activity;
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

        return view('home', compact('achievements', 'activities'));
    }
}

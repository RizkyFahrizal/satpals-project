<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display admin dashboard page
     */
    public function index(): View
    {
        return view('admin.index');
    }
}

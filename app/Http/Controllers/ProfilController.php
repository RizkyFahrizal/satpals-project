<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    /**
     * Display the UKM Musik profile page.
     */
    public function index()
    {
        return view('profil.index');
    }
}

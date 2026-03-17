<?php

namespace App\Http\Controllers;

use App\Models\BoardMember;
use Illuminate\Http\Request;

class StrukturController extends Controller
{
    /**
     * Display the organization structure for public.
     */
    public function index(Request $request)
    {
        $currentPeriode = BoardMember::getCurrentPeriode();
        $selectedPeriode = $request->get('periode', $currentPeriode);

        // Get active board members for selected periode
        $boardMembers = BoardMember::with('member')
            ->where('periode', $selectedPeriode)
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        // Group by jabatan type
        $pimpinan = $boardMembers->whereIn('jabatan', BoardMember::JABATAN_PIMPINAN);
        $subsie = $boardMembers->whereIn('jabatan', BoardMember::JABATAN_SUBSIE);

        // Get all available periodes that have members
        $periodeList = BoardMember::distinct()
            ->pluck('periode')
            ->filter()
            ->sort()
            ->reverse()
            ->values();

        // Add current periode if not exists
        if (!$periodeList->contains($currentPeriode)) {
            $periodeList->prepend($currentPeriode);
        }

        return view('struktur.index', compact(
            'boardMembers',
            'pimpinan',
            'subsie',
            'periodeList',
            'selectedPeriode',
            'currentPeriode'
        ));
    }
}

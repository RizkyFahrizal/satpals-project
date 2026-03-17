<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoardMemberController extends Controller
{
    /**
     * Display a listing of the board members.
     */
    public function index(Request $request)
    {
        $currentPeriode = BoardMember::getCurrentPeriode();
        $selectedPeriode = $request->get('periode', $currentPeriode);

        $query = BoardMember::with(['member', 'user'])
            ->where('periode', $selectedPeriode)
            ->orderBy('urutan')
            ->orderBy('jabatan');

        $boardMembers = $query->get();

        // Group by jabatan type
        $grouped = [
            'pimpinan' => $boardMembers->whereIn('jabatan', BoardMember::JABATAN_PIMPINAN),
            'subsie' => $boardMembers->whereIn('jabatan', BoardMember::JABATAN_SUBSIE),
        ];

        // Get all available periodes
        $periodeList = BoardMember::distinct()->pluck('periode')->filter()->sort()->reverse()->values();
        if (!$periodeList->contains($currentPeriode)) {
            $periodeList->prepend($currentPeriode);
        }

        // Get active members for selection
        $availableMembers = Member::where('status', 'aktif')
            ->whereDoesntHave('boardPositions', function($q) use ($selectedPeriode) {
                $q->where('periode', $selectedPeriode);
            })
            ->orderBy('nama_lengkap')
            ->get();

        $jabatanOptions = BoardMember::JABATAN_OPTIONS;

        return view('admin.board.index', compact(
            'boardMembers', 
            'grouped', 
            'periodeList', 
            'selectedPeriode', 
            'currentPeriode',
            'availableMembers',
            'jabatanOptions'
        ));
    }

    /**
     * Store a newly created board member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'jabatan' => 'required|string',
            'periode' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'create_account' => 'nullable|boolean',
        ]);

        // Check if member already has position in this periode
        if (BoardMember::where('member_id', $validated['member_id'])
            ->where('periode', $validated['periode'])
            ->exists()) {
            return back()->with('error', 'Anggota sudah memiliki jabatan di periode ini.');
        }

        // Get max urutan
        $maxUrutan = BoardMember::where('periode', $validated['periode'])->max('urutan') ?? 0;

        // Handle foto upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('board-members', 'public');
        }

        $boardMember = BoardMember::create([
            'member_id' => $validated['member_id'],
            'jabatan' => $validated['jabatan'],
            'periode' => $validated['periode'],
            'foto' => $fotoPath,
            'is_active' => true,
            'urutan' => $maxUrutan + 1,
        ]);

        // Create user account if requested
        if ($request->filled('create_account') && $request->create_account) {
            $user = $boardMember->createUserAccount();
            return back()->with('success', "Pengurus berhasil ditambahkan. Akun login: {$user->email} / satpals123");
        }

        return back()->with('success', 'Pengurus berhasil ditambahkan.');
    }

    /**
     * Update the specified board member.
     */
    public function update(Request $request, BoardMember $boardMember)
    {
        $validated = $request->validate([
            'jabatan' => 'required|string',
            'urutan' => 'nullable|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'hapus_foto' => 'nullable|boolean',
        ]);

        // Handle hapus foto
        if ($request->filled('hapus_foto') && $request->hapus_foto) {
            if ($boardMember->foto) {
                Storage::disk('public')->delete($boardMember->foto);
            }
            $validated['foto'] = null;
        }
        // Handle foto upload
        elseif ($request->hasFile('foto')) {
            // Delete old foto
            if ($boardMember->foto) {
                Storage::disk('public')->delete($boardMember->foto);
            }
            $validated['foto'] = $request->file('foto')->store('board-members', 'public');
        } else {
            unset($validated['foto']);
        }

        unset($validated['hapus_foto']);
        $boardMember->update($validated);

        return back()->with('success', 'Data pengurus berhasil diperbarui.');
    }

    /**
     * Toggle active status of board member.
     */
    public function toggleStatus(BoardMember $boardMember)
    {
        $boardMember->update(['is_active' => !$boardMember->is_active]);

        $status = $boardMember->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Pengurus berhasil {$status}.");
    }

    /**
     * Create user account for board member.
     */
    public function createAccount(BoardMember $boardMember)
    {
        if ($boardMember->user_id) {
            return back()->with('error', 'Pengurus sudah memiliki akun login.');
        }

        $user = $boardMember->createUserAccount();

        return back()->with('success', "Akun login berhasil dibuat. Email: {$user->email} / Password: satpals123");
    }

    /**
     * Remove the specified board member.
     */
    public function destroy(BoardMember $boardMember)
    {
        $boardMember->delete();

        return back()->with('success', 'Pengurus berhasil dihapus dari struktur.');
    }

    /**
     * Reorder board members.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:board_members,id',
            'orders.*.urutan' => 'required|integer|min:0',
        ]);

        foreach ($validated['orders'] as $order) {
            BoardMember::where('id', $order['id'])->update(['urutan' => $order['urutan']]);
        }

        return response()->json(['success' => true]);
    }
}

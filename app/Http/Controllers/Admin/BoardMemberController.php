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

        // Group by jabatan type (per subsie/position)
        $grouped = [];
        $grouped['mpa'] = $boardMembers->where('jabatan', 'mpa');
        $grouped['pimpinan'] = $boardMembers->whereIn('jabatan', ['ketua_umum', 'wakil_ketua_umum', 'sekretaris', 'bendahara']);

        // Subsie - group individually
        $grouped['subsie_kesekretariatan'] = $boardMembers->where('jabatan', 'subsie_kesekretariatan');
        $grouped['subsie_peralatan'] = $boardMembers->where('jabatan', 'subsie_peralatan');
        $grouped['subsie_humas'] = $boardMembers->where('jabatan', 'subsie_humas');
        $grouped['subsie_pdd'] = $boardMembers->where('jabatan', 'subsie_pdd');
        $grouped['subsie_band'] = $boardMembers->where('jabatan', 'subsie_band');

        // Get all available periodes from members angkatan (dynamically from active members)
        // Periode only appears when there are members registered with that year
        $periodeList = BoardMember::getAvailablePeriodes();

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
        // Check authorization: only super_admin, ketua_umum, wakil_ketua_umum, and mpa (active) can add
        if (!auth()->user()->canAddBoardMembers($request->get('periode'))) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menambah pengurus. Hanya Ketua Umum, Wakil Ketua Umum, dan MPA yang aktif yang dapat menambah pengurus.');
        }

        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'jabatan' => 'required|string|in:' . implode(',', array_keys(BoardMember::JABATAN_OPTIONS)),
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

        // Check for unique position constraint (ketua_umum, wakil_ketua_umum, bendahara, sekretaris max 1 per periode)
        $uniquePositions = ['ketua_umum', 'wakil_ketua_umum', 'bendahara', 'sekretaris'];
        if (in_array($validated['jabatan'], $uniquePositions)) {
            $existingPosition = BoardMember::where('jabatan', $validated['jabatan'])
                ->where('periode', $validated['periode'])
                ->exists();
            
            if ($existingPosition) {
                $jabatanLabel = BoardMember::JABATAN_OPTIONS[$validated['jabatan']] ?? $validated['jabatan'];
                return back()->with('error', "Jabatan {$jabatanLabel} sudah terisi untuk periode {$validated['periode']}. Maksimal 1 orang per jabatan per periode.");
            }
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
            if ($boardMember->member?->user) {
                return back()->with('error', 'Pengurus berhasil ditambahkan, tetapi akun login tidak dibuat karena member ini sudah memiliki akun.');
            }

            $user = $boardMember->createUserAccount();
            $defaultPassword = strtolower(str_replace(' ', '', $boardMember->member->nama_lengkap));
            $message = "Pengurus berhasil ditambahkan. Akun login: {$user->email} / {$defaultPassword}";
            return back()->with('success', $message);
        }

            return back()->with('success', 'Pengurus berhasil ditambahkan.');
    }

    /**
     * Update the specified board member.
     */
    public function update(Request $request, BoardMember $boardMember)
    {
        // Check authorization: only super_admin, ketua_umum, wakil_ketua_umum, and mpa (active) can update
        if (!auth()->user()->canAddBoardMembers($boardMember->periode)) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengubah pengurus.');
        }

        $validated = $request->validate([
            'jabatan' => 'required|string',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'hapus_foto' => 'nullable|boolean',
        ]);

        // Check for unique position constraint if jabatan is being changed
        $uniquePositions = ['ketua_umum', 'wakil_ketua_umum', 'bendahara', 'sekretaris'];
        if ($validated['jabatan'] !== $boardMember->jabatan && in_array($validated['jabatan'], $uniquePositions)) {
            $existingPosition = BoardMember::where('jabatan', $validated['jabatan'])
                ->where('periode', $boardMember->periode)
                ->where('id', '!=', $boardMember->id)
                ->exists();
            
            if ($existingPosition) {
                $jabatanLabel = BoardMember::JABATAN_OPTIONS[$validated['jabatan']] ?? $validated['jabatan'];
                return back()->with('error', "Jabatan {$jabatanLabel} sudah terisi untuk periode {$boardMember->periode}. Maksimal 1 orang per jabatan per periode.");
            }
        }

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

        // Convert is_active to boolean
        $validated['is_active'] = (bool) $request->filled('is_active');

        unset($validated['hapus_foto']);
        $boardMember->update($validated);

            return back()->with('success', 'Pengurus berhasil diupdate.');
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
        if ($boardMember->user_id || $boardMember->member?->user) {
            $existingUser = $boardMember->member?->user;
            $message = $existingUser
                ? 'Pengurus ini sudah memiliki akun login. Tidak dibuat akun baru agar data tidak dobel.'
                : 'Pengurus sudah memiliki akun login.';

            return back()->with('error', $message);
        }

        $user = $boardMember->createUserAccount();

        return back()->with('success', "Akun login berhasil dibuat. Email: {$user->email} / Password: satpals123");
    }

    /**
     * Remove the specified board member.
     */
    public function destroy(BoardMember $boardMember)
    {
        // Check authorization: only super_admin, ketua_umum, wakil_ketua_umum, and mpa (active) can delete
        if (!auth()->user()->canAddBoardMembers($boardMember->periode)) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus pengurus.');
        }

        $boardMember->delete();

        return back()->with('success', 'Pengurus berhasil dihapus dari struktur.');
    }

    /**
     * Search members for board position (API endpoint)
     */
    public function searchMembers(Request $request)
    {
        $search = $request->get('search', '');
        $periode = $request->get('periode');

        $query = Member::where('status', 'aktif');

        // Search by nama or jabatan from board_members
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('npm', 'like', "%{$search}%");
            });
        }

        // Exclude members who already have position in this periode
        if ($periode) {
            $query->whereDoesntHave('boardPositions', function($q) use ($periode) {
                $q->where('periode', $periode);
            });
        }

        $members = $query->with('user')
            ->select('id', 'nama_lengkap', 'npm', 'prodi')
            ->limit(10)
            ->get()
            ->map(function($member) {
                $accountStatus = $member->user ? 'Punya Akun' : 'Belum Punya Akun';
                $accountClass = $member->user ? 'text-green-600' : 'text-blue-600';
                
                return [
                    'id' => $member->id,
                    'text' => "{$member->nama_lengkap} ({$member->npm}) - {$member->prodi}",
                    'nama_lengkap' => $member->nama_lengkap,
                    'npm' => $member->npm,
                    'has_account' => (bool) $member->user,
                    'account_status' => $accountStatus,
                    'account_class' => $accountClass,
                ];
            });

        return response()->json($members);
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

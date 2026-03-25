<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BoardMember;
use App\Models\Member;
use App\Models\DiklatPeriod;
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

        // Get all available periodes (only years where members registered)
        // Collect tahun_daftar from active members
        $memberYears = Member::where('status', 'aktif')
            ->distinct('tahun_daftar')
            ->pluck('tahun_daftar')
            ->filter()
            ->map(fn($year) => $year . '/' . ($year + 1))
            ->sort()
            ->reverse()
            ->values();

        // Also include existing board periods
        $boardPeriodes = BoardMember::distinct('periode')
            ->pluck('periode')
            ->filter()
            ->sort()
            ->reverse()
            ->values();

        // Merge and deduplicate
        $periodeList = $memberYears->merge($boardPeriodes)->unique()->sort()->reverse()->values();

        // Get active members for selection
        $availableMembers = Member::where('status', 'aktif')
            ->whereDoesntHave('boardPositions', function($q) use ($selectedPeriode) {
                $q->where('periode', $selectedPeriode);
            })
            ->orderBy('nama_lengkap')
            ->get();

        $jabatanOptions = BoardMember::JABATAN_OPTIONS;

        // Get diklat periods for form select (only with registered members)
        $diklatPeriods = DiklatPeriod::whereHas('members', function($q) {
            $q->where('status', 'aktif');
        })->orderBy('tahun_masuk', 'desc')->get();

        return view('admin.board.index', compact(
            'boardMembers', 
            'grouped', 
            'periodeList', 
            'selectedPeriode', 
            'currentPeriode',
            'availableMembers',
            'jabatanOptions',
            'diklatPeriods'
        ));
    }

    /**
     * Store a newly created board member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'diklat_period_id' => 'nullable|exists:diklat_periods,id',
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

        // Get tanggal_buka and tanggal_tutup from diklat_period if provided
        $tanggalBuka = null;
        $tanggalTutup = null;
        if ($validated['diklat_period_id']) {
            $period = \App\Models\DiklatPeriod::find($validated['diklat_period_id']);
            if ($period) {
                $tanggalBuka = $period->tanggal_buka;
                $tanggalTutup = $period->tanggal_tutup;
            }
        }

        // Handle foto upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('board-members', 'public');
        }

        $boardMember = BoardMember::create([
            'member_id' => $validated['member_id'],
            'diklat_period_id' => $validated['diklat_period_id'],
            'jabatan' => $validated['jabatan'],
            'periode' => $validated['periode'],
            'tanggal_buka' => $tanggalBuka,
            'tanggal_tutup' => $tanggalTutup,
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

        $members = $query->select('id', 'nama_lengkap', 'npm', 'prodi')
            ->limit(10)
            ->get()
            ->map(function($member) {
                return [
                    'id' => $member->id,
                    'text' => "{$member->nama_lengkap} ({$member->npm}) - {$member->prodi}",
                    'nama_lengkap' => $member->nama_lengkap,
                    'npm' => $member->npm,
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

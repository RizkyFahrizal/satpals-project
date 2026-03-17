<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     */
    public function index(Request $request)
    {
        $query = Member::with(['activeBoardPosition']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by angkatan
        if ($request->filled('angkatan')) {
            $query->where('angkatan', $request->angkatan);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('npm', 'like', "%{$search}%")
                  ->orWhere('fakultas', 'like', "%{$search}%")
                  ->orWhere('prodi', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(10)->withQueryString();

        // Get available angkatan for filter
        $angkatanList = Member::distinct()->pluck('angkatan')->filter()->sort()->values();

        // Statistics
        $stats = [
            'total' => Member::count(),
            'aktif' => Member::where('status', 'aktif')->count(),
            'alumni' => Member::where('status', 'alumni')->count(),
            'pengurus' => Member::whereHas('boardPositions', fn($q) => $q->where('is_active', true))->count(),
        ];

        return view('admin.members.index', compact('members', 'stats', 'angkatanList'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        $spesifikasiOptions = Member::SPESIFIKASI_OPTIONS;
        return view('admin.members.create', compact('spesifikasiOptions'));
    }

    /**
     * Store a newly created member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telepon' => 'required|string|max:20',
            'npm' => 'required|string|max:20|unique:members,npm',
            'fakultas' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'spesifikasi' => 'required|array|min:1',
            'spesifikasi.*' => 'in:drum,keyboard,vocal,bass,guitar',
            'tahun_daftar' => 'required|integer|min:2000|max:' . (now()->year + 1),
            'angkatan' => 'required|string|max:10',
            'status' => 'required|in:aktif,alumni,keluar',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $validated['npm'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('members', $filename, 'public');
            $validated['foto'] = $path;
        }

        Member::create($validated);

        return redirect()->route('admin.members.index')->with('success', 'Data anggota berhasil ditambahkan.');
    }

    /**
     * Display the specified member.
     */
    public function show(Member $member)
    {
        $member->load(['boardPositions.user', 'diklatRegistration', 'user']);
        return view('admin.members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(Member $member)
    {
        $spesifikasiOptions = Member::SPESIFIKASI_OPTIONS;
        return view('admin.members.edit', compact('member', 'spesifikasiOptions'));
    }

    /**
     * Update the specified member.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'no_telepon' => 'required|string|max:20',
            'npm' => 'required|string|max:20|unique:members,npm,' . $member->id,
            'fakultas' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'spesifikasi' => 'required|array|min:1',
            'spesifikasi.*' => 'in:drum,keyboard,vocal,bass,guitar',
            'tahun_daftar' => 'required|integer|min:2000|max:' . (now()->year + 1),
            'angkatan' => 'required|string|max:10',
            'status' => 'required|in:aktif,alumni,keluar',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo
            if ($member->foto) {
                Storage::disk('public')->delete($member->foto);
            }
            
            $file = $request->file('foto');
            $filename = time() . '_' . $validated['npm'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('members', $filename, 'public');
            $validated['foto'] = $path;
        }

        $member->update($validated);

        return redirect()->route('admin.members.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    /**
     * Update member status.
     */
    public function updateStatus(Request $request, Member $member)
    {
        $request->validate([
            'status' => 'required|in:aktif,alumni,keluar',
        ]);

        $member->update(['status' => $request->status]);

        return back()->with('success', 'Status anggota berhasil diperbarui.');
    }

    /**
     * Remove the specified member.
     */
    public function destroy(Member $member)
    {
        // Delete photo
        if ($member->foto) {
            Storage::disk('public')->delete($member->foto);
        }

        $member->delete();

        return redirect()->route('admin.members.index')->with('success', 'Data anggota berhasil dihapus.');
    }
}

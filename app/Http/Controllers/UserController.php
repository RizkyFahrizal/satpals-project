<?php

namespace App\Http\Controllers;

use App\Models\BoardMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        
        $query = User::query();
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
                
                // Search by role label with exact/prefix matching
                $roleLabels = \App\Models\User::getRoleLabels();
                $normalizedSearch = strtolower(str_replace(' ', '', $search));
                $matchingRoles = [];
                
                foreach ($roleLabels as $roleKey => $roleLabel) {
                    $normalizedLabel = strtolower(str_replace(' ', '', $roleLabel));
                    
                    // Exact match after normalization
                    if ($normalizedLabel === $normalizedSearch) {
                        $matchingRoles[] = $roleKey;
                    }
                    // Or if search starts with this label (for partial matches like "bendahara", "band")
                    else if (strlen($search) > 3 && strpos($normalizedLabel, $normalizedSearch) === 0) {
                        $matchingRoles[] = $roleKey;
                    }
                }
                
                if (!empty($matchingRoles)) {
                    $q->orWhereIn('role', $matchingRoles);
                }
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Get members without user account
        $availableMembers = \App\Models\Member::where('status', 'aktif')
            ->whereDoesntHave('user')
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.users.create', compact('availableMembers'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Get allowed roles (all roles except super_admin)
        $allowedRoles = array_keys(User::getBoardMemberRoles());
        $allowedRoles[] = User::ROLE_PUBLIC;
        
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id|unique:users,member_id',
            'role' => ['required', Rule::in($allowedRoles)],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'member_id.unique' => 'Anggota ini sudah memiliki akun user.',
            'member_id.exists' => 'Anggota yang dipilih tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $member = \App\Models\Member::findOrFail($validated['member_id']);

        // Check if account with same name and npm already exists
        $existingUser = User::whereHas('member', function ($query) use ($member) {
            $query->where('nama_lengkap', $member->nama_lengkap)
                  ->where('npm', $member->npm);
        })->first();

        if ($existingUser) {
            return back()
                ->withErrors(['member_id' => "Akun sudah ada dengan nama '{$member->nama_lengkap}' dan NPM '{$member->npm}'. Gunakan akun yang sudah ada atau ubah data anggota."])
                ->withInput();
        }

        $user = User::create([
            'member_id' => $validated['member_id'],
            'name' => $member->nama_lengkap,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'email_verified_at' => now(),
        ]);

        $this->syncBoardMemberUserLink($user);

        $message = "User berhasil ditambahkan!\n📧 Email: {$user->email}\n🔐 Anggota: {$user->name}\n👤 Role: {$user->role_label}";

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        // Jika user adalah super_admin, role tidak bisa diubah
        if ($user->isSuperAdmin()) {
            $roleValidation = ['required', Rule::in([User::ROLE_SUPER_ADMIN])];
        } else {
            // Allowed roles (all roles except super_admin)
            $allowedRoles = array_keys(User::getBoardMemberRoles());
            $allowedRoles[] = User::ROLE_PUBLIC;
            $roleValidation = ['required', Rule::in($allowedRoles)];
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => $roleValidation,
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Hanya update role jika bukan super_admin
        if (!$user->isSuperAdmin()) {
            $user->role = $validated['role'];
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        $this->syncBoardMemberUserLink($user);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate!');
    }

    /**
     * Show the form for editing user role.
     */
    public function editRole(User $user)
    {
        // Super Admin cannot change role
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Role Super Admin tidak dapat diubah!');
        }

        return view('admin.users.edit-role', compact('user'));
    }

    /**
     * Update user role.
     */
    public function updateRole(Request $request, User $user)
    {
        // Super Admin tidak bisa mengubah role sendiri atau role super_admin lain
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Role Super Admin tidak dapat diubah!');
        }

        // Get allowed roles (all roles except super_admin)
        $allowedRoles = array_keys(User::getBoardMemberRoles());
        $allowedRoles[] = User::ROLE_PUBLIC;

        $validated = $request->validate([
            'role' => ['required', Rule::in($allowedRoles)],
        ]);

        $user->role = $validated['role'];
        $user->save();

        $this->syncBoardMemberUserLink($user);

        return redirect()->route('admin.users.index')->with('success', 'Role user berhasil diubah!');
    }

    /**
     * Sync board member records to this user by member_id.
     */
    private function syncBoardMemberUserLink(User $user): void
    {
        if (!$user->member_id) {
            return;
        }

        BoardMember::where('member_id', $user->member_id)
            ->update(['user_id' => $user->id]);
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }

    /**
     * Toggle user active status (activate/deactivate account)
     */
    public function toggleStatus(User $user)
    {
        // Prevent toggling self
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Tidak dapat mengubah status akun sendiri!');
        }

        // Prevent toggling super admin
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Status Super Admin tidak dapat diubah!');
        }

        $user->is_active = !($user->is_active ?? true);
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.users.index')->with('success', "User berhasil {$status}.");
    }
}

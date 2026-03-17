<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in([User::ROLE_PENGURUS, User::ROLE_PUBLIC])], // Super Admin tidak bisa ditambahkan
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
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
        $roleValidation = $user->isSuperAdmin() 
            ? ['required', Rule::in([User::ROLE_SUPER_ADMIN])] 
            : ['required', Rule::in([User::ROLE_PENGURUS, User::ROLE_PUBLIC])];

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

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate!');
    }

    /**
     * Update user role only.
     */
    public function updateRole(Request $request, User $user)
    {
        // Super Admin tidak bisa mengubah role sendiri atau role super_admin lain
        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Role Super Admin tidak dapat diubah!');
        }

        $validated = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_PENGURUS, User::ROLE_PUBLIC])],
        ]);

        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Role user berhasil diubah!');
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
}

@extends('layouts.admin')

@section('title', 'Edit User - Admin Satya Palapa')

@section('header', 'Edit User')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-100 p-8">
        <!-- Error Messages -->
        @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
            <p class="text-sm font-semibold text-red-800 mb-2">Ada kesalahan:</p>
            <ul class="text-sm text-red-700 space-y-1">
                @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Hidden role field -->
            <input type="hidden" name="role" value="{{ $user->role }}">

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="Masukkan nama lengkap">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="contoh@email.com">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Info -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Role</label>
                @php
                    $allRoles = array_merge(
                        \App\Models\User::getBoardMemberRoles(),
                        [\App\Models\User::ROLE_SUPER_ADMIN => 'Super Admin', \App\Models\User::ROLE_PUBLIC => 'Public']
                    );
                    $roleLabel = $allRoles[$user->role] ?? $user->role;
                @endphp
                <div class="p-4 rounded-xl border-2 border-yellow-200 bg-yellow-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white shadow-md">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $roleLabel }}</p>
                                <p class="text-xs text-gray-500">Untuk mengubah role, gunakan menu edit role terpisah</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.edit-role', $user) }}" class="text-yellow-600 hover:text-yellow-700 font-medium text-sm underline">
                            Edit Role
                        </a>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Password Baru <span class="font-normal text-gray-400">(kosongkan jika tidak ingin mengubah)</span>
                </label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="Minimal 8 karakter">
                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-8">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="Ulangi password baru">
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold px-8 py-3 rounded-xl transition-all">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

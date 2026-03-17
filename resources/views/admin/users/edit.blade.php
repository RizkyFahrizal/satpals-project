@extends('layouts.admin')

@section('title', 'Edit User - Admin Satya Palapa')

@section('header', 'Edit User')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-100 p-8">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

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

            <!-- Role -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Role</label>
                @if($user->role === 'super_admin')
                    {{-- Super Admin role tidak bisa diubah --}}
                    <div class="p-4 rounded-xl border-2 border-red-200 bg-red-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center text-white shadow-md">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">Super Admin</p>
                                <p class="text-xs text-gray-500">Role ini tidak dapat diubah</p>
                            </div>
                            <svg class="w-5 h-5 ml-auto text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    <input type="hidden" name="role" value="super_admin">
                @else
                <div class="grid grid-cols-2 gap-4">
                    <!-- Pengurus Option -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="pengurus" {{ old('role', $user->role) === 'pengurus' ? 'checked' : '' }} required
                            class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-200 bg-white transition-all duration-200
                            peer-checked:border-yellow-400 peer-checked:bg-yellow-50 peer-checked:shadow-lg
                            hover:border-yellow-300 hover:bg-yellow-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white shadow-md">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Pengurus</p>
                                    <p class="text-xs text-gray-500">Akses menu operasional</p>
                                </div>
                            </div>
                            <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 transition-all flex items-center justify-center">
                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>

                    <!-- Public Option -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="public" {{ old('role', $user->role) === 'public' ? 'checked' : '' }} required
                            class="peer sr-only">
                        <div class="p-4 rounded-xl border-2 border-gray-200 bg-white transition-all duration-200
                            peer-checked:border-gray-400 peer-checked:bg-gray-50 peer-checked:shadow-lg
                            hover:border-gray-300 hover:bg-gray-50/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white shadow-md">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">Public</p>
                                    <p class="text-xs text-gray-500">User biasa</p>
                                </div>
                            </div>
                            <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-gray-500 peer-checked:bg-gray-500 transition-all flex items-center justify-center">
                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                </div>
                @endif
                @error('role')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
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

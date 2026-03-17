@extends('layouts.admin')

@section('title', 'Tambah User - Admin Satya Palapa')

@section('header', 'Tambah User Baru')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-100 p-8">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <!-- Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="Masukkan nama lengkap">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="contoh@email.com">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Role</label>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Pengurus Option -->
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="pengurus" {{ old('role') === 'pengurus' ? 'checked' : '' }} required
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
                        <input type="radio" name="role" value="public" {{ old('role') === 'public' ? 'checked' : '' }} required
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
                @error('role')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="Minimal 8 karakter">
                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-8">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="Ulangi password">
            </div>

            <!-- Buttons -->
            <div class="flex items-center gap-4">
                <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold px-8 py-3 rounded-xl transition-all">
                    Simpan User
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

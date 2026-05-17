@extends('layouts.admin')

@section('title', 'Edit Role User - Admin Satya Palapa')

@section('header', 'Edit Role User')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-100 p-8">
        <!-- User Info -->
        <div class="mb-8 pb-6 border-b border-gray-200">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-amber-500 flex items-center justify-center text-white text-2xl font-bold shadow-md">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Role Selection Form -->
        <form action="{{ route('admin.users.update-role', $user) }}" method="POST">
            @csrf
            @method('PATCH')

            <!-- Current Role Display -->
            <div class="mb-8 p-4 rounded-xl bg-blue-50 border border-blue-200">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role Saat Ini</label>
                @php
                    $allRoles = array_merge(
                        \App\Models\User::getBoardMemberRoles(),
                        [\App\Models\User::ROLE_SUPER_ADMIN => 'Super Admin', \App\Models\User::ROLE_PUBLIC => 'Public']
                    );
                    $currentRoleLabel = $allRoles[$user->role] ?? $user->role;
                @endphp
                <p class="text-lg font-semibold text-blue-800">{{ $currentRoleLabel }}</p>
            </div>

            <!-- New Role Selection -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-4">Pilih Role Baru</label>
                
                @php
                    $allowedRoles = array_keys(\App\Models\User::getBoardMemberRoles());
                    sort($allowedRoles);
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($allowedRoles as $roleKey)
                        @php
                            $roleLabel = $allRoles[$roleKey] ?? $roleKey;
                            $roleDescription = [
                                'ketua_umum' => 'Ketua Umum - Kepemimpinan organisasi',
                                'wakil_ketua_umum' => 'Wakil Ketua - Pembantu ketua',
                                'bendahara' => 'Bendahara - Mengelola keuangan',
                                'sekretaris' => 'Sekretaris - Administrasi & dokumentasi',
                                'mpa' => 'MPA - Majelis Perwakilan Anggota',
                                'band' => 'Band - Divisi musik',
                                'peralatan' => 'Peralatan - Manajemen alat',
                                'humas' => 'Humas - Hubungan masyarakat',
                                'pdd' => 'PDD - Produksi Dan Dokumentasi',
                                'kesekretariatan' => 'Kesekretariatan - Tim sekretariat',
                            ];
                            $desc = $roleDescription[$roleKey] ?? '';
                        @endphp
                        <label class="relative cursor-pointer block">
                            <input 
                                type="radio" 
                                name="role" 
                                value="{{ $roleKey }}"
                                {{ $user->role === $roleKey ? 'checked' : '' }}
                                class="absolute opacity-0 peer"
                            >
                            <div class="p-4 rounded-xl border-2 border-gray-200 bg-white transition-all peer-checked:border-yellow-400 peer-checked:bg-yellow-50 hover:border-gray-300 peer-checked:shadow-md peer-checked:shadow-yellow-200">
                                <div class="flex items-start gap-3">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800 peer-checked:text-yellow-700">{{ $roleLabel }}</p>
                                        @if ($desc)
                                            <p class="text-sm text-gray-500 mt-1">{{ $desc }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="mb-8 p-4 rounded-xl bg-amber-50 border border-amber-200">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-amber-800">
                        <p class="font-semibold">Perubahan Role</p>
                        <p class="mt-1">Role akan diperbarui segera setelah Anda menyimpan. User akan mendapat akses sesuai role baru.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold px-6 py-3 rounded-xl transition-all">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan Role
                    </div>
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Kelola User - Admin Satya Palapa')

@section('header', 'Kelola User')

@section('content')
<div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Daftar User</h3>
            <p class="text-sm text-gray-500">Kelola semua user dan role mereka</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-semibold px-6 py-2 rounded-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah User
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
        {{ session('error') }}
    </div>
    @endif

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-yellow-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Role</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Terdaftar</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-yellow-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-gray-800 font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if(auth()->id() === $user->id)
                                {{-- Super Admin tidak bisa edit role sendiri --}}
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-red-500 to-red-600 text-white shadow-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Super Admin
                                </span>
                            @elseif($user->role === 'super_admin')
                                {{-- User lain yang super_admin tidak bisa diubah --}}
                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-red-500 to-red-600 text-white shadow-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    Super Admin
                                </span>
                            @else
                                <form action="{{ route('admin.users.update-role', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <div class="relative inline-block">
                                        <select name="role" onchange="this.form.submit()" 
                                            class="appearance-none cursor-pointer pl-4 pr-10 py-2 rounded-full text-sm font-medium border-2 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2
                                            @if($user->role === 'pengurus') 
                                                bg-gradient-to-r from-yellow-400 to-amber-400 text-gray-800 border-yellow-300 focus:ring-yellow-400 hover:from-yellow-500 hover:to-amber-500
                                            @else 
                                                bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 border-gray-300 focus:ring-gray-400 hover:from-gray-200 hover:to-gray-300
                                            @endif">
                                            <option value="pengurus" {{ $user->role === 'pengurus' ? 'selected' : '' }}>👤 Pengurus</option>
                                            <option value="public" {{ $user->role === 'public' ? 'selected' : '' }}>👥 Public</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="w-4 h-4 text-current opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Belum ada user terdaftar
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

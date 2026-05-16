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
            <table class="w-full table-fixed">
                <thead class="bg-yellow-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 w-12">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 w-56">Nama</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 w-64">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 w-40">Role</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 w-36">Terdaftar</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-yellow-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 w-56">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0 shadow-sm border border-yellow-300">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800 line-clamp-2 break-words">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 w-64 truncate">{{ $user->email }}</td>
                        <td class="px-6 py-4 w-40">
                            @php
                                $roleLabel = $user->getRoleLabelAttribute();
                                $roleColor = match($user->role) {
                                    'super_admin' => 'from-red-500 to-red-600 text-white',
                                    'ketua_umum' => 'from-blue-500 to-blue-600 text-white',
                                    'wakil_ketua_umum' => 'from-purple-500 to-purple-600 text-white',
                                    'bendahara' => 'from-green-500 to-green-600 text-white',
                                    'sekretaris' => 'from-indigo-500 to-indigo-600 text-white',
                                    'mpa' => 'from-pink-500 to-pink-600 text-white',
                                    'band' => 'from-cyan-500 to-cyan-600 text-white',
                                    'peralatan' => 'from-orange-500 to-orange-600 text-white',
                                    'humas' => 'from-teal-500 to-teal-600 text-white',
                                    'pdd' => 'from-fuchsia-500 to-fuchsia-600 text-white',
                                    'kesekretariatan' => 'from-amber-500 to-amber-600 text-white',
                                    'pengurus' => 'from-yellow-400 to-amber-400 text-gray-800',
                                    'public' => 'from-gray-300 to-gray-400 text-gray-800',
                                    default => 'from-gray-300 to-gray-400 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r {{ $roleColor }} shadow-sm border border-opacity-20 border-white whitespace-nowrap">
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                {{ $roleLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="inline-flex items-center justify-center w-9 h-9 text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" onsubmit="return confirm('{{ $user->is_active ? 'Yakin ingin menonaktifkan user ini?' : 'Yakin ingin mengaktifkan user ini?' }}')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center justify-center w-9 h-9 text-gray-600 hover:bg-gray-100 rounded-md transition-colors" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        @if($user->is_active)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414 1.414M6.343 17.657l-1.414 1.414M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-9 h-9 text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

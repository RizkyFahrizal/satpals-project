@extends('layouts.admin')

@section('title', 'Tambah User - Admin Satya Palapa')

@section('header', 'Tambah User Baru')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-yellow-100 p-8">
        <form action="{{ route('admin.users.store') }}" method="POST" id="createUserForm">
            @csrf

            <!-- Member Search Input -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pilih Anggota <span class="text-red-500">*</span>
                </label>
                @if($availableMembers->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-700">
                    <p>Tidak ada anggota aktif yang tersedia (semua sudah punya akun)</p>
                </div>
                @else
                <div class="relative">
                    <input type="text" id="searchMember" placeholder="Cari anggota (nama/npm)..." 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-2 focus:ring-yellow-400/20 transition-all">
                    <input type="hidden" name="member_id" id="memberInput" required>
                    <div id="searchResults" class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-xl shadow-lg z-10 max-h-48 overflow-y-auto"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">* Mulai ketik untuk mencari anggota</p>
                <p class="text-xs text-blue-600 mt-2 bg-blue-50 p-2 rounded">
                    <strong>ℹ️ Catatan:</strong> Sistem akan mencegah pembuatan akun duplikat jika anggota dengan nama dan NPM yang sama sudah memiliki akun di periode sebelumnya.
                </p>
                @endif
                @error('member_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Role <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-4 max-h-80 overflow-y-auto pr-2">
                    @php
                        $allRoles = array_merge(
                            \App\Models\User::getBoardMemberRoles(),
                            [\App\Models\User::ROLE_PUBLIC => 'Public']
                        );
                    @endphp
                    
                    @foreach($allRoles as $roleValue => $roleLabel)
                    <label class="relative cursor-pointer">
                        <input type="radio" name="role" value="{{ $roleValue }}" {{ old('role') === $roleValue ? 'checked' : '' }} required
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
                                    <p class="font-semibold text-gray-800 text-sm">{{ $roleLabel }}</p>
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

            <!-- Email -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="contoh@email.com">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="Minimal 8 karakter">
                @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-8">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-yellow-400 focus:ring-yellow-400 transition-colors"
                    placeholder="Ulangi password">
            </div>

            <!-- Info Box -->
            <div class="mb-8 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                <p class="text-sm text-blue-800">
                    <strong>💡 Info:</strong> Email bisa disesuaikan, username akan otomatis di-generate dari nama anggota.
                </p>
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

@section('scripts')
<script>
// Search Members Functionality for User Creation
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchMember');
    const resultsDiv = document.getElementById('searchResults');
    const memberInput = document.getElementById('memberInput');

    if (!searchInput) return;

    let searchTimeout;

    searchInput.addEventListener('input', async (e) => {
        clearTimeout(searchTimeout);
        const search = e.target.value.trim();

        if (search.length < 2) {
            resultsDiv.classList.add('hidden');
            memberInput.value = '';
            return;
        }

        searchTimeout = setTimeout(async () => {
            try {
                const params = new URLSearchParams();
                params.append('search', search);

                const response = await fetch(`/admin/board/search-members?${params.toString()}`);
                const members = await response.json();

                if (members.length === 0) {
                    resultsDiv.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500">Tidak ada anggota ditemukan</div>';
                } else {
                    resultsDiv.innerHTML = members.map(member => `
                        <div class="px-4 py-3 hover:bg-yellow-50 cursor-pointer border-b last:border-b-0 transition-colors" 
                             onclick="selectMember(${member.id}, '${member.nama_lengkap.replace(/'/g, "\\'")}')">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <div class="text-sm font-semibold text-gray-700">${member.nama_lengkap}</div>
                                    <div class="text-xs text-gray-500">${member.npm}</div>
                                </div>
                                <span class="text-xs font-medium px-2 py-1 rounded-full ${member.account_class} ${member.has_account ? 'bg-green-100' : 'bg-blue-100'}">
                                    ${member.account_status}
                                </span>
                            </div>
                        </div>
                    `).join('');
                }

                resultsDiv.classList.remove('hidden');
            } catch (error) {
                console.error('Search error:', error);
                resultsDiv.innerHTML = '<div class="px-4 py-2 text-sm text-red-500">Error saat mencari</div>';
                resultsDiv.classList.remove('hidden');
            }
        }, 300);
    });

    // Close results when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#searchMember') && !e.target.closest('#searchResults')) {
            resultsDiv.classList.add('hidden');
        }
    });

    // Global function to select member
    window.selectMember = (id, nama) => {
        memberInput.value = id;
        document.getElementById('searchMember').value = nama;
        resultsDiv.classList.add('hidden');
    };
});
</script>
@endsection
@endsection

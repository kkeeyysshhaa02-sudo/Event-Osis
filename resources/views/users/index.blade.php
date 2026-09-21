@extends('layouts.app')

@section('title', 'Kelola Pengguna (User Management)')

@section('content')
<div class="space-y-8 max-w-6xl mx-auto">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Kelola Akun Pengguna</h1>
        <p class="text-sm text-gray-600 mt-1">Pengaturan hak akses role (Admin, Panitia, Peserta) dan data pengguna</p>
    </div>

    <!-- Search + Filter Form -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100">
        <form action="{{ route('users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Cari Pengguna</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Cari nama, email, kelas..."
                    class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 text-sm">
            </div>

            <div>
                <label for="role" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Filter Role</label>
                <select name="role" id="role" class="w-full px-3 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 text-sm">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="panitia" {{ request('role') == 'panitia' ? 'selected' : '' }}>Panitia</option>
                    <option value="peserta" {{ request('role') == 'peserta' ? 'selected' : '' }}>Peserta</option>
                </select>
            </div>

            <div class="md:col-span-3 flex justify-end space-x-2 pt-2 border-t border-gray-100">
                @if(request('search') || request('role'))
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-lg transition">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg shadow transition">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse min-w-[650px]">
            <thead class="bg-emerald-50/80 text-emerald-900 text-xs uppercase font-bold border-b border-emerald-100">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Pengguna</th>
                    <th class="px-6 py-4">Kelas & Kontak</th>
                    <th class="px-6 py-4">Role Saat Ini</th>
                    <th class="px-6 py-4 text-right">Ubah Role & Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse($users as $index => $u)
                    <tr class="hover:bg-emerald-50/30 transition">
                        <td class="px-6 py-4 font-bold text-gray-400">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900">{{ $u->name }}</p>
                            <p class="text-xs text-gray-500">{{ $u->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            <p class="font-semibold text-gray-800">Kelas: {{ $u->kelas ?? '-' }}</p>
                            <p class="text-gray-500">HP: {{ $u->phone ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-bold uppercase rounded-lg shadow-sm
                                {{ $u->isAdmin() ? 'bg-purple-100 text-purple-900 border border-purple-300' : '' }}
                                {{ $u->isPanitia() ? 'bg-blue-100 text-blue-900 border border-blue-300' : '' }}
                                {{ $u->isPeserta() ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : '' }}">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <form action="{{ route('users.update-role', $u) }}" method="POST" class="inline-flex items-center space-x-1">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="px-2.5 py-1 text-xs font-semibold rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500">
                                    <option value="peserta" {{ $u->role == 'peserta' ? 'selected' : '' }}>Peserta</option>
                                    <option value="panitia" {{ $u->role == 'panitia' ? 'selected' : '' }}>Panitia</option>
                                    <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <button type="submit" class="px-3 py-1 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-lg transition shadow-sm">
                                    Simpan Role
                                </button>
                            </form>

                            @if($u->id !== Auth::id())
                                <form action="{{ route('users.destroy', $u) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-800 hover:bg-red-200 text-xs font-bold rounded-lg transition">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-xs text-gray-500">Tidak ada pengguna ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $users->links() }}
    </div>
</div>
@endsection

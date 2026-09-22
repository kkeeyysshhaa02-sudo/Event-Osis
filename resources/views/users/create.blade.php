@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center space-x-3">
        <a href="{{ route('users.index') }}" class="text-xs font-bold text-emerald-700 hover:underline flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Kelola Pengguna</span>
        </a>
    </div>

    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Tambah Pengguna Baru</h1>
        <p class="text-sm text-gray-600 mt-1">Buat akun pengguna baru dan tentukan role-nya</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-6 sm:p-8">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Nama --}}
            <div>
                <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    placeholder="Contoh: Budi Santoso"
                    class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Alamat Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    placeholder="contoh@email.com"
                    class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                @error('email')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required
                        placeholder="Min. 8 karakter"
                        class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        placeholder="Ulangi password"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                </div>
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Role <span class="text-red-500">*</span>
                </label>
                <select id="role" name="role" required
                    class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('role') ? 'border-red-400 bg-red-50' : 'border-gray-300' }} focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                    <option value="">-- Pilih Role --</option>
                    <option value="peserta" {{ old('role') === 'peserta' ? 'selected' : '' }}>Peserta</option>
                    <option value="panitia" {{ old('role') === 'panitia' ? 'selected' : '' }}>Panitia</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kelas & Phone (opsional) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="kelas" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        Kelas <span class="text-gray-400 font-normal normal-case">(opsional)</span>
                    </label>
                    <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}"
                        placeholder="Contoh: XI-2"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                    @error('kelas')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                        No. HP <span class="text-gray-400 font-normal normal-case">(opsional)</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                        placeholder="Contoh: 08123456789"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm transition">
                    @error('phone')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('users.index') }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-xl transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-sm rounded-xl shadow transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span>Tambah Pengguna</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

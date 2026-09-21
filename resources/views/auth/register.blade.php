@extends('layouts.app')

@section('title', 'Registrasi Akun')

@section('content')
<div class="max-w-md mx-auto my-6">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-emerald-100">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-6 text-white text-center">
            <h2 class="text-2xl font-bold tracking-tight">Daftar Akun Baru</h2>
            <p class="text-emerald-100 text-xs mt-1">Bergabunglah untuk mengikuti berbagai event OSIS menarik!</p>
        </div>

        <!-- Card Body -->
        <div class="p-8">
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm @error('name') border-red-500 @enderror"
                        placeholder="Nama lengkap siswa">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm @error('email') border-red-500 @enderror"
                        placeholder="email@sekolah.sch.id">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="kelas" class="block text-sm font-semibold text-gray-700 mb-1">Kelas</label>
                        <input type="text" name="kelas" id="kelas" value="{{ old('kelas') }}"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                            placeholder="Contoh: XI-2">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp/HP</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                            placeholder="08123456789">
                    </div>
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Daftar Sebagai (Role)</label>
                    <select name="role" id="role" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        <option value="peserta" {{ old('role') == 'peserta' ? 'selected' : '' }}>Peserta (Siswa)</option>
                        <option value="panitia" {{ old('role') == 'panitia' ? 'selected' : '' }}>Panitia OSIS</option>
                    </select>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm @error('password') border-red-500 @enderror"
                        placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm"
                        placeholder="Ulangi password">
                </div>

                <button type="submit" class="w-full py-3 mt-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow transition duration-200">
                    Daftar Sekarang
                </button>
            </form>

            <p class="text-center text-xs text-gray-600 mt-6">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-emerald-700 font-bold hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection

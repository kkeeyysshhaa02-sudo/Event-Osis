@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-emerald-100">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 p-6 text-white text-center">
            <div class="w-12 h-12 bg-emerald-500/30 rounded-full flex items-center justify-center mx-auto mb-3 backdrop-blur-sm">
                <svg class="w-7 h-7 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold tracking-tight">Masuk ke Event-Osis</h2>
            <p class="text-emerald-100 text-xs mt-1">Silakan masuk menggunakan akun terdaftar Anda</p>
        </div>

        <!-- Card Body -->
        <div class="p-8">
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm @error('email') border-red-500 @enderror"
                        placeholder="contoh: admin@osis.sch.id">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition text-sm @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password Anda">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition duration-200">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Quick Demo Credentials Box -->
            <!-- <div class="mt-8 p-4 bg-emerald-50 rounded-xl border border-emerald-200 text-xs text-emerald-900">
                <p class="font-bold text-emerald-800 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Akun Uji Coba (Demo):
                </p>
                <div class="space-y-1 font-mono text-[11px]">
                    <p><span class="font-bold text-emerald-700">Admin:</span> admin@osis.sch.id / password</p>
                    <p><span class="font-bold text-emerald-700">Panitia:</span> panitia@osis.sch.id / password</p>
                    <p><span class="font-bold text-emerald-700">Peserta:</span> peserta@osis.sch.id / password</p>
                </div>
            </div> -->

            <div class="mt-6 p-3.5 bg-emerald-50 rounded-xl border border-emerald-200 text-center">
                <p class="text-xs text-emerald-800 font-semibold">Belum memiliki akun?</p>
                <p class="text-[11px] text-emerald-600 mt-0.5">Akun peserta dan panitia dibuat &amp; dikelola langsung oleh Administrator OSIS SMK Pesat.</p>
            </div>
        </div>
    </div>
</div>
@endsection

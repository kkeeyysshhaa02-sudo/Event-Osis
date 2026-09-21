@extends('layouts.app')

@section('title', $event->name)

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    <!-- Navigation back -->
    <div>
        <a href="{{ route('events.index') }}" class="inline-flex items-center text-xs font-bold text-emerald-700 hover:underline">
            &larr; Kembali ke Daftar Event
        </a>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden">
        <!-- Event Header Banner -->
        <div class="h-64 bg-gradient-to-r from-emerald-800 via-emerald-700 to-emerald-600 relative flex items-center justify-center">
            @if($event->image)
                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
            @else
                <div class="text-center p-6 text-emerald-100">
                    <svg class="w-16 h-16 mx-auto opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-bold text-lg block mt-2">{{ $event->category->name }}</span>
                </div>
            @endif

            <span class="absolute top-4 left-4 px-3 py-1 text-xs font-bold uppercase rounded-lg bg-emerald-900/90 text-emerald-200 backdrop-blur-sm border border-emerald-500/30 shadow">
                {{ $event->category->name }}
            </span>

            <span class="absolute top-4 right-4 px-3 py-1 text-xs font-bold uppercase rounded-lg shadow backdrop-blur-sm
                {{ $event->status === 'upcoming' ? 'bg-blue-600/90 text-white' : '' }}
                {{ $event->status === 'ongoing' ? 'bg-emerald-600/90 text-white' : '' }}
                {{ $event->status === 'completed' ? 'bg-gray-800/90 text-gray-200' : '' }}
                {{ $event->status === 'cancelled' ? 'bg-red-600/90 text-white' : '' }}">
                Status: {{ $event->status }}
            </span>
        </div>

        <!-- Event Body -->
        <div class="p-6 sm:p-8 space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-100 pb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">{{ $event->name }}</h1>
                    <p class="text-xs text-gray-500 mt-1">Dibuat oleh: <span class="font-bold text-gray-700">{{ $event->creator->name }}</span> &bull; {{ $event->created_at->format('d M Y') }}</p>
                </div>

                <!-- Admin / Panitia Action Buttons -->
                @auth
                    @if(Auth::user()->isAdmin() || (Auth::user()->isPanitia() && $event->created_by === Auth::id()))
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('events.edit', $event) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow transition">
                                Edit Event
                            </a>
                            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow transition">
                                    Hapus Event
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Event Information Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 bg-emerald-50/60 rounded-xl border border-emerald-100">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Waktu Pelaksanaan</span>
                    <span class="text-sm font-bold text-gray-900 mt-1 block">📅 {{ $event->event_date->format('d M Y, H:i') }} WIB</span>
                </div>

                <div class="p-4 bg-emerald-50/60 rounded-xl border border-emerald-100">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Lokasi Event</span>
                    <span class="text-sm font-bold text-gray-900 mt-1 block">📍 {{ $event->location }}</span>
                </div>

                <div class="p-4 bg-emerald-50/60 rounded-xl border border-emerald-100">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Kapasitas / Sisa Kuota</span>
                    <span class="text-sm font-bold {{ $event->isFull() ? 'text-red-600' : 'text-emerald-900' }} mt-1 block">
                        👥 {{ $event->active_registrations_count }} / {{ $event->capacity }} (Sisa: {{ $event->availableSeats() }})
                    </span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Deskripsi Lengkap Event</h3>
                <div class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-5 rounded-xl border border-gray-100 whitespace-pre-line">
                    {{ $event->description }}
                </div>
            </div>

            <!-- Registration Action Box -->
            <div class="mt-8 pt-6 border-t border-emerald-100">
                @auth
                    @if($userRegistration)
                        <!-- Already Registered Status Box -->
                        <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-200 flex flex-col md:flex-row items-center justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-emerald-900 text-base">Anda Telah Mendaftar Event Ini</h4>
                                <p class="text-xs text-emerald-700 mt-0.5">
                                    Tanggal Pendaftaran: {{ $userRegistration->registration_date->format('d M Y, H:i') }} WIB
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1.5 rounded-lg font-bold text-xs uppercase shadow-sm
                                    {{ $userRegistration->status === 'approved' ? 'bg-emerald-600 text-white' : '' }}
                                    {{ $userRegistration->status === 'pending' ? 'bg-amber-500 text-white' : '' }}
                                    {{ $userRegistration->status === 'rejected' ? 'bg-red-600 text-white' : '' }}
                                    {{ $userRegistration->status === 'attended' ? 'bg-blue-600 text-white' : '' }}
                                    {{ $userRegistration->status === 'cancelled' ? 'bg-gray-600 text-white' : '' }}">
                                    Status: {{ $userRegistration->status }}
                                </span>

                                @if(in_array($userRegistration->status, ['approved', 'attended']))
                                    <a href="{{ route('registrations.ticket', $userRegistration) }}" class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-lg transition shadow flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                        </svg>
                                        <span>Lihat E-Tiket</span>
                                    </a>
                                @endif

                                @if($userRegistration->status === 'pending')
                                    <form action="{{ route('registrations.cancel', $userRegistration) }}" method="POST" onsubmit="return confirm('Membatalkan pendaftaran?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 text-xs font-bold rounded-lg transition">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @elseif($event->isFull())
                        <div class="p-6 bg-red-50 rounded-2xl border border-red-200 text-center">
                            <h4 class="font-bold text-red-900 text-base">Kuota Pendaftaran Penuh</h4>
                            <p class="text-xs text-red-700 mt-1">Maaf, kapasitas peserta untuk event ini sudah mencapai batas maksimal.</p>
                        </div>
                    @elseif(in_array($event->status, ['completed', 'cancelled']))
                        <div class="p-6 bg-gray-100 rounded-2xl border border-gray-200 text-center">
                            <h4 class="font-bold text-gray-800 text-base">Pendaftaran Ditutup</h4>
                            <p class="text-xs text-gray-600 mt-1">Event ini telah {{ $event->status === 'completed' ? 'selesai' : 'dibatalkan' }}.</p>
                        </div>
                    @else
                        <!-- Registration Form -->
                        <div class="p-6 bg-emerald-600 rounded-2xl text-white shadow-lg">
                            <h4 class="font-bold text-lg mb-1">Daftar Event Sekarang</h4>
                            <p class="text-emerald-100 text-xs mb-4">Pastikan data akun Anda sudah lengkap sebelum mendaftar.</p>

                            <form action="{{ route('registrations.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="event_id" value="{{ $event->id }}">

                                <div>
                                    <label for="notes" class="block text-xs font-semibold text-emerald-100 mb-1">Catatan Tambahan / Perwakilan Kelas (Opsional)</label>
                                    <input type="text" name="notes" id="notes" placeholder="Contoh: Perwakilan kelas XI-2 / Tim Futsal A"
                                        class="w-full px-4 py-2 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-emerald-300">
                                </div>

                                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-white hover:bg-emerald-50 text-emerald-800 font-bold rounded-xl shadow transition text-sm">
                                    Kirim Pendaftaran Event &rarr;
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-200 text-center">
                        <p class="text-sm font-semibold text-emerald-900 mb-3">Silakan login terlebih dahulu untuk mendaftar pada event ini.</p>
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition inline-block">
                            Login Sekarang
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

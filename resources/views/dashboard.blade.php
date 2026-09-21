@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header Banner -->
    <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-emerald-600 rounded-2xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 bg-emerald-900/40 px-3 py-1 rounded-full text-xs font-semibold text-emerald-200 mb-2 border border-emerald-500/30">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Role: {{ strtoupper(Auth::user()->role) }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-emerald-100 text-sm mt-1">
                @if(Auth::user()->kelas) Kelas: {{ Auth::user()->kelas }} &bull; @endif
                Sistem Pengelolaan Event & Pendaftaran OSIS SMK Negeri OSIS
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if(Auth::user()->isAdmin() || Auth::user()->isPanitia())
                <a href="{{ route('events.create') }}" class="px-4 py-2 bg-white text-emerald-800 font-bold text-sm rounded-lg shadow hover:bg-emerald-50 transition">
                    + Buat Event Baru
                </a>
            @endif
            @if(Auth::user()->isPeserta())
                <a href="{{ route('registrations.my') }}" class="px-4 py-2 bg-emerald-500 text-white font-bold text-sm rounded-lg shadow hover:bg-emerald-400 transition">
                    Riwayat Pendaftaran Saya
                </a>
            @endif
        </div>
    </div>

    <!-- Announcement & Event Countdown Banner (Fitur 4: Countdown & Pengumuman OSIS) -->
    @php
        $nextUpcoming = $recentEvents->where('status', 'upcoming')->sortBy('event_date')->first();
    @endphp
    @if($nextUpcoming)
        <div class="bg-emerald-900 text-white rounded-2xl p-6 shadow-lg border border-emerald-700 flex flex-col md:flex-row items-center justify-between gap-6"
             x-data="{
                targetDate: new Date('{{ $nextUpcoming->event_date->toIso8601String() }}').getTime(),
                days: 0, hours: 0, minutes: 0, seconds: 0,
                updateTimer() {
                    const now = new Date().getTime();
                    const diff = this.targetDate - now;
                    if (diff > 0) {
                        this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                    }
                }
             }" x-init="updateTimer(); setInterval(() => updateTimer(), 1000)">
            
            <div class="space-y-1 text-center md:text-left">
                <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded bg-emerald-700 text-emerald-200">
                    📢 Pengumuman Event Terdekat
                </span>
                <h3 class="text-xl font-bold tracking-tight text-white mt-1">{{ $nextUpcoming->name }}</h3>
                <p class="text-xs text-emerald-300">
                    Pelaksanaan: {{ $nextUpcoming->event_date->format('d M Y, H:i') }} WIB &bull; 📍 {{ $nextUpcoming->location }}
                </p>
            </div>

            <!-- Countdown Display -->
            <div class="flex items-center space-x-3 text-center">
                <div class="bg-emerald-800/80 px-3 py-2 rounded-xl border border-emerald-600 min-w-[55px]">
                    <span class="text-xl font-black text-emerald-200 block" x-text="days">0</span>
                    <span class="text-[9px] font-bold uppercase text-emerald-400">Hari</span>
                </div>
                <span class="text-lg font-bold text-emerald-500">:</span>
                <div class="bg-emerald-800/80 px-3 py-2 rounded-xl border border-emerald-600 min-w-[55px]">
                    <span class="text-xl font-black text-emerald-200 block" x-text="hours">0</span>
                    <span class="text-[9px] font-bold uppercase text-emerald-400">Jam</span>
                </div>
                <span class="text-lg font-bold text-emerald-500">:</span>
                <div class="bg-emerald-800/80 px-3 py-2 rounded-xl border border-emerald-600 min-w-[55px]">
                    <span class="text-xl font-black text-emerald-200 block" x-text="minutes">0</span>
                    <span class="text-[9px] font-bold uppercase text-emerald-400">Menit</span>
                </div>
                <span class="text-lg font-bold text-emerald-500">:</span>
                <div class="bg-emerald-800/80 px-3 py-2 rounded-xl border border-emerald-600 min-w-[55px]">
                    <span class="text-xl font-black text-emerald-200 block" x-text="seconds">0</span>
                    <span class="text-[9px] font-bold uppercase text-emerald-400">Detik</span>
                </div>

                <a href="{{ route('events.show', $nextUpcoming) }}" class="ml-2 px-4 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-white text-xs font-bold rounded-xl shadow transition">
                    Daftar Event &rarr;
                </a>
            </div>
        </div>
    @endif

    <!-- Overview Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Event -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100 flex items-center space-x-4">
            <div class="p-3.5 bg-emerald-100 text-emerald-700 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Event</p>
                <h3 class="text-2xl font-black text-gray-900 mt-0.5">{{ $stats['total_events'] }}</h3>
            </div>
        </div>

        <!-- Card 2: Upcoming Events -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100 flex items-center space-x-4">
            <div class="p-3.5 bg-blue-100 text-blue-700 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Akan Datang</p>
                <h3 class="text-2xl font-black text-gray-900 mt-0.5">{{ $stats['upcoming_events'] }}</h3>
            </div>
        </div>

        <!-- Card 3: Total Registrations -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100 flex items-center space-x-4">
            <div class="p-3.5 bg-amber-100 text-amber-700 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pendaftaran</p>
                <h3 class="text-2xl font-black text-gray-900 mt-0.5">{{ $stats['total_registrations'] }}</h3>
            </div>
        </div>

        <!-- Card 4: Total Users -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-100 flex items-center space-x-4">
            <div class="p-3.5 bg-purple-100 text-purple-700 rounded-xl">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Pengguna</p>
                <h3 class="text-2xl font-black text-gray-900 mt-0.5">{{ $stats['total_users'] }}</h3>
            </div>
        </div>
    </div>

    <!-- Role-Specific Widget & Recent Events -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Columns: Recent Events List -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H14"></path>
                    </svg>
                    Event Terbaru
                </h2>
                <a href="{{ route('events.index') }}" class="text-xs font-bold text-emerald-700 hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 divide-y divide-gray-100 overflow-hidden">
                @forelse($recentEvents as $event)
                    <div class="p-5 hover:bg-emerald-50/40 transition flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-emerald-100 text-emerald-800">
                                    {{ $event->category->name }}
                                </span>
                                <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded 
                                    {{ $event->status === 'upcoming' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $event->status === 'ongoing' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $event->status === 'completed' ? 'bg-gray-100 text-gray-700' : '' }}
                                    {{ $event->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ $event->status }}
                                </span>
                            </div>
                            <h3 class="font-bold text-gray-900 mt-1 hover:text-emerald-700 transition">
                                <a href="{{ route('events.show', $event) }}">{{ $event->name }}</a>
                            </h3>
                            <p class="text-xs text-gray-500 mt-1 flex items-center space-x-3">
                                <span>📅 {{ $event->event_date->format('d M Y, H:i') }} WIB</span>
                                <span>📍 {{ $event->location }}</span>
                            </p>
                        </div>

                        <div class="flex items-center space-x-3 sm:self-center">
                            <span class="text-xs text-emerald-800 font-semibold bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                                {{ $event->active_registrations_count }} / {{ $event->capacity }} Peserta
                            </span>
                            <a href="{{ route('events.show', $event) }}" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                                Detail & Daftar
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="p-8 text-center text-sm text-gray-500">Belum ada event yang dibuat.</p>
                @endforelse
            </div>
        </div>

        <!-- Right Column: My Activity / Quick Actions -->
        <div class="space-y-6">
            @if(Auth::user()->isPeserta())
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-emerald-100">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Pendaftaran Terbaru Saya
                    </h2>

                    <div class="space-y-3">
                        @forelse($myRegistrations as $reg)
                            <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100 text-xs flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-gray-900 truncate max-w-[150px]">{{ $reg->event->name }}</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $reg->registration_date->format('d M Y') }}</p>
                                </div>
                                <span class="px-2 py-1 text-[10px] font-bold rounded uppercase
                                    {{ $reg->status === 'approved' ? 'bg-emerald-200 text-emerald-900' : '' }}
                                    {{ $reg->status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $reg->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $reg->status === 'attended' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $reg->status === 'cancelled' ? 'bg-gray-200 text-gray-800' : '' }}">
                                    {{ $reg->status }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 py-4 text-center">Anda belum mendaftar pada event apa pun.</p>
                        @endforelse
                    </div>

                    <a href="{{ route('events.index') }}" class="mt-4 block w-full text-center py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl hover:bg-emerald-700 transition">
                        Cari & Daftar Event
                    </a>
                </div>
            @endif

            @if(Auth::user()->isPanitia())
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-emerald-100">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Event Yang Saya Kelola
                    </h2>

                    <div class="space-y-3">
                        @forelse($myManagedEvents as $mEvent)
                            <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100 text-xs flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-gray-900 truncate max-w-[150px]">{{ $mEvent->name }}</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $mEvent->active_registrations_count }}/{{ $mEvent->capacity }} Peserta</p>
                                </div>
                                <a href="{{ route('events.show', $mEvent) }}" class="px-2.5 py-1 bg-emerald-700 text-white text-[11px] font-bold rounded hover:bg-emerald-800">
                                    Kelola
                                </a>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 py-4 text-center">Anda belum membuat event.</p>
                        @endforelse
                    </div>

                    <a href="{{ route('events.create') }}" class="mt-4 block w-full text-center py-2 bg-emerald-600 text-white font-bold text-xs rounded-xl hover:bg-emerald-700 transition">
                        + Buat Event Baru
                    </a>
                </div>
            @endif

            <!-- Quick Links -->
            <div class="bg-gradient-to-br from-emerald-900 to-emerald-800 text-white rounded-2xl p-6 shadow-md">
                <h3 class="font-bold text-sm text-emerald-100 uppercase tracking-wider mb-3">Informasi Sistem</h3>
                <ul class="text-xs space-y-2 text-emerald-200">
                    <li class="flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2"></span>
                        Hak Akses Admin (Keca): Full CRUD & User Management
                    </li>
                    <li class="flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2"></span>
                        Hak Akses Panitia (Kayla): Buat Event & Presensi Peserta
                    </li>
                    <li class="flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-2"></span>
                        Hak Akses Peserta (Wyanet): Lihat & Daftar Event
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection

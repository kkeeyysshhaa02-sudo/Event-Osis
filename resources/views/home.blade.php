@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- ==================== HERO SECTION ==================== --}}
<div class="relative -mx-4 sm:-mx-6 lg:-mx-8 -mt-8 mb-12 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-800 via-emerald-700 to-emerald-600"></div>
    {{-- Decorative blobs --}}
    <div class="absolute -top-16 -right-16 w-64 h-64 bg-emerald-500/30 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-16 -left-16 w-64 h-64 bg-emerald-900/40 rounded-full blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="max-w-2xl">
            <span class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-900/60 border border-emerald-500/40 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span>OSIS SMK Pesat</span>
            </span>

            <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight tracking-tight mb-4">
                Temukan &amp; Ikuti<br>
                <span class="text-emerald-300">Event OSIS</span> Terbaik
            </h1>

            <p class="text-emerald-100 text-base md:text-lg leading-relaxed mb-8 max-w-xl">
                Platform resmi pendaftaran dan pengelolaan kegiatan OSIS. Daftarkan dirimu ke berbagai event seru, dan dapatkan e-tiket digital!
            </p>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('events.index') }}"
                   class="inline-flex items-center space-x-2 px-6 py-3 bg-white text-emerald-800 font-bold rounded-xl shadow-lg hover:bg-emerald-50 transition text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Lihat Semua Event</span>
                </a>

                @guest
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center space-x-2 px-6 py-3 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-xl shadow-lg transition text-sm border border-emerald-400/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span>Masuk ke Akun</span>
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>

{{-- ==================== STATS SECTION ==================== --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-14">
    @php
        $statItems = [
            ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'value' => $stats['total_events'], 'label' => 'Total Event'],
            ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'value' => $stats['total_registrations'], 'label' => 'Total Pendaftaran'],
            ['icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'value' => $stats['total_categories'], 'label' => 'Kategori Event'],
            ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'value' => $stats['upcoming_events'], 'label' => 'Event Aktif'],
        ];
    @endphp

    @foreach($statItems as $stat)
        <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm p-5 flex items-center space-x-4">
            <div class="p-3 bg-emerald-100 rounded-xl flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-extrabold text-emerald-800">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-500 font-medium">{{ $stat['label'] }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- ==================== UPCOMING EVENTS ==================== --}}
<div class="mb-14">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Event Mendatang</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kegiatan yang sedang berjalan &amp; akan datang</p>
        </div>
        <a href="{{ route('events.index') }}" class="text-sm font-bold text-emerald-700 hover:text-emerald-900 hover:underline flex items-center space-x-1">
            <span>Lihat Semua</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    @if($upcomingEvents->isEmpty())
        <div class="bg-white rounded-2xl border border-emerald-100 p-12 text-center">
            <svg class="w-12 h-12 text-emerald-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-gray-500 text-sm">Belum ada event mendatang.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($upcomingEvents as $event)
                <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden flex flex-col hover:shadow-md transition group">
                    {{-- Card Banner --}}
                    <div class="h-40 bg-gradient-to-r from-emerald-700 to-emerald-600 relative overflow-hidden flex items-center justify-center">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="text-center p-4">
                                <svg class="w-10 h-10 text-emerald-300 mx-auto opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif

                        <span class="absolute top-3 left-3 px-2.5 py-1 text-[11px] font-bold uppercase rounded-lg bg-emerald-900/80 text-emerald-200 backdrop-blur-sm border border-emerald-500/30">
                            {{ $event->category->name }}
                        </span>
                        <span class="absolute top-3 right-3 px-2.5 py-1 text-[11px] font-bold uppercase rounded-lg shadow backdrop-blur-sm
                            {{ $event->status === 'upcoming' ? 'bg-blue-600/90 text-white' : 'bg-emerald-600/90 text-white' }}">
                            {{ $event->status }}
                        </span>
                    </div>

                    {{-- Card Body --}}
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                        <div>
                            <h3 class="text-base font-bold text-gray-900 group-hover:text-emerald-700 transition line-clamp-2 mb-1">
                                {{ $event->name }}
                            </h3>
                            <p class="text-xs text-gray-500 line-clamp-2">{{ $event->description }}</p>
                        </div>

                        <div class="text-xs text-gray-600 space-y-1 pt-2 border-t border-gray-100">
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>{{ $event->event_date->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="truncate">{{ $event->location }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-2">
                            <span class="text-xs font-bold {{ $event->isFull() ? 'text-red-600' : 'text-emerald-800' }}">
                                {{ $event->active_registrations_count }} / {{ $event->capacity }} Pendaftar
                            </span>
                            <a href="{{ route('events.show', $event) }}"
                               class="px-3.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow transition">
                                @if(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isPanitia()))
                                    Lihat Detail
                                @else
                                    Detail &amp; Daftar
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ==================== KATEGORI SECTION ==================== --}}
<div class="mb-14">
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Jelajahi Kategori</h2>
        <p class="text-sm text-gray-500 mt-0.5">Temukan event berdasarkan kategori yang kamu minati</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        @forelse($categories as $category)
            <a href="{{ route('events.index', ['category' => $category->id]) }}"
               class="group bg-white rounded-2xl border border-emerald-100 shadow-sm p-5 text-center hover:border-emerald-400 hover:shadow-md transition">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-emerald-700 transition">
                    <svg class="w-6 h-6 text-emerald-700 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-800 group-hover:text-emerald-700 transition">{{ $category->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $category->events_count }} event</p>
            </a>
        @empty
            <p class="col-span-4 text-center text-sm text-gray-400 py-8">Belum ada kategori.</p>
        @endforelse
    </div>
</div>

{{-- ==================== CTA SECTION ==================== --}}
@guest
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-800 to-emerald-600 p-8 md:p-12 text-white text-center shadow-xl mb-4">
    <div class="absolute -top-8 -right-8 w-40 h-40 bg-emerald-500/20 rounded-full blur-2xl"></div>
    <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-emerald-900/30 rounded-full blur-2xl"></div>
    <div class="relative">
        <h2 class="text-2xl md:text-3xl font-extrabold mb-3">Ikuti Kegiatan &amp; Event OSIS</h2>
        <p class="text-emerald-100 text-sm md:text-base mb-6 max-w-md mx-auto">
            Silakan masuk menggunakan akun yang telah diberikan oleh pihak sekolah untuk mendaftar event.
        </p>
        <div class="flex justify-center gap-3 flex-wrap">
            <a href="{{ route('login') }}"
               class="px-6 py-3 bg-white text-emerald-800 font-bold rounded-xl shadow hover:bg-emerald-50 transition text-sm">
                Masuk ke Akun
            </a>
            <a href="{{ route('events.index') }}"
               class="px-6 py-3 border border-white/40 text-white font-bold rounded-xl hover:bg-white/10 transition text-sm">
                Lihat Katalog Event
            </a>
        </div>
    </div>
</div>
@endguest

@endsection

@extends('layouts.app')

@section('title', 'Daftar Event OSIS')

@section('content')
<div class="space-y-8">
    <!-- Header Title & Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Daftar Event OSIS</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Temukan dan ikuti berbagai kegiatan seru yang diselenggarakan OSIS SMK Pesat</p>
        </div>

        @auth
            @if(Auth::user()->isAdmin() || Auth::user()->isPanitia())
                <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow transition self-start md:self-auto">
                    <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Event Baru
                </a>
            @endif
        @endauth
    </div>

    <!-- Search + Filter Form (Requirements 06: Search + Filter + Pagination ketiganya bekerja bersama) -->
    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow-sm border border-emerald-100 dark:border-gray-800">
        <form action="{{ route('events.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            
            <!-- Search Keyword Input -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Cari Event</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Cari berdasarkan nama, deskripsi, lokasi..." 
                        class="w-full pl-10 pr-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filter Category Dropdown -->
            <div>
                <label for="category" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Kategori</label>
                <select name="category" id="category" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status Dropdown -->
            <div>
                <label for="status" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                    <option value="">Semua Status</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Akan Datang (Upcoming)</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung (Ongoing)</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                </select>
            </div>

            <!-- Form Submit & Reset Buttons -->
            <div class="md:col-span-4 flex justify-end space-x-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                @if(request('search') || request('category') || request('status'))
                    <a href="{{ route('events.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 font-bold text-xs rounded-lg transition">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg shadow transition flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Event Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-emerald-100 dark:border-gray-800 overflow-hidden flex flex-col justify-between hover:shadow-md transition group">
                <div>
                    <!-- Banner or Default Emerald Header -->
                    <div class="h-44 bg-gradient-to-r from-emerald-700 to-emerald-600 relative overflow-hidden flex items-center justify-center">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="text-center p-4">
                                <svg class="w-12 h-12 text-emerald-300 mx-auto opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-emerald-100 font-bold text-sm block mt-1 tracking-wide">{{ $event->category->name }}</span>
                            </div>
                        @endif

                        <!-- Category Badge Top Left -->
                        <span class="absolute top-3 left-3 px-2.5 py-1 text-[11px] font-bold uppercase rounded-lg bg-emerald-900/80 text-emerald-200 backdrop-blur-sm border border-emerald-500/30">
                            {{ $event->category->name }}
                        </span>

                        <!-- Status Badge Top Right -->
                        <span class="absolute top-3 right-3 px-2.5 py-1 text-[11px] font-bold uppercase rounded-lg shadow backdrop-blur-sm
                            {{ $event->status === 'upcoming' ? 'bg-blue-600/90 text-white' : '' }}
                            {{ $event->status === 'ongoing' ? 'bg-emerald-600/90 text-white' : '' }}
                            {{ $event->status === 'completed' ? 'bg-gray-800/90 text-gray-200' : '' }}
                            {{ $event->status === 'cancelled' ? 'bg-red-600/90 text-white' : '' }}">
                            {{ $event->status }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 space-y-3">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition line-clamp-2">
                            <a href="{{ route('events.show', $event) }}">{{ $event->name }}</a>
                        </h2>

                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">
                            {{ $event->description }}
                        </p>

                        <!-- Date & Location Badges -->
                        <div class="space-y-1.5 pt-2 border-t border-gray-100 dark:border-gray-800 text-xs text-gray-600 dark:text-gray-400">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $event->event_date->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="truncate">{{ $event->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer (Capacity & Action Button) -->
                <div class="px-6 py-4 bg-emerald-50/50 dark:bg-gray-800/50 border-t border-emerald-100 dark:border-gray-800 flex items-center justify-between gap-2">
                    <div>
                        <span class="text-[10px] text-gray-500 dark:text-gray-400 uppercase font-bold block">Kuota Peserta</span>
                        <span class="text-xs font-bold {{ $event->isFull() ? 'text-red-600 dark:text-red-400' : 'text-emerald-800 dark:text-emerald-400' }}">
                            {{ $event->active_registrations_count }} / {{ $event->capacity }} Pendaftar
                        </span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <a href="{{ route('events.show', $event) }}" class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow transition">
                            @if(Auth::check() && (Auth::user()->isAdmin() || Auth::user()->isPanitia()))
                                Lihat Detail
                            @else
                                Detail & Daftar
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="md:col-span-2 lg:col-span-3 bg-white p-12 rounded-2xl text-center border border-emerald-100">
                <svg class="w-16 h-16 text-emerald-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-gray-800">Tidak ada event ditemukan</h3>
                <p class="text-xs text-gray-500 mt-1">Coba sesuaikan kata kunci pencarian atau filter kategori & status Anda.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="mt-8">
        {{ $events->links() }}
    </div>
</div>
@endsection

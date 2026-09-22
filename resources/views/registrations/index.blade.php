@extends('layouts.app')

@section('title', 'Kelola Peserta Pendaftar')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Kelola Data Pendaftaran Peserta</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Cari, filter, dan ubah status konfirmasi peserta event OSIS</p>
    </div>

    <!-- Search + Filter Controls -->
    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow-sm border border-emerald-100 dark:border-gray-800">
        <form action="{{ route('registrations.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search Participant Name/Email/Class -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Cari Peserta</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Cari nama peserta, email, kelas..."
                    class="w-full px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500 text-sm">
            </div>

            <!-- Filter Event -->
            <div>
                <label for="event_id" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Filter Event</label>
                <select name="event_id" id="event_id" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500 text-sm">
                    <option value="">Semua Event</option>
                    @foreach($events as $e)
                        <option value="{{ $e->id }}" {{ request('event_id') == $e->id ? 'selected' : '' }}>
                            {{ $e->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div>
                <label for="status" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Filter Status</label>
                <select name="status" id="status" class="w-full px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500 text-sm">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                    <option value="attended" {{ request('status') == 'attended' ? 'selected' : '' }}>Hadir (Attended)</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end space-x-2 pt-2 border-t border-gray-100 dark:border-gray-800">
                @if(request('search') || request('event_id') || request('status'))
                    <a href="{{ route('registrations.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 font-bold text-xs rounded-lg transition">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg shadow transition">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Registrations Table -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-emerald-100 dark:border-gray-800 overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse min-w-[700px]">
            <thead class="bg-emerald-50/80 dark:bg-gray-800/80 text-emerald-900 dark:text-emerald-300 text-xs uppercase font-bold border-b border-emerald-100 dark:border-gray-800">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Peserta</th>
                    <th class="px-6 py-4">Event</th>
                    <th class="px-6 py-4">Tgl Daftar</th>
                    <th class="px-6 py-4">Status & Catatan</th>
                    <th class="px-6 py-4 text-right">Ubah Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-gray-700 dark:text-gray-300">
                @forelse($registrations as $index => $reg)
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-gray-800/50 transition">
                        <td class="px-6 py-4 font-bold text-gray-400 dark:text-gray-500">
                            {{ $registrations->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-900 dark:text-white">{{ $reg->user->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $reg->user->email }} &bull; Kelas: {{ $reg->user->kelas ?? '-' }}</p>
                            @if($reg->user->phone)
                                <p class="text-[11px] text-emerald-700 dark:text-emerald-400 mt-0.5">HP: {{ $reg->user->phone }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('events.show', $reg->event) }}" class="font-bold text-emerald-800 dark:text-emerald-400 hover:underline">
                                {{ $reg->event->name }}
                            </a>
                            <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $reg->event->category->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs">
                            {{ $reg->registration_date->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold uppercase rounded-lg shadow-sm inline-block
                                {{ $reg->status === 'approved' ? 'bg-emerald-100 dark:bg-emerald-900/80 text-emerald-900 dark:text-emerald-200 border border-emerald-300 dark:border-emerald-700' : '' }}
                                {{ $reg->status === 'pending' ? 'bg-amber-100 dark:bg-amber-900/80 text-amber-900 dark:text-amber-200 border border-amber-300 dark:border-amber-700' : '' }}
                                {{ $reg->status === 'rejected' ? 'bg-red-100 dark:bg-red-900/80 text-red-900 dark:text-red-200 border border-red-300 dark:border-red-700' : '' }}
                                {{ $reg->status === 'attended' ? 'bg-blue-100 dark:bg-blue-900/80 text-blue-900 dark:text-blue-200 border border-blue-300 dark:border-blue-700' : '' }}
                                {{ $reg->status === 'cancelled' ? 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600' : '' }}">
                                {{ $reg->status }}
                            </span>
                            @if($reg->notes)
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 italic">"{{ $reg->notes }}"</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col sm:flex-row items-end sm:items-center justify-end gap-2">
                                @if($reg->status === 'approved')
                                    <form action="{{ route('registrations.check-in', $reg) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Hadir
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('registrations.update-status', $reg) }}" method="POST" class="inline-flex items-center space-x-1">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="px-2 py-1 text-xs font-semibold rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-emerald-500">
                                        <option value="pending" {{ $reg->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $reg->status == 'approved' ? 'selected' : '' }}>Setujui (Approved)</option>
                                        <option value="rejected" {{ $reg->status == 'rejected' ? 'selected' : '' }}>Tolak (Rejected)</option>
                                        <option value="attended" {{ $reg->status == 'attended' ? 'selected' : '' }}>Hadir (Attended)</option>
                                        <option value="cancelled" {{ $reg->status == 'cancelled' ? 'selected' : '' }}>Batal (Cancelled)</option>
                                    </select>
                                    <button type="submit" class="px-2.5 py-1 bg-gray-700 dark:bg-gray-600 hover:bg-gray-800 dark:hover:bg-gray-500 text-white text-xs font-bold rounded-lg transition shadow-sm">
                                        Ubah
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-xs text-gray-500 dark:text-gray-400">
                            Belum ada pendaftaran peserta yang sesuai filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $registrations->links() }}
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Riwayat Pendaftaran Saya')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Pendaftaran Event Saya</h1>
        <p class="text-sm text-gray-600 mt-1">Daftar event OSIS yang pernah dan sedang Anda ikuti</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($registrations as $reg)
                <div class="p-6 hover:bg-emerald-50/40 transition flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded bg-emerald-100 text-emerald-800">
                                {{ $reg->event->category->name }}
                            </span>
                            <span class="text-xs text-gray-400">📅 {{ $reg->registration_date->format('d M Y, H:i') }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 hover:text-emerald-700 transition">
                            <a href="{{ route('events.show', $reg->event) }}">{{ $reg->event->name }}</a>
                        </h3>
                        <p class="text-xs text-gray-600">
                            Waktu Pelaksanaan: <span class="font-bold text-gray-800">{{ $reg->event->event_date->format('d M Y, H:i') }} WIB</span> &bull; Lokasi: {{ $reg->event->location }}
                        </p>
                        @if($reg->notes)
                            <p class="text-xs text-gray-500 italic mt-1">Catatan: "{{ $reg->notes }}"</p>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2 sm:self-center">
                        <span class="px-3 py-1.5 text-xs font-bold uppercase rounded-lg shadow-sm
                            {{ $reg->status === 'approved' ? 'bg-emerald-600 text-white' : '' }}
                            {{ $reg->status === 'pending' ? 'bg-amber-500 text-white' : '' }}
                            {{ $reg->status === 'rejected' ? 'bg-red-600 text-white' : '' }}
                            {{ $reg->status === 'attended' ? 'bg-blue-600 text-white' : '' }}
                            {{ $reg->status === 'cancelled' ? 'bg-gray-600 text-white' : '' }}">
                            Status: {{ $reg->status }}
                        </span>

                        @if(in_array($reg->status, ['approved', 'attended']))
                            <a href="{{ route('registrations.ticket', $reg) }}" class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs rounded-lg transition shadow flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                                <span>E-Tiket</span>
                            </a>
                        @endif

                        @if($reg->status === 'pending')
                            <form action="{{ route('registrations.cancel', $reg) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran ini?')">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 font-bold text-xs rounded-lg transition">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-emerald-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800">Belum Ada Pendaftaran</h3>
                    <p class="text-xs text-gray-500 mt-1">Anda belum mendaftar di event OSIS mana pun.</p>
                    <a href="{{ route('events.index') }}" class="mt-4 inline-block px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow transition">
                        Lihat Event Terseedia &rarr;
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <div>
        {{ $registrations->links() }}
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'E-Tiket Digital - ' . $registration->event->name)

@section('content')
<style>
    @media print {
        header, footer, nav, .no-print {
            display: none !important;
        }
        body {
            background-color: white !important;
        }
        .print-area {
            box-shadow: none !important;
            border: 2px solid #047857 !important;
            margin: 0 auto !important;
        }
    }
</style>

<div class="space-y-6 max-w-2xl mx-auto">
    <!-- Back & Print Buttons -->
    <div class="flex justify-between items-center no-print">
        <a href="{{ route('registrations.my') }}" class="text-xs font-bold text-emerald-700 hover:underline">
            &larr; Kembali ke Pendaftaran Saya
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold rounded-xl shadow transition flex items-center space-x-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            <span>Cetak / Download PDF</span>
        </button>
    </div>

    <!-- E-Ticket Card -->
    <div class="print-area bg-white rounded-3xl shadow-xl overflow-hidden border border-emerald-200">
        <!-- Ticket Header -->
        <div class="bg-gradient-to-r from-emerald-800 via-emerald-700 to-emerald-600 p-6 text-white flex justify-between items-center relative overflow-hidden">
            <div class="space-y-1 z-10">
                <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded bg-emerald-900/60 text-emerald-200 border border-emerald-500/30">
                    E-TIKET RESMI OSIS
                </span>
                <h2 class="text-xl font-extrabold tracking-tight">Event-Osis Digital Ticket</h2>
                <p class="text-emerald-100 text-xs">SMK Negeri OSIS &bull; {{ $registration->event->category->name }}</p>
            </div>
            
            <div class="text-right z-10">
                <span class="text-[10px] uppercase font-bold text-emerald-300 block">Status Presensi</span>
                <span class="px-3 py-1 text-xs font-black uppercase rounded-lg shadow-sm inline-block mt-0.5
                    {{ $registration->status === 'approved' ? 'bg-emerald-300 text-emerald-900' : '' }}
                    {{ $registration->status === 'attended' ? 'bg-blue-300 text-blue-900' : '' }}
                    {{ $registration->status === 'pending' ? 'bg-amber-300 text-amber-900' : '' }}">
                    {{ $registration->status }}
                </span>
            </div>

            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-xl pointer-events-none"></div>
        </div>

        <!-- Ticket Divider with Notch -->
        <div class="relative bg-emerald-50 h-4 border-y border-dashed border-emerald-200 flex items-center justify-between px-4">
            <div class="w-4 h-4 rounded-full bg-emerald-50/40 border border-emerald-200 -ml-6"></div>
            <div class="text-[10px] font-mono text-emerald-700 font-bold uppercase tracking-widest">
                ID TIKET: REG-OSIS-{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}
            </div>
            <div class="w-4 h-4 rounded-full bg-emerald-50/40 border border-emerald-200 -mr-6"></div>
        </div>

        <!-- Ticket Body -->
        <div class="p-6 sm:p-8 space-y-6">
            <!-- Event Title -->
            <div class="border-b border-gray-100 pb-4">
                <span class="text-[10px] font-bold uppercase text-emerald-700 tracking-wider">Nama Event</span>
                <h1 class="text-2xl font-black text-gray-900 mt-0.5">{{ $registration->event->name }}</h1>
            </div>

            <!-- 2-Column Info Grid -->
            <div class="grid grid-cols-2 gap-6 text-xs">
                <div>
                    <span class="font-bold text-gray-400 uppercase tracking-wider block text-[10px]">Nama Peserta</span>
                    <span class="font-bold text-gray-900 text-sm block mt-0.5">{{ $registration->user->name }}</span>
                    <span class="text-gray-500 block">Kelas: {{ $registration->user->kelas ?? '-' }}</span>
                </div>

                <div>
                    <span class="font-bold text-gray-400 uppercase tracking-wider block text-[10px]">Kontak Peserta</span>
                    <span class="font-bold text-gray-900 block mt-0.5">{{ $registration->user->email }}</span>
                    <span class="text-gray-500 block">HP: {{ $registration->user->phone ?? '-' }}</span>
                </div>

                <div>
                    <span class="font-bold text-gray-400 uppercase tracking-wider block text-[10px]">Waktu Pelaksanaan</span>
                    <span class="font-bold text-emerald-800 block mt-0.5">📅 {{ $registration->event->event_date->format('d M Y') }}</span>
                    <span class="text-gray-600 font-medium">Jam: {{ $registration->event->event_date->format('H:i') }} WIB</span>
                </div>

                <div>
                    <span class="font-bold text-gray-400 uppercase tracking-wider block text-[10px]">Lokasi Event</span>
                    <span class="font-bold text-gray-900 block mt-0.5">📍 {{ $registration->event->location }}</span>
                </div>
            </div>

            @if($registration->notes)
                <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100 text-xs">
                    <span class="font-bold text-emerald-800 block">Catatan Pendaftaran:</span>
                    <p class="text-gray-700 italic mt-0.5">"{{ $registration->notes }}"</p>
                </div>
            @endif

            <!-- Ticket Footer: QR Code & Verification Stamp -->
            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <!-- Dynamic SVG QR Code Simulation -->
                    <div class="p-2 bg-white rounded-xl border border-emerald-200 shadow-sm flex-shrink-0">
                        <svg class="w-16 h-16 text-emerald-900" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm-2 10h8v8H2v-8zm2 2v4h4v-4H4zm10-14h8v8h-8V2zm2 2v4h4V4h-4zm-2 10h2v2h-2v-2zm4 0h2v2h-2v-2zm2 2h2v2h-2v-2zm-6 2h2v2h-2v-2zm4 0h2v4h-2v-4zm-4 2h2v2h-2v-2z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide block">Scan untuk Presensi</span>
                        <span class="text-xs font-mono font-bold text-emerald-800">OSIS-PASS-{{ $registration->id }}-{{ strtoupper(substr(md5($registration->id . $registration->user_id), 0, 6)) }}</span>
                        <span class="text-[10px] text-gray-400 block mt-0.5">Tunjukkan e-tiket ini kepada panitia</span>
                    </div>
                </div>

                <div class="text-right sm:text-right text-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Penyelenggara</span>
                    <span class="text-xs font-bold text-gray-800">Panitia OSIS SMKN OSIS</span>
                    <span class="text-[10px] text-emerald-600 font-bold block mt-0.5">&check; Verifikasi Terkonfirmasi</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

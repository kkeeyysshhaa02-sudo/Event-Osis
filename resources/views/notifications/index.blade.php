@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Notifikasi Akun</h1>
            <p class="text-sm text-gray-600 mt-1">Informasi pendaftaran, permohonan, dan update event</p>
        </div>

        @if(Auth::user()->unreadNotifications->count() > 0)
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-emerald-100 text-emerald-800 hover:bg-emerald-200 text-xs font-bold rounded-xl transition">
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden divide-y divide-gray-100">
        @forelse($notifications as $notification)
            <div class="p-5 flex items-start justify-between gap-4 {{ $notification->read_at ? 'bg-white' : 'bg-emerald-50/50' }} transition">
                <div class="space-y-1">
                    <div class="flex items-center space-x-2">
                        <span class="font-bold text-gray-900 text-sm">{{ $notification->data['title'] ?? 'Notifikasi' }}</span>
                        @if(! $notification->read_at)
                            <span class="px-2 py-0.5 text-[10px] font-bold uppercase rounded bg-red-500 text-white">Baru</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-700">{{ $notification->data['message'] ?? '' }}</p>
                    <span class="text-[11px] text-gray-400 block pt-1">{{ $notification->created_at->diffForHumans() }}</span>
                </div>

                <div class="flex items-center space-x-2">
                    @if(isset($notification->data['url']))
                        <a href="{{ $notification->data['url'] }}" class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700">
                            Lihat
                        </a>
                    @endif

                    @if(! $notification->read_at)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-bold rounded-lg hover:bg-gray-200">
                                Tandai Dibaca
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-500 text-sm">
                Belum ada notifikasi.
            </div>
        @endforelse
    </div>

    <div>
        {{ $notifications->links() }}
    </div>
</div>
@endsection

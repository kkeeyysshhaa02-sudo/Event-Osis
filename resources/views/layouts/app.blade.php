<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Event-Osis') - Sistem Pengelolaan Event OSIS</title>

    <!-- Tailwind CSS CDN for instant rendering & emerald green theme -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js for interactive dropdowns & tabs -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-emerald-50/40 text-gray-800 font-sans min-h-screen flex flex-col antialiased">

    <!-- Header Navigation -->
    <header class="bg-emerald-700 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand & Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('events.index') }}" class="flex items-center space-x-2 font-bold text-xl tracking-tight hover:text-emerald-200 transition">
                        <span class="p-2 bg-emerald-800 rounded-lg shadow-inner">
                            <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <span>Event-<span class="text-emerald-300">Osis</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('events.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('events.index', 'events.show') ? 'bg-emerald-800 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-600' }}">
                        Daftar Event
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-emerald-800 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-600' }}">
                            Dashboard
                        </a>

                        @if(Auth::user()->isPeserta())
                            <a href="{{ route('registrations.my') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('registrations.my') ? 'bg-emerald-800 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-600' }}">
                                Pendaftaran Saya
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin() || Auth::user()->isPanitia())
                            <a href="{{ route('events.create') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('events.create') ? 'bg-emerald-800 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-600' }}">
                                + Buat Event
                            </a>
                            <a href="{{ route('registrations.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('registrations.index') ? 'bg-emerald-800 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-600' }}">
                                Kelola Peserta
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('categories.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('categories.index') ? 'bg-emerald-800 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-600' }}">
                                Kategori
                            </a>
                            <a href="{{ route('users.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('users.index') ? 'bg-emerald-800 text-white font-semibold' : 'text-emerald-100 hover:bg-emerald-600' }}">
                                Kelola User
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- User Profile & Notifications -->
                <div class="flex items-center space-x-3">
                    @auth
                        <!-- Notifications Bell -->
                        @php
                            $unreadCount = Auth::user()->unreadNotifications->count();
                        @endphp
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="relative p-2 text-emerald-100 hover:text-white hover:bg-emerald-600 rounded-full focus:outline-none transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute top-1 right-1 px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full shadow">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notifications Dropdown -->
                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl py-2 z-50 text-gray-800 border border-emerald-100">
                                <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center bg-emerald-50">
                                    <span class="font-bold text-sm text-emerald-800">Notifikasi ({{ $unreadCount }})</span>
                                    <a href="{{ route('notifications.index') }}" class="text-xs text-emerald-600 hover:underline">Lihat Semua</a>
                                </div>
                                <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                                    @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                        <div class="px-4 py-2.5 text-xs hover:bg-emerald-50/60 {{ $notification->read_at ? 'opacity-70' : 'bg-emerald-50/30 font-semibold' }}">
                                            <p class="text-emerald-900 font-bold">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                            <p class="text-gray-600 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                                            <span class="text-[10px] text-gray-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    @empty
                                        <p class="px-4 py-4 text-xs text-gray-500 text-center">Belum ada notifikasi.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- User Profile Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 bg-emerald-800 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-emerald-900 transition">
                                <span class="w-7 h-7 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs uppercase shadow">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                                <span class="hidden sm:inline-block max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                <span class="px-1.5 py-0.5 text-[10px] uppercase tracking-wider rounded font-semibold bg-emerald-300 text-emerald-900">
                                    {{ Auth::user()->role }}
                                </span>
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-1 z-50 text-gray-800 border border-gray-100">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-xs font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-emerald-100 hover:text-white px-3 py-1.5 rounded-md hover:bg-emerald-600 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-emerald-500 hover:bg-emerald-400 text-white px-3 py-1.5 rounded-md shadow transition">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-600 text-emerald-900 rounded-r shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-700 hover:text-emerald-900 font-bold text-lg">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-600 text-red-900 rounded-r shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-700 hover:text-red-900 font-bold text-lg">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-emerald-900 text-emerald-200 border-t border-emerald-800 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center text-sm space-y-4 md:space-y-0">
            <div>
                <p class="font-bold text-emerald-100">Event-Osis &copy; {{ date('Y') }}</p>
                <p class="text-xs text-emerald-400 mt-0.5">Sistem Informasi Pengelolaan Event & Pendaftaran OSIS</p>
            </div>
            <div class="text-right text-xs text-emerald-300">
                <p>Nama: <span class="font-semibold text-white">Wyanet In Nakeisha</span> | Kelas: <span class="font-semibold text-white">XI-2</span></p>
                <p class="mt-0.5">Sumatif Tengah Semester &bull; XI RPL</p>
            </div>
        </div>
    </footer>

</body>
</html>

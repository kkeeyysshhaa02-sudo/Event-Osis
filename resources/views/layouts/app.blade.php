<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Event-Osis') - Sistem Pengelolaan Event OSIS SMK Pesat</title>

    <!-- Dark Mode Init Script (Prevents Theme Flash) -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Tailwind CSS CDN with dark mode class support -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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
                            950: '#022c22',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js for interactive sidebar, theme toggle, dropdowns & modals -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Custom scrollbar styling for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.2);
            border-radius: 9999px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.4);
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-800 dark:text-gray-100 font-sans min-h-screen antialiased transition-colors duration-200"
      x-data="{ 
          darkMode: document.documentElement.classList.contains('dark'),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('theme', 'light');
              }
          }
      }">

@auth
    {{-- ==================== AUTHENTICATED USER: FIXED STICKY SIDEBAR LAYOUT ==================== --}}
    <div class="min-h-screen flex flex-row" x-data="{ sidebarOpen: false }">

        <!-- Mobile Backdrop -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900/70 backdrop-blur-sm lg:hidden" 
             style="display: none;"></div>

        <!-- Sticky Fixed Sidebar (Tidak Ikut Roll saat halaman di-scroll) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-emerald-900 dark:bg-gray-900 text-white flex flex-col justify-between transition-transform duration-300 ease-in-out lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 shadow-2xl border-r border-emerald-800 dark:border-gray-800 flex-shrink-0">
            
            <!-- Sidebar Brand -->
            <div class="p-5 border-b border-emerald-800/80 dark:border-gray-800 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <span class="p-2 bg-emerald-700 dark:bg-emerald-800 rounded-xl shadow-inner group-hover:bg-emerald-600 transition">
                            <svg class="w-6 h-6 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <div>
                            <span class="font-extrabold text-lg tracking-tight block leading-none text-white">Event-<span class="text-emerald-300">Osis</span></span>
                            <span class="text-[10px] text-emerald-300 font-medium tracking-wider uppercase mt-1 block">OSIS SMK Pesat</span>
                        </div>
                    </a>
                    
                    <!-- Close Button on Mobile -->
                    <button @click="sidebarOpen = false" class="lg:hidden text-emerald-300 hover:text-white p-1 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Sidebar Navigation Links (Independent Scroll if content exceeds height) -->
            <div class="flex-1 overflow-y-auto sidebar-scroll px-4 py-6 space-y-6">
                
                <!-- Navigasi Utama -->
                <div>
                    <p class="px-3 text-[11px] font-bold text-emerald-300/70 dark:text-gray-400 uppercase tracking-wider mb-2">Navigasi Utama</p>
                    <nav class="space-y-1">
                        <a href="{{ route('home') }}" 
                           class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('home') ? 'bg-emerald-700 dark:bg-emerald-800 text-white shadow-md' : 'text-emerald-100 dark:text-gray-300 hover:bg-emerald-800/80 dark:hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Beranda</span>
                        </a>

                        {{-- Khusus Admin TIDAK ADA menu "Daftar Event" --}}
                        @if(!Auth::user()->isAdmin())
                            <a href="{{ route('events.index') }}" 
                               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('events.index', 'events.show') ? 'bg-emerald-700 dark:bg-emerald-800 text-white shadow-md' : 'text-emerald-100 dark:text-gray-300 hover:bg-emerald-800/80 dark:hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span>Daftar Event</span>
                            </a>
                        @endif

                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-emerald-700 dark:bg-emerald-800 text-white shadow-md' : 'text-emerald-100 dark:text-gray-300 hover:bg-emerald-800/80 dark:hover:bg-gray-800 hover:text-white' }}">
                            <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </nav>
                </div>

                <!-- Menu Khusus Peserta -->
                @if(Auth::user()->isPeserta())
                    <div>
                        <p class="px-3 text-[11px] font-bold text-emerald-300/70 dark:text-gray-400 uppercase tracking-wider mb-2">Kegiatan Saya</p>
                        <nav class="space-y-1">
                            <a href="{{ route('registrations.my') }}" 
                               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('registrations.my', 'registrations.ticket') ? 'bg-emerald-700 dark:bg-emerald-800 text-white shadow-md' : 'text-emerald-100 dark:text-gray-300 hover:bg-emerald-800/80 dark:hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                <span>Pendaftaran Saya</span>
                            </a>
                        </nav>
                    </div>
                @endif

                <!-- Menu Khusus Panitia / Admin (Kelola Event & Peserta) -->
                @if(Auth::user()->isAdmin() || Auth::user()->isPanitia())
                    <div>
                        <p class="px-3 text-[11px] font-bold text-emerald-300/70 dark:text-gray-400 uppercase tracking-wider mb-2">Kelola Kegiatan</p>
                        <nav class="space-y-1">
                            <a href="{{ route('events.create') }}" 
                               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('events.create') ? 'bg-emerald-700 dark:bg-emerald-800 text-white shadow-md' : 'text-emerald-100 dark:text-gray-300 hover:bg-emerald-800/80 dark:hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                <span>Buat Event Baru</span>
                            </a>
                            <a href="{{ route('registrations.index') }}" 
                               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('registrations.index') ? 'bg-emerald-700 dark:bg-emerald-800 text-white shadow-md' : 'text-emerald-100 dark:text-gray-300 hover:bg-emerald-800/80 dark:hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <span>Kelola Peserta</span>
                            </a>
                        </nav>
                    </div>
                @endif

                <!-- Menu Khusus Admin (Master Data & User) -->
                @if(Auth::user()->isAdmin())
                    <div>
                        <p class="px-3 text-[11px] font-bold text-emerald-300/70 dark:text-gray-400 uppercase tracking-wider mb-2">Master &amp; Pengaturan</p>
                        <nav class="space-y-1">
                            <a href="{{ route('categories.index') }}" 
                               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('categories.index') ? 'bg-emerald-700 dark:bg-emerald-800 text-white shadow-md' : 'text-emerald-100 dark:text-gray-300 hover:bg-emerald-800/80 dark:hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <span>Kelola Kategori</span>
                            </a>
                            <a href="{{ route('users.index') }}" 
                               class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('users.index', 'users.create') ? 'bg-emerald-700 dark:bg-emerald-800 text-white shadow-md' : 'text-emerald-100 dark:text-gray-300 hover:bg-emerald-800/80 dark:hover:bg-gray-800 hover:text-white' }}">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>Kelola Pengguna</span>
                            </a>
                        </nav>
                    </div>
                @endif

                <!-- Notifikasi Menu -->
                <div>
                    <p class="px-3 text-[11px] font-bold text-emerald-300/70 dark:text-gray-400 uppercase tracking-wider mb-2">Pemberitahuan</p>
                    <a href="{{ route('notifications.index') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('notifications.index') ? 'bg-emerald-700 dark:bg-emerald-800 text-white shadow-md' : 'text-emerald-100 dark:text-gray-300 hover:bg-emerald-800/80 dark:hover:bg-gray-800 hover:text-white' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span>Notifikasi</span>
                        </div>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="px-2 py-0.5 text-xs font-bold bg-red-500 text-white rounded-full">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                </div>

            </div>

            <!-- Sidebar User Profile & Theme Quick Bar Footer -->
            <div class="p-4 border-t border-emerald-800 dark:border-gray-800 bg-emerald-950/60 dark:bg-gray-950 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <span class="w-9 h-9 rounded-xl bg-emerald-600 dark:bg-emerald-700 text-white flex items-center justify-center font-extrabold text-sm uppercase shadow-inner flex-shrink-0">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </span>
                        <div class="truncate">
                            <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</p>
                            <span class="inline-block px-1.5 py-0.2 text-[9px] uppercase tracking-wider rounded font-bold bg-emerald-700 dark:bg-gray-800 text-emerald-200 dark:text-emerald-400 mt-0.5">
                                {{ Auth::user()->role }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-1">
                        <!-- Dark mode toggle inside sidebar footer -->
                        <button @click="toggleTheme()" 
                                type="button"
                                :title="darkMode ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Malam'" 
                                class="p-2 text-emerald-300 dark:text-gray-400 hover:text-amber-300 dark:hover:text-amber-300 hover:bg-emerald-800/60 dark:hover:bg-gray-800 rounded-lg transition">
                            <template x-if="darkMode">
                                <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </template>
                            <template x-if="!darkMode">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                            </template>
                        </button>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" title="Keluar Akun" 
                                    class="p-2 text-emerald-300 dark:text-gray-400 hover:text-red-300 dark:hover:text-red-400 hover:bg-emerald-800/60 dark:hover:bg-gray-800 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area (Scrollable independently) -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            
            <!-- Topbar (Sticky on content scroll) -->
            <header class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-30 shadow-2xs transition-colors duration-200">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">
                        
                        <!-- Left: Mobile Toggle & Header Title -->
                        <div class="flex items-center space-x-3">
                            <button @click="sidebarOpen = true" 
                                    type="button" 
                                    class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-gray-100 dark:hover:bg-gray-800 lg:hidden focus:outline-none transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>

                            <div>
                                <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Sistem Informasi Event OSIS SMK Pesat</span>
                            </div>
                        </div>

                        <!-- Right: Dark Mode Toggle, Notifications & Profile -->
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            
                            <!-- Dark Mode Switch Button -->
                            <button @click="toggleTheme()" 
                                    type="button" 
                                    :title="darkMode ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap / Malam'"
                                    class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-amber-500 dark:hover:text-amber-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none transition">
                                <template x-if="darkMode">
                                    <!-- Sun Icon -->
                                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </template>
                                <template x-if="!darkMode">
                                    <!-- Moon Icon -->
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                    </svg>
                                </template>
                            </button>

                            <!-- Notifications Bell -->
                            @php
                                $unreadCount = Auth::user()->unreadNotifications->count();
                            @endphp
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-emerald-700 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-gray-800 rounded-xl focus:outline-none transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @if($unreadCount > 0)
                                        <span class="absolute top-1 right-1 px-1.5 py-0.5 text-[10px] font-bold leading-none text-white bg-red-500 rounded-full shadow">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </button>

                                <div x-show="open" 
                                     @click.outside="open = false" 
                                     x-transition 
                                     class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-900 rounded-2xl shadow-xl py-2 z-50 text-gray-800 dark:text-gray-100 border border-gray-100 dark:border-gray-800" 
                                     style="display: none;">
                                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-emerald-50/60 dark:bg-gray-800/70 rounded-t-xl">
                                        <span class="font-bold text-sm text-emerald-900 dark:text-emerald-400">Notifikasi ({{ $unreadCount }})</span>
                                        <a href="{{ route('notifications.index') }}" class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold hover:underline">Lihat Semua</a>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
                                        @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
                                            <div class="px-4 py-3 text-xs hover:bg-emerald-50/50 dark:hover:bg-gray-800/60 {{ $notification->read_at ? 'opacity-70' : 'bg-emerald-50/20 dark:bg-gray-800/40 font-semibold' }}">
                                                <p class="text-emerald-900 dark:text-emerald-300 font-bold">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                                <p class="text-gray-600 dark:text-gray-300 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                                                <span class="text-[10px] text-gray-400 dark:text-gray-500 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                            </div>
                                        @empty
                                            <p class="px-4 py-6 text-xs text-gray-500 dark:text-gray-400 text-center">Belum ada notifikasi.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- User Info Badge -->
                            <div class="flex items-center space-x-2 pl-2 border-l border-gray-200 dark:border-gray-700">
                                <span class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 flex items-center justify-center font-bold text-xs uppercase border border-emerald-200 dark:border-emerald-700">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                                <span class="hidden sm:inline-block text-xs font-bold text-gray-700 dark:text-gray-200 max-w-[120px] truncate">
                                    {{ Auth::user()->name }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-950/80 border-l-4 border-emerald-600 text-emerald-900 dark:text-emerald-200 rounded-r-xl shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-700 dark:text-emerald-400 hover:text-emerald-900 font-bold text-lg">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 dark:bg-red-950/80 border-l-4 border-red-600 text-red-900 dark:text-red-200 rounded-r-xl shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-700 dark:text-red-400 hover:text-red-900 font-bold text-lg">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-800 py-5 mt-auto transition-colors duration-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center text-xs space-y-2 sm:space-y-0">
                    <div>
                        <p class="font-bold text-gray-700 dark:text-gray-200">Event-Osis &copy; {{ date('Y') }} &bull; OSIS SMK Pesat</p>
                    </div>
                    <div class="text-gray-400 dark:text-gray-500">
                        Sistem Pengelolaan Event &amp; Pendaftaran Peserta
                    </div>
                </div>
            </footer>

        </div>
    </div>

@else
    {{-- ==================== GUEST USER: CLEAN TOP NAVBAR (NO SIDEBAR) ==================== --}}
    <div class="min-h-screen flex flex-col">
        <!-- Top Navbar for Guests -->
        <header class="bg-emerald-700 dark:bg-gray-900 text-white shadow-md sticky top-0 z-50 border-b border-emerald-800 dark:border-gray-800 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Brand -->
                    <a href="{{ route('home') }}" class="flex items-center space-x-2.5 font-bold text-xl tracking-tight hover:text-emerald-200 transition">
                        <span class="p-2 bg-emerald-800 dark:bg-emerald-700 rounded-xl shadow-inner">
                            <svg class="w-6 h-6 text-emerald-300 dark:text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </span>
                        <div>
                            <span>Event-<span class="text-emerald-300">Osis</span></span>
                            <span class="text-[10px] text-emerald-200 font-medium block leading-none">SMK Pesat</span>
                        </div>
                    </a>

                    <!-- Guest Nav Links & Dark Mode Toggle -->
                    <nav class="flex items-center space-x-2 sm:space-x-3">
                        <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('home') ? 'bg-emerald-800 dark:bg-gray-800 text-white' : 'text-emerald-100 hover:bg-emerald-600 dark:hover:bg-gray-800' }}">
                            Beranda
                        </a>
                        <a href="{{ route('events.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('events.index', 'events.show') ? 'bg-emerald-800 dark:bg-gray-800 text-white' : 'text-emerald-100 hover:bg-emerald-600 dark:hover:bg-gray-800' }}">
                            Daftar Event
                        </a>

                        <!-- Dark Mode Toggle for Guests -->
                        <button @click="toggleTheme()" 
                                type="button" 
                                :title="darkMode ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap / Malam'"
                                class="p-2 rounded-xl text-emerald-100 hover:text-amber-300 hover:bg-emerald-600 dark:hover:bg-gray-800 focus:outline-none transition">
                            <template x-if="darkMode">
                                <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </template>
                            <template x-if="!darkMode">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                            </template>
                        </button>

                        <a href="{{ route('login') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 bg-emerald-500 hover:bg-emerald-400 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold text-sm rounded-xl shadow transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>Masuk</span>
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content Area for Guests -->
        <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-950/80 border-l-4 border-emerald-600 text-emerald-900 dark:text-emerald-200 rounded-r-xl shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-700 dark:text-emerald-400 hover:text-emerald-900 font-bold text-lg">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 dark:bg-red-950/80 border-l-4 border-red-600 text-red-900 dark:text-red-200 rounded-r-xl shadow-sm flex justify-between items-center" x-data="{ show: true }" x-show="show">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-700 dark:text-red-400 hover:text-red-900 font-bold text-lg">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer for Guests -->
        <footer class="bg-emerald-900 dark:bg-gray-900 text-emerald-200 dark:text-gray-400 border-t border-emerald-800 dark:border-gray-800 py-6 mt-12 transition-colors duration-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center text-sm space-y-4 md:space-y-0">
                <div>
                    <p class="font-bold text-emerald-100 dark:text-gray-200">Event-Osis &copy; {{ date('Y') }} &bull; OSIS SMK Pesat</p>
                    <p class="text-xs text-emerald-400 dark:text-gray-500 mt-0.5">Sistem Informasi Pengelolaan Event &amp; Pendaftaran OSIS</p>
                </div>
                <div class="text-right text-xs text-emerald-300 dark:text-gray-500">
                    <p class="mt-0.5">Sistem Pendaftaran Event Resmi Siswa</p>
                </div>
            </div>
        </footer>
    </div>
@endauth

</body>
</html>

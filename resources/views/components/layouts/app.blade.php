<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' — Bakso Console' : 'Bakso Console — Rent Smarter. Play Better.' }}</title>
    
    <!-- Theme Initializer (Prevent FOUC) -->
    <script>
        if (localStorage.theme === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Instrument Sans', sans-serif; }
    </style>
</head>
<body x-data="{ mobileMenuOpen: false }" class="min-h-screen bg-slate-50 dark:bg-[#0b0f19] text-slate-900 dark:text-slate-100 antialiased selection:bg-[#f95721] selection:text-white flex flex-col justify-between transition-colors duration-300">
    
    <div>
        <!-- Top Navbar -->
        <header class="sticky top-0 z-50 border-b border-slate-200 dark:border-white/10 bg-white/85 dark:bg-[#0b0f19]/85 backdrop-blur-xl transition-colors duration-300">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                
                <!-- Brand Logo (Left) -->
                <div class="flex items-center gap-6">
                    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="group flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#f95721] to-amber-600 shadow-md shadow-orange-500/25 transition-transform group-hover:scale-105">
                            <i class="fa-solid fa-gamepad text-white text-base"></i>
                        </div>
                        <div>
                            <div class="font-black text-lg tracking-tight text-slate-900 dark:text-white flex items-center gap-1">
                                Bakso <span class="text-[#f95721]">Console</span>
                            </div>
                        </div>
                    </a>

                    <!-- Katalog & SmartPick Capsule Dropdown (Center Left) -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 rounded-full border border-slate-200 dark:border-white/15 bg-slate-100 dark:bg-slate-900/90 px-4 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:border-orange-500/50 transition">
                            <i class="fa-solid fa-magnifying-glass text-[11px] text-[#f95721]"></i>
                            <span>Katalog & SmartPick</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <div x-show="open" x-transition.origin.top.left class="absolute left-0 mt-2 w-56 rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/95 p-2 shadow-2xl backdrop-blur-xl z-50" style="display: none;">
                            <a href="{{ route('catalogue') }}" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-orange-500/10 hover:text-[#f95721] transition">
                                <i class="fa-solid fa-wand-magic-sparkles text-[#f95721]"></i>
                                <span>Semua Unit & SmartPick</span>
                            </a>
                            <a href="{{ route('catalogue') }}?firmware_type=original" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-blue-500/10 hover:text-blue-600 dark:hover:text-blue-300 transition">
                                <i class="fa-solid fa-globe text-blue-500"></i>
                                <span>Konsol Original (Online)</span>
                            </a>
                            <a href="{{ route('catalogue') }}?firmware_type=jailbreak" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-purple-500/10 hover:text-purple-600 dark:hover:text-purple-300 transition">
                                <i class="fa-solid fa-hard-drive text-purple-500"></i>
                                <span>Konsol Jailbreak (Offline)</span>
                            </a>
                            <a href="{{ route('catalogue') }}?players=4" class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-emerald-500/10 hover:text-emerald-600 dark:hover:text-emerald-300 transition">
                                <i class="fa-solid fa-users text-emerald-500"></i>
                                <span>Mode Mabar (4 Stick)</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links for Member & Admin -->
                <nav class="hidden lg:flex items-center gap-1 text-xs font-medium text-slate-600 dark:text-slate-300">
                    @auth
                        @if((auth()->user()->role?->value ?? 'user') === 'user')
                            <a href="{{ route('bookings') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 transition hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white {{ request()->routeIs('bookings') ? 'bg-orange-500/15 text-[#f95721] font-bold' : '' }}">
                                <i class="fa-solid fa-calendar-days text-xs"></i>
                                <span>Booking</span>
                            </a>
                            <a href="{{ route('rentals') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 transition hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white {{ request()->routeIs('rentals') ? 'bg-orange-500/15 text-[#f95721] font-bold' : '' }}">
                                <i class="fa-solid fa-clock text-xs"></i>
                                <span>Rental Aktif</span>
                            </a>
                            <a href="{{ route('history') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 transition hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white {{ request()->routeIs('history') ? 'bg-orange-500/15 text-[#f95721] font-bold' : '' }}">
                                <i class="fa-solid fa-file-invoice text-xs"></i>
                                <span>Riwayat</span>
                            </a>
                            <a href="{{ route('leaderboard') }}" class="inline-flex items-center gap-2 rounded-lg px-3 py-1.5 transition hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white {{ request()->routeIs('leaderboard') ? 'bg-orange-500/15 text-[#f95721] font-bold' : '' }}">
                                <i class="fa-solid fa-trophy text-xs"></i>
                                <span>Leaderboard</span>
                            </a>
                        @endif

                        @if((auth()->user()->role?->value ?? '') === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-orange-500 to-amber-600 px-4 py-1.5 text-xs text-white font-bold shadow-md shadow-orange-500/20 hover:brightness-110 transition">
                                <i class="fa-solid fa-bolt text-xs"></i>
                                <span>Admin Operations Hub</span>
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Right Action Bar -->
                <div class="flex items-center gap-2">
                    
                    <!-- Theme Toggle Button -->
                    <button onclick="toggleTheme()" type="button" aria-label="Toggle Dark/Light Mode" class="group flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-900/80 text-slate-700 dark:text-slate-300 hover:border-orange-500 hover:text-[#f95721] transition shadow-sm">
                        <svg class="h-4 w-4 hidden dark:block text-amber-400 group-hover:rotate-45 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <svg class="h-4 w-4 block dark:hidden text-slate-700 group-hover:-rotate-12 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>

                    @auth
                        <div class="hidden lg:flex items-center gap-2">
                            <a href="{{ route('profile') }}" class="flex items-center gap-2 rounded-full border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-900/80 px-3 py-1 hover:border-orange-500/40 transition">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="h-6 w-6 rounded-full object-cover">
                                @else
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-orange-500/20 text-[10px] font-bold text-orange-600 dark:text-orange-400">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                    </div>
                                @endif
                                <span class="text-xs font-bold text-slate-900 dark:text-white max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" title="Keluar" class="flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-900/80 text-slate-500 hover:bg-red-500/10 hover:text-red-500 hover:border-red-500/40 transition">
                                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Mobile Hamburger for member nav -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-900/80 text-slate-600 dark:text-slate-300 hover:border-orange-500/40 transition">
                            <i class="fa-solid fa-bars text-sm"></i>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="rounded-full border border-slate-200 dark:border-white/20 bg-slate-100 dark:bg-slate-900/90 px-4 py-1.5 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-orange-500 hover:text-slate-900 dark:hover:text-white transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="hidden sm:inline-flex rounded-full bg-gradient-to-r from-[#f95721] to-amber-600 px-4 py-1.5 text-xs font-bold text-white shadow-md shadow-orange-500/20 hover:brightness-110 transition">
                            Premium Access
                        </a>
                    @endauth
                </div>

            </div>
        </header>

        <!-- Mobile Navigation Drawer (members only) -->
        @auth
            @if((auth()->user()->role?->value ?? 'user') === 'user')
                <div
                    x-show="mobileMenuOpen"
                    x-transition:enter="transition-all duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition-all duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="lg:hidden border-b border-slate-200 dark:border-white/10 bg-white/95 dark:bg-[#0b0f19]/95 backdrop-blur-xl px-4 py-3 space-y-1"
                    style="display: none;"
                >
                    <a href="{{ route('bookings') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('bookings') ? 'bg-orange-500/15 text-[#f95721] font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5' }}">
                        <i class="fa-solid fa-calendar-days text-sm w-5 text-center"></i><span>Booking Saya</span>
                    </a>
                    <a href="{{ route('rentals') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('rentals') ? 'bg-orange-500/15 text-[#f95721] font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5' }}">
                        <i class="fa-solid fa-clock text-sm w-5 text-center"></i><span>Rental Aktif</span>
                    </a>
                    <a href="{{ route('history') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('history') ? 'bg-orange-500/15 text-[#f95721] font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5' }}">
                        <i class="fa-solid fa-file-invoice text-sm w-5 text-center"></i><span>Riwayat Transaksi</span>
                    </a>
                    <a href="{{ route('leaderboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('leaderboard') ? 'bg-orange-500/15 text-[#f95721] font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5' }}">
                        <i class="fa-solid fa-trophy text-sm w-5 text-center"></i><span>Leaderboard</span>
                    </a>
                    <a href="{{ route('profile') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium transition text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5">
                        <i class="fa-solid fa-user text-sm w-5 text-center"></i><span>Profil Saya</span>
                    </a>
                    <div class="border-t border-slate-200 dark:border-white/10 pt-2 mt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-500/10 transition">
                                <i class="fa-solid fa-right-from-bracket w-5 text-center"></i><span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        @endauth

        <!-- Dynamic Floating Toast Alert Notifications -->
        <x-toast-container />

        <!-- Main Content Slot -->
        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>
    </div>

    <!-- Footer -->
    <footer class="mt-20 border-t border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-[#070a11] py-12 text-xs text-slate-600 dark:text-slate-400 transition-colors duration-300">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 md:grid-cols-3 items-start pb-8 border-b border-slate-200 dark:border-white/5">
                
                <!-- Left: Logo & Slogan -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-[#f95721] to-amber-600 shadow-md shadow-orange-500/25">
                            <i class="fa-solid fa-gamepad text-white text-base"></i>
                        </div>
                        <div class="font-black text-lg tracking-tight text-slate-900 dark:text-white">
                            Bakso <span class="text-[#f95721]">Console</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed max-w-xs">
                        Premium service for PlayStation console gaming rental at Bakso Console.
                    </p>
                </div>

                <!-- Center: Contact Info -->
                <div class="space-y-2">
                    <div class="font-bold text-slate-900 dark:text-white text-xs uppercase tracking-wider">Contact Info</div>
                    <div class="text-xs text-slate-600 dark:text-slate-400">bakso.console@gmail.com</div>
                    <div class="text-xs text-slate-600 dark:text-slate-400">info@baksoconsole.com</div>
                </div>

                <!-- Right: Contact Phone & Support -->
                <div class="space-y-2">
                    <div class="font-bold text-slate-900 dark:text-white text-xs uppercase tracking-wider">Contact Us</div>
                    <div class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-2">
                        <i class="fa-solid fa-phone text-[#f95721]"></i>
                        <span>+62 812 503 3085</span>
                    </div>
                    <div class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-2">
                        <i class="fa-regular fa-envelope text-[#f95721]"></i>
                        <span>support@baksoconsole.com</span>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-slate-500 dark:text-slate-500">
                <div>
                    Copyright &copy; {{ date('Y') }} Bakso Console. All rights reserved.
                </div>
                <div>
                    Gupron in da House &middot; BNSP Smart Console Rental System
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Utility Scripts (Theme, Modals & Toasts) -->
    <script>
        window.toggleTheme = function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        };

        window.openModal = function(name) {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: name }));
        };

        window.closeModal = function(name) {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: name }));
        };

        window.showToast = function(detail) {
            window.dispatchEvent(new CustomEvent('toast', { detail: detail }));
        };
    </script>
</body>
</html>

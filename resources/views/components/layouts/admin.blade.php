<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' — Bakso Console Admin' : 'Admin Operations Hub — Bakso Console' }}</title>
    
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
<body x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased selection:bg-orange-500 selection:text-white transition-colors duration-300">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm md:hidden"
        style="display: none;"
    ></div>

    <div class="flex min-h-screen">
        <!-- Sidebar Navigation -->
        <aside
            id="admin-sidebar"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform border-r border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/98 backdrop-blur-xl transition-transform duration-200 ease-in-out md:translate-x-0 md:static md:z-auto flex flex-col justify-between flex-shrink-0"
        >
            <!-- Sidebar Top / Brand & Navigation -->
            <div class="flex flex-col h-full overflow-y-auto p-4 space-y-5">
                <!-- Brand -->
                <div class="flex items-center justify-between px-2 py-1">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 shadow-lg shadow-orange-500/25">
                            <i class="fa-solid fa-bolt text-white text-lg"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 font-black text-base text-slate-900 dark:text-white">
                                Bakso <span class="text-orange-500">Console</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Admin Hub
                            </div>
                        </div>
                    </div>
                    <!-- Close button (mobile only) -->
                    <button @click="sidebarOpen = false" class="md:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-white/5">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <!-- Navigation Sections -->
                <nav class="space-y-1 text-sm font-medium flex-1">
                    <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Menu Utama
                    </div>

                    @php
                        $navLinks = [
                            ['route' => 'admin.dashboard',  'icon' => 'fa-chart-pie',     'label' => 'Dashboard & Insight'],
                            ['route' => 'admin.units',      'icon' => 'fa-gamepad',       'label' => 'Kelola Unit Konsol'],
                            ['route' => 'admin.categories', 'icon' => 'fa-tags',          'label' => 'Kategori & Combo'],
                            ['route' => 'admin.members',    'icon' => 'fa-users',         'label' => 'Manajemen Anggota'],
                        ];
                    @endphp

                    @foreach($navLinks as $nav)
                        <a href="{{ route($nav['route']) }}"
                           @click="sidebarOpen = false"
                           class="group flex items-center justify-between rounded-xl px-3 py-2.5 transition {{ request()->routeIs($nav['route']) ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid {{ $nav['icon'] }} text-sm w-5 text-center"></i>
                                <span class="text-sm">{{ $nav['label'] }}</span>
                            </div>
                            @if(request()->routeIs($nav['route']))
                                <i class="fa-solid fa-chevron-right text-xs opacity-75"></i>
                            @endif
                        </a>
                    @endforeach

                    <div class="pt-3 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Operasional & Transaksi
                    </div>

                    @php
                        $opLinks = [
                            ['route' => 'admin.bookings',   'icon' => 'fa-handshake',     'label' => 'Antrean Serah Terima'],
                            ['route' => 'admin.returns',    'icon' => 'fa-rotate-left',   'label' => 'Pengembalian & Denda'],
                            ['route' => 'admin.deliveries', 'icon' => 'fa-truck-fast',    'label' => 'Pickup & Delivery'],
                            ['route' => 'admin.history',    'icon' => 'fa-file-invoice',  'label' => 'Laporan & Riwayat'],
                            ['route' => 'admin.leaderboard','icon' => 'fa-trophy',        'label' => 'Leaderboard Member'],
                        ];
                    @endphp

                    @foreach($opLinks as $nav)
                        <a href="{{ route($nav['route']) }}"
                           @click="sidebarOpen = false"
                           class="group flex items-center justify-between rounded-xl px-3 py-2.5 transition {{ request()->routeIs($nav['route']) ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid {{ $nav['icon'] }} text-sm w-5 text-center"></i>
                                <span class="text-sm">{{ $nav['label'] }}</span>
                            </div>
                            @if(request()->routeIs($nav['route']))
                                <i class="fa-solid fa-chevron-right text-xs opacity-75"></i>
                            @endif
                        </a>
                    @endforeach

                    <div class="pt-3 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        Sistem & Pemantauan
                    </div>

                    <a href="{{ route('admin.resources') }}"
                       @click="sidebarOpen = false"
                       class="group flex items-center justify-between rounded-xl px-3 py-2.5 transition {{ request()->routeIs('admin.resources') ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-microchip text-sm w-5 text-center"></i>
                            <span class="text-sm">Resource Monitor</span>
                        </div>
                        @if(request()->routeIs('admin.resources'))
                            <i class="fa-solid fa-chevron-right text-xs opacity-75"></i>
                        @else
                            <span class="flex h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        @endif
                    </a>
                </nav>

                <!-- Bottom Section: Admin User & Links -->
                <div class="pt-4 border-t border-slate-200 dark:border-white/10 space-y-2.5">
                    <a href="{{ route('catalogue') }}" class="flex items-center gap-2.5 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950/60 px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-300 transition hover:bg-slate-100 dark:hover:bg-white/5 hover:text-slate-900 dark:hover:text-white">
                        <i class="fa-solid fa-globe text-xs text-orange-500"></i>
                        <span>Lihat Portal Publik</span>
                    </a>

                    <div class="flex items-center justify-between rounded-xl bg-slate-50 dark:bg-slate-950 p-3 border border-slate-200 dark:border-white/10">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-orange-500/20 text-orange-600 dark:text-orange-400 font-bold text-xs border border-orange-500/30">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 capitalize">{{ auth()->user()->role->value }}</div>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                            @csrf
                            <button type="submit" title="Keluar" class="rounded-lg p-1.5 text-slate-500 hover:bg-red-500/10 hover:text-red-500 transition cursor-pointer">
                                <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-x-hidden">

            <!-- Top Action Bar -->
            <header class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-200 dark:border-white/10 bg-white/90 dark:bg-slate-950/90 px-4 md:px-6 py-3.5 backdrop-blur-md transition-colors duration-300 gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Mobile Hamburger -->
                    <button @click="sidebarOpen = true" class="md:hidden rounded-lg border border-slate-200 dark:border-white/10 p-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 shrink-0">
                        <i class="fa-solid fa-bars text-sm"></i>
                    </button>

                    <!-- Brand for mobile -->
                    <div class="md:hidden flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-orange-500 to-amber-600">
                            <i class="fa-solid fa-gamepad text-white text-xs"></i>
                        </div>
                        <span class="font-black text-sm text-slate-900 dark:text-white">Bakso <span class="text-orange-500">Console</span></span>
                    </div>

                    <!-- Page Title — Desktop -->
                    <div class="hidden md:block min-w-0">
                        @if(isset($header) && $header->isNotEmpty())
                            <div class="flex items-center gap-2">
                                {!! $header !!}
                            </div>
                        @else
                            <h1 class="text-base font-black text-slate-900 dark:text-white truncate">{{ $title ?? 'Admin Operations Hub' }}</h1>
                        @endif
                        @if(isset($subtitle))
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <!-- Theme Switcher Button -->
                    <button onclick="toggleTheme()" type="button" aria-label="Toggle Dark/Light Mode" title="Ganti Tema" class="group flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:border-orange-500 hover:text-orange-500 transition shadow-sm">
                        <!-- Sun Icon for Dark Mode -->
                        <svg class="h-3.5 w-3.5 hidden dark:block text-amber-400 group-hover:rotate-45 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <!-- Moon Icon for Light Mode -->
                        <svg class="h-3.5 w-3.5 block dark:hidden text-slate-700 group-hover:-rotate-12 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>

                    <a href="{{ route('admin.history.print') }}" target="_blank" class="hidden sm:inline-flex items-center gap-1.5 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-900 px-3 py-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/5 transition">
                        <i class="fa-solid fa-print text-xs"></i>
                        <span>Cetak</span>
                    </a>

                    <div class="flex items-center gap-1.5 rounded-xl bg-orange-500/10 border border-orange-500/30 px-2.5 py-1.5 text-xs font-bold text-orange-600 dark:text-orange-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                        <span class="hidden sm:inline">System Active</span>
                        <span class="sm:hidden">Live</span>
                    </div>
                </div>
            </header>

            <!-- Mobile Page Title (shown below header on mobile) -->
            @if(isset($header) && $header->isNotEmpty() || isset($title))
                <div class="md:hidden px-4 pt-4 pb-2">
                    @if(isset($header) && $header->isNotEmpty())
                        <div class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                            {!! $header !!}
                        </div>
                    @else
                        <h1 class="text-base font-black text-slate-900 dark:text-white">{{ $title ?? '' }}</h1>
                    @endif
                    @if(isset($subtitle))
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif

            <!-- Dynamic Floating Toast Alert Notifications -->
            <x-toast-container />

            <!-- Content Slot -->
            <main class="flex-1 px-4 md:px-6 pt-5 pb-12">
                {{ $slot }}
            </main>
        </div>
    </div>

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

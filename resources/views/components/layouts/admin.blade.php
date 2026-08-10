<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' — Bakso Console Admin' : 'Admin Operations Hub — Bakso Console' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased selection:bg-orange-500 selection:text-white flex flex-col md:flex-row">

    <!-- Mobile Header & Toggle -->
    <div class="md:hidden sticky top-0 z-50 flex items-center justify-between border-b border-white/10 bg-slate-950/95 px-4 py-3 backdrop-blur-md">
        <div class="flex items-center gap-2.5">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 shadow-md shadow-orange-500/20">
                <i class="fa-solid fa-gamepad text-white text-base"></i>
            </div>
            <div>
                <span class="font-black text-white text-base">Bakso <span class="text-orange-400">Console</span></span>
                <span class="ml-1.5 rounded-md bg-orange-500/20 px-1.5 py-0.5 text-[10px] font-bold text-orange-400 border border-orange-500/30">ADMIN</span>
            </div>
        </div>
        <button onclick="document.getElementById('admin-sidebar').classList.toggle('-translate-x-full')" class="rounded-lg border border-white/10 p-2 text-slate-300 hover:bg-white/5">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full transform border-r border-white/10 bg-slate-900/95 backdrop-blur-xl transition-transform duration-200 ease-in-out md:translate-x-0 md:static flex flex-col justify-between">
        
        <!-- Sidebar Top / Brand & Navigation -->
        <div class="flex flex-col h-full overflow-y-auto p-4 space-y-6">
            <!-- Brand -->
            <div class="flex items-center gap-3 px-2 py-1">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 shadow-lg shadow-orange-500/25">
                    <i class="fa-solid fa-bolt text-white text-xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-1.5 font-black text-lg text-white">
                        Bakso <span class="text-orange-400">Console</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Admin Operations Hub
                    </div>
                </div>
            </div>

            <!-- Navigation Sections -->
            <nav class="space-y-1 text-sm font-medium">
                <div class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Menu Utama
                </div>

                <!-- 1. Dashboard & Analytics -->
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 transition {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-chart-pie text-base w-5 text-center"></i>
                        <span>Dashboard & Insight</span>
                    </div>
                    @if(request()->routeIs('admin.dashboard'))
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    @endif
                </a>

                <!-- 2. Units Management -->
                <a href="{{ route('admin.units') }}" class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 transition {{ request()->routeIs('admin.units') ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-gamepad text-base w-5 text-center"></i>
                        <span>Kelola Unit Konsol</span>
                    </div>
                    @if(request()->routeIs('admin.units'))
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    @endif
                </a>

                <!-- 3. Categories & Combos -->
                <a href="{{ route('admin.categories') }}" class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 transition {{ request()->routeIs('admin.categories') ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-tags text-base w-5 text-center"></i>
                        <span>Kategori & Combo</span>
                    </div>
                    @if(request()->routeIs('admin.categories'))
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    @endif
                </a>

                <!-- 4. Member Management -->
                <a href="{{ route('admin.members') }}" class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 transition {{ request()->routeIs('admin.members') ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-users text-base w-5 text-center"></i>
                        <span>Manajemen Anggota</span>
                    </div>
                    @if(request()->routeIs('admin.members'))
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    @endif
                </a>

                <div class="pt-4 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    Operasional & Transaksi
                </div>

                <!-- 5. Bookings & Active Rentals -->
                <a href="{{ route('admin.bookings') }}" class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 transition {{ request()->routeIs('admin.bookings') ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-calendar-check text-base w-5 text-center"></i>
                        <span>Reservasi & Booking</span>
                    </div>
                    @if(request()->routeIs('admin.bookings'))
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    @endif
                </a>

                <!-- 6. Returns & Fines -->
                <a href="{{ route('admin.returns') }}" class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 transition {{ request()->routeIs('admin.returns') ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-rotate-left text-base w-5 text-center"></i>
                        <span>Pengembalian & Denda</span>
                    </div>
                    @if(request()->routeIs('admin.returns'))
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    @endif
                </a>

                <!-- 7. Pickup & Delivery Service -->
                <a href="{{ route('admin.deliveries') }}" class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 transition {{ request()->routeIs('admin.deliveries') ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-truck-fast text-base w-5 text-center"></i>
                        <span>Pickup & Delivery</span>
                    </div>
                    @if(request()->routeIs('admin.deliveries'))
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    @endif
                </a>

                <!-- 8. Reports & Print -->
                <a href="{{ route('admin.history') }}" class="group flex items-center justify-between rounded-xl px-3.5 py-2.5 transition {{ request()->routeIs('admin.history') ? 'bg-orange-500 text-white font-semibold shadow-lg shadow-orange-500/25' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-file-invoice text-base w-5 text-center"></i>
                        <span>Laporan & Riwayat</span>
                    </div>
                    @if(request()->routeIs('admin.history'))
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    @endif
                </a>
            </nav>

            <!-- Bottom Section: Admin User & Links -->
            <div class="pt-6 border-t border-white/10 space-y-3">
                <a href="{{ route('catalogue') }}" class="flex items-center gap-2.5 rounded-xl border border-white/10 bg-slate-950/60 px-3.5 py-2.5 text-xs font-semibold text-slate-300 transition hover:bg-white/5 hover:text-white">
                    <i class="fa-solid fa-globe text-xs"></i>
                    <span>Lihat Portal Publik</span>
                </a>

                <div class="flex items-center justify-between rounded-xl bg-slate-950 p-3 border border-white/10">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-500/20 text-orange-400 font-bold text-xs border border-orange-500/30">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        <div class="truncate">
                            <div class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</div>
                            <div class="text-[10px] text-slate-400 capitalize">Role: {{ auth()->user()->role->value }}</div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar" class="rounded-lg p-1.5 text-slate-400 hover:bg-red-500/20 hover:text-red-400 transition">
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
        <header class="sticky top-0 z-30 hidden md:flex items-center justify-between border-b border-white/10 bg-slate-950/80 px-8 py-4 backdrop-blur-md">
            <div>
                <h1 class="text-xl font-black text-white">{{ $header ?? $title ?? 'Admin Operations Hub' }}</h1>
                <p class="text-xs text-slate-400">{{ $subtitle ?? 'Kelola inventaris, reservasi, dan performa bisnis Bakso Console.' }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.history.print') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-slate-900 px-3.5 py-2 text-xs font-bold text-slate-300 hover:bg-white/5 hover:text-white transition">
                    <i class="fa-solid fa-print text-xs"></i>
                    <span>Cetak Laporan</span>
                </a>

                <div class="flex items-center gap-2 rounded-xl bg-orange-500/10 border border-orange-500/30 px-3 py-1.5 text-xs font-bold text-orange-400">
                    <i class="fa-solid fa-bolt text-xs"></i>
                    <span>System Active</span>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 md:px-8 pt-6">
            @if(session('success'))
                <div class="flex items-center justify-between rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-300 shadow-lg backdrop-blur-md mb-6">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-xl"></i>
                        <div>
                            <div class="font-bold">Aksi Berhasil!</div>
                            <div class="text-xs text-emerald-400/90">{{ session('success') }}</div>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white">✕</button>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div class="rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-300 shadow-lg backdrop-blur-md mb-6">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-red-400 text-xl"></i>
                        <div>
                            <div class="font-bold">Terjadi Kesalahan!</div>
                            <div class="text-xs text-red-400/90">
                                {{ session('error') ?? $errors->first() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="flex-1 px-4 md:px-8 pb-16">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="border-t border-white/10 bg-slate-900/50 py-4 px-8 text-xs text-slate-500 flex flex-col md:flex-row items-center justify-between gap-2">
            <div>
                <span class="font-bold text-slate-400">Bakso Console</span> &copy; 2026 &mdash; BNSP Smart Console Rental Certification
            </div>
            <div>
                Gupron in da House &middot; Powered by Laravel 12 & Tailwind CSS
            </div>
        </footer>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' — Bakso Console' : 'Bakso Console — Smart Console Rental' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased selection:bg-orange-500 selection:text-white">
    <!-- Top Navbar -->
    <header class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-md">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
            <!-- Brand Logo -->
            <div class="flex items-center gap-3">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="group flex items-center gap-2.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-amber-600 shadow-lg shadow-orange-500/20 transition-transform group-hover:scale-105">
                        <i class="fa-solid fa-gamepad text-white text-lg"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 font-black text-lg tracking-tight text-white">
                            Bakso <span class="text-orange-400">Console</span>
                        </div>
                        <div class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Rent Smarter. Play Better.</div>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-1 text-sm font-medium text-slate-300">
                <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 transition hover:bg-white/5 hover:text-white {{ request()->routeIs('catalogue') ? 'bg-orange-500/10 text-orange-400 font-semibold' : '' }}">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    <span>Katalog & SmartPick</span>
                </a>
                @auth
                    @if((auth()->user()->role?->value ?? 'user') === 'user')
                        <a href="{{ route('bookings') }}" class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 transition hover:bg-white/5 hover:text-white {{ request()->routeIs('bookings') ? 'bg-orange-500/10 text-orange-400 font-semibold' : '' }}">
                            <i class="fa-solid fa-calendar-days text-xs"></i>
                            <span>Booking Saya</span>
                        </a>
                        <a href="{{ route('rentals') }}" class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 transition hover:bg-white/5 hover:text-white {{ request()->routeIs('rentals') ? 'bg-orange-500/10 text-orange-400 font-semibold' : '' }}">
                            <i class="fa-solid fa-clock text-xs"></i>
                            <span>Rental Aktif</span>
                        </a>
                        <a href="{{ route('history') }}" class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 transition hover:bg-white/5 hover:text-white {{ request()->routeIs('history') ? 'bg-orange-500/10 text-orange-400 font-semibold' : '' }}">
                            <i class="fa-solid fa-file-invoice text-xs"></i>
                            <span>Riwayat & Rank</span>
                        </a>
                        <a href="{{ route('leaderboard') }}" class="inline-flex items-center gap-2 rounded-lg px-3.5 py-2 transition hover:bg-white/5 hover:text-white {{ request()->routeIs('leaderboard') ? 'bg-orange-500/10 text-orange-400 font-semibold' : '' }}">
                            <i class="fa-solid fa-trophy text-xs"></i>
                            <span>Leaderboard</span>
                        </a>
                    @endif

                    @if((auth()->user()->role?->value ?? '') === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-orange-500/20 px-3.5 py-2 text-orange-400 font-semibold border border-orange-500/30 transition hover:bg-orange-500 hover:text-white">
                            <i class="fa-solid fa-bolt text-xs"></i>
                            <span>Admin Operations Hub</span>
                        </a>
                    @endif
                @endauth
            </nav>

            <!-- User Area / Auth CTA -->
            <div class="flex items-center gap-3">
                @auth
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile') }}" class="flex items-center gap-2 rounded-xl bg-white/5 px-3 py-1.5 border border-white/10 hover:border-orange-500/40 transition">
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="h-7 w-7 rounded-lg object-cover">
                            @else
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-orange-500/20 text-xs font-bold text-orange-400">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <div class="hidden text-left sm:block">
                                <div class="text-xs font-bold text-white leading-none">{{ auth()->user()->name }}</div>
                                <div class="text-[10px] text-slate-400 capitalize">{{ auth()->user()->role?->value ?? 'Member' }}</div>
                            </div>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Keluar" class="rounded-xl border border-white/10 bg-white/5 p-2 text-slate-400 hover:bg-red-500/10 hover:text-red-400 hover:border-red-500/30 transition">
                                <i class="fa-solid fa-right-from-bracket text-xs"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-300 hover:text-white transition">Masuk</a>
                        <a href="{{ route('register') }}" class="rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 px-4 py-2 text-sm font-bold text-white shadow-md shadow-orange-500/20 hover:brightness-110 transition">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="flex items-center justify-between rounded-2xl border border-emerald-500/30 bg-emerald-950/40 p-4 text-emerald-300 backdrop-blur-md shadow-lg shadow-emerald-950/50">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-lg"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mt-3 rounded-2xl border border-red-500/30 bg-red-950/40 p-4 text-red-300 backdrop-blur-md shadow-lg shadow-red-950/50">
                <div class="flex items-center gap-2 font-bold text-sm">
                    <i class="fa-solid fa-triangle-exclamation text-red-400"></i>
                    <span>Terdapat kendala pada permintaan Anda:</span>
                </div>
                <ul class="mt-2 list-inside list-disc text-xs space-y-1 text-red-200">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="mt-16 border-t border-white/10 bg-slate-900/50 py-8 text-center text-xs text-slate-500">
        <div class="mx-auto max-w-7xl px-4">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="flex items-center gap-2 font-bold text-slate-300">
                    <i class="fa-solid fa-gamepad text-orange-400"></i>
                    <span>Bakso Console</span> — <span class="font-normal text-slate-400">Rent Smarter. Play Better.</span>
                </div>
                <div>
                    Gupron in da House &copy; {{ date('Y') }} — BNSP Smart Console Rental & Management System
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

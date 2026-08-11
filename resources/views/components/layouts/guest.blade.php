<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' — Bakso Console' : 'Bakso Console — Smart Console Rental' }}</title>
    
    <!-- Theme Initializer (Prevent FOUC) -->
    <script>
        if (localStorage.theme === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 flex flex-col justify-center items-center px-4 py-12 selection:bg-[#f95721] selection:text-white relative overflow-x-hidden transition-colors duration-300">
    
    <!-- Top Right Theme Toggle (Crisp Vector SVG Icons) -->
    <div class="absolute top-6 right-6 z-20">
        <button onclick="toggleTheme()" type="button" aria-label="Toggle Dark/Light Mode" title="Ganti Tema (Light / Dark Mode)" class="group flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:border-orange-500 hover:text-orange-500 shadow-md transition">
            <!-- Sun Icon for Dark Mode -->
            <svg class="h-4 w-4 hidden dark:block text-amber-400 group-hover:rotate-45 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
            </svg>
            <!-- Moon Icon for Light Mode -->
            <svg class="h-4 w-4 block dark:hidden text-slate-700 group-hover:-rotate-12 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
            </svg>
        </button>
    </div>

    <!-- Background Glow Accents -->
    <div class="absolute -top-40 -left-40 h-96 w-96 rounded-full bg-orange-500/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 h-96 w-96 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>

    <main class="w-full max-w-md relative z-10">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2.5 group">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-[#f95721] to-amber-600 shadow-xl shadow-orange-500/25 transition-transform group-hover:scale-105">
                    <i class="fa-solid fa-gamepad text-white text-xl"></i>
                </div>
                <div class="text-left">
                    <div class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Bakso <span class="text-[#f95721]">Console</span></div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rent Smarter. Play Better.</div>
                </div>
            </a>
        </div>

        <!-- Auth Card -->
        <section class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-8 shadow-xl dark:shadow-2xl backdrop-blur-xl transition-colors duration-300">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-500/30 bg-red-50 dark:bg-red-950/40 p-4 text-sm text-red-800 dark:text-red-300">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <i class="fa-solid fa-triangle-exclamation text-red-500 dark:text-red-400"></i>
                        <span>Mohon periksa kembali:</span>
                    </div>
                    <ul class="list-inside list-disc text-xs text-red-700 dark:text-red-200 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-50 dark:bg-emerald-950/40 p-4 text-sm text-emerald-800 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </section>

        <!-- Back to Home -->
        <div class="mt-6 text-center text-xs text-slate-500">
            <a href="/" class="hover:text-slate-900 dark:hover:text-slate-300 transition">&larr; Kembali ke Beranda Bakso Console</a>
        </div>
    </main>

    <!-- Global Theme Switcher Script -->
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
    </script>
</body>
</html>

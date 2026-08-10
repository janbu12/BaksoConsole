<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title . ' — Bakso Console' : 'Bakso Console — Smart Console Rental' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 flex flex-col justify-center items-center px-4 py-12 selection:bg-orange-500 selection:text-white relative overflow-x-hidden">
    <!-- Background Glow Accents -->
    <div class="absolute -top-40 -left-40 h-96 w-96 rounded-full bg-orange-500/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 h-96 w-96 rounded-full bg-amber-500/10 blur-3xl pointer-events-none"></div>

    <main class="w-full max-w-md relative z-10">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2.5 group">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 shadow-xl shadow-orange-500/25 transition-transform group-hover:scale-105">
                    <i class="fa-solid fa-gamepad text-white text-xl"></i>
                </div>
                <div class="text-left">
                    <div class="text-2xl font-black tracking-tight text-white">Bakso <span class="text-orange-400">Console</span></div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Rent Smarter. Play Better.</div>
                </div>
            </a>
        </div>

        <!-- Auth Card -->
        <section class="rounded-3xl border border-white/10 bg-slate-900/90 p-8 shadow-2xl backdrop-blur-xl">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-500/30 bg-red-950/40 p-4 text-sm text-red-300">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <i class="fa-solid fa-triangle-exclamation text-red-400"></i>
                        <span>Mohon periksa kembali:</span>
                    </div>
                    <ul class="list-inside list-disc text-xs text-red-200 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-950/40 p-4 text-sm text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </section>

        <!-- Back to Home -->
        <div class="mt-6 text-center text-xs text-slate-500">
            <a href="/" class="hover:text-slate-300 transition">&larr; Kembali ke Beranda Bakso Console</a>
        </div>
    </main>
</body>
</html>

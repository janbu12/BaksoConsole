<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Bakso Console' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <main class="mx-auto flex min-h-screen max-w-md items-center px-6 py-12">
        <section class="w-full rounded-3xl border border-white/10 bg-slate-900 p-8 shadow-2xl">
            <a href="/" class="mb-8 block text-2xl font-black text-orange-400">🍜 Bakso Console</a>
            @if ($errors->any())<div class="mb-4 rounded-xl bg-red-500/10 p-4 text-sm text-red-300">{{ $errors->first() }}</div>@endif
            {{ $slot }}
        </section>
    </main>
</body>
</html>

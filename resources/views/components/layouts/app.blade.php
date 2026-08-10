<!DOCTYPE html>
<html lang="id" class="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $title ?? 'Bakso Console' }}</title>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
<nav class="border-b border-white/10 bg-slate-900/90"><div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4"><a href="/dashboard" class="font-black text-orange-400">🍜 Bakso Console</a><div class="flex items-center gap-4 text-sm"><a href="/catalogue">Console</a><a href="/bookings">Booking</a><a href="/rentals">Rental</a><form method="POST" action="/logout">@csrf<button class="text-red-300">Keluar</button></form></div></div></nav>
@if(session('success'))<div class="mx-auto mt-4 max-w-7xl rounded-xl bg-emerald-500/10 p-4 text-emerald-300">{{ session('success') }}</div>@endif
<main class="mx-auto max-w-7xl px-5 py-8">{{ $slot }}</main>
</body></html>

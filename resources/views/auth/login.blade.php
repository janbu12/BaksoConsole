<x-layouts.guest title="Masuk">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">Selamat Datang Kembali!</h1>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Masuk untuk melanjutkan sewa konsol impianmu.</p>
    </div>

    <form method="POST" action="/login" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email</label>
            <input name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none transition" required autofocus>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Kata Sandi</label>
            <input name="password" type="password" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none transition" required>
        </div>

        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-[#f95721] to-amber-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-orange-500/25 hover:brightness-110 active:scale-[0.99] transition">
            Masuk ke Akun
        </button>
    </form>

    <div class="mt-6 border-t border-slate-200 dark:border-white/10 pt-6 text-center text-sm text-slate-600 dark:text-slate-400">
        Belum memiliki akun? 
        <a href="/register" class="font-bold text-[#f95721] hover:underline transition">Daftar Sekarang</a>
    </div>

    <!-- Quick Demo Logins Note -->
    <div class="mt-6 rounded-2xl bg-slate-50 dark:bg-white/5 p-3.5 text-xs text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-white/5">
        <div class="font-bold text-slate-800 dark:text-slate-300 mb-1">Akun Pengujian Demo:</div>
        <div>Admin: <span class="font-mono text-[#f95721]">admin@baksoconsole.test</span> / <span class="font-mono text-slate-700 dark:text-slate-300">password</span></div>
        <div>Member: <span class="font-mono text-[#f95721]">budi@example.com</span> / <span class="font-mono text-slate-700 dark:text-slate-300">password</span></div>
    </div>
</x-layouts.guest>

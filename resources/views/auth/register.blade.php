<x-layouts.guest title="Daftar Anggota">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">Buat Akun Member</h1>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Dapatkan akses sewa konsol dan tingkatkan Bakso Rank-mu!</p>
    </div>

    <form method="POST" action="/register" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap</label>
            <input name="name" type="text" value="{{ old('name') }}" placeholder="Contoh: Nable Gamer" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none transition" required autofocus>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email</label>
            <input name="email" type="email" value="{{ old('email') }}" placeholder="nama@email.com" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none transition" required>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Nomor Telepon / WhatsApp</label>
            <input name="phone" type="text" value="{{ old('phone') }}" placeholder="081234567890" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none transition">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Alamat Domisili</label>
            <input name="address" type="text" value="{{ old('address') }}" placeholder="Jl. Sudirman No. 12, Jakarta" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none transition">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Kata Sandi</label>
            <input name="password" type="password" placeholder="Minimal 8 karakter" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none transition" required>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Konfirmasi Kata Sandi</label>
            <input name="password_confirmation" type="password" placeholder="Ulangi kata sandi" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none transition" required>
        </div>

        <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-[#f95721] to-amber-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-orange-500/25 hover:brightness-110 active:scale-[0.99] transition">
            Daftar & Mulai Sewa
        </button>
    </form>

    <div class="mt-6 border-t border-slate-200 dark:border-white/10 pt-6 text-center text-sm text-slate-600 dark:text-slate-400">
        Sudah memiliki akun? 
        <a href="/login" class="font-bold text-[#f95721] hover:underline transition">Masuk Disini</a>
    </div>
</x-layouts.guest>

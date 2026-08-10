<x-layouts.app title="Profil Saya">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="text-xs font-bold uppercase tracking-wider text-orange-400">Pengaturan Akun</div>
            <h1 class="text-3xl font-black text-white mt-1">Profil Member</h1>
            <p class="text-sm text-slate-400 mt-0.5">Kelola identitas akun dan alamat pengantaran konsol Anda.</p>
        </div>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-3">
        <!-- User Badge & Rank Card (Left) -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 flex flex-col justify-between shadow-xl text-center">
            <div>
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-orange-500 to-amber-600 text-3xl font-black text-white shadow-xl shadow-orange-500/25">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <h2 class="text-xl font-bold text-white mt-4">{{ $user->name }}</h2>
                <p class="text-xs text-slate-400">{{ $user->email }}</p>

                <!-- Rank Badge -->
                <div class="mt-6 rounded-2xl bg-slate-950 p-4 border border-white/5 text-left">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Bakso Rank</span>
                        <span class="text-xl">{{ substr($rank['badge'], 0, 4) }}</span>
                    </div>
                    <div class="text-lg font-black text-white mt-1">{{ $rank['name'] }}</div>
                    <div class="text-xs text-orange-400 mt-0.5">{{ $totalDays }} Total Hari Sewa Kumulatif</div>
                    <p class="text-[11px] text-slate-400 mt-2">{{ $rank['benefit'] }}</p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-white/10 text-xs text-slate-500">
                Terdaftar sejak {{ $user->created_at->format('d M Y') }}
            </div>
        </div>

        <!-- Edit Profile Form (Right) -->
        <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-slate-900/90 p-6 sm:p-8 shadow-xl">
            <h2 class="text-lg font-bold text-white mb-6">Ubah Informasi Pribadi</h2>

            <form method="POST" action="/profile" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Lengkap</label>
                    <input name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-sm text-white focus:border-orange-500 focus:outline-none transition" required>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nomor Telepon / WhatsApp</label>
                        <input name="phone" value="{{ old('phone', $user->profile?->phone) }}" placeholder="081234567890" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->profile?->date_of_birth?->format('Y-m-d')) }}" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-sm text-white focus:border-orange-500 focus:outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Alamat Lengkap Pengantaran (Domisili)</label>
                    <textarea name="address" rows="3" placeholder="Alamat rumah lengkap untuk keperluan delivery konsol" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none transition">{{ old('address', $user->profile?->address) }}</textarea>
                </div>

                <div class="pt-4 border-t border-white/10 flex justify-end">
                    <button type="submit" class="rounded-xl bg-orange-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-orange-500/25 hover:bg-orange-600 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>

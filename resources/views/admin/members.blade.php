<x-layouts.admin title="Manajemen Anggota">
    <x-slot:header><i class="fa-solid fa-users"></i> Manajemen Anggota & Loyalitas Pelanggan</x-slot:header>
    <x-slot:subtitle>Kelola data profil pelanggan, pantau total hari sewa, dan status tingkatan loyalitas Bakso Rank.</x-slot:subtitle>

    <div class="grid gap-8 lg:grid-cols-3">
        <!-- Form Tambah Anggota Baru -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
                <h2 class="text-base font-bold text-white mb-1 flex items-center gap-2">
                    <span><i class="fa-solid fa-user-plus"></i></span> Tambah Anggota Baru
                </h2>
                <p class="text-xs text-slate-400 mb-4">Daftarkan akun member baru ke dalam sistem.</p>

                <form method="POST" action="/admin/members" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Lengkap</label>
                        <input name="name" type="text" placeholder="Budi Santoso" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Alamat Email</label>
                        <input name="email" type="email" placeholder="budi@example.com" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Password</label>
                            <input name="password" type="password" placeholder="••••••••" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Konfirmasi</label>
                            <input name="password_confirmation" type="password" placeholder="••••••••" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nomor WhatsApp / HP</label>
                        <input name="phone" type="text" placeholder="08123456789" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Alamat Pengiriman</label>
                        <input name="address" type="text" placeholder="Jl. Sudirman No. 45, Jakarta" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Tanggal Lahir</label>
                        <input name="date_of_birth" type="date" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none">
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-orange-600 py-3 text-xs font-bold text-white shadow-lg shadow-orange-600/25 hover:bg-orange-500 transition">
                        Daftarkan Anggota
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel & Kartu Daftar Anggota -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-white">Daftar Anggota Terdaftar ({{ $members->count() }} Anggota)</h2>
                    <p class="text-xs text-slate-400">Total hari sewa dihitung otomatis untuk menentukan tingkatan loyalitas pelanggan.</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                @forelse($members as $member)
                    @php
                        $totalDays = (int) $member->rentals->where('status', \App\Enums\RentalStatus::Returned)->sum('duration_days');
                        $rank = \App\Domain\Loyalty\BaksoRank::fromDays($totalDays);
                    @endphp
                    <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 shadow-xl transition hover:border-orange-500/30 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="font-bold text-white text-sm">{{ $member->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $member->email }}</div>
                                    <div class="text-xs text-orange-400/90 font-mono mt-0.5"><i class="fa-solid fa-phone"></i> {{ $member->profile?->phone ?? 'Belum ada nomor' }}</div>
                                </div>
                                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $rank['badge_class'] }}">
                                    {{ $rank['name'] }}
                                </span>
                            </div>

                            <div class="mt-4 rounded-2xl bg-slate-950/60 p-3 border border-white/5 space-y-1 text-xs">
                                <div class="flex justify-between text-slate-400">
                                    <span>Total Hari Sewa:</span>
                                    <span class="font-bold text-white">{{ $totalDays }} Hari</span>
                                </div>
                                <div class="flex justify-between text-slate-400">
                                    <span>Total Transaksi:</span>
                                    <span class="font-bold text-amber-400">{{ $member->rentals->count() }} Transaksi</span>
                                </div>
                                <div class="text-[11px] text-slate-400 truncate pt-1 border-t border-white/5">
                                    <i class="fa-solid fa-location-dot"></i> {{ $member->profile?->address ?? 'Belum ada alamat' }}
                                </div>
                            </div>
                        </div>

                        <!-- Edit & Delete Action Modal Trigger -->
                        <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between">
                            <!-- Toggle Edit Form Details -->
                            <details class="group w-full">
                                <summary class="cursor-pointer text-[11px] font-bold text-orange-400 hover:underline flex items-center justify-between">
                                    <span><i class="fa-solid fa-pen-to-square"></i> Edit Profil Member</span>
                                    <span class="group-open:rotate-180 transition-transform">▼</span>
                                </summary>
                                
                                <form method="POST" action="/admin/members/{{ $member->id }}" class="mt-3 space-y-2.5 rounded-2xl bg-slate-950 p-3 border border-white/10">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="block text-[9px] uppercase font-bold text-slate-400">Nama</label>
                                        <input name="name" type="text" value="{{ $member->name }}" class="w-full rounded-lg border border-white/10 bg-slate-900 p-2 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] uppercase font-bold text-slate-400">Email</label>
                                        <input name="email" type="email" value="{{ $member->email }}" class="w-full rounded-lg border border-white/10 bg-slate-900 p-2 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                                    </div>
                                    <div>
                                        <label class="block text-[9px] uppercase font-bold text-slate-400">No. WhatsApp</label>
                                        <input name="phone" type="text" value="{{ $member->profile?->phone }}" class="w-full rounded-lg border border-white/10 bg-slate-900 p-2 text-xs text-white focus:border-orange-500 focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[9px] uppercase font-bold text-slate-400">Alamat</label>
                                        <input name="address" type="text" value="{{ $member->profile?->address }}" class="w-full rounded-lg border border-white/10 bg-slate-900 p-2 text-xs text-white focus:border-orange-500 focus:outline-none">
                                    </div>

                                    <div class="flex items-center gap-2 pt-1">
                                        <button type="submit" class="flex-1 rounded-lg bg-orange-600 py-1.5 text-xs font-bold text-white hover:bg-orange-500 transition">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </details>

                            <form method="POST" action="/admin/members/{{ $member->id }}" onsubmit="return confirm('Hapus member ini beserta seluruh datanya?');" class="ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg bg-slate-800 p-1.5 text-slate-400 hover:bg-red-500/20 hover:text-red-400 transition" title="Hapus Anggota">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 rounded-3xl border border-white/10 bg-slate-900/90 p-8 text-center text-xs text-slate-500">
                        Belum ada member terdaftar selain akun administrator.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>

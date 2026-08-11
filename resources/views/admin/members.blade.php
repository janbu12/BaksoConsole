<x-layouts.admin title="Manajemen Anggota">
    <x-slot:header>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-users text-[#f95721]"></i> Manajemen Anggota & Loyalitas Pelanggan
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Kelola data profil pelanggan, pantau total hari sewa, dan status tingkatan loyalitas Bakso Rank.
                </p>
            </div>
            <div>
                <button onclick="openModal('add-member-modal')" type="button" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-[#f95721] to-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-500/25 hover:brightness-110 active:scale-95 transition cursor-pointer">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Tambah Anggota Baru</span>
                </button>
            </div>
        </div>
    </x-slot:header>

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-[#f95721]"></i> Daftar Anggota Terdaftar ({{ $members->count() }} Anggota)
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Total hari sewa dihitung otomatis untuk menentukan tingkatan loyalitas pelanggan.</p>
            </div>
        </div>

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse($members as $member)
                @php
                    $totalDays = (int) $member->rentals->where('status', \App\Enums\RentalStatus::Returned)->sum('duration_days');
                    $rank = \App\Domain\Loyalty\BaksoRank::fromDays($totalDays);
                @endphp
                <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-5 shadow-xl backdrop-blur-xl transition hover:border-orange-500/30 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white text-base">{{ $member->name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $member->email }}</div>
                                <div class="text-xs text-[#f95721] font-mono mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-phone text-[10px]"></i>
                                    <span>{{ $member->profile?->phone ?? 'Belum ada nomor' }}</span>
                                </div>
                            </div>
                            <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase shrink-0 {{ $rank['badge_class'] }}">
                                {{ $rank['name'] }}
                            </span>
                        </div>

                        <div class="mt-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 p-3.5 border border-slate-200/80 dark:border-white/5 space-y-1.5 text-xs">
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Total Hari Sewa:</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $totalDays }} Hari</span>
                            </div>
                            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                                <span>Total Transaksi:</span>
                                <span class="font-bold text-amber-600 dark:text-amber-400">{{ $member->rentals->count() }} Transaksi</span>
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate pt-1.5 border-t border-slate-200 dark:border-white/5 flex items-center gap-1.5">
                                <i class="fa-solid fa-location-dot text-slate-400"></i>
                                <span>{{ $member->profile?->address ?? 'Belum ada alamat' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Edit & Delete Action Modal Triggers -->
                    <div class="mt-5 pt-3 border-t border-slate-200 dark:border-white/10 flex items-center justify-between gap-2">
                        <button onclick="openModal('edit-member-{{ $member->id }}')" type="button" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#f95721] hover:underline cursor-pointer">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                            <span>Edit Profil Member</span>
                        </button>

                        <button onclick="openModal('delete-member-{{ $member->id }}')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 p-2 text-slate-500 hover:bg-red-500/10 hover:text-red-500 hover:border-red-500/40 transition cursor-pointer" title="Hapus Anggota">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>

                    <!-- EDIT MEMBER DYNAMIC MODAL -->
                    <x-modal name="edit-member-{{ $member->id }}" title="Edit Profil Member - {{ $member->name }}" subtitle="Perbarui informasi profil dan kontak pelanggan." icon="fa-solid fa-user-pen" maxWidth="md">
                        <form method="POST" action="/admin/members/{{ $member->id }}" class="space-y-4 text-left">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                                <input name="name" type="text" value="{{ $member->name }}" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
                                <input name="email" type="email" value="{{ $member->email }}" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp / HP</label>
                                <input name="phone" type="text" value="{{ $member->profile?->phone }}" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Alamat Pengiriman</label>
                                <input name="address" type="text" value="{{ $member->profile?->address }}" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white">
                            </div>

                            <div class="pt-2 flex justify-end gap-2">
                                <button onclick="closeModal('edit-member-{{ $member->id }}')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition cursor-pointer">
                                    Batal
                                </button>
                                <button type="submit" class="rounded-xl bg-[#f95721] px-5 py-2 text-xs font-bold text-white shadow-md shadow-orange-500/25 hover:brightness-110 transition cursor-pointer">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </x-modal>

                    <!-- DELETE MEMBER CONFIRMATION MODAL -->
                    <x-modal name="delete-member-{{ $member->id }}" title="Konfirmasi Hapus Anggota" subtitle="Tindakan ini akan menghapus akun dan histori sewa." icon="fa-solid fa-triangle-exclamation" maxWidth="md">
                        <div class="space-y-4 text-left">
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                Apakah Anda yakin ingin menghapus akun member <strong class="text-slate-900 dark:text-white">{{ $member->name }} ({{ $member->email }})</strong>?
                            </p>
                            <form method="POST" action="/admin/members/{{ $member->id }}" class="flex justify-end gap-2 pt-2">
                                @csrf
                                @method('DELETE')
                                <button onclick="closeModal('delete-member-{{ $member->id }}')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition cursor-pointer">
                                    Batal
                                </button>
                                <button type="submit" class="rounded-xl bg-red-600 px-5 py-2 text-xs font-bold text-white shadow-md shadow-red-500/25 hover:bg-red-500 transition cursor-pointer">
                                    Hapus Member
                                </button>
                            </form>
                        </div>
                    </x-modal>
                </div>
            @empty
                <div class="col-span-full rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-8 text-center text-xs text-slate-500">
                    Belum ada member terdaftar selain akun administrator. Klik "+ Tambah Anggota Baru" untuk mendaftarkan member pertama.
                </div>
            @endforelse
        </div>
    </div>

    <!-- ADD MEMBER DYNAMIC MODAL -->
    <x-modal name="add-member-modal" title="Tambah Anggota Baru" subtitle="Daftarkan akun member baru ke dalam sistem." icon="fa-solid fa-user-plus" maxWidth="lg">
        <form method="POST" action="/admin/members" class="space-y-4 text-left">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                <input name="name" type="text" placeholder="Budi Santoso" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
                <input name="email" type="email" placeholder="budi@example.com" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Password</label>
                    <input name="password" type="password" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Password</label>
                    <input name="password_confirmation" type="password" placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Nomor WhatsApp / HP</label>
                <input name="phone" type="text" placeholder="08123456789" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Alamat Pengiriman</label>
                <input name="address" type="text" placeholder="Jl. Sudirman No. 45, Jakarta" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Tanggal Lahir</label>
                <input name="date_of_birth" type="date" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button onclick="closeModal('add-member-modal')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-[#f95721] px-5 py-2 text-xs font-bold text-white shadow-md shadow-orange-500/25 hover:brightness-110 transition cursor-pointer">
                    + Daftarkan Member
                </button>
            </div>
        </form>
    </x-modal>
</x-layouts.admin>

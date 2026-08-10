<x-layouts.admin title="Kelola Unit Konsol">
    <x-slot:header><i class="fa-solid fa-gamepad"></i> Kelola Unit Konsol & Status Ketersediaan</x-slot:header>
    <x-slot:subtitle>Tambah konsol baru, atur tarif harian, kapasitas mabar (1-4P), serta status ketersediaan live.</x-slot:subtitle>

    <div class="grid gap-8 lg:grid-cols-3">
        <!-- Form Tambah Unit Konsol -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
                <h2 class="text-base font-bold text-white mb-1 flex items-center gap-2">
                    <span><i class="fa-solid fa-plus"></i></span> Tambah Unit Konsol Baru
                </h2>
                <p class="text-xs text-slate-400 mb-5">Daftarkan konsol ke inventaris Bakso Console.</p>

                <form method="POST" action="/admin/units" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Konsol</label>
                        <input name="name" type="text" placeholder="Contoh: PlayStation 5 Slim Edition" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Kode Unit</label>
                            <input name="code" type="text" placeholder="PS5-004" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white placeholder-slate-500 uppercase font-mono focus:border-orange-500 focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Kategori</label>
                            <select name="category_id" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none">
                                <option value="">Tanpa Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Tarif Sewa (Rp/Hari)</label>
                            <input name="daily_price" type="number" placeholder="50000" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Kapasitas Mabar</label>
                            <select name="max_players" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none">
                                <option value="1">1 Player</option>
                                <option value="2">2 Players</option>
                                <option value="3">3 Players</option>
                                <option value="4" selected>4 Players (Mabar Full)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Deskripsi & Kelengkapan</label>
                        <textarea name="description" rows="2" placeholder="Include 2 DualSense + Kabel HDMI 2.1 + Game Pre-installed" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none"></textarea>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-orange-600 py-3 text-xs font-bold text-white shadow-lg shadow-orange-600/25 hover:bg-orange-500 transition">
                        Simpan Unit Konsol
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Unit Konsol -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-white">Daftar Inventaris Unit ({{ $units->total() ?? $units->count() }} Total)</h2>
                    <p class="text-xs text-slate-400">Status unit diperbarui secara otomatis sesuai alur booking & rental.</p>
                </div>
            </div>

            <div class="overflow-x-auto rounded-3xl border border-white/10 bg-slate-900/90 shadow-2xl">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-white/10 bg-slate-950/60 text-slate-400 uppercase font-bold text-[10px]">
                        <tr>
                            <th class="p-4">Kode Unit</th>
                            <th class="p-4">Nama Konsol</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Tarif/Hari</th>
                            <th class="p-4">Kapasitas</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-200">
                        @forelse($units as $unit)
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="p-4 font-mono font-bold text-orange-400">{{ $unit->code }}</td>
                                <td class="p-4 font-semibold text-white">
                                    {{ $unit->name }}
                                    @if($unit->description)
                                        <div class="text-[10px] text-slate-400 font-normal truncate max-w-xs">{{ $unit->description }}</div>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @foreach($unit->categories as $cat)
                                        <span class="rounded bg-slate-800 px-2 py-0.5 text-[10px] text-slate-300 border border-white/10">{{ $cat->name }}</span>
                                    @endforeach
                                </td>
                                <td class="p-4 font-bold text-amber-400">Rp {{ number_format($unit->daily_price, 0, ',', '.') }}</td>
                                <td class="p-4">
                                    <span class="rounded-full bg-blue-500/10 border border-blue-500/30 px-2 py-0.5 text-[10px] font-bold text-blue-400">
                                        {{ $unit->max_players }} Player{{ $unit->max_players > 1 ? 's' : '' }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @php
                                        $statusConfig = match($unit->status->value) {
                                            'available' => ['class' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30', 'label' => 'Available'],
                                            'booked' => ['class' => 'bg-amber-500/20 text-amber-300 border-amber-500/30', 'label' => 'Booked'],
                                            'rented' => ['class' => 'bg-blue-500/20 text-blue-400 border-blue-500/30', 'label' => 'Rented'],
                                            'maintenance' => ['class' => 'bg-red-500/20 text-red-400 border-red-500/30', 'label' => 'Maintenance'],
                                            default => ['class' => 'bg-slate-500/20 text-slate-300 border-slate-500/30', 'label' => $unit->status->value],
                                        };
                                    @endphp
                                    <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border uppercase {{ $statusConfig['class'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Quick Status Toggle Form -->
                                        <form method="POST" action="/admin/units/{{ $unit->id }}" class="inline-flex items-center gap-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{ $unit->name }}">
                                            <input type="hidden" name="daily_price" value="{{ $unit->daily_price }}">
                                            <input type="hidden" name="max_players" value="{{ $unit->max_players }}">
                                            
                                            @if($unit->status->value === 'available')
                                                <input type="hidden" name="status" value="maintenance">
                                                <button type="submit" title="Ubah ke Maintenance" class="rounded-lg bg-red-500/10 border border-red-500/20 px-2 py-1 text-[10px] font-bold text-red-400 hover:bg-red-500 hover:text-white transition">
                                                    Set Service
                                                </button>
                                            @elseif($unit->status->value === 'maintenance')
                                                <input type="hidden" name="status" value="available">
                                                <button type="submit" title="Aktifkan Kembali" class="rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2 py-1 text-[10px] font-bold text-emerald-400 hover:bg-emerald-500 hover:text-white transition">
                                                    Set Ready
                                                </button>
                                            @endif
                                        </form>

                                        <!-- Delete Unit Form -->
                                        <form method="POST" action="/admin/units/{{ $unit->id }}" onsubmit="return confirm('Hapus unit konsol ini dari inventaris?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-slate-800 p-1.5 text-slate-400 hover:bg-red-500/20 hover:text-red-400 transition" title="Hapus Unit">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-500">Belum ada data unit konsol. Tambahkan unit pertama di formulir samping.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($units, 'links'))
                <div class="mt-4">{{ $units->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.admin>

<x-layouts.admin title="Kelola Unit Konsol">
    <x-slot:header><i class="fa-solid fa-gamepad"></i> Kelola Unit Konsol, Game & Hardware</x-slot:header>
    <x-slot:subtitle>Manajemen inventaris konsol lengkap dengan Kode Rangka / Model, Nomor Seri (S/N), game terpasang, serta status ketersediaan live.</x-slot:subtitle>

    <div class="space-y-8">
        <div class="grid gap-8 lg:grid-cols-3">
            <!-- Form Tambah Unit Konsol -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl space-y-4">
                    <div>
                        <h2 class="text-base font-bold text-white flex items-center gap-2">
                            <span><i class="fa-solid fa-plus"></i></span> Tambah Unit Konsol Baru
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Daftarkan konsol ke inventaris lengkap dengan game dan nomor seri.</p>
                    </div>

                    <form method="POST" action="/admin/units" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Konsol</label>
                            <input name="name" type="text" placeholder="Contoh: PlayStation 5 Slim Edition" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none" required>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1 flex items-center justify-between">
                                    <span>Kode Unit</span>
                                    <span class="text-[9px] text-orange-400 lowercase font-normal">(auto-increment)</span>
                                </label>
                                <input name="code" type="text" value="{{ $suggestedCode ?? '' }}" placeholder="Otomatis ({{ $suggestedCode ?? 'PS5-001' }})" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white placeholder-slate-500 uppercase font-mono focus:border-orange-500 focus:outline-none">
                                <span class="text-[9px] text-slate-500 block mt-1">Kosongkan jika ingin auto-generate</span>
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

                        <!-- Hardware & Firmware Info -->
                        <div class="space-y-2 p-3 rounded-2xl bg-slate-950/60 border border-white/5">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                    <i class="fa-solid fa-microchip text-orange-400"></i> Tipe Sistem / Firmware
                                </label>
                                <select name="firmware_type" class="w-full rounded-xl border border-white/10 bg-slate-900 p-2 text-xs text-white focus:border-orange-500 focus:outline-none">
                                    <option value="original">🌐 Original / OFW (Bisa Online & PSN)</option>
                                    <option value="jailbreak">💾 Jailbreak / HEN (Full Game Offline)</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                        <i class="fa-solid fa-barcode text-orange-400"></i> No. Seri (S/N)
                                    </label>
                                    <input name="serial_number" type="text" placeholder="S01-7489210-E" class="w-full rounded-xl border border-white/10 bg-slate-900 p-2 text-xs text-white placeholder-slate-500 font-mono focus:border-orange-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">
                                        Model / Rangka
                                    </label>
                                    <input name="model_number" type="text" placeholder="CFI-1218A" class="w-full rounded-xl border border-white/10 bg-slate-900 p-2 text-xs text-white placeholder-slate-500 font-mono focus:border-orange-500 focus:outline-none">
                                </div>
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

                        <!-- Game Terpasang Selector -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    <i class="fa-solid fa-gamepad text-orange-400"></i> Game Terpasang di Unit Ini
                                </label>
                                <span class="text-[9px] text-slate-500">{{ $games->count() }} Game Tersedia</span>
                            </div>
                            <div class="max-h-36 overflow-y-auto rounded-xl border border-white/10 bg-slate-950 p-2.5 space-y-1.5 scrollbar-thin">
                                @forelse($games as $game)
                                    <label class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-white/5 cursor-pointer text-xs transition">
                                        <input type="checkbox" name="game_ids[]" value="{{ $game->id }}" class="rounded border-white/20 bg-slate-900 text-orange-500 focus:ring-orange-500">
                                        <span class="text-white">{{ $game->icon ?? '🎮' }} {{ $game->name }}</span>
                                        <span class="text-[9px] text-slate-500 ml-auto">{{ $game->genre ?? '' }}</span>
                                    </label>
                                @empty
                                    <p class="text-[11px] text-slate-500 text-center py-2">Belum ada master game. Tambahkan di bawah.</p>
                                @endforelse
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
                        <p class="text-xs text-slate-400">Daftar unit beserta identitas hardware (S/N, Model) dan game yang terpasang.</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-3xl border border-white/10 bg-slate-900/90 shadow-2xl">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-white/10 bg-slate-950/60 text-slate-400 uppercase font-bold text-[10px]">
                            <tr>
                                <th class="p-4">Kode & Hardware</th>
                                <th class="p-4">Nama Konsol & Game</th>
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
                                    <!-- Kode & Hardware -->
                                    <td class="p-4">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-mono font-bold text-orange-400 text-sm">#{{ $unit->code }}</span>
                                            <span class="rounded px-1.5 py-0.5 text-[9px] font-bold uppercase {{ $unit->firmware_type?->value === 'jailbreak' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30' }}">
                                                {{ $unit->firmware_type?->value === 'jailbreak' ? '💾 Jailbreak' : '🌐 Online' }}
                                            </span>
                                        </div>
                                        @if($unit->serial_number)
                                            <div class="text-[10px] text-slate-400 font-mono mt-0.5" title="Nomor Seri">
                                                <i class="fa-solid fa-barcode text-slate-500"></i> {{ $unit->serial_number }}
                                            </div>
                                        @endif
                                        @if($unit->model_number)
                                            <div class="text-[10px] text-slate-500 font-mono" title="Kode Model / Rangka">
                                                <i class="fa-solid fa-microchip text-slate-600"></i> {{ $unit->model_number }}
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Nama Konsol & Game -->
                                    <td class="p-4">
                                        <div class="font-semibold text-white text-sm">{{ $unit->name }}</div>
                                        @if($unit->description)
                                            <div class="text-[10px] text-slate-400 font-normal truncate max-w-xs">{{ $unit->description }}</div>
                                        @endif
                                        <!-- Game Badges -->
                                        @if($unit->games->isNotEmpty())
                                            <div class="mt-1.5 flex flex-wrap gap-1">
                                                @foreach($unit->games as $game)
                                                    <span class="inline-flex items-center gap-1 rounded-md bg-orange-500/10 border border-orange-500/20 px-1.5 py-0.5 text-[9px] font-bold text-orange-300">
                                                        <span>{{ $game->icon ?? '🎮' }}</span> {{ $game->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-[10px] text-slate-500 italic mt-1">Belum ada game terpasang</div>
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
                                            <!-- Edit Unit Modal / Details -->
                                            <details class="group relative">
                                                <summary class="cursor-pointer rounded-lg bg-slate-800 p-1.5 text-slate-300 hover:bg-orange-500/20 hover:text-orange-400 transition list-none" title="Edit Unit & Game">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </summary>
                                                <form method="POST" action="/admin/units/{{ $unit->id }}" class="mt-2 w-80 space-y-3 rounded-2xl bg-slate-900 p-4 border border-white/10 shadow-2xl absolute right-0 z-30 text-left">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="font-bold text-white text-xs border-b border-white/10 pb-2">
                                                        Edit Unit #{{ $unit->code }}
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] text-slate-400">Nama Konsol</label>
                                                        <input name="name" type="text" value="{{ $unit->name }}" class="w-full rounded-lg border border-white/10 bg-slate-950 p-2 text-xs text-white" required>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div>
                                                            <label class="block text-[10px] text-slate-400">No. Seri (S/N)</label>
                                                            <input name="serial_number" type="text" value="{{ $unit->serial_number }}" class="w-full rounded-lg border border-white/10 bg-slate-950 p-1.5 text-xs text-white font-mono">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] text-slate-400">Model / Rangka</label>
                                                            <input name="model_number" type="text" value="{{ $unit->model_number }}" class="w-full rounded-lg border border-white/10 bg-slate-950 p-1.5 text-xs text-white font-mono">
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] text-slate-400">Tipe Sistem / Firmware</label>
                                                        <select name="firmware_type" class="w-full rounded-lg border border-white/10 bg-slate-950 p-1.5 text-xs text-white">
                                                            <option value="original" @selected(($unit->firmware_type?->value ?? 'original') === 'original')>🌐 Original / OFW (Bisa Online)</option>
                                                            <option value="jailbreak" @selected(($unit->firmware_type?->value ?? 'original') === 'jailbreak')>💾 Jailbreak / HEN (Full Game Offline)</option>
                                                        </select>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <div>
                                                            <label class="block text-[10px] text-slate-400">Tarif Sewa (Rp)</label>
                                                            <input name="daily_price" type="number" value="{{ intval($unit->daily_price) }}" class="w-full rounded-lg border border-white/10 bg-slate-950 p-1.5 text-xs text-white" required>
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] text-slate-400">Kapasitas (Pemain)</label>
                                                            <input name="max_players" type="number" value="{{ $unit->max_players }}" min="1" max="4" class="w-full rounded-lg border border-white/10 bg-slate-950 p-1.5 text-xs text-white" required>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] text-slate-400">Status</label>
                                                        <select name="status" class="w-full rounded-lg border border-white/10 bg-slate-950 p-1.5 text-xs text-white">
                                                            <option value="available" @selected($unit->status->value === 'available')>Available (Tersedia)</option>
                                                            <option value="maintenance" @selected($unit->status->value === 'maintenance')>Maintenance (Servis)</option>
                                                            <option value="rented" @selected($unit->status->value === 'rented')>Rented (Sedang Disewa)</option>
                                                            <option value="booked" @selected($unit->status->value === 'booked')>Booked (Dipesan)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] text-slate-400 mb-1">Game Terpasang</label>
                                                        <div class="max-h-28 overflow-y-auto rounded-lg border border-white/10 bg-slate-950 p-2 space-y-1">
                                                            @foreach($games as $game)
                                                                <label class="flex items-center gap-1.5 text-[11px] text-slate-300">
                                                                    <input type="checkbox" name="game_ids[]" value="{{ $game->id }}" @checked($unit->games->contains($game->id)) class="rounded border-white/20 bg-slate-900 text-orange-500">
                                                                    <span>{{ $game->icon ?? '🎮' }} {{ $game->name }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="w-full rounded-lg bg-orange-600 py-2 text-xs font-bold text-white hover:bg-orange-500">
                                                        Simpan Perubahan
                                                    </button>
                                                </form>
                                            </details>

                                            <!-- Quick Status Toggle Form -->
                                            <form method="POST" action="/admin/units/{{ $unit->id }}" class="inline-flex items-center gap-1">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="name" value="{{ $unit->name }}">
                                                <input type="hidden" name="daily_price" value="{{ $unit->daily_price }}">
                                                <input type="hidden" name="max_players" value="{{ $unit->max_players }}">
                                                <input type="hidden" name="serial_number" value="{{ $unit->serial_number }}">
                                                <input type="hidden" name="model_number" value="{{ $unit->model_number }}">
                                                
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

        <!-- Section 2: Master Katalog Game -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span><i class="fa-solid fa-compact-disc text-orange-400"></i></span> Master Katalog Game PS
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Daftar game resmi yang dapat di-install dan dipasangkan ke unit konsol PlayStation.</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Form Tambah Game Baru -->
                <div class="lg:col-span-1 rounded-2xl border border-white/5 bg-slate-950/80 p-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-orange-400 mb-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-plus-circle"></i> Tambah Master Game Baru
                    </h3>
                    <form method="POST" action="/admin/games" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Judul Game</label>
                            <input name="name" type="text" placeholder="Contoh: Assassin's Creed Mirage" class="w-full rounded-xl border border-white/10 bg-slate-900 p-2 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none" required>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Genre</label>
                                <input name="genre" type="text" placeholder="Action-Adventure / Racing / Sports" class="w-full rounded-xl border border-white/10 bg-slate-900 p-2 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Ikon / Emoji</label>
                                <input name="icon" type="text" value="🎮" placeholder="🎮" class="w-full rounded-xl border border-white/10 bg-slate-900 p-2 text-xs text-white text-center focus:border-orange-500 focus:outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Deskripsi Singkat (Opsional)</label>
                            <input name="description" type="text" placeholder="Misal: Resolusi 4K 60FPS, Co-op 2P" class="w-full rounded-xl border border-white/10 bg-slate-900 p-2 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none">
                        </div>
                        <button type="submit" class="w-full rounded-xl bg-orange-600 py-2.5 text-xs font-bold text-white hover:bg-orange-500 transition">
                            + Tambah ke Katalog Game
                        </button>
                    </form>
                </div>

                <!-- Grid Daftar Master Game -->
                <div class="lg:col-span-2">
                    <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3">
                        @forelse($games as $game)
                            <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-950/60 border border-white/5 hover:border-orange-500/30 transition">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="text-xl p-2 rounded-xl bg-white/5">{{ $game->icon ?? '🎮' }}</div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-white text-xs truncate">{{ $game->name }}</div>
                                        <div class="text-[10px] text-slate-400">{{ $game->genre ?? 'General' }}</div>
                                    </div>
                                </div>
                                <form method="POST" action="/admin/games/{{ $game->id }}" onsubmit="return confirm('Hapus game ini dari katalog?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-500 hover:text-red-400 p-1 transition" title="Hapus Game">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="col-span-full py-8 text-center text-xs text-slate-500">Belum ada game di master katalog.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

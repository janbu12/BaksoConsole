<x-layouts.admin title="Kelola Unit Konsol">
    <x-slot:header>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-gamepad text-[#f95721]"></i> Kelola Unit Konsol, Game & Hardware
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Manajemen inventaris konsol lengkap dengan Kode Rangka / Model, Nomor Seri (S/N), game terpasang, serta status ketersediaan live.
                </p>
            </div>
            <div>
                <button @click="$dispatch('open-modal', 'add-unit-modal')" type="button" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-[#f95721] to-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-500/25 hover:brightness-110 active:scale-95 transition cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Unit Konsol Baru</span>
                </button>
            </div>
        </div>
    </x-slot:header>

    <div class="space-y-8">
        <!-- Section 1: Tabel Inventaris Konsol (Full Width) -->
        <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-6 shadow-xl backdrop-blur-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-[#f95721]"></i> Daftar Inventaris Konsol ({{ $units->count() }} Unit)
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Lihat dan atur informasi hardware, firmware, game, dan status operasional.</p>
                </div>
            </div>

            <!-- Table Unit -->
            <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-50/50 dark:bg-slate-950/60">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-100 dark:bg-slate-900/80 text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-white/10">
                        <tr>
                            <th class="p-4">Kode & Konsol</th>
                            <th class="p-4">Tipe & Hardware</th>
                            <th class="p-4">Kategori</th>
                            <th class="p-4">Game Terpasang</th>
                            <th class="p-4">Tarif Sewa</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Aksi Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-white/5">
                        @forelse($units as $unit)
                            <tr class="hover:bg-slate-100/50 dark:hover:bg-white/5 transition">
                                <!-- Kode & Konsol -->
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-[#f95721] bg-orange-500/10 px-2 py-0.5 rounded border border-orange-500/20">
                                            {{ $unit->code }}
                                        </span>
                                        <div>
                                            <div class="font-bold text-slate-900 dark:text-white">{{ $unit->name }}</div>
                                            <div class="text-[10px] text-slate-500 dark:text-slate-400">Max {{ $unit->max_players }} Player</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Tipe & Hardware -->
                                <td class="p-4">
                                    <div class="space-y-0.5">
                                        <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[9px] font-bold uppercase {{ $unit->firmware_type?->value === 'jailbreak' ? 'bg-purple-500/15 text-purple-700 dark:text-purple-300 border border-purple-500/30' : 'bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30' }}">
                                            <i class="fa-solid {{ $unit->firmware_type?->value === 'jailbreak' ? 'fa-hard-drive' : 'fa-globe' }} text-[9px]"></i>
                                            <span>{{ $unit->firmware_type?->value === 'jailbreak' ? 'Jailbreak' : 'Online OFW' }}</span>
                                        </span>
                                        @if($unit->serial_number || $unit->model_number)
                                            <div class="font-mono text-[10px] text-slate-500 dark:text-slate-400">
                                                S/N: {{ $unit->serial_number ?: '-' }} | Model: {{ $unit->model_number ?: '-' }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Kategori -->
                                <td class="p-4">
                                    <span class="rounded-lg bg-slate-200 dark:bg-white/10 px-2 py-1 text-[10px] font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $unit->category?->name ?? 'Uncategorized' }}
                                    </span>
                                </td>

                                <!-- Game Terpasang -->
                                <td class="p-4">
                                    @if($unit->games->isNotEmpty())
                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                            @foreach($unit->games->take(3) as $g)
                                                <span class="rounded-md bg-slate-200/80 dark:bg-slate-800 border border-slate-300 dark:border-white/10 px-1.5 py-0.5 text-[10px] text-slate-800 dark:text-slate-300">
                                                    {{ $g->icon ?? '🎮' }} {{ $g->name }}
                                                </span>
                                            @endforeach
                                            @if($unit->games->count() > 3)
                                                <span class="rounded-md bg-orange-500/15 px-1.5 py-0.5 text-[10px] font-bold text-orange-600 dark:text-orange-400">
                                                    +{{ $unit->games->count() - 3 }} game
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 italic">Belum ada game</span>
                                    @endif
                                </td>

                                <!-- Tarif Sewa -->
                                <td class="p-4 font-mono font-bold text-slate-900 dark:text-white">
                                    Rp {{ number_format($unit->daily_price, 0, ',', '.') }}<span class="text-[10px] font-normal text-slate-500">/hr</span>
                                </td>

                                <!-- Status -->
                                <td class="p-4">
                                    @php
                                        $statusConfig = match ($unit->status->value) {
                                            'available' => ['class' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30', 'label' => 'Available'],
                                            'booked' => ['class' => 'bg-amber-500/15 text-amber-700 dark:text-amber-300 border-amber-500/30', 'label' => 'Booked'],
                                            'rented' => ['class' => 'bg-blue-500/15 text-blue-700 dark:text-blue-300 border-blue-500/30', 'label' => 'Rented'],
                                            'maintenance' => ['class' => 'bg-red-500/15 text-red-700 dark:text-red-300 border-red-500/30', 'label' => 'Maintenance'],
                                            default => ['class' => 'bg-slate-500/15 text-slate-700 dark:text-slate-300 border-slate-500/30', 'label' => $unit->status->value],
                                        };
                                    @endphp
                                    <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border uppercase {{ $statusConfig['class'] }}">
                                        {{ $statusConfig['label'] }}
                                    </span>
                                </td>

                                <!-- Aksi Operations -->
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit Modal Trigger Button -->
                                        <button @click="$dispatch('open-modal', 'edit-unit-{{ $unit->id }}')" type="button" title="Edit Unit & Game" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 p-2 text-slate-700 dark:text-slate-300 hover:border-orange-500 hover:text-[#f95721] transition shadow-sm">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>

                                        <!-- Quick Status Toggle Form -->
                                        <form method="POST" action="/admin/units/{{ $unit->id }}" class="inline-flex items-center">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{ $unit->name }}">
                                            <input type="hidden" name="daily_price" value="{{ $unit->daily_price }}">
                                            <input type="hidden" name="max_players" value="{{ $unit->max_players }}">
                                            <input type="hidden" name="serial_number" value="{{ $unit->serial_number }}">
                                            <input type="hidden" name="model_number" value="{{ $unit->model_number }}">
                                            
                                            @if($unit->status->value === 'available')
                                                <input type="hidden" name="status" value="maintenance">
                                                <button type="submit" title="Ubah ke Maintenance" class="rounded-xl bg-red-500/10 border border-red-500/20 px-2.5 py-1 text-[10px] font-bold text-red-600 dark:text-red-400 hover:bg-red-500 hover:text-white transition">
                                                    Set Service
                                                </button>
                                            @elseif($unit->status->value === 'maintenance')
                                                <input type="hidden" name="status" value="available">
                                                <button type="submit" title="Aktifkan Kembali" class="rounded-xl bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500 hover:text-white transition">
                                                    Set Ready
                                                </button>
                                            @endif
                                        </form>

                                        <!-- Delete Modal Trigger Button -->
                                        <button @click="$dispatch('open-modal', 'delete-unit-{{ $unit->id }}')" type="button" title="Hapus Unit" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 p-2 text-slate-500 hover:bg-red-500/10 hover:text-red-500 hover:border-red-500/40 transition">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </div>

                                    <!-- EDIT UNIT DYNAMIC MODAL -->
                                    <x-modal name="edit-unit-{{ $unit->id }}" title="Edit Unit Konsol #{{ $unit->code }}" subtitle="Perbarui spesifikasi hardware, serial number, status, dan game terpasang." icon="fa-solid fa-pen-to-square">
                                        <form method="POST" action="/admin/units/{{ $unit->id }}" class="space-y-4 text-left">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Nama Konsol</label>
                                                <input name="name" type="text" value="{{ $unit->name }}" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white" required>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">No. Seri (S/N)</label>
                                                    <input name="serial_number" type="text" value="{{ $unit->serial_number }}" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs font-mono text-slate-900 dark:text-white">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Model / Rangka</label>
                                                    <input name="model_number" type="text" value="{{ $unit->model_number }}" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs font-mono text-slate-900 dark:text-white">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Tipe Sistem / Firmware</label>
                                                <select name="firmware_type" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white">
                                                    <option value="original" @selected(($unit->firmware_type?->value ?? 'original') === 'original')>🌐 Original / OFW (Bisa Online & PSN)</option>
                                                    <option value="jailbreak" @selected(($unit->firmware_type?->value ?? 'original') === 'jailbreak')>💾 Jailbreak / HEN (Full Game Offline)</option>
                                                </select>
                                            </div>

                                            <div class="grid grid-cols-3 gap-3">
                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Tarif Sewa (Rp)</label>
                                                    <input name="daily_price" type="number" value="{{ intval($unit->daily_price) }}" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white" required>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Pemain</label>
                                                    <input name="max_players" type="number" value="{{ $unit->max_players }}" min="1" max="4" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white" required>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Status Live</label>
                                                    <select name="status" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white">
                                                        <option value="available" @selected($unit->status->value === 'available')>Available (Tersedia)</option>
                                                        <option value="maintenance" @selected($unit->status->value === 'maintenance')>Maintenance (Servis)</option>
                                                        <option value="rented" @selected($unit->status->value === 'rented')>Rented (Disewa)</option>
                                                        <option value="booked" @selected($unit->status->value === 'booked')>Booked (Dipesan)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Game Terpasang di Unit Ini</label>
                                                <div class="max-h-36 overflow-y-auto rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-3 space-y-1.5 scrollbar-thin">
                                                    @foreach($games as $game)
                                                        <label class="flex items-center gap-2 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 cursor-pointer text-xs transition">
                                                            <input type="checkbox" name="game_ids[]" value="{{ $game->id }}" @checked($unit->games->contains($game->id)) class="rounded border-slate-300 dark:border-white/20 bg-white dark:bg-slate-900 text-[#f95721] focus:ring-orange-500">
                                                            <span class="text-slate-900 dark:text-white">{{ $game->icon ?? '🎮' }} {{ $game->name }}</span>
                                                            <span class="text-[10px] text-slate-500 ml-auto">{{ $game->genre ?? '' }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="pt-2 flex justify-end gap-2">
                                                <button @click="$dispatch('close-modal', 'edit-unit-{{ $unit->id }}')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition">
                                                    Batal
                                                </button>
                                                <button type="submit" class="rounded-xl bg-[#f95721] px-5 py-2 text-xs font-bold text-white shadow-md shadow-orange-500/25 hover:brightness-110 transition">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </x-modal>

                                    <!-- DELETE UNIT CONFIRMATION MODAL -->
                                    <x-modal name="delete-unit-{{ $unit->id }}" title="Konfirmasi Hapus Unit Konsol" subtitle="Tindakan ini tidak dapat dibatalkan." icon="fa-solid fa-triangle-exclamation" maxWidth="md">
                                        <div class="space-y-4 text-left">
                                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                                Apakah Anda yakin ingin menghapus unit konsol <strong class="text-slate-900 dark:text-white">#{{ $unit->code }} - {{ $unit->name }}</strong> dari inventaris?
                                            </p>

                                            <form method="POST" action="/admin/units/{{ $unit->id }}" class="flex justify-end gap-2 pt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button @click="$dispatch('close-modal', 'delete-unit-{{ $unit->id }}')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition">
                                                    Batal
                                                </button>
                                                <button type="submit" class="rounded-xl bg-red-600 px-5 py-2 text-xs font-bold text-white shadow-md shadow-red-500/25 hover:bg-red-500 transition">
                                                    Hapus Permanent
                                                </button>
                                            </form>
                                        </div>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-500">Belum ada data unit konsol. Klik tombol "+ Tambah Unit Konsol Baru" untuk mendaftarkan unit pertama.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($units, 'links'))
                <div class="mt-4">{{ $units->links() }}</div>
            @endif
        </div>

        <!-- Section 2: Master Katalog Game -->
        <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-6 shadow-xl backdrop-blur-xl">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-compact-disc text-[#f95721]"></i> Master Katalog Game PS
                    </h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar game resmi yang dapat di-install dan dipasangkan ke unit konsol PlayStation.</p>
                </div>
                <div>
                    <button @click="$dispatch('open-modal', 'add-game-modal')" type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-3.5 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:border-orange-500 hover:text-[#f95721] transition shadow-sm cursor-pointer">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Tambah Game Baru</span>
                    </button>
                </div>
            </div>

            <!-- Grid Daftar Master Game -->
            <div class="grid gap-3 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @forelse($games as $game)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-white/5 hover:border-orange-500/30 transition">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="text-lg p-2 rounded-xl bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-white/5">
                                {{ $game->icon ?? '🎮' }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-slate-900 dark:text-white text-xs truncate">{{ $game->name }}</div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400">{{ $game->genre ?? 'General' }}</div>
                            </div>
                        </div>

                        <button @click="$dispatch('open-modal', 'delete-game-{{ $game->id }}')" type="button" class="text-slate-400 hover:text-red-500 p-1.5 transition" title="Hapus Game">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>

                        <!-- DELETE GAME CONFIRMATION MODAL -->
                        <x-modal name="delete-game-{{ $game->id }}" title="Konfirmasi Hapus Game" subtitle="Hapus game dari master katalog." icon="fa-solid fa-triangle-exclamation" maxWidth="md">
                            <div class="space-y-4 text-left">
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                    Hapus game <strong class="text-slate-900 dark:text-white">{{ $game->name }}</strong> dari master katalog? Game ini akan dilepas dari unit konsol terkait.
                                </p>
                                <form method="POST" action="/admin/games/{{ $game->id }}" class="flex justify-end gap-2 pt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button @click="$dispatch('close-modal', 'delete-game-{{ $game->id }}')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition">
                                        Batal
                                    </button>
                                    <button type="submit" class="rounded-xl bg-red-600 px-5 py-2 text-xs font-bold text-white shadow-md shadow-red-500/25 hover:bg-red-500 transition">
                                        Hapus Game
                                    </button>
                                </form>
                            </div>
                        </x-modal>
                    </div>
                @empty
                    <p class="col-span-full py-8 text-center text-xs text-slate-500">Belum ada game di master katalog.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- ADD UNIT DYNAMIC MODAL -->
    <x-modal name="add-unit-modal" title="Tambah Unit Konsol Baru" subtitle="Daftarkan konsol baru ke dalam inventaris operasional." icon="fa-solid fa-plus">
        <form method="POST" action="/admin/units" class="space-y-4 text-left">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Nama Konsol</label>
                <input name="name" type="text" placeholder="Contoh: PlayStation 5 Slim Edition" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none" required>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1 flex items-center justify-between">
                        <span>Kode Unit</span>
                        <span class="text-[9px] text-[#f95721] lowercase font-normal">(auto-increment)</span>
                    </label>
                    <input name="code" type="text" value="{{ $suggestedCode ?? '' }}" placeholder="Otomatis ({{ $suggestedCode ?? 'PS5-001' }})" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs font-mono uppercase text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none">
                    <span class="text-[9px] text-slate-500 block mt-1">Kosongkan jika ingin auto-generate</span>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Kategori Konsol</label>
                    <select name="category_id" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                        <option value="">Tanpa Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Hardware & Firmware Info -->
            <div class="space-y-3 p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-white/5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-microchip text-[#f95721]"></i>
                        <span>Tipe Sistem / Firmware</span>
                    </label>
                    <select name="firmware_type" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                        <option value="original">🌐 Original / OFW (Bisa Online & PSN)</option>
                        <option value="jailbreak">💾 Jailbreak / HEN (Full Game Offline)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            No. Seri (S/N)
                        </label>
                        <input name="serial_number" type="text" placeholder="S01-7489210-E" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-2.5 text-xs font-mono text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                            Model / Rangka
                        </label>
                        <input name="model_number" type="text" placeholder="CFI-1218A" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 p-2.5 text-xs font-mono text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Tarif Sewa (Rp/Hari)</label>
                    <input name="daily_price" type="number" placeholder="50000" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Kapasitas Mabar</label>
                    <select name="max_players" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                        <option value="1">1 Player</option>
                        <option value="2">2 Players</option>
                        <option value="3">3 Players</option>
                        <option value="4" selected>4 Players (Mabar Full)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Pilih Game Terpasang</label>
                <div class="max-h-36 overflow-y-auto rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-3 space-y-1.5 scrollbar-thin">
                    @forelse($games as $game)
                        <label class="flex items-center gap-2 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-white/5 cursor-pointer text-xs transition">
                            <input type="checkbox" name="game_ids[]" value="{{ $game->id }}" class="rounded border-slate-300 dark:border-white/20 bg-white dark:bg-slate-900 text-[#f95721] focus:ring-orange-500">
                            <span class="text-slate-900 dark:text-white">{{ $game->icon ?? '🎮' }} {{ $game->name }}</span>
                            <span class="text-[10px] text-slate-500 ml-auto">{{ $game->genre ?? '' }}</span>
                        </label>
                    @empty
                        <span class="text-xs text-slate-400 italic">Belum ada master game.</span>
                    @endforelse
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button @click="$dispatch('close-modal', 'add-unit-modal')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-[#f95721] px-5 py-2 text-xs font-bold text-white shadow-md shadow-orange-500/25 hover:brightness-110 transition">
                    + Simpan Unit Baru
                </button>
            </div>
        </form>
    </x-modal>

    <!-- ADD GAME DYNAMIC MODAL -->
    <x-modal name="add-game-modal" title="Tambah Master Game Baru" subtitle="Tambahkan game ke katalog master agar dapat dipasang ke unit konsol." icon="fa-solid fa-plus" maxWidth="md">
        <form method="POST" action="/admin/games" class="space-y-3 text-left">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Judul Game</label>
                <input name="name" type="text" placeholder="Contoh: Assassin's Creed Mirage" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none" required>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Genre</label>
                    <input name="genre" type="text" placeholder="Action / Racing / Sports" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Ikon / Emoji</label>
                    <input name="icon" type="text" value="🎮" placeholder="🎮" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white text-center focus:border-orange-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Deskripsi Singkat (Opsional)</label>
                <input name="description" type="text" placeholder="Misal: Resolusi 4K 60FPS, Co-op 2P" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none">
            </div>
            <div class="pt-2 flex justify-end gap-2">
                <button @click="$dispatch('close-modal', 'add-game-modal')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-[#f95721] px-5 py-2 text-xs font-bold text-white shadow-md shadow-orange-500/25 hover:brightness-110 transition">
                    + Simpan Game
                </button>
            </div>
        </form>
    </x-modal>
</x-layouts.admin>

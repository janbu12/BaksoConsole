<x-layouts.admin title="Kategori & Paket Combo">
    <x-slot:header><i class="fa-solid fa-tags"></i> Kelola Kategori & Paket Bundling Bakso Combo</x-slot:header>
    <x-slot:subtitle>Kelola kategori platform konsol dan racik paket bundling sewa hemat lengkap dengan stik ekstra.</x-slot:subtitle>

    <div class="grid gap-8 lg:grid-cols-2">
        <!-- Section 1: Categories Management -->
        <div class="space-y-6">
            <!-- Add Category Form -->
            <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
                <h2 class="text-base font-bold text-white mb-1 flex items-center gap-2">
                    <span><i class="fa-solid fa-plus"></i></span> Tambah Kategori Platform
                </h2>
                <p class="text-xs text-slate-400 mb-4">Kelompokkan konsol berdasarkan platform (PlayStation 5, Nintendo Switch, dll).</p>

                <form method="POST" action="/admin/categories" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Kategori</label>
                        <input name="name" type="text" placeholder="Contoh: PlayStation 5" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Deskripsi Singkat</label>
                        <input name="description" type="text" placeholder="Konsol generasi terbaru resolusi 4K 120Hz" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none">
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-orange-600 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-600/25 hover:bg-orange-500 transition">
                        Simpan Kategori
                    </button>
                </form>
            </div>

            <!-- Categories List Table -->
            <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
                <h3 class="text-sm font-bold text-white mb-4">Daftar Kategori Terdaftar</h3>
                <div class="space-y-2">
                    @forelse($categories as $category)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-950/60 border border-white/5">
                            <div>
                                <div class="font-bold text-white text-xs">{{ $category->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">/{{ $category->slug }}</div>
                            </div>
                            <span class="rounded-full bg-slate-800 border border-white/10 px-2.5 py-0.5 text-[10px] text-slate-300">
                                {{ $category->units_count ?? $category->units()->count() }} Unit Terhubung
                            </span>
                        </div>
                    @empty
                        <div class="text-xs text-slate-500 py-4 text-center">Belum ada kategori terdaftar.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Section 2: Combos Management (Feature #5) -->
        <div class="space-y-6">
            <!-- Add Combo Form -->
            <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
                <h2 class="text-base font-bold text-white mb-1 flex items-center gap-2">
                    <span><i class="fa-solid fa-bowl-food"></i></span> Buat Paket Bundling Bakso Combo
                </h2>
                <p class="text-xs text-slate-400 mb-4">Paket hemat all-in-one konsol + stik ekstra + durasi multi-hari.</p>

                <form method="POST" action="/admin/combos" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Paket Combo</label>
                        <input name="name" type="text" placeholder="Contoh: Bakso Mabar Package" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Durasi (Hari)</label>
                            <input name="duration_days" type="number" min="1" max="5" value="3" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Jml Controller</label>
                            <input name="controller_count" type="number" min="1" max="4" value="4" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Harga Paket Total (Rp)</label>
                        <input name="price" type="number" placeholder="150000" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Deskripsi Keuntungan</label>
                        <textarea name="description" rows="2" placeholder="Include 4 Controller DualSense Original, durasi 3 hari full tanpa biaya tambahan." class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none"></textarea>
                    </div>

                    <button type="submit" class="w-full rounded-xl bg-amber-600 py-2.5 text-xs font-bold text-white shadow-lg shadow-amber-600/25 hover:bg-amber-500 transition">
                        Simpan Paket Combo
                    </button>
                </form>
            </div>

            <!-- Combos List Table -->
            <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
                <h3 class="text-sm font-bold text-white mb-4">Daftar Paket Bundling Aktif</h3>
                <div class="space-y-3">
                    @forelse($combos as $combo)
                        <div class="p-4 rounded-2xl bg-slate-950/60 border border-white/5 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="font-bold text-white text-sm">{{ $combo->name }}</div>
                                <span class="font-bold text-amber-400 text-xs">Rp {{ number_format($combo->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-[10px]">
                                <span class="rounded bg-orange-500/20 text-orange-400 border border-orange-500/30 px-2 py-0.5 font-bold">
                                    <i class="fa-solid fa-hourglass-half"></i> {{ $combo->duration_days }} Hari Sewa
                                </span>
                                <span class="rounded bg-blue-500/20 text-blue-400 border border-blue-500/30 px-2 py-0.5 font-bold">
                                    <i class="fa-solid fa-gamepad"></i> {{ $combo->controller_count }} Controller
                                </span>
                            </div>
                            @if($combo->description)
                                <p class="text-[11px] text-slate-400">{{ $combo->description }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-xs text-slate-500 py-4 text-center">Belum ada paket bundling Bakso Combo.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

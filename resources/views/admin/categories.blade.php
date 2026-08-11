<x-layouts.admin title="Kategori & Paket Combo">
    <x-slot:header>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
            <div>
                <h1 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-tags text-[#f95721]"></i> Kelola Kategori & Paket Bundling Bakso Combo
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Kelola kategori platform konsol dan racik paket bundling sewa hemat lengkap dengan stik ekstra.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="openModal('add-category-modal')" type="button" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-[#f95721] to-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-500/25 hover:brightness-110 active:scale-95 transition cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Kategori</span>
                </button>
                <button onclick="openModal('add-combo-modal')" type="button" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:border-orange-500 hover:text-[#f95721] transition shadow-sm cursor-pointer">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                    <span>+ Buat Combo</span>
                </button>
            </div>
        </div>
    </x-slot:header>

    <div class="grid gap-8 lg:grid-cols-2">
        <!-- Section 1: Categories Management -->
        <div class="space-y-6">
            <!-- Categories List Table -->
            <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-6 shadow-xl backdrop-blur-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-list text-[#f95721]"></i> Daftar Kategori Platform ({{ $categories->count() }})
                    </h3>
                </div>
                <div class="space-y-3">
                    @forelse($categories as $category)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-white/5 hover:border-orange-500/30 transition">
                            <div>
                                <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $category->name }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $category->description ?: 'Tanpa deskripsi' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono mt-1">/{{ $category->slug }}</div>
                            </div>
                            <span class="rounded-full bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-white/10 px-3 py-1 text-[10px] font-bold text-slate-700 dark:text-slate-300">
                                {{ $category->units_count ?? $category->units()->count() }} Unit Terhubung
                            </span>
                        </div>
                    @empty
                        <div class="text-xs text-slate-500 py-8 text-center">Belum ada kategori terdaftar. Klik "+ Tambah Kategori" untuk menambahkan.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Section 2: Combos Management -->
        <div class="space-y-6">
            <!-- Combos List Table -->
            <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-6 shadow-xl backdrop-blur-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-cubes text-amber-500"></i> Daftar Paket Bundling Aktif ({{ $combos->count() }})
                    </h3>
                </div>
                <div class="space-y-3">
                    @forelse($combos as $combo)
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-white/5 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="font-bold text-slate-900 dark:text-white text-sm">{{ $combo->name }}</div>
                                <span class="font-bold font-mono text-[#f95721] text-sm">Rp {{ number_format($combo->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-[10px]">
                                <span class="rounded-lg bg-orange-500/15 text-orange-600 dark:text-orange-400 border border-orange-500/30 px-2 py-0.5 font-bold">
                                    <i class="fa-solid fa-hourglass-half"></i> {{ $combo->duration_days }} Hari Sewa
                                </span>
                                <span class="rounded-lg bg-blue-500/15 text-blue-600 dark:text-blue-400 border border-blue-500/30 px-2 py-0.5 font-bold">
                                    <i class="fa-solid fa-gamepad"></i> {{ $combo->controller_count }} Controller
                                </span>
                            </div>
                            @if($combo->description)
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">{{ $combo->description }}</p>
                            @endif
                        </div>
                    @empty
                        <div class="text-xs text-slate-500 py-8 text-center">Belum ada paket bundling Bakso Combo. Klik "+ Buat Combo" untuk membuat paket baru.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ADD CATEGORY DYNAMIC MODAL -->
    <x-modal name="add-category-modal" title="Tambah Kategori Platform" subtitle="Kelompokkan konsol berdasarkan platform." icon="fa-solid fa-tags" maxWidth="md">
        <form method="POST" action="/admin/categories" class="space-y-4 text-left">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Nama Kategori</label>
                <input name="name" type="text" placeholder="Contoh: PlayStation 5" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Deskripsi Singkat</label>
                <input name="description" type="text" placeholder="Konsol generasi terbaru resolusi 4K 120Hz" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none">
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button onclick="closeModal('add-category-modal')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-[#f95721] px-5 py-2 text-xs font-bold text-white shadow-md shadow-orange-500/25 hover:brightness-110 transition">
                    + Simpan Kategori
                </button>
            </div>
        </form>
    </x-modal>

    <!-- ADD COMBO DYNAMIC MODAL -->
    <x-modal name="add-combo-modal" title="Buat Paket Bundling Bakso Combo" subtitle="Paket sewa hemat konsol + stik ekstra + durasi multi-hari." icon="fa-solid fa-layer-group" maxWidth="lg">
        <form method="POST" action="/admin/combos" class="space-y-4 text-left">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Nama Paket Combo</label>
                <input name="name" type="text" placeholder="Contoh: Bakso Mabar Package" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:outline-none" required>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Durasi (Hari)</label>
                    <input name="duration_days" type="number" min="1" max="7" value="3" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Jumlah Controller</label>
                    <input name="controller_count" type="number" min="1" max="4" value="4" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Harga Paket Total (Rp)</label>
                <input name="price" type="number" placeholder="150000" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">Deskripsi Keuntungan</label>
                <textarea name="description" rows="2" placeholder="Include 4 Controller DualSense Original, durasi 3 hari full tanpa biaya tambahan." class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-2.5 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none"></textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button onclick="closeModal('add-combo-modal')" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-4 py-2 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-white/10 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="rounded-xl bg-[#f95721] px-5 py-2 text-xs font-bold text-white shadow-md shadow-orange-500/25 hover:brightness-110 transition">
                    + Simpan Paket Combo
                </button>
            </div>
        </form>
    </x-modal>
</x-layouts.admin>

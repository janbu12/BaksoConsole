<x-layouts.admin title="Dashboard & Insight Bisnis">
    <x-slot:header><i class="fa-solid fa-chart-simple"></i> Executive Dashboard & Insight Bisnis</x-slot:header>
    <x-slot:subtitle>Pantau performa rental, intensitas heatmap peminjaman harian, dan metrik operasional.</x-slot:subtitle>

    <!-- Top Executive KPIs -->
    <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
        @php
            $kpis = [
                ['label' => 'Total Pendapatan', 'val' => 'Rp ' . number_format($stats['Total Pendapatan'] ?? 0, 0, ',', '.'), 'icon' => 'fa-sack-dollar', 'color' => 'text-amber-500 dark:text-amber-400', 'sub' => 'Dari semua transaksi lunas'],
                ['label' => 'Rental Aktif', 'val' => $stats['Unit Aktif Disewa'] ?? 0, 'icon' => 'fa-hourglass-half', 'color' => 'text-orange-500 dark:text-orange-400', 'sub' => 'Unit berada di tangan pelanggan'],
                ['label' => 'Unit Tersedia', 'val' => $stats['Unit Tersedia'] ?? 0, 'icon' => 'fa-gamepad', 'color' => 'text-blue-600 dark:text-blue-400', 'sub' => 'Siap langsung disewa'],
                ['label' => 'Total Pelanggan', 'val' => $stats['Total Anggota'] ?? 0, 'icon' => 'fa-users', 'color' => 'text-emerald-600 dark:text-emerald-400', 'sub' => 'Member terdaftar'],
            ];
        @endphp

        @foreach($kpis as $kpi)
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-4 shadow-sm transition hover:border-orange-500/30 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 leading-tight">{{ $kpi['label'] }}</span>
                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/5">
                        <i class="fa-solid {{ $kpi['icon'] }} text-sm {{ $kpi['color'] }}"></i>
                    </div>
                </div>
                <div class="mt-3 text-xl sm:text-2xl font-black text-slate-900 dark:text-white truncate">{{ $kpi['val'] }}</div>
                <div class="mt-1 text-[10px] text-slate-500 dark:text-slate-400">{{ $kpi['sub'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- Highlight Widgets: Popular Unit, Active Member, Delivery Mix -->
    <div class="mt-5 grid gap-4 grid-cols-1 sm:grid-cols-3">
        <!-- Popular Unit -->
        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-4 shadow-sm">
            <div class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-gamepad text-orange-500"></i> Unit Paling Laris
            </div>
            <div class="mt-2 text-base sm:text-lg font-bold text-slate-900 dark:text-white truncate">{{ $popularUnit?->name ?? 'Belum ada data' }}</div>
            <div class="text-xs text-orange-500 dark:text-orange-400 font-semibold mt-0.5">{{ $popularUnit?->rentals_count ?? 0 }} Total Penyewaan</div>
        </div>

        <!-- Active Member -->
        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-4 shadow-sm">
            <div class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-user text-emerald-500"></i> Anggota Paling Aktif
            </div>
            <div class="mt-2 text-base sm:text-lg font-bold text-slate-900 dark:text-white truncate">{{ $activeMember?->name ?? 'Belum ada data' }}</div>
            <div class="text-xs text-emerald-500 dark:text-emerald-400 font-semibold mt-0.5">{{ $activeMember?->rentals_count ?? 0 }} Kali Sewa</div>
        </div>

        <!-- Delivery vs Pickup Mix -->
        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-4 shadow-sm">
            <div class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                <i class="fa-solid fa-truck text-blue-500"></i> Delivery vs Pickup Mix
            </div>
            <div class="mt-2 flex items-center gap-2 text-sm font-bold text-slate-900 dark:text-white">
                <span class="text-orange-500">{{ $deliveryMix['delivery_percent'] ?? 50 }}%</span>
                <span class="text-slate-400 font-normal text-xs">Antar</span>
                <span class="text-slate-400">/</span>
                <span class="text-blue-500">{{ $deliveryMix['pickup_percent'] ?? 50 }}%</span>
                <span class="text-slate-400 font-normal text-xs">Pickup</span>
            </div>
            <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800 flex">
                <div style="width: {{ $deliveryMix['delivery_percent'] ?? 50 }}%" class="bg-gradient-to-r from-orange-500 to-amber-500"></div>
                <div style="width: {{ $deliveryMix['pickup_percent'] ?? 50 }}%" class="bg-blue-500"></div>
            </div>
        </div>
    </div>

    <!-- Rental Heatmap & Peak Rental Period -->
    <div class="mt-5 rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-5 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 dark:border-white/10 pb-4 mb-5">
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-fire text-orange-500"></i> Rental Heatmap & Peak Period Intensity
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Intensitas transaksi rental berdasarkan tanggal mulai sewa konsol.</p>
            </div>
            @if($peakDay)
                <div class="inline-flex items-center gap-2 rounded-xl bg-orange-500/10 border border-orange-500/20 px-3 py-1.5 text-xs text-orange-600 dark:text-orange-300 shrink-0">
                    <i class="fa-solid fa-bolt"></i>
                    <span class="font-bold">Peak:</span>
                    <span>{{ \Carbon\Carbon::parse($peakDay->start_date)->format('d M Y') }} ({{ $peakDay->total }} rental)</span>
                </div>
            @endif
        </div>

        @if($heatmap->isEmpty())
            <div class="py-8 text-center text-xs text-slate-500">Belum ada riwayat transaksi rental yang tercatat.</div>
        @else
            <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-7 gap-2">
                @foreach($heatmap as $item)
                    @php
                        $intensityClass = match(true) {
                            $item->total >= 5 => 'bg-orange-500 text-white font-bold shadow-md shadow-orange-500/30 border-orange-400',
                            $item->total >= 3 => 'bg-amber-400/80 text-white font-semibold border-amber-400/50',
                            $item->total >= 2 => 'bg-orange-500/30 text-orange-700 dark:text-orange-200 border-orange-500/30',
                            default => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-white/10',
                        };
                    @endphp
                    <div class="flex flex-col items-center justify-center p-2.5 rounded-xl border {{ $intensityClass }} text-center transition hover:scale-105 cursor-default">
                        <div class="text-[9px] uppercase tracking-wide opacity-80 leading-tight">
                            {{ \Carbon\Carbon::parse($item->start_date)->format('D, d M') }}
                        </div>
                        <div class="text-xl font-black mt-0.5">{{ $item->total }}</div>
                        <div class="text-[9px] opacity-75">rental</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Quick Operations Shortcuts -->
    <div class="mt-5">
        <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
            <i class="fa-solid fa-bolt text-orange-500"></i> Akses Cepat Modul Operasional
        </h2>
        <div class="grid gap-3 grid-cols-2 lg:grid-cols-4">
            @php $shortcuts = [
                ['route' => 'admin.units',      'icon' => 'fa-gamepad',     'label' => 'Kelola Unit Konsol',       'desc' => 'Tambah unit, ubah tarif & status.'],
                ['route' => 'admin.bookings',   'icon' => 'fa-calendar',    'label' => 'Reservasi Masuk',          'desc' => 'Konfirmasi booking & perpanjangan.'],
                ['route' => 'admin.returns',    'icon' => 'fa-rotate-left', 'label' => 'Pengembalian & Denda',     'desc' => 'Proses return & denda otomatis.'],
                ['route' => 'admin.deliveries', 'icon' => 'fa-truck',       'label' => 'Pickup & Delivery',        'desc' => 'Tugaskan kurir & update status kirim.'],
            ]; @endphp

            @foreach($shortcuts as $sc)
                <a href="{{ route($sc['route']) }}" class="group rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/80 p-4 transition hover:border-orange-500/40 hover:shadow-md hover:shadow-orange-500/5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-500/10 border border-orange-500/20 mb-3">
                        <i class="fa-solid {{ $sc['icon'] }} text-orange-500 text-sm"></i>
                    </div>
                    <div class="font-bold text-slate-900 dark:text-white text-xs group-hover:text-orange-500 transition">{{ $sc['label'] }}</div>
                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 leading-tight">{{ $sc['desc'] }}</div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Recent Transactions Snapshot -->
    <div class="mt-5 rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-white/10">
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-file-invoice text-orange-500"></i> Transaksi & Rental Terbaru
                </h2>
                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Snapshot 5 aktivitas rental terbaru.</p>
            </div>
            <a href="{{ route('admin.history') }}" class="text-xs font-bold text-orange-500 hover:text-orange-600 transition shrink-0">
                Lihat Semua →
            </a>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="px-5 py-3">Kode Rental</th>
                        <th class="px-5 py-3">Pelanggan</th>
                        <th class="px-5 py-3">Unit</th>
                        <th class="px-5 py-3">Durasi</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Total Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-200">
                    @forelse($recentRentals as $rental)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition">
                            <td class="px-5 py-3 font-mono font-bold text-orange-500">#{{ $rental->rental_code }}</td>
                            <td class="px-5 py-3 font-medium">{{ $rental->user->name }}</td>
                            <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $rental->unit->name }}</td>
                            <td class="px-5 py-3 text-slate-600 dark:text-slate-300">{{ $rental->duration_days }} Hari</td>
                            <td class="px-5 py-3">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase border
                                    @if($rental->status->value === 'active') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30
                                    @elseif($rental->status->value === 'overdue') bg-red-500/15 text-red-700 dark:text-red-300 border-red-500/30
                                    @elseif($rental->status->value === 'returned') bg-blue-500/15 text-blue-700 dark:text-blue-300 border-blue-500/30
                                    @else bg-slate-200 dark:bg-slate-500/20 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-white/10 @endif">
                                    {{ $rental->status->value }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right font-bold text-slate-900 dark:text-white">
                                Rp {{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-xs text-slate-500">Belum ada transaksi tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden divide-y divide-slate-100 dark:divide-white/5">
            @forelse($recentRentals as $rental)
                <div class="px-4 py-3.5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-orange-500 text-xs">#{{ $rental->rental_code }}</span>
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase border
                            @if($rental->status->value === 'active') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30
                            @elseif($rental->status->value === 'overdue') bg-red-500/15 text-red-700 dark:text-red-300 border-red-500/30
                            @elseif($rental->status->value === 'returned') bg-blue-500/15 text-blue-700 dark:text-blue-300 border-blue-500/30
                            @else bg-slate-200 dark:bg-slate-500/20 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-white/10 @endif">
                            {{ $rental->status->value }}
                        </span>
                    </div>
                    <div class="text-xs font-semibold text-slate-900 dark:text-white">{{ $rental->user->name }}</div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-slate-500 dark:text-slate-400">{{ $rental->unit->name }} · {{ $rental->duration_days }} Hari</span>
                        <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-xs text-slate-500">Belum ada transaksi tercatat.</div>
            @endforelse
        </div>
    </div>
</x-layouts.admin>

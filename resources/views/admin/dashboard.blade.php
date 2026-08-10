<x-layouts.admin title="Dashboard & Insight Bisnis">
    <x-slot:header><i class="fa-solid fa-chart-simple"></i> Executive Dashboard & Insight Bisnis</x-slot:header>
    <x-slot:subtitle>Pantau performa rental, intensitas heatmap peminjaman harian, dan metrik operasional.</x-slot:subtitle>

    <!-- Top Executive KPIs -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @php
            $kpis = [
                ['label' => 'Total Pendapatan', 'val' => 'Rp ' . number_format($stats['Total Pendapatan'] ?? 0, 0, ',', '.'), 'icon' => '<i class="fa-solid fa-sack-dollar"></i>', 'color' => 'text-amber-400', 'sub' => 'Dari semua transaksi lunas'],
                ['label' => 'Rental Sedang Aktif', 'val' => $stats['Unit Aktif Disewa'] ?? 0, 'icon' => '<i class="fa-solid fa-hourglass-half"></i>', 'color' => 'text-orange-400', 'sub' => 'Unit berada di tangan pelanggan'],
                ['label' => 'Unit Tersedia', 'val' => $stats['Unit Tersedia'] ?? 0, 'icon' => '<i class="fa-solid fa-gamepad"></i>', 'color' => 'text-blue-400', 'sub' => 'Siap langsung disewa'],
                ['label' => 'Total Pelanggan', 'val' => $stats['Total Anggota'] ?? 0, 'icon' => '<i class="fa-solid fa-users"></i>', 'color' => 'text-emerald-400', 'sub' => 'Member terdaftar di sistem'],
            ];
        @endphp

        @foreach($kpis as $kpi)
            <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 shadow-xl transition hover:border-orange-500/30">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $kpi['label'] }}</span>
                    <span class="text-2xl">{!! $kpi['icon'] !!}</span>
                </div>
                <div class="mt-3 text-2xl font-black {{ $kpi['color'] }}">{{ $kpi['val'] }}</div>
                <div class="mt-1 text-[11px] text-slate-400">{{ $kpi['sub'] }}</div>
            </div>
        @endforeach
    </div>

    <!-- Highlight Widgets: Popular Unit, Active Member, Delivery Ratio -->
    <div class="mt-6 grid gap-4 md:grid-cols-3">
        <!-- Popular Unit -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 shadow-xl">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider"><i class="fa-solid fa-gamepad"></i> Unit Paling Laris</div>
            <div class="mt-2 text-xl font-bold text-white">{{ $popularUnit?->name ?? 'Belum ada data' }}</div>
            <div class="text-xs text-orange-400 font-semibold mt-0.5">
                {{ $popularUnit?->rentals_count ?? 0 }} Total Penyewaan
            </div>
        </div>

        <!-- Active Member -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 shadow-xl">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider"><i class="fa-solid fa-user"></i> Anggota paling aktif</div>
            <div class="mt-2 text-xl font-bold text-white">{{ $activeMember?->name ?? 'Belum ada data' }}</div>
            <div class="text-xs text-emerald-400 font-semibold mt-0.5">
                {{ $activeMember?->rentals_count ?? 0 }} Kali Sewa
            </div>
        </div>

        <!-- Delivery vs Pickup Mix (Feature #24 Analytics) -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 shadow-xl">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider"><i class="fa-solid fa-truck"></i> Delivery vs Pickup Mix</div>
            <div class="mt-2 text-xl font-bold text-white">
                {{ $deliveryMix['delivery_percent'] ?? 50 }}% <span class="text-xs text-slate-400 font-normal">Antar Kurir</span> / {{ $deliveryMix['pickup_percent'] ?? 50 }}% <span class="text-xs text-slate-400 font-normal">Pickup</span>
            </div>
            <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-800 flex">
                <div style="width: {{ $deliveryMix['delivery_percent'] ?? 50 }}%" class="bg-gradient-to-r from-orange-500 to-amber-500"></div>
                <div style="width: {{ $deliveryMix['pickup_percent'] ?? 50 }}%" class="bg-blue-500"></div>
            </div>
        </div>
    </div>

    <!-- Rental Heatmap & Peak Rental Period (Feature #20 & #21) -->
    <div class="mt-8 rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-4">
            <div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-fire"></i> Rental Heatmap & Peak Period Intensity
                </h2>
                <p class="text-xs text-slate-400">Intensitas transaksi rental berdasarkan tanggal mulai sewa konsol.</p>
            </div>
            @if($peakDay)
                <div class="inline-flex items-center gap-2 rounded-xl bg-orange-500/20 border border-orange-500/30 px-3.5 py-1.5 text-xs text-orange-300">
                    <span class="font-bold"><i class="fa-solid fa-bolt"></i> Peak Day:</span>
                    <span>{{ \Carbon\Carbon::parse($peakDay->start_date)->format('d M Y') }} ({{ $peakDay->total }} rental)</span>
                </div>
            @endif
        </div>

        <div class="mt-6">
            @if($heatmap->isEmpty())
                <div class="py-8 text-center text-xs text-slate-500">Belum ada riwayat transaksi rental yang tercatat.</div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-7 gap-3">
                    @foreach($heatmap as $item)
                        @php
                            $intensityClass = match(true) {
                                $item->total >= 5 => 'bg-orange-500 text-white font-bold shadow-lg shadow-orange-500/30 border-orange-400',
                                $item->total >= 3 => 'bg-amber-500/80 text-white font-semibold border-amber-400/50',
                                $item->total >= 2 => 'bg-orange-600/50 text-orange-200 border-orange-500/30',
                                default => 'bg-slate-800 text-slate-300 border-white/10',
                            };
                        @endphp
                        <div class="flex flex-col items-center justify-center p-3 rounded-2xl border {{ $intensityClass }} text-center transition hover:scale-105">
                            <div class="text-[10px] uppercase tracking-wider opacity-80">
                                {{ \Carbon\Carbon::parse($item->start_date)->format('D, d M') }}
                            </div>
                            <div class="text-lg font-black mt-0.5">{{ $item->total }}</div>
                            <div class="text-[9px] opacity-75">Rental Mulai</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Operations Shortcuts -->
    <div class="mt-8">
        <h2 class="text-lg font-bold text-white mb-4"><i class="fa-solid fa-bolt"></i> Akses Cepat Modul Operasional</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('admin.units') }}" class="group rounded-3xl border border-white/10 bg-slate-900/80 p-5 transition hover:border-orange-500/40 hover:bg-slate-900">
                <div class="text-3xl mb-2"><i class="fa-solid fa-gamepad"></i></div>
                <div class="font-bold text-white group-hover:text-orange-400 transition">Kelola Unit Konsol</div>
                <div class="text-xs text-slate-400 mt-1">Tambah unit, ubah tarif, kapasitas mabar & status ketersediaan.</div>
            </a>

            <a href="{{ route('admin.bookings') }}" class="group rounded-3xl border border-white/10 bg-slate-900/80 p-5 transition hover:border-orange-500/40 hover:bg-slate-900">
                <div class="text-3xl mb-2"><i class="fa-regular fa-calendar"></i></div>
                <div class="font-bold text-white group-hover:text-orange-400 transition">Reservasi Masuk</div>
                <div class="text-xs text-slate-400 mt-1">Konfirmasi booking dan review pengajuan perpanjangan sewa.</div>
            </a>

            <a href="{{ route('admin.returns') }}" class="group rounded-3xl border border-white/10 bg-slate-900/80 p-5 transition hover:border-orange-500/40 hover:bg-slate-900">
                <div class="text-3xl mb-2"><i class="fa-solid fa-rotate-left"></i></div>
                <div class="font-bold text-white group-hover:text-orange-400 transition">Pengembalian & Denda</div>
                <div class="text-xs text-slate-400 mt-1">Proses return unit, denda telat otomatis, & denda kerusakan.</div>
            </a>

            <a href="{{ route('admin.deliveries') }}" class="group rounded-3xl border border-white/10 bg-slate-900/80 p-5 transition hover:border-orange-500/40 hover:bg-slate-900">
                <div class="text-3xl mb-2"><i class="fa-solid fa-truck"></i></div>
                <div class="font-bold text-white group-hover:text-orange-400 transition">Pickup & Delivery</div>
                <div class="text-xs text-slate-400 mt-1">Tugaskan nama kurir, atur ongkir, dan update status kirim.</div>
            </a>
        </div>
    </div>

    <!-- Recent Transactions Snapshot -->
    <div class="mt-8 rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-white"><i class="fa-solid fa-file-invoice"></i> Transaksi & Rental Terbaru</h2>
                <p class="text-xs text-slate-400">Snapshot 5 aktivitas rental terbaru.</p>
            </div>
            <a href="{{ route('admin.history') }}" class="text-xs font-bold text-orange-400 hover:underline">
                Lihat Semua Riwayat →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-white/10 bg-slate-950/60 text-slate-400 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3">Kode Rental</th>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Unit</th>
                        <th class="p-3">Durasi</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Total Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                    @forelse($recentRentals as $rental)
                        <tr>
                            <td class="p-3 font-mono font-bold text-orange-400">#{{ $rental->rental_code }}</td>
                            <td class="p-3 font-medium">{{ $rental->user->name }}</td>
                            <td class="p-3">{{ $rental->unit->name }}</td>
                            <td class="p-3">{{ $rental->duration_days }} Hari</td>
                            <td class="p-3">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase
                                    @if($rental->status->value === 'active') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                                    @elseif($rental->status->value === 'overdue') bg-red-500/20 text-red-300 border border-red-500/30
                                    @elseif($rental->status->value === 'returned') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                    @else bg-slate-500/20 text-slate-300 @endif">
                                    {{ $rental->status->value }}
                                </span>
                            </td>
                            <td class="p-3 text-right font-bold text-white">
                                Rp {{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-500">Belum ada transaksi tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>

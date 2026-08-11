<x-layouts.admin title="Laporan & Riwayat Transaksi">
    <x-slot:header><i class="fa-solid fa-file-invoice"></i> Laporan Rekapitulasi Riwayat & Transaksi</x-slot:header>
    <x-slot:subtitle>Rekapitulasi seluruh transaksi sewa konsol, kalkulasi denda, dan pencetakan laporan resmi.</x-slot:subtitle>

    <div class="space-y-4">
        <!-- Summary Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 px-5 py-4 shadow-sm">
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white">Ringkasan Laporan Operasional</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total {{ $rentals->count() }} transaksi rental tercatat di dalam database sistem.</p>
            </div>
            <a href="{{ route('admin.history.print') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-orange-500/25 hover:bg-orange-400 transition shrink-0">
                <i class="fa-solid fa-print"></i>
                <span>Cetak / Simpan PDF</span>
            </a>
        </div>

        <!-- Desktop Table (md+) -->
        <div class="hidden md:block rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Kode & Invoice</th>
                            <th class="px-4 py-3">Pelanggan</th>
                            <th class="px-4 py-3">Unit Konsol</th>
                            <th class="px-4 py-3">Periode Sewa</th>
                            <th class="px-4 py-3">Rincian Biaya</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Total / Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-200">
                        @forelse($rentals as $rental)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition">
                                <td class="px-4 py-3.5">
                                    <div class="font-mono font-bold text-orange-500">#{{ $rental->rental_code }}</div>
                                    <div class="font-mono text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">{{ $rental->transaction?->invoice_number ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ $rental->user->name }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400"><i class="fa-solid fa-phone"></i> {{ $rental->user->profile?->phone ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ $rental->unit->name }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $rental->unit->code }}</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="text-slate-700 dark:text-slate-300">{{ $rental->start_date->format('d M Y') }} – {{ $rental->due_date->format('d M Y') }}</div>
                                    <div class="text-[10px] text-amber-600 dark:text-amber-400 font-bold mt-0.5">{{ $rental->duration_days }} Hari Sewa</div>
                                </td>
                                <td class="px-4 py-3.5 space-y-0.5">
                                    <div class="text-slate-700 dark:text-slate-300">Sewa: <span class="font-semibold">Rp {{ number_format($rental->subtotal, 0, ',', '.') }}</span></div>
                                    @if(($rental->transaction?->fine_amount ?? 0) > 0)
                                        <div class="text-red-500 dark:text-red-400 font-semibold text-[10px]">+ Denda: Rp {{ number_format($rental->transaction->fine_amount, 0, ',', '.') }}</div>
                                    @endif
                                    @if(($rental->transaction?->delivery_fee ?? 0) > 0)
                                        <div class="text-blue-500 dark:text-blue-400 font-semibold text-[10px]">+ Ongkir: Rp {{ number_format($rental->transaction->delivery_fee, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase border
                                        @if($rental->status->value === 'returned') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30
                                        @elseif($rental->status->value === 'active') bg-blue-500/15 text-blue-700 dark:text-blue-300 border-blue-500/30
                                        @elseif($rental->status->value === 'overdue') bg-red-500/15 text-red-700 dark:text-red-300 border-red-500/30
                                        @else bg-slate-200 dark:bg-slate-500/20 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-white/10 @endif">
                                        {{ $rental->status->value }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <div class="font-black text-slate-900 dark:text-white">
                                        Rp {{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}
                                    </div>
                                    @if($rental->status->value === 'returned')
                                        @php
                                            $hasFine = (float)($rental->transaction?->fine_amount ?? 0) > 0;
                                            $returnPickupFee = (float) $rental->deliveries->where('type.value', 'delivery_return')->where('method.value', 'delivery')->sum('delivery_fee');
                                            $hasAdditional = $hasFine || $returnPickupFee > 0;
                                            $alreadyConfirmed = $rental->transaction?->notes && str_contains($rental->transaction->notes, 'dibayar cash');
                                        @endphp
                                        @if($hasAdditional)
                                            @if($alreadyConfirmed)
                                                <span class="mt-1 inline-block rounded-full bg-emerald-500/15 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30 px-2 py-0.5 text-[9px] font-bold">✓ Cash OK</span>
                                            @else
                                                @php
                                                    $labelParts = [];
                                                    if($hasFine) $labelParts[] = 'Denda Rp' . number_format($rental->transaction->fine_amount, 0, ',', '.');
                                                    if($returnPickupFee > 0) $labelParts[] = 'Ongkir Rp' . number_format($returnPickupFee, 0, ',', '.');
                                                    $confirmLabel = implode(' + ', $labelParts);
                                                @endphp
                                                <form method="POST" action="/admin/rentals/{{ $rental->id }}/confirm-fine-paid" class="mt-1.5">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Konfirmasi {{ $confirmLabel }} sudah diterima secara cash?');"
                                                        class="rounded-lg bg-amber-500/15 border border-amber-500/30 px-2 py-1 text-[9px] font-bold text-amber-600 dark:text-amber-300 hover:bg-amber-500 hover:text-white transition cursor-pointer">
                                                        <i class="fa-solid fa-sack-dollar"></i> Konfirmasi Cash
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-xs text-slate-500">
                                    <i class="fa-solid fa-folder-open text-2xl mb-2 block opacity-30"></i>
                                    Belum ada riwayat transaksi rental yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card List (< md) -->
        <div class="md:hidden space-y-3">
            @forelse($rentals as $rental)
                <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 overflow-hidden shadow-sm">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-white/10">
                        <div>
                            <span class="font-mono font-bold text-orange-500 text-sm">#{{ $rental->rental_code }}</span>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-0.5">{{ $rental->transaction?->invoice_number ?? '-' }}</div>
                        </div>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase border
                            @if($rental->status->value === 'returned') bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30
                            @elseif($rental->status->value === 'active') bg-blue-500/15 text-blue-700 dark:text-blue-300 border-blue-500/30
                            @elseif($rental->status->value === 'overdue') bg-red-500/15 text-red-700 dark:text-red-300 border-red-500/30
                            @else bg-slate-200 dark:bg-slate-500/20 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-white/10 @endif">
                            {{ $rental->status->value }}
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="px-4 py-3 space-y-2.5 text-xs">
                        <div class="font-semibold text-slate-900 dark:text-white">{{ $rental->user->name }}</div>
                        <div class="text-slate-600 dark:text-slate-300">{{ $rental->unit->name }} <span class="text-slate-400">({{ $rental->unit->code }})</span></div>
                        <div class="text-slate-500 dark:text-slate-400">{{ $rental->start_date->format('d M Y') }} – {{ $rental->due_date->format('d M Y') }} · <span class="text-amber-600 dark:text-amber-400 font-bold">{{ $rental->duration_days }} Hari</span></div>

                        <div class="rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-white/10 p-3 space-y-1">
                            <div>Sewa: <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($rental->subtotal, 0, ',', '.') }}</span></div>
                            @if(($rental->transaction?->fine_amount ?? 0) > 0)
                                <div class="text-red-500 font-semibold">+ Denda: Rp {{ number_format($rental->transaction->fine_amount, 0, ',', '.') }}</div>
                            @endif
                            @if(($rental->transaction?->delivery_fee ?? 0) > 0)
                                <div class="text-blue-500 font-semibold">+ Ongkir: Rp {{ number_format($rental->transaction->delivery_fee, 0, ',', '.') }}</div>
                            @endif
                            <div class="pt-1 border-t border-slate-200 dark:border-white/10 font-black text-slate-900 dark:text-white">
                                Total: Rp {{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-10 text-center text-xs text-slate-500">
                    Belum ada riwayat transaksi tercatat.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.admin>

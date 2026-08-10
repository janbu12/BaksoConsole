<x-layouts.app title="Riwayat Sewa & Bakso Rank">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="text-xs font-bold uppercase tracking-wider text-orange-400">Loyalitas & Riwayat Transaksi</div>
            <h1 class="text-3xl font-black text-white mt-1">Riwayat Sewa & Bakso Rank</h1>
            <p class="text-sm text-slate-400 mt-0.5">Seluruh catatan penyewaan selesai dan akumulasi hari sewa loyalitasmu.</p>
        </div>
    </div>

    <!-- Bakso Rank Banner -->
    <div class="mt-8 rounded-3xl border border-orange-500/30 bg-gradient-to-r from-orange-950/40 via-slate-900 to-amber-950/40 p-6 sm:p-8 shadow-2xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-500/20 text-3xl border border-orange-500/30 shadow-lg">
                    {{ substr($rank['badge'], 0, 4) }}
                </div>
                <div>
                    <div class="text-xs font-bold uppercase tracking-wider text-orange-400">Tingkatan Loyalitas Saat Ini:</div>
                    <h2 class="text-2xl sm:text-3xl font-black text-white mt-0.5">{{ $rank['name'] }}</h2>
                    <div class="text-xs text-slate-300 mt-1">
                        Akumulasi: <b class="text-white">{{ $days }} Total Hari Sewa</b> &middot; {{ $rank['benefit'] }}
                    </div>
                </div>
            </div>

            @if($rank['next_rank'])
                <div class="w-full md:w-72 rounded-2xl bg-slate-950/80 p-4 border border-white/10">
                    <div class="flex justify-between text-xs mb-1.5">
                        <span class="text-slate-400">Menuju <b class="text-white">{{ $rank['next_rank'] }}</b></span>
                        <span class="font-bold text-orange-400">{{ $rank['progress_percent'] }}%</span>
                    </div>
                    <div class="h-2 w-full rounded-full bg-slate-900 overflow-hidden">
                        <div class="h-full rounded-full bg-orange-500" style="width: {{ $rank['progress_percent'] }}%"></div>
                    </div>
                    <div class="text-[11px] text-slate-400 mt-2 text-center">
                        Butuh <b>{{ $rank['days_needed'] }} hari sewa lagi</b>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- History Table -->
    <div class="mt-10">
        <h2 class="text-xl font-bold text-white mb-4">Daftar Transaksi Penyewaan Selesai</h2>

        <div class="overflow-x-auto rounded-3xl border border-white/10 bg-slate-900/90 shadow-2xl">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-white/10 bg-slate-950/60 text-slate-400 uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="p-4 sm:px-6">Kode Sewa & Invoice</th>
                        <th class="p-4 sm:px-6">Unit Konsol</th>
                        <th class="p-4 sm:px-6">Periode Sewa</th>
                        <th class="p-4 sm:px-6">Durasi</th>
                        <th class="p-4 sm:px-6">Rincian Tagihan</th>
                        <th class="p-4 sm:px-6">Total Pembayaran</th>
                        <th class="p-4 sm:px-6">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                    @forelse($rentals as $rental)
                        <tr class="hover:bg-white/5 transition">
                            <td class="p-4 sm:px-6">
                                <div class="font-mono font-bold text-orange-400">{{ $rental->rental_code }}</div>
                                <div class="font-mono text-[11px] text-slate-400">{{ $rental->transaction?->invoice_number }}</div>
                            </td>
                            <td class="p-4 sm:px-6 font-bold text-white">
                                {{ $rental->unit->name }}
                                <span class="block font-mono text-[10px] font-normal text-slate-400">{{ $rental->unit->code }}</span>
                            </td>
                            <td class="p-4 sm:px-6 text-slate-300">
                                <div>{{ $rental->start_date->format('d M Y') }} &ndash; {{ $rental->returned_at ? \Carbon\Carbon::parse($rental->returned_at)->format('d M Y') : $rental->due_date->format('d M Y') }}</div>
                            </td>
                            <td class="p-4 sm:px-6">
                                <span class="font-bold text-white">{{ $rental->duration_days }}</span> Hari
                            </td>
                            <td class="p-4 sm:px-6 space-y-0.5 text-[11px]">
                                <div>Sewa: Rp{{ number_format($rental->subtotal) }}</div>
                                @if($rental->fines->isNotEmpty())
                                    <div class="text-red-400">Denda: +Rp{{ number_format($rental->fines->sum('amount')) }}</div>
                                @endif
                                @if($rental->deliveries->sum('delivery_fee') > 0)
                                    <div class="text-teal-400">Ongkir: +Rp{{ number_format($rental->deliveries->sum('delivery_fee')) }}</div>
                                @endif
                            </td>
                            <td class="p-4 sm:px-6 font-bold text-white text-sm">
                                Rp{{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}
                            </td>
                            <td class="p-4 sm:px-6">
                                <span class="rounded-full bg-emerald-500/20 border border-emerald-500/30 px-2.5 py-1 text-[10px] font-bold text-emerald-300 uppercase">
                                    {{ $rental->transaction?->status->value ?? 'Lunas' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-500">
                                <div class="text-3xl mb-2">📜</div>
                                Belum ada riwayat penyewaan yang telah selesai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $rentals->links() }}
        </div>
    </div>
</x-layouts.app>

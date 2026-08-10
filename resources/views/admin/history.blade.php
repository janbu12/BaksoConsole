<x-layouts.admin title="Laporan & Riwayat Transaksi">
    <x-slot:header><i class="fa-solid fa-file-invoice"></i> Laporan Rekapitulasi Riwayat & Transaksi</x-slot:header>
    <x-slot:subtitle>Rekapitulasi seluruh transaksi sewa konsol, kalkulasi denda, dan pencetakan laporan resmi.</x-slot:subtitle>

    <div class="space-y-6">
        <!-- Top Summary Cards & Print Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
            <div>
                <h2 class="text-base font-bold text-white">Ringkasan Laporan Operasional</h2>
                <p class="text-xs text-slate-400">Total {{ $rentals->count() }} transaksi rental tercatat di dalam database sistem.</p>
            </div>

            <a href="{{ route('admin.history.print') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-orange-600 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-600/25 hover:bg-orange-500 transition">
                <span><i class="fa-solid fa-print"></i> Cetak / Simpan PDF Laporan Resmi</span>
            </a>
        </div>

        <!-- History Table -->
        <div class="overflow-x-auto rounded-3xl border border-white/10 bg-slate-900/90 shadow-2xl">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-white/10 bg-slate-950/60 text-slate-400 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-4">Kode Rental & Invoice</th>
                        <th class="p-4">Pelanggan</th>
                        <th class="p-4">Unit Konsol</th>
                        <th class="p-4">Periode Sewa</th>
                        <th class="p-4">Rincian Biaya</th>
                        <th class="p-4">Status Rental</th>
                        <th class="p-4 text-right">Total Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                    @forelse($rentals as $rental)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="p-4">
                                <div class="font-mono font-bold text-orange-400">#{{ $rental->rental_code }}</div>
                                <div class="font-mono text-[10px] text-slate-400">{{ $rental->transaction?->invoice_number ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-white">{{ $rental->user->name }}</div>
                                <div class="text-[10px] text-slate-400"><i class="fa-solid fa-phone"></i> {{ $rental->user->profile?->phone ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-white">{{ $rental->unit->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $rental->unit->code }}</div>
                            </td>
                            <td class="p-4">
                                <div>{{ $rental->start_date->format('d M Y') }} &ndash; {{ $rental->due_date->format('d M Y') }}</div>
                                <div class="text-[10px] text-amber-400 font-bold mt-0.5">({{ $rental->duration_days }} Hari Sewa)</div>
                            </td>
                            <td class="p-4 text-[11px] text-slate-300 space-y-0.5">
                                <div>Sewa: <span class="font-semibold">Rp {{ number_format($rental->subtotal, 0, ',', '.') }}</span></div>
                                @if(($rental->transaction?->fine_amount ?? 0) > 0)
                                    <div class="text-red-400 font-semibold">+ Denda: Rp {{ number_format($rental->transaction->fine_amount, 0, ',', '.') }}</div>
                                @endif
                                @if(($rental->transaction?->delivery_fee ?? 0) > 0)
                                    <div class="text-blue-400 font-semibold">+ Ongkir: Rp {{ number_format($rental->transaction->delivery_fee, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase
                                    @if($rental->status->value === 'returned') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                                    @elseif($rental->status->value === 'active') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                    @elseif($rental->status->value === 'overdue') bg-red-500/20 text-red-300 border border-red-500/30
                                    @else bg-slate-500/20 text-slate-300 @endif">
                                    {{ $rental->status->value }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-black text-sm text-white">
                                Rp {{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-xs text-slate-500">
                                Belum ada riwayat transaksi rental yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>

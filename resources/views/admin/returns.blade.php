<x-layouts.admin title="Pengembalian & Denda">
    <x-slot:header><i class="fa-solid fa-rotate-left"></i> Manajemen Pengembalian Unit (Return) & Denda</x-slot:header>
    <x-slot:subtitle>Pantau status jatuh tempo sewa, proses pengembalian unit, kalkulasi denda telat otomatis, dan input denda kerusakan.</x-slot:subtitle>

    <div class="space-y-6">
        <div class="overflow-x-auto rounded-3xl border border-white/10 bg-slate-900/90 shadow-2xl">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-white/10 bg-slate-950/60 text-slate-400 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-4">Kode Rental & User</th>
                        <th class="p-4">Unit Konsol</th>
                        <th class="p-4">Jatuh Tempo & Peringatan</th>
                        <th class="p-4">Total Tagihan</th>
                        <th class="p-4">Proses Pengembalian</th>
                        <th class="p-4">Denda Kerusakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                    @forelse($activeRentals as $rental)
                        @php
                            $warn = \App\Domain\Rentals\RentalWarning::details($rental->due_date);
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="p-4">
                                <div class="font-mono font-bold text-orange-400">#{{ $rental->rental_code }}</div>
                                <div class="font-semibold text-white text-xs mt-0.5">{{ $rental->user->name }}</div>
                                <div class="text-[10px] text-slate-400"><i class="fa-solid fa-phone"></i> {{ $rental->user->profile?->phone ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-white">{{ $rental->unit->name }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $rental->unit->code }}</div>
                            </td>
                            <td class="p-4">
                                <div class="text-white font-medium">{{ $rental->due_date->format('d M Y') }}</div>
                                <span class="inline-block mt-1 rounded-full px-2 py-0.5 text-[9px] font-bold uppercase {{ $warn['badge_class'] }}">
                                    {{ $warn['code'] }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-white text-sm">
                                    Rp {{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    Sewa: Rp {{ number_format($rental->subtotal, 0, ',', '.') }}
                                    @if(($rental->transaction?->fine_amount ?? 0) > 0)
                                        <br><span class="text-red-400 font-semibold">+ Denda: Rp {{ number_format($rental->transaction->fine_amount, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Action 1: Process Return Form (Feature #16) -->
                            <td class="p-4">
                                <form method="POST" action="/admin/rentals/{{ $rental->id }}/return" class="space-y-1.5">
                                    @csrf
                                    <input type="hidden" name="returned_at" value="{{ now()->format('Y-m-d') }}">
                                    <div class="flex items-center gap-1.5">
                                        <input name="daily_fine" type="number" value="10000" placeholder="Denda/hr" class="w-20 rounded-lg border border-white/10 bg-slate-950 p-1.5 text-[10px] text-white focus:border-orange-500 focus:outline-none" title="Denda keterlambatan per hari">
                                        <button type="submit" onclick="return confirm('Proses pengembalian unit konsol ini? Sistem akan menghitung denda keterlambatan otomatis.');" class="rounded-lg bg-emerald-600 px-2.5 py-1.5 text-[10px] font-bold text-white hover:bg-emerald-500 transition shadow-md shadow-emerald-600/20">
                                            ✓ Return
                                        </button>
                                    </div>
                                    <input name="return_notes" type="text" placeholder="Kondisi barang..." class="w-full rounded-lg border border-white/10 bg-slate-950 p-1 text-[10px] text-white focus:border-orange-500 focus:outline-none">
                                </form>
                            </td>

                            <!-- Action 2: Manual Damage Fine Form (Feature #17) -->
                            <td class="p-4">
                                <form method="POST" action="/admin/rentals/{{ $rental->id }}/fines" class="space-y-1.5">
                                    @csrf
                                    <div class="flex items-center gap-1.5">
                                        <input name="amount" type="number" placeholder="Nominal Rp" class="w-24 rounded-lg border border-white/10 bg-slate-950 p-1.5 text-[10px] text-white focus:border-orange-500 focus:outline-none" required>
                                        <button type="submit" class="rounded-lg bg-red-600 px-2 py-1.5 text-[10px] font-bold text-white hover:bg-red-500 transition">
                                            + Denda
                                        </button>
                                    </div>
                                    <input name="reason" type="text" placeholder="Alasan (misal: stik pecah)" class="w-full rounded-lg border border-white/10 bg-slate-950 p-1 text-[10px] text-white focus:border-orange-500 focus:outline-none" required>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-xs text-slate-500">
                                Tidak ada rental konsol yang sedang aktif saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($activeRentals, 'links'))
            <div class="mt-4">{{ $activeRentals->links() }}</div>
        @endif
    </div>
</x-layouts.admin>

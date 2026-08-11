<x-layouts.admin title="Pengembalian & Denda">
    <x-slot:header><i class="fa-solid fa-rotate-left"></i> Manajemen Pengembalian Unit & Denda</x-slot:header>
    <x-slot:subtitle>Pantau jatuh tempo, proses pengembalian unit, kalkulasi denda telat otomatis, dan input denda kerusakan.</x-slot:subtitle>

    <div class="space-y-4">

        <!-- Desktop Table (md+) -->
        <div class="hidden md:block rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Kode Rental & User</th>
                            <th class="px-4 py-3">Unit Konsol</th>
                            <th class="px-4 py-3">Jatuh Tempo</th>
                            <th class="px-4 py-3">Total Tagihan</th>
                            <th class="px-4 py-3">Proses Pengembalian</th>
                            <th class="px-4 py-3">Denda Kerusakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-200">
                        @forelse($activeRentals as $rental)
                            @php $warn = \App\Domain\Rentals\RentalWarning::details($rental->due_date); @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition">
                                <td class="px-4 py-3.5">
                                    <div class="font-mono font-bold text-orange-500">#{{ $rental->rental_code }}</div>
                                    <div class="font-semibold text-slate-900 dark:text-white text-xs mt-0.5">{{ $rental->user->name }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400"><i class="fa-solid fa-phone"></i> {{ $rental->user->profile?->phone ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="font-semibold text-slate-900 dark:text-white">{{ $rental->unit->name }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono">{{ $rental->unit->code }}</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="text-slate-900 dark:text-white font-medium">{{ $rental->due_date->format('d M Y') }}</div>
                                    <span class="inline-block mt-1 rounded-full px-2 py-0.5 text-[9px] font-bold uppercase {{ $warn['badge_class'] }}">{{ $warn['code'] }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400">Sewa: Rp {{ number_format($rental->subtotal, 0, ',', '.') }}</div>
                                    @if(($rental->transaction?->fine_amount ?? 0) > 0)
                                        <div class="text-[10px] text-red-500 dark:text-red-400 font-semibold">+ Denda: Rp {{ number_format($rental->transaction->fine_amount, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    <form method="POST" action="/admin/rentals/{{ $rental->id }}/return" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="returned_at" value="{{ now()->format('Y-m-d') }}">
                                        <div class="flex items-center gap-1.5">
                                            <input name="daily_fine" type="number" value="10000" placeholder="Denda/hr" class="w-24 rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-1.5 text-[10px] text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                                            <button type="submit" onclick="return confirm('Proses pengembalian unit konsol ini?');" class="rounded-lg bg-emerald-600 px-2.5 py-1.5 text-[10px] font-bold text-white hover:bg-emerald-500 transition cursor-pointer">
                                                <i class="fa-solid fa-check"></i> Return
                                            </button>
                                        </div>
                                        <input name="return_notes" type="text" placeholder="Kondisi barang..." class="w-full rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-1.5 text-[10px] text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                                    </form>
                                </td>
                                <td class="px-4 py-3.5">
                                    <form method="POST" action="/admin/rentals/{{ $rental->id }}/fines" class="space-y-2">
                                        @csrf
                                        <div class="flex items-center gap-1.5">
                                            <input name="amount" type="number" placeholder="Nominal Rp" class="w-28 rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-1.5 text-[10px] text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
                                            <button type="submit" class="rounded-lg bg-red-600 px-2 py-1.5 text-[10px] font-bold text-white hover:bg-red-500 transition cursor-pointer">
                                                +Denda
                                            </button>
                                        </div>
                                        <input name="reason" type="text" placeholder="Alasan kerusakan..." class="w-full rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-1.5 text-[10px] text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-xs text-slate-500">
                                    <i class="fa-solid fa-circle-check text-2xl text-emerald-500 mb-2 block"></i>
                                    Tidak ada rental konsol yang sedang aktif saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card List (< md) -->
        <div class="md:hidden space-y-3">
            @forelse($activeRentals as $rental)
                @php $warn = \App\Domain\Rentals\RentalWarning::details($rental->due_date); @endphp
                <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 overflow-hidden shadow-sm">
                    <!-- Card Header -->
                    <div class="flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-white/10">
                        <div>
                            <span class="font-mono font-bold text-orange-500 text-sm">#{{ $rental->rental_code }}</span>
                            <div class="text-xs font-semibold text-slate-900 dark:text-white mt-0.5">{{ $rental->user->name }}</div>
                        </div>
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $warn['badge_class'] }}">{{ $warn['code'] }}</span>
                    </div>

                    <!-- Card Body -->
                    <div class="px-4 py-3 space-y-2.5 text-xs">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Unit</div>
                                <div class="font-semibold text-slate-900 dark:text-white">{{ $rental->unit->name }}</div>
                                <div class="text-[10px] text-slate-500 font-mono">{{ $rental->unit->code }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Jatuh Tempo</div>
                                <div class="font-medium text-slate-900 dark:text-white">{{ $rental->due_date->format('d M Y') }}</div>
                            </div>
                        </div>

                        <div class="rounded-xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-white/10 p-3">
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-2">Total Tagihan</div>
                            <div class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}</div>
                            @if(($rental->transaction?->fine_amount ?? 0) > 0)
                                <div class="text-[10px] text-red-500 font-semibold mt-0.5">+ Denda: Rp {{ number_format($rental->transaction->fine_amount, 0, ',', '.') }}</div>
                            @endif
                        </div>

                        <!-- Return Form -->
                        <div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1.5">Proses Pengembalian</div>
                            <form method="POST" action="/admin/rentals/{{ $rental->id }}/return" class="space-y-2">
                                @csrf
                                <input type="hidden" name="returned_at" value="{{ now()->format('Y-m-d') }}">
                                <input name="return_notes" type="text" placeholder="Kondisi barang..." class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                                <div class="flex items-center gap-2">
                                    <input name="daily_fine" type="number" value="10000" placeholder="Denda/hr" class="flex-1 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                                    <button type="submit" onclick="return confirm('Proses pengembalian unit ini?');" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-500 transition cursor-pointer">
                                        <i class="fa-solid fa-check mr-1"></i> Return
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Damage Fine Form -->
                        <div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1.5">Tambah Denda Kerusakan</div>
                            <form method="POST" action="/admin/rentals/{{ $rental->id }}/fines" class="space-y-2">
                                @csrf
                                <input name="reason" type="text" placeholder="Alasan kerusakan..." class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
                                <div class="flex items-center gap-2">
                                    <input name="amount" type="number" placeholder="Nominal Rp" class="flex-1 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none" required>
                                    <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-500 transition cursor-pointer">
                                        <i class="fa-solid fa-plus mr-1"></i> Denda
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-10 text-center text-xs text-slate-500">
                    <i class="fa-solid fa-circle-check text-3xl text-emerald-500 mb-3 block"></i>
                    Tidak ada rental konsol yang sedang aktif.
                </div>
            @endforelse
        </div>

        @if(method_exists($activeRentals, 'links'))
            <div>{{ $activeRentals->links() }}</div>
        @endif
    </div>
</x-layouts.admin>

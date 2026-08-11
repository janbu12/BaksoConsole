<x-layouts.app title="Dashboard Member">
    <!-- Header Greeting -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-white/10">
        <div>
            <div class="text-xs font-bold uppercase tracking-wider text-orange-500">Portal Member Bakso Console</div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">Halo, {{ $user->name }}! 👋</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Pantau rental aktifmu, cek sisa masa sewa, dan raih tingkatan rank tertinggi.</p>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('catalogue') }}" class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-500/25 hover:bg-orange-400 transition">
                <i class="fa-solid fa-gamepad"></i>
                <span>Cari & Sewa Unit</span>
            </a>
        </div>
    </div>

    <!-- Overview Grid (Rank + Active Rentals) -->
    <div class="mt-6 grid gap-5 grid-cols-1 lg:grid-cols-3">
        <!-- Bakso Rank Widget -->
        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-5 flex flex-col justify-between shadow-sm">
            <div>
                @php
                    $rankIcon = match($rank['level']) {
                        1 => 'fa-shield text-slate-500 dark:text-slate-400',
                        2 => 'fa-medal text-blue-500 dark:text-blue-400',
                        3 => 'fa-trophy text-purple-500 dark:text-purple-400',
                        4 => 'fa-crown text-amber-500 dark:text-amber-400',
                        default => 'fa-trophy text-orange-500 dark:text-orange-400',
                    };
                @endphp
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Loyalitas</span>
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10">
                        <i class="fa-solid {{ $rankIcon }} text-xl"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="text-xl font-black text-slate-900 dark:text-white">{{ $rank['name'] }}</div>
                    <div class="text-xs text-orange-500 dark:text-orange-400 font-semibold mt-0.5">{{ $totalDays }} Total Hari Sewa</div>
                </div>

                <!-- Progress to next rank -->
                @if($rank['next_rank'])
                    <div class="mt-5">
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-slate-500 dark:text-slate-400">Progress ke <b class="text-slate-900 dark:text-white">{{ $rank['next_rank'] }}</b></span>
                            <span class="font-bold text-orange-500">{{ $rank['progress_percent'] }}%</span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-slate-950 overflow-hidden border border-slate-300 dark:border-white/5">
                            <div class="h-full rounded-full bg-gradient-to-r from-orange-500 to-amber-400 transition-all duration-500" style="width: {{ $rank['progress_percent'] }}%"></div>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-2">
                            Kurang <b class="text-slate-900 dark:text-white">{{ $rank['days_needed'] }} hari sewa lagi</b> untuk naik rank!
                        </div>
                    </div>
                @else
                    <div class="mt-5 rounded-xl bg-amber-500/10 border border-amber-500/20 p-3 text-xs text-amber-600 dark:text-amber-300 flex items-center gap-2">
                        <i class="fa-solid fa-crown text-amber-500"></i>
                        <span>Selamat! Anda telah mencapai rank tertinggi (Bakso Legend).</span>
                    </div>
                @endif
            </div>

            <!-- Active Benefits -->
            <div class="mt-5 pt-4 border-t border-slate-200 dark:border-white/10 text-xs text-slate-600 dark:text-slate-300">
                <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 block mb-1">Benefit Aktif:</span>
                <p>{{ $rank['benefit'] }}</p>
            </div>
        </div>

        <!-- Active Rentals Widget (Columns 2 & 3) -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2 flex-wrap">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Rental Sedang Berjalan</h2>
                    <span class="rounded-full bg-orange-500/15 px-2.5 py-0.5 text-xs font-bold text-orange-600 dark:text-orange-400 border border-orange-500/20">
                        {{ $activeRentals->count() }} / 2 Unit
                    </span>
                </div>
                <a href="{{ route('rentals') }}" class="text-xs font-bold text-orange-500 hover:text-orange-400 transition shrink-0">
                    Kelola Rental &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($activeRentals as $rental)
                    @php $warn = $warnings[$rental->id]; @endphp
                    <div class="rounded-xl border p-4 {{ $warn['bg_card'] }} transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-mono text-xs font-bold text-orange-500">{{ $rental->unit->code }}</span>
                                    <h3 class="font-bold text-slate-900 dark:text-white text-sm truncate">{{ $rental->unit->name }}</h3>
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Kode: <span class="font-mono text-slate-600 dark:text-slate-300">{{ $rental->rental_code }}</span>
                                    &middot;
                                    Jatuh Tempo: <b class="text-slate-700 dark:text-slate-200">{{ $rental->due_date->format('d M Y') }}</b>
                                </div>
                            </div>

                            <!-- Smart Timer & Warning Badge -->
                            <div class="flex items-center gap-3 shrink-0">
                                <div class="text-right">
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400">Sisa Waktu:</div>
                                    <div class="text-sm font-black">
                                        @if($warn['remaining_days'] < 0)
                                            <span class="text-red-500 dark:text-red-400">Terlambat {{ abs($warn['remaining_days']) }} Hari</span>
                                        @elseif($warn['remaining_days'] === 0)
                                            <span class="text-yellow-500 dark:text-yellow-400">Hari ini Terakhir!</span>
                                        @else
                                            <span class="text-emerald-600 dark:text-emerald-400">Sisa {{ $warn['remaining_days'] }} Hari</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="rounded-xl px-3 py-1.5 text-xs font-bold {{ $warn['badge_class'] }}">
                                    {{ $warn['code'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Warning Message Box -->
                        <div class="mt-3 text-xs flex items-start gap-2 bg-black/5 dark:bg-black/30 p-2.5 rounded-xl">
                            <i class="fa-solid {{ $warn['is_safe'] ? 'fa-circle-check text-emerald-500' : ($warn['is_warning'] ? 'fa-triangle-exclamation text-amber-500' : 'fa-circle-exclamation text-red-500') }} mt-0.5 shrink-0"></i>
                            <span class="text-slate-600 dark:text-slate-300">{{ $warn['message'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center rounded-xl bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-white/5">
                        <i class="fa-solid fa-gamepad text-3xl text-slate-300 dark:text-slate-600 mb-3 block"></i>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Tidak ada rental konsol yang aktif.</p>
                        <a href="{{ route('catalogue') }}" class="mt-3 inline-block text-xs font-bold text-orange-500 hover:underline">
                            + Mulai sewa konsol sekarang
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Quick Tip -->
            <div class="mt-4 pt-3 border-t border-slate-200 dark:border-white/10 text-[11px] text-slate-500 dark:text-slate-400 flex items-start gap-2">
                <i class="fa-solid fa-circle-info text-orange-500 mt-0.5 shrink-0"></i>
                <span>Batas sewa maksimal 5 hari per rental. Perpanjangan dapat diajukan sebelum masa sewa berakhir.</span>
            </div>
        </div>
    </div>

    <!-- Active Bookings & Recommendations Grid -->
    <div class="mt-6 grid gap-5 grid-cols-1 lg:grid-cols-3">
        <!-- Active Bookings -->
        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-sm font-bold text-slate-900 dark:text-white">Reservasi / Booking</h2>
                <a href="{{ route('bookings') }}" class="text-xs font-bold text-orange-500 hover:text-orange-400 transition">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($activeBookings as $booking)
                    <div class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950/60 p-3.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono font-bold text-orange-500">{{ $booking->booking_code }}</span>
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $booking->status->value === 'confirmed' ? 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30' }}">
                                {{ $booking->status->value }}
                            </span>
                        </div>
                        <h4 class="mt-2 font-bold text-slate-900 dark:text-white text-xs">{{ $booking->unit->name }}</h4>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">
                            {{ $booking->start_date->format('d M') }} – {{ $booking->end_date->format('d M Y') }} ({{ $booking->duration_days }} Hari)
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-500">
                        <i class="fa-regular fa-calendar text-2xl mb-2 block opacity-40"></i>
                        Tidak ada reservasi pending.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recommended Units (SmartPick Preview) -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Rekomendasi Konsol Hari Ini</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Unit terlaris siap sewa dengan performa maksimal.</p>
                </div>
                <a href="{{ route('catalogue') }}" class="text-xs font-bold text-orange-500 hover:text-orange-400 transition shrink-0">
                    Katalog &rarr;
                </a>
            </div>

            <div class="grid gap-3 grid-cols-1 sm:grid-cols-3">
                @foreach($recommendedUnits as $unit)
                    <div class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950/60 p-4 flex flex-col justify-between hover:border-orange-500/40 transition">
                        <div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-mono text-orange-500 font-bold">{{ $unit->code }}</span>
                                <span class="text-slate-500 dark:text-slate-400"><i class="fa-solid fa-users text-[10px] mr-1"></i>{{ $unit->max_players }}P</span>
                            </div>
                            <h4 class="mt-2 font-bold text-slate-900 dark:text-white text-xs">{{ $unit->name }}</h4>
                            <div class="mt-1.5 text-xs font-black text-slate-900 dark:text-white">
                                Rp{{ number_format($unit->daily_price, 0, ',', '.') }}<span class="text-[10px] font-normal text-slate-500 dark:text-slate-400">/hari</span>
                            </div>
                        </div>
                        <a href="{{ route('catalogue') }}?q={{ urlencode($unit->name) }}" class="mt-4 block text-center rounded-xl bg-orange-500/10 border border-orange-500/20 py-2 text-xs font-bold text-orange-500 hover:bg-orange-500 hover:text-white transition">
                            Pesan Unit
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>

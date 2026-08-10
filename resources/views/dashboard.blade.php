<x-layouts.app title="Dashboard Member">
    <!-- Header Greeting -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="text-xs font-bold uppercase tracking-wider text-orange-400">Portal Member Bakso Console</div>
            <h1 class="text-3xl font-black text-white mt-1">Halo, {{ $user->name }}!</h1>
            <p class="text-sm text-slate-400 mt-0.5">Pantau rental aktifmu, cek sisa masa sewa, dan raih tingkatan rank tertinggi.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('catalogue') }}" class="rounded-xl bg-orange-500 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-500/25 hover:bg-orange-600 transition flex items-center gap-2">
                <i class="fa-solid fa-gamepad"></i>
                <span>Cari & Sewa Unit Baru</span>
            </a>
        </div>
    </div>

    <!-- Overview Grid -->
    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <!-- Bakso Rank Widget (Column 1) -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 flex flex-col justify-between shadow-xl">
            <div>
                @php
                    $rankIcon = match($rank['level']) {
                        1 => 'fa-shield text-slate-400',
                        2 => 'fa-medal text-blue-400',
                        3 => 'fa-trophy text-purple-400',
                        4 => 'fa-crown text-amber-400',
                        default => 'fa-trophy text-orange-400',
                    };
                @endphp
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Loyalitas</span>
                    <i class="fa-solid {{ $rankIcon }} text-2xl"></i>
                </div>
                <div class="mt-3">
                    <div class="text-2xl font-black text-white">{{ $rank['name'] }}</div>
                    <div class="text-xs text-orange-400 font-semibold mt-0.5">{{ $totalDays }} Total Hari Sewa Kumulatif</div>
                </div>

                <!-- Progress to next rank -->
                @if($rank['next_rank'])
                    <div class="mt-6">
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-slate-400">Progress ke <b class="text-white">{{ $rank['next_rank'] }}</b></span>
                            <span class="font-bold text-orange-400">{{ $rank['progress_percent'] }}%</span>
                        </div>
                        <div class="h-2.5 w-full rounded-full bg-slate-950 overflow-hidden border border-white/5">
                            <div class="h-full rounded-full bg-gradient-to-r from-orange-500 to-amber-400 transition-all duration-500" style="width: {{ $rank['progress_percent'] }}%"></div>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-2">
                            Kurang <b>{{ $rank['days_needed'] }} hari sewa lagi</b> untuk naik rank!
                        </div>
                    </div>
                @else
                    <div class="mt-6 rounded-2xl bg-amber-500/10 border border-amber-500/20 p-3 text-xs text-amber-300 flex items-center gap-2">
                        <i class="fa-solid fa-crown text-amber-400"></i>
                        <span>Selamat! Anda telah mencapai tingkatan rank tertinggi (Bakso Legend).</span>
                    </div>
                @endif
            </div>

            <!-- Active Benefits -->
            <div class="mt-6 pt-4 border-t border-white/10 text-xs text-slate-300">
                <span class="text-[10px] uppercase font-bold text-slate-400 block mb-1">Benefit Aktif:</span>
                <p>{{ $rank['benefit'] }}</p>
            </div>
        </div>

        <!-- Active Rentals Widget (Columns 2 & 3) -->
        <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-slate-900/90 p-6 flex flex-col justify-between shadow-xl">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-bold text-white">Rental Sedang Berjalan</h2>
                        <span class="rounded-full bg-orange-500/20 px-2.5 py-0.5 text-xs font-bold text-orange-400 border border-orange-500/30">
                            {{ $activeRentals->count() }} / 2 Unit Maksimal
                        </span>
                    </div>
                    <a href="{{ route('rentals') }}" class="text-xs font-bold text-orange-400 hover:text-orange-300 transition">
                        Kelola Rental &rarr;
                    </a>
                </div>

                @forelse($activeRentals as $rental)
                    @php $warn = $warnings[$rental->id]; @endphp
                    <div class="mt-3 rounded-2xl border p-4 {{ $warn['bg_card'] }} transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-orange-400">{{ $rental->unit->code }}</span>
                                    <h3 class="font-bold text-white text-base">{{ $rental->unit->name }}</h3>
                                </div>
                                <div class="text-xs text-slate-400 mt-1">
                                    Kode Sewa: <span class="font-mono text-slate-300">{{ $rental->rental_code }}</span> &middot;
                                    Jatuh Tempo: <b class="text-slate-200">{{ $rental->due_date->format('d M Y') }}</b>
                                </div>
                            </div>

                            <!-- Smart Timer & Warning Badge -->
                            <div class="flex items-center gap-3">
                                <div class="text-right">
                                    <div class="text-xs text-slate-400">Smart Rental Timer:</div>
                                    <div class="text-sm font-black text-white">
                                        @if($warn['remaining_days'] < 0)
                                            <span class="text-red-400">Terlambat {{ abs($warn['remaining_days']) }} Hari</span>
                                        @elseif($warn['remaining_days'] === 0)
                                            <span class="text-yellow-400">Hari ini Terakhir!</span>
                                        @else
                                            <span class="text-emerald-400">Sisa {{ $warn['remaining_days'] }} Hari</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="rounded-xl px-3 py-1.5 text-xs font-bold {{ $warn['badge_class'] }}">
                                    {{ $warn['code'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Warning Message Box -->
                        <div class="mt-3 text-xs text-slate-300 flex items-center gap-2 bg-slate-950/60 p-2.5 rounded-xl">
                            <i class="fa-solid {{ $warn['is_safe'] ? 'fa-circle-check text-emerald-400' : ($warn['is_warning'] ? 'fa-triangle-exclamation text-amber-400' : 'fa-circle-exclamation text-red-400') }}"></i>
                            <span>{{ $warn['message'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-500 rounded-2xl bg-slate-950/50 border border-white/5">
                        <div class="text-3xl mb-2 text-slate-600">
                            <i class="fa-solid fa-gamepad"></i>
                        </div>
                        <p class="text-sm font-medium">Saat ini Anda tidak memiliki rental konsol yang aktif.</p>
                        <a href="{{ route('catalogue') }}" class="mt-3 inline-block text-xs font-bold text-orange-400 hover:underline">
                            + Mulai sewa konsol sekarang
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Quick Tip -->
            <div class="mt-4 pt-3 border-t border-white/5 text-[11px] text-slate-500 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-orange-400"></i>
                <span>Batas sewa maksimal 5 hari per rental. Perpanjangan dapat diajukan sebelum masa sewa berakhir.</span>
            </div>
        </div>
    </div>

    <!-- Active Bookings & Recommendations Grid -->
    <div class="mt-8 grid gap-6 lg:grid-cols-3">
        <!-- Active Bookings -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-white">Reservasi / Booking</h2>
                <a href="{{ route('bookings') }}" class="text-xs font-bold text-orange-400 hover:text-orange-300">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                @forelse($activeBookings as $booking)
                    <div class="rounded-2xl border border-white/10 bg-slate-950 p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono font-bold text-orange-400">{{ $booking->booking_code }}</span>
                            <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $booking->status->value === 'confirmed' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                                {{ $booking->status->value }}
                            </span>
                        </div>
                        <h4 class="mt-2 font-bold text-white text-sm">{{ $booking->unit->name }}</h4>
                        <div class="text-xs text-slate-400 mt-1">
                            {{ $booking->start_date->format('d M') }} &ndash; {{ $booking->end_date->format('d M Y') }} ({{ $booking->duration_days }} Hari)
                        </div>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs text-slate-500">
                        Tidak ada reservasi pending.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recommended Units (SmartPick Preview) -->
        <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-white">Rekomendasi Konsol Hari Ini</h2>
                    <p class="text-xs text-slate-400">Unit terlaris siap sewa dengan performa maksimal.</p>
                </div>
                <a href="{{ route('catalogue') }}" class="text-xs font-bold text-orange-400 hover:text-orange-300">
                    Katalog Lengkap &rarr;
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach($recommendedUnits as $unit)
                    <div class="rounded-2xl border border-white/10 bg-slate-950 p-4 flex flex-col justify-between hover:border-orange-500/40 transition">
                        <div>
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span class="font-mono text-orange-400 font-bold">{{ $unit->code }}</span>
                                <span><i class="fa-solid fa-users text-[10px] mr-1"></i>{{ $unit->max_players }}P</span>
                            </div>
                            <h4 class="mt-2 font-bold text-white text-sm">{{ $unit->name }}</h4>
                            <div class="mt-2 text-xs font-black text-slate-200">
                                Rp{{ number_format($unit->daily_price, 0, ',', '.') }}<span class="text-[10px] font-normal text-slate-400">/hari</span>
                            </div>
                        </div>
                        <a href="{{ route('catalogue') }}?q={{ urlencode($unit->name) }}" class="mt-4 block text-center rounded-xl bg-white/5 py-2 text-xs font-bold text-orange-400 hover:bg-orange-500 hover:text-white transition border border-orange-500/20">
                            Pesan Unit
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>

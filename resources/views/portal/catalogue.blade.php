<x-layouts.app title="Katalog Console & SmartPick">
    <!-- Header Title -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="text-xs font-bold uppercase tracking-wider text-orange-400">Pencarian & Rekomendasi Pintar</div>
            <h1 class="text-3xl font-black text-white mt-1">Katalog Konsol & SmartPick</h1>
            <p class="text-sm text-slate-400 mt-0.5">Cari unit konsol, filter kapasitas mabar, atau biarkan algoritma SmartPick memilihkan unit terbaik untukmu.</p>
        </div>
    </div>

    <!-- Mabar Capacity Quick Selector (Selling Point #5) -->
    <div class="mt-6 flex flex-wrap items-center gap-2">
        <span class="text-xs font-bold uppercase text-slate-400 mr-2">👥 Mabar Capacity:</span>
        <a href="{{ route('catalogue', array_merge(request()->except('players'), [])) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition border {{ !request('players') ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-slate-900 text-slate-300 border-white/10 hover:border-orange-500/30' }}">
            Semua Kapasitas
        </a>
        <a href="{{ route('catalogue', array_merge(request()->all(), ['players' => 1])) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition border {{ request('players') == 1 ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-slate-900 text-slate-300 border-white/10 hover:border-orange-500/30' }}">
            🎮 1 Player
        </a>
        <a href="{{ route('catalogue', array_merge(request()->all(), ['players' => 2])) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition border {{ request('players') == 2 ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-slate-900 text-slate-300 border-white/10 hover:border-orange-500/30' }}">
            👥 2 Players
        </a>
        <a href="{{ route('catalogue', array_merge(request()->all(), ['players' => 3])) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition border {{ request('players') == 3 ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-slate-900 text-slate-300 border-white/10 hover:border-orange-500/30' }}">
            👨‍👩‍👧 3 Players
        </a>
        <a href="{{ route('catalogue', array_merge(request()->all(), ['players' => 4])) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition border {{ request('players') == 4 ? 'bg-orange-500 text-white border-orange-500 shadow-md shadow-orange-500/20' : 'bg-slate-900 text-slate-300 border-white/10 hover:border-orange-500/30' }}">
            🔥 4+ Players (Mabar Full)
        </a>
    </div>

    <!-- SmartPick Recommendation Filter Form (Selling Point #1) -->
    <div class="mt-6 rounded-3xl border border-orange-500/30 bg-slate-900/90 p-6 shadow-2xl backdrop-blur-xl">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <span class="text-xl">🤖</span>
                <div>
                    <h2 class="text-base font-bold text-white">Smart Rental Recommendation (Bakso SmartPick)</h2>
                    <p class="text-xs text-slate-400">Masukkan preferensi Anda dan sistem akan menghitung unit terbaik.</p>
                </div>
            </div>
            @if(request()->anyFilled(['q', 'players', 'duration', 'budget', 'category', 'game', 'firmware_type']))
                <a href="{{ route('catalogue') }}" class="text-xs font-bold text-red-400 hover:underline">
                    &times; Reset Filter
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('catalogue') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-7">
            <!-- Search Name / Game -->
            <div>
                <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Cari Unit / Game</label>
                <input name="q" value="{{ request('q') }}" placeholder="Misal: PS5, Assassin, F1..." class="w-full rounded-xl border border-white/10 bg-slate-950 px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none">
            </div>

            <!-- Tipe Sistem / Firmware Filter -->
            <div>
                <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Tipe Sistem</label>
                <select name="firmware_type" class="w-full rounded-xl border border-white/10 bg-slate-950 px-3.5 py-2.5 text-xs text-white focus:border-orange-500 focus:outline-none">
                    <option value="">Semua Sistem</option>
                    <option value="original" @selected(request('firmware_type') === 'original')>🌐 Original (Bisa Online)</option>
                    <option value="jailbreak" @selected(request('firmware_type') === 'jailbreak')>💾 Jailbreak (Full Offline)</option>
                </select>
            </div>

            <!-- Game Filter -->
            <div>
                <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Game Terpasang</label>
                <select name="game" class="w-full rounded-xl border border-white/10 bg-slate-950 px-3.5 py-2.5 text-xs text-white focus:border-orange-500 focus:outline-none">
                    <option value="">Semua Game</option>
                    @foreach($games as $g)
                        <option value="{{ $g->id }}" @selected(request('game') == $g->id)>{{ $g->icon ?? '🎮' }} {{ $g->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Players -->
            <div>
                <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Jumlah Pemain</label>
                <select name="players" class="w-full rounded-xl border border-white/10 bg-slate-950 px-3.5 py-2.5 text-xs text-white focus:border-orange-500 focus:outline-none">
                    <option value="">Semua Kapasitas</option>
                    <option value="1" @selected(request('players') == 1)>1 Player (Single)</option>
                    <option value="2" @selected(request('players') == 2)>2 Players (Co-op)</option>
                    <option value="3" @selected(request('players') == 3)>3 Players (Party)</option>
                    <option value="4" @selected(request('players') == 4)>4+ Players (Mabar)</option>
                </select>
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Durasi Sewa (Hari)</label>
                <select name="duration" class="w-full rounded-xl border border-white/10 bg-slate-950 px-3.5 py-2.5 text-xs text-white focus:border-orange-500 focus:outline-none">
                    <option value="">Pilih Durasi</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" @selected(request('duration') == $i)>{{ $i }} Hari (Maksimal 5 Hari)</option>
                    @endfor
                </select>
            </div>

            <!-- Budget -->
            <div>
                <label class="block text-[11px] font-bold uppercase text-slate-400 mb-1">Maksimal Budget (Rp)</label>
                <input name="budget" type="number" step="10000" value="{{ request('budget') }}" placeholder="Contoh: 150000" class="w-full rounded-xl border border-white/10 bg-slate-950 px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none">
            </div>

            <!-- Category / Action Button -->
            <div class="flex flex-col justify-end gap-2">
                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-500/20 hover:brightness-110 transition flex items-center justify-center gap-1.5">
                    <span>✨</span> Jalankan SmartPick
                </button>
            </div>
        </form>
    </div>

    <!-- Units Showcase Grid -->
    <div class="mt-10">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-white">Daftar Unit Konsol ({{ $units->count() }} Unit Tersedia)</h2>
            <div class="flex items-center gap-4 text-xs text-slate-400">
                <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> Available</span>
                <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-amber-400"></span> Booked</span>
                <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-red-400"></span> Rented</span>
                <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-slate-500"></span> Maintenance</span>
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($units as $unit)
                @php
                    $isAvailable = $unit->status->value === 'available';
                    $isBestMatch = $unit->smart_pick['is_best_match'] ?? false;
                    $canBook = $unit->status->value !== 'maintenance';
                    $blockedPeriods = $unit->bookings
                        ->map(fn ($booking) => [
                            'start' => $booking->start_date,
                            'end' => $booking->end_date,
                            'label' => 'Sudah dibooking',
                        ])
                        ->concat($unit->rentals->map(fn ($rental) => [
                            'start' => $rental->start_date,
                            'end' => $rental->due_date,
                            'label' => 'Sedang disewa',
                        ]))
                        ->sortBy('start')
                        ->values();
                @endphp
                <article class="relative rounded-3xl border {{ $isBestMatch ? 'border-orange-500 ring-2 ring-orange-500/30 bg-gradient-to-b from-orange-950/20 to-slate-900' : 'border-white/10 bg-slate-900/90' }} p-6 flex flex-col justify-between shadow-xl transition-all duration-300 hover:border-orange-500/40">
                    <!-- Best Match Ribbon -->
                    @if($isBestMatch)
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-orange-500 to-amber-500 px-4 py-1 text-[10px] font-black uppercase tracking-wider text-white shadow-lg shadow-orange-500/30 flex items-center gap-1">
                            <span>👑</span> Rekomendasi Utama (Best Match)
                        </div>
                    @endif

                    <div>
                        <!-- Header Unit: Code & Firmware & Live Availability Badge -->
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5">
                                <span class="font-mono text-xs font-bold uppercase tracking-wider text-orange-400 bg-orange-500/10 px-2.5 py-1 rounded-lg border border-orange-500/20">
                                    {{ $unit->code }}
                                </span>
                                <span class="rounded-lg px-2 py-1 text-[10px] font-bold uppercase {{ $unit->firmware_type?->value === 'jailbreak' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30' }}">
                                    {{ $unit->firmware_type?->value === 'jailbreak' ? '💾 Jailbreak' : '🌐 Online Ready' }}
                                </span>
                            </div>

                            @if($unit->status->value === 'available')
                                <span class="flex items-center gap-1.5 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-2.5 py-1 text-xs font-bold text-emerald-400">
                                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span> AVAILABLE
                                </span>
                            @elseif($unit->status->value === 'booked')
                                <span class="flex items-center gap-1.5 rounded-lg border border-yellow-500/30 bg-yellow-500/10 px-2.5 py-1 text-xs font-bold text-yellow-400">
                                    <span class="h-2 w-2 rounded-full bg-yellow-400"></span> BOOKED
                                </span>
                            @elseif($unit->status->value === 'rented')
                                <span class="flex items-center gap-1.5 rounded-lg border border-red-500/30 bg-red-500/10 px-2.5 py-1 text-xs font-bold text-red-400">
                                    <span class="h-2 w-2 rounded-full bg-red-400"></span> RENTED
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 rounded-lg border border-slate-500/30 bg-slate-500/10 px-2.5 py-1 text-xs font-bold text-slate-400">
                                    ⚙️ MAINTENANCE
                                </span>
                            @endif
                        </div>

                        <!-- Unit Name & Specs -->
                        <div class="mt-5 flex items-start justify-between">
                            <div>
                                <h3 class="text-xl font-black text-white">{{ $unit->name }}</h3>
                                <div class="mt-1 flex items-center gap-2 text-xs text-slate-400">
                                    <span>👥 Kapasitas {{ $unit->max_players }} Pemain</span>
                                </div>
                            </div>
                            <div class="text-3xl opacity-80">🎮</div>
                        </div>

                        <!-- Match Indicators (SmartPick Badges) -->
                        @if(!empty($unit->smart_pick['badges']))
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach($unit->smart_pick['badges'] as $badge)
                                    <span class="rounded-lg bg-emerald-500/15 border border-emerald-500/30 px-2 py-0.5 text-[10px] font-bold text-emerald-300">
                                        {{ $badge }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Categories & Hardware Model -->
                        <div class="mt-3 flex flex-wrap items-center gap-1.5">
                            @foreach($unit->categories as $category)
                                <span class="rounded-lg bg-white/5 px-2.5 py-1 text-[11px] font-medium text-slate-300 border border-white/5">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                            @if($unit->model_number)
                                <span class="rounded-lg bg-slate-800/80 px-2 py-1 text-[10px] font-mono text-slate-400 border border-white/5">
                                    Model: {{ $unit->model_number }}
                                </span>
                            @endif
                        </div>

                        <!-- Game Terpasang (Pre-installed Games) -->
                        <div class="mt-4 pt-3 border-t border-white/5">
                            <div class="flex items-center justify-between text-[11px] text-slate-400 font-bold uppercase tracking-wider mb-2">
                                <span><i class="fa-solid fa-gamepad text-orange-400 mr-1"></i> Game Terpasang:</span>
                                <span class="text-[10px] font-normal lowercase text-slate-500">({{ $unit->games->count() }} game)</span>
                            </div>
                            @if($unit->games->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto scrollbar-thin">
                                    @foreach($unit->games as $game)
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-orange-500/10 border border-orange-500/20 px-2 py-1 text-[11px] font-semibold text-orange-300 hover:bg-orange-500/20 transition">
                                            <span>{{ $game->icon ?? '🎮' }}</span>
                                            <span>{{ $game->name }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-[11px] text-slate-500 italic">Game pre-installed siap request saat sewa.</p>
                            @endif
                        </div>

                        <!-- Price Info -->
                        <div class="mt-4 pt-3 border-t border-white/5">
                            <span class="text-xs text-slate-400">Tarif Sewa Harian:</span>
                            <div class="text-2xl font-black text-white">
                                Rp{{ number_format($unit->daily_price, 0, ',', '.') }}<span class="text-xs font-normal text-slate-400">/hari</span>
                            </div>
                        </div>

                        <div class="mt-4 rounded-xl border {{ $blockedPeriods->isEmpty() ? 'border-emerald-500/20 bg-emerald-500/5' : 'border-amber-500/20 bg-amber-500/5' }} p-3">
                            <div class="text-[10px] font-bold uppercase tracking-wide {{ $blockedPeriods->isEmpty() ? 'text-emerald-400' : 'text-amber-400' }}">
                                Jadwal unit
                            </div>
                            @forelse($blockedPeriods as $period)
                                <div class="mt-1.5 flex items-center justify-between gap-2 text-[11px]">
                                    <span class="text-slate-300">{{ $period['label'] }}</span>
                                    <strong class="text-white">{{ $period['start']->format('d/m/Y') }} - {{ $period['end']->format('d/m/Y') }}</strong>
                                </div>
                            @empty
                                <p class="mt-1 text-[11px] text-emerald-300">Belum ada jadwal yang terisi mulai hari ini.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Booking Form / Action -->
                    <div class="mt-6 pt-4 border-t border-white/10">
                        @auth
                            @if($canBook)
                                <details class="group/booking">
                                    <summary class="cursor-pointer list-none rounded-xl bg-orange-500 px-4 py-2.5 text-center text-xs font-bold text-white shadow-md hover:bg-orange-600 transition flex items-center justify-center gap-1.5">
                                        <span>📅</span> Buat Reservasi / Booking
                                    </summary>

                                    <form method="POST" action="/bookings" class="mt-3 space-y-3 rounded-2xl bg-slate-950 p-4 border border-white/10 text-left" x-data="{ deliveryMethod: 'pickup' }">
                                        @csrf
                                        <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                                        <div>
                                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Tanggal Mulai</label>
                                            <input type="date" name="start_date" min="{{ today()->toDateString() }}" value="{{ today()->toDateString() }}" class="w-full rounded-lg border border-white/10 bg-slate-900 p-2 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Tanggal Selesai (Maks 5 Hari)</label>
                                            <input type="date" name="end_date" min="{{ today()->toDateString() }}" value="{{ today()->addDays(2)->toDateString() }}" class="w-full rounded-lg border border-white/10 bg-slate-900 p-2 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                                        </div>

                                        <div>
                                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Metode Pengiriman</label>
                                            <select name="delivery_method" x-model="deliveryMethod" class="w-full rounded-lg border border-white/10 bg-slate-900 p-2 text-xs text-white focus:border-orange-500 focus:outline-none">
                                                <option value="pickup">Ambil di Toko</option>
                                                <option value="delivery">Diantar Kurir (+Rp 15.000)</option>
                                            </select>
                                        </div>

                                        <template x-if="deliveryMethod === 'delivery'">
                                            <div class="space-y-3 border-l-2 border-orange-500 pl-3 ml-1 mt-2">
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Alamat Pengiriman</label>
                                                    <textarea name="delivery_address" rows="2" placeholder="Alamat lengkap..." class="w-full rounded-lg border border-white/10 bg-slate-900 p-2 text-xs text-white focus:border-orange-500 focus:outline-none" required>{{ auth()->user()->profile?->address ?? '' }}</textarea>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nomor Kontak / WhatsApp</label>
                                                    <input type="text" name="contact_number" value="{{ auth()->user()->profile?->phone ?? '' }}" placeholder="08..." class="w-full rounded-lg border border-white/10 bg-slate-900 p-2 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Conditional Game Selection based on Firmware Status -->
                                        @if(($unit->firmware_type?->value ?? 'original') === 'original')
                                            <div>
                                                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1 flex items-center justify-between">
                                                    <span><i class="fa-solid fa-gamepad text-orange-400"></i> Pilih Game yang Diinginkan:</span>
                                                    <span class="text-[9px] text-blue-400 lowercase font-normal">🌐 online multiplayer ready</span>
                                                </label>
                                                <div class="max-h-28 overflow-y-auto rounded-lg border border-white/10 bg-slate-900 p-2 space-y-1.5 scrollbar-thin">
                                                    @forelse($unit->games as $game)
                                                        <label class="flex items-center gap-2 text-xs text-slate-300 hover:text-white cursor-pointer">
                                                            <input type="checkbox" name="requested_games[]" value="{{ $game->name }}" checked class="rounded border-white/20 bg-slate-950 text-orange-500 focus:ring-orange-500">
                                                            <span>{{ $game->icon ?? '🎮' }} {{ $game->name }}</span>
                                                            <span class="text-[9px] text-slate-500 ml-auto">{{ $game->genre ?? '' }}</span>
                                                        </label>
                                                    @empty
                                                        <p class="text-[10px] text-slate-500 italic">Game siap request di catatan sewa</p>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @else
                                            <div class="rounded-xl border border-purple-500/30 bg-purple-500/10 p-2.5 text-xs text-purple-200">
                                                <div class="font-bold flex items-center gap-1.5 text-purple-300 text-[11px]">
                                                    <span>💾</span> Paket Full 200+ Game Lengkap
                                                </div>
                                                <p class="text-[10px] text-purple-300/80 mt-0.5 leading-relaxed">
                                                    Unit sudah terisi 200+ game lengkap (The Warriors, GTA V, dll) siap main offline tanpa perlu koneksi/kuota internet.
                                                </p>
                                            </div>
                                        @endif

                                        <div>
                                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Catatan Tambahan (Opsional)</label>
                                            <input name="notes" placeholder="Misal: butuh tambahan kabel HDMI" class="w-full rounded-lg border border-white/10 bg-slate-900 p-2 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none">
                                        </div>

                                        <button type="submit" class="w-full rounded-lg bg-emerald-600 py-2.5 text-xs font-bold text-white hover:bg-emerald-500 transition">
                                            Konfirmasi Booking Ini
                                        </button>
                                    </form>
                                </details>
                            @else
                                <button disabled class="w-full rounded-xl bg-slate-800 py-2.5 text-xs font-bold text-slate-500 cursor-not-allowed">
                                    Unit Sedang Tidak Tersedia
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block text-center rounded-xl bg-white/10 py-2.5 text-xs font-bold text-slate-300 hover:bg-orange-500 hover:text-white transition">
                                Masuk untuk Booking
                            </a>
                        @endauth
                    </div>
                </article>
            @empty
                <div class="col-span-full py-16 text-center text-slate-400 rounded-3xl bg-slate-900/50 border border-white/5">
                    <div class="text-4xl mb-2">🔍</div>
                    <h3 class="text-lg font-bold text-white">Tidak ada unit konsol yang cocok</h3>
                    <p class="text-xs text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian, kurangi jumlah pemain, atau naikkan batas budget.</p>
                    <a href="{{ route('catalogue') }}" class="mt-4 inline-block text-xs font-bold text-orange-400 hover:underline">
                        Reset Semua Filter
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Bakso Combo Bundling Section (Selling Point #14) -->
    @if($combos->isNotEmpty())
        <div class="mt-16 pt-10 border-t border-white/10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Paket Hemat</span>
                    <h2 class="text-2xl font-black text-white mt-0.5">Paket Bundling &middot; Bakso Combo</h2>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                @foreach($combos as $combo)
                    <div class="rounded-3xl border border-amber-500/30 bg-gradient-to-br from-amber-500/10 via-slate-900 to-slate-900 p-6 flex flex-col justify-between shadow-xl">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-amber-400 bg-amber-500/20 px-3 py-1 rounded-full border border-amber-500/30">
                                    📦 {{ $combo->name }}
                                </span>
                                <span class="text-xs text-slate-400">{{ $combo->duration_days }} Hari Durasi</span>
                            </div>
                            <h3 class="mt-4 text-xl font-bold text-white">{{ $combo->name }} Package</h3>
                            <p class="mt-1 text-xs text-slate-300">
                                Dilengkapi dengan <b>{{ $combo->controller_count }} Controller Original</b> untuk durasi penuh <b>{{ $combo->duration_days }} Hari</b> tanpa biaya stik tambahan.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 uppercase font-bold block">Harga Paket Hemat:</span>
                                <span class="text-2xl font-black text-amber-400">Rp{{ number_format($combo->price, 0, ',', '.') }}</span>
                            </div>
                            <span class="text-xs text-slate-400 bg-white/5 px-3 py-2 rounded-xl border border-white/5">
                                Pilih unit di atas & hubungi Admin
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-layouts.app>

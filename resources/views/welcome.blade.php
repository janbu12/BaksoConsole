<x-layouts.app title="Bakso Console — Rent Smarter. Play Better.">
    <!-- Hero Section -->
    <section class="relative pt-6 pb-16 lg:py-20 overflow-hidden">
        <!-- Glow accents -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 h-[450px] w-[600px] rounded-full bg-gradient-to-tr from-orange-500/20 via-amber-500/10 to-transparent blur-3xl pointer-events-none"></div>

        <div class="relative z-10 text-center max-w-4xl mx-auto">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 rounded-full border border-orange-500/30 bg-orange-500/10 px-4 py-1.5 text-xs font-bold text-orange-400 mb-6 backdrop-blur-md">
                <span class="flex h-2 w-2 rounded-full bg-orange-400 animate-pulse"></span>
                Sistem Rental Konsol Cerdas &middot; Gupron in da House
            </div>

            <!-- Headline -->
            <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-white leading-[1.1]">
                Rent Smarter.<br>
                <span class="bg-gradient-to-r from-orange-400 via-amber-300 to-orange-500 bg-clip-text text-transparent">
                    Play Better.
                </span>
            </h1>

            <p class="mt-6 text-base sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                Nikmati pengalaman rental PlayStation terbaik tanpa repot. Dilengkapi rekomendasi cerdas <b>SmartPick</b>, loyalitas <b>Bakso Rank</b>, timer sisa hari, dan opsi <b>Delivery kurir</b> langsung ke pintu rumah Anda.
            </p>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('catalogue') }}" class="flex items-center gap-2.5 rounded-2xl bg-gradient-to-r from-orange-500 to-amber-600 px-7 py-4 text-base font-bold text-white shadow-xl shadow-orange-500/25 hover:brightness-110 active:scale-95 transition">
                    <i class="fa-solid fa-gamepad"></i>
                    <span>Mulai Cari Konsol</span>
                </a>
                <a href="{{ route('catalogue') }}?players=4" class="flex items-center gap-2.5 rounded-2xl border border-white/10 bg-white/5 px-6 py-4 text-base font-bold text-slate-200 hover:bg-white/10 hover:border-orange-500/40 transition">
                    <i class="fa-solid fa-users"></i>
                    <span>Mode Mabar 4P</span>
                </a>
            </div>

            <!-- Stat Counters -->
            <div class="mt-14 grid grid-cols-2 gap-4 sm:grid-cols-4 max-w-3xl mx-auto">
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 backdrop-blur-md">
                    <div class="text-2xl sm:text-3xl font-black text-orange-400">{{ $stats['units'] }}+</div>
                    <div class="text-xs text-slate-400 mt-0.5">Unit Konsol Siap Main</div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 backdrop-blur-md">
                    <div class="text-2xl sm:text-3xl font-black text-amber-400">{{ $stats['categories'] }}</div>
                    <div class="text-xs text-slate-400 mt-0.5">Kategori & Genre</div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 backdrop-blur-md">
                    <div class="text-2xl sm:text-3xl font-black text-emerald-400">{{ $stats['members'] }}+</div>
                    <div class="text-xs text-slate-400 mt-0.5">Member Terdaftar</div>
                </div>
                <div class="rounded-2xl border border-white/10 bg-slate-900/60 p-4 backdrop-blur-md">
                    <div class="text-2xl sm:text-3xl font-black text-sky-400">100%</div>
                    <div class="text-xs text-slate-400 mt-0.5">Antar & Jemput Ready</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8 Selling Points Section -->
    <section class="py-16 border-t border-white/10">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-orange-400">Keunggulan Eksklusif</span>
            <h2 class="mt-2 text-3xl sm:text-4xl font-black text-white">8 Selling Point Bakso Console</h2>
            <p class="mt-3 text-sm text-slate-400">Dirancang khusus untuk menghadirkan kenyamanan sewa dan efisiensi manajemen operasional.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- 1. Smart Rental Recommendation -->
            <div class="group rounded-3xl border border-white/10 bg-slate-900/80 p-6 hover:border-orange-500/50 hover:bg-slate-900 transition-all duration-300">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-500/20 text-orange-400 text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <h3 class="mt-5 text-lg font-bold text-white">SmartPick Recommendation</h3>
                <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                    Sistem otomatis merekomendasikan unit paling pas berdasarkan jumlah pemain, durasi hari, kategori, dan budget Anda.
                </p>
            </div>

            <!-- 2. Bakso Rank -->
            <div class="group rounded-3xl border border-white/10 bg-slate-900/80 p-6 hover:border-orange-500/50 hover:bg-slate-900 transition-all duration-300">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-500/20 text-amber-400 text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h3 class="mt-5 text-lg font-bold text-white">Bakso Rank Loyalty</h3>
                <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                    Semakin banyak hari sewa kumulatif, semakin tinggi tingkatan rank dari Rookie hingga Legend dengan aneka diskon khusus.
                </p>
            </div>

            <!-- 3. Smart Rental Timer -->
            <div class="group rounded-3xl border border-white/10 bg-slate-900/80 p-6 hover:border-orange-500/50 hover:bg-slate-900 transition-all duration-300">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-500/20 text-sky-400 text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                <h3 class="mt-5 text-lg font-bold text-white">Smart Rental Timer</h3>
                <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                    Countdown sisa masa sewa akurat dalam satuan <b>HARI</b>, memudahkan pemantauan jadwal pengembalian tepat waktu.
                </p>
            </div>

            <!-- 4. Rental Warning System -->
            <div class="group rounded-3xl border border-white/10 bg-slate-900/80 p-6 hover:border-orange-500/50 hover:bg-slate-900 transition-all duration-300">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-500/20 text-red-400 text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="mt-5 text-lg font-bold text-white">Rental Warning System</h3>
                <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                    Notifikasi visual 3-level (Aman, Segera Berakhir, Terlambat) untuk meminimalisir denda sewa.
                </p>
            </div>

            <!-- 5. Live Console Availability -->
            <div class="group rounded-3xl border border-white/10 bg-slate-900/80 p-6 hover:border-orange-500/50 hover:bg-slate-900 transition-all duration-300">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-emerald-400 text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h3 class="mt-5 text-lg font-bold text-white">Live Console Availability</h3>
                <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                    Status unit konsol tampil real-time (Available, Booked, Rented, Maintenance) mencegah bentrok jadwal rental.
                </p>
            </div>

            <!-- 6. Mabar Capacity -->
            <div class="group rounded-3xl border border-white/10 bg-slate-900/80 p-6 hover:border-orange-500/50 hover:bg-slate-900 transition-all duration-300">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-500/20 text-purple-400 text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3 class="mt-5 text-lg font-bold text-white">Mabar Capacity Filter</h3>
                <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                    Pencarian instan berdasarkan jumlah pemain mabar (1–4+ Player) lengkap dengan stik controller tambahan.
                </p>
            </div>

            <!-- 7. Rental Heatmap & Analytics -->
            <div class="group rounded-3xl border border-white/10 bg-slate-900/80 p-6 hover:border-orange-500/50 hover:bg-slate-900 transition-all duration-300">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-500/20 text-blue-400 text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <h3 class="mt-5 text-lg font-bold text-white">Rental Heatmap & Insight</h3>
                <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                    Visualisasi intensitas penyewaan harian untuk mendeteksi Peak Rental Period serta statistik performa bisnis admin.
                </p>
            </div>

            <!-- 8. Pickup & Delivery Service -->
            <div class="group rounded-3xl border border-white/10 bg-slate-900/80 p-6 hover:border-orange-500/50 hover:bg-slate-900 transition-all duration-300 relative">
                <div class="absolute top-4 right-4 rounded-full bg-orange-500 px-2 py-0.5 text-[10px] font-bold text-white uppercase">
                    Fitur Baru
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-teal-500/20 text-teal-400 text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h3 class="mt-5 text-lg font-bold text-white">Pickup & Delivery Service</h3>
                <p class="mt-2 text-xs text-slate-400 leading-relaxed">
                    Opsi ambil mandiri di outlet atau diantar dan dijemput kurir langsung ke alamat tanpa perlu keluar rumah.
                </p>
            </div>
        </div>
    </section>

    <!-- Featured Units Showcase -->
    <section class="py-16 border-t border-white/10">
        <div class="flex flex-col sm:flex-row sm:end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-orange-400">Pilihan Populer</span>
                <h2 class="mt-2 text-3xl font-black text-white">Konsol Siap Sewa</h2>
                <p class="mt-1 text-sm text-slate-400">Unit terawat, stik responsif, dan siap langsung dimainkan.</p>
            </div>
            <a href="{{ route('catalogue') }}" class="text-sm font-bold text-orange-400 hover:text-orange-300 transition">
                Lihat Semua Konsol &rarr;
            </a>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($units as $unit)
                <div class="group relative rounded-3xl border border-white/10 bg-slate-900/90 p-6 hover:border-orange-500/50 transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <!-- Header Unit -->
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs font-bold uppercase tracking-wider text-orange-400 bg-orange-500/10 px-2.5 py-1 rounded-lg border border-orange-500/20">
                                {{ $unit->code }}
                            </span>
                            <span class="flex items-center gap-1.5 text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20">
                                <span class="h-2 w-2 rounded-full bg-emerald-400 animate-ping"></span> Available
                            </span>
                        </div>

                        <!-- Console Title & Icon -->
                        <div class="mt-6 flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-black text-white group-hover:text-orange-400 transition">{{ $unit->name }}</h3>
                                <div class="mt-1 flex items-center gap-2 text-xs text-slate-400">
                                    <span><i class="fa-solid fa-users text-xs mr-1"></i>{{ $unit->max_players }} Pemain</span> &middot;
                                    <span><i class="fa-solid fa-bolt text-xs mr-1"></i>4K 120Hz HDR</span>
                                </div>
                            </div>
                            <div class="text-3xl text-slate-600 group-hover:text-orange-400 transition-transform group-hover:scale-110">
                                <i class="fa-solid fa-gamepad"></i>
                            </div>
                        </div>

                        <!-- Category Tags -->
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @foreach($unit->categories as $category)
                                <span class="rounded-lg bg-white/5 px-2.5 py-1 text-[11px] font-medium text-slate-300 border border-white/5">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pricing & CTA -->
                    <div class="mt-8 border-t border-white/10 pt-4 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400">Harga Sewa</span>
                            <div class="text-xl font-black text-white">
                                Rp{{ number_format($unit->daily_price, 0, ',', '.') }}<span class="text-xs font-normal text-slate-400">/hari</span>
                            </div>
                        </div>
                        <a href="{{ route('catalogue') }}?q={{ urlencode($unit->name) }}" class="rounded-xl bg-orange-500 px-4 py-2 text-xs font-bold text-white hover:bg-orange-600 transition">
                            Sewa Sekarang
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Bakso Combo Bundling Section -->
    @if($combos->isNotEmpty())
        <section class="py-16 border-t border-white/10">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-400">Hemat & Lengkap</span>
                <h2 class="mt-2 text-3xl font-black text-white">Paket Bundling &middot; Bakso Combo</h2>
                <p class="mt-2 text-sm text-slate-400">Paket hemat all-in-one konsol + stik ekstra + durasi mabar maksimal.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 max-w-4xl mx-auto">
                @foreach($combos as $combo)
                    <div class="rounded-3xl border border-amber-500/30 bg-gradient-to-br from-amber-500/10 via-slate-900 to-slate-900 p-6 flex flex-col justify-between shadow-xl">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-400 bg-amber-500/20 px-3 py-1 rounded-full border border-amber-500/30">
                                    <i class="fa-solid fa-box"></i>
                                    <span>Paket Bundling</span>
                                </span>
                                <span class="text-xs text-slate-400">Durasi {{ $combo->duration_days }} Hari</span>
                            </div>
                            <h3 class="mt-4 text-2xl font-black text-white">{{ $combo->name }}</h3>
                            <ul class="mt-4 space-y-2 text-sm text-slate-300">
                                <li class="flex items-center gap-2">
                                    <i class="fa-solid fa-check text-emerald-400 text-xs"></i>
                                    <span>Termasuk Unit Konsol Generasi Terbaru</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fa-solid fa-check text-emerald-400 text-xs"></i>
                                    <span><b>{{ $combo->controller_count }} Unit Controller Original</b></span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fa-solid fa-check text-emerald-400 text-xs"></i>
                                    <span>Durasi Sewa Penuh <b>{{ $combo->duration_days }} Hari</b></span>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between">
                            <div>
                                <span class="text-xs text-slate-400">Harga Paket</span>
                                <div class="text-2xl font-black text-amber-400">Rp{{ number_format($combo->price, 0, ',', '.') }}</div>
                            </div>
                            <a href="{{ route('catalogue') }}" class="rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-2.5 text-xs font-bold text-white shadow-md hover:brightness-110 transition">
                                Pilih Paket Ini
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Bakso Rank Loyalty Tiers -->
    <section class="py-16 border-t border-white/10">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold uppercase tracking-widest text-orange-400">Program Loyalitas</span>
            <h2 class="mt-2 text-3xl font-black text-white">Tingkatan & Benefit Bakso Rank</h2>
            <p class="mt-2 text-sm text-slate-400">Dihitung otomatis dari akumulasi total hari sewa Anda.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($rankTiers as $tier)
                @php
                    $tierIcon = match($tier['level']) {
                        1 => 'fa-shield text-slate-400',
                        2 => 'fa-medal text-blue-400',
                        3 => 'fa-trophy text-purple-400',
                        4 => 'fa-crown text-amber-400',
                        default => 'fa-trophy text-orange-400',
                    };
                @endphp
                <div class="rounded-3xl border border-white/10 bg-slate-900/80 p-6 text-center hover:border-orange-500/40 transition">
                    <div class="text-3xl mb-3">
                        <i class="fa-solid {{ $tierIcon }}"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ $tier['name'] }}</h3>
                    <div class="mt-2 inline-block rounded-full bg-white/5 px-3 py-1 text-xs font-semibold text-orange-400 border border-white/5">
                        @if($tier['level'] === 1) 0 &ndash; 5 Hari
                        @elseif($tier['level'] === 2) 6 &ndash; 15 Hari
                        @elseif($tier['level'] === 3) 16 &ndash; 30 Hari
                        @else > 30 Hari
                        @endif
                    </div>
                    <p class="mt-4 text-xs text-slate-400 leading-relaxed">{{ $tier['benefit'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Delivery Callout Banner -->
    <section class="my-12 rounded-3xl border border-orange-500/30 bg-gradient-to-r from-orange-950/60 via-slate-900 to-amber-950/60 p-8 sm:p-12 relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-500/20 px-3 py-1 text-xs font-bold text-orange-400 border border-orange-500/30">
                <i class="fa-solid fa-truck-fast"></i>
                <span>Layanan Antar Jemput</span>
            </span>
            <h2 class="mt-4 text-3xl sm:text-4xl font-black text-white">Malas Keluar Rumah? Kami Antar Sampai Kamar!</h2>
            <p class="mt-3 text-sm text-slate-300 leading-relaxed">
                Pilih opsi <b>Delivery</b> saat checkout. Kurir Bakso Console akan mengantar konsol, menyeting kabel & TV, dan menjemput kembali saat masa sewa usai.
            </p>
            <div class="mt-6 flex gap-4">
                <a href="{{ route('catalogue') }}" class="rounded-xl bg-orange-500 px-6 py-3 text-sm font-bold text-white hover:bg-orange-600 shadow-lg shadow-orange-500/30 transition">
                    Sewa Konsol Sekarang &rarr;
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>

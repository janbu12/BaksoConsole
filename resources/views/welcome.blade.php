<x-layouts.app title="Bakso Console — Rent Smarter. Play Better.">
    <!-- 1. Hero Section matching screen.png -->
    <section class="relative pt-6 pb-16 lg:py-16 overflow-hidden">
        <!-- Subtle Glow accents -->
        <div class="absolute top-1/3 left-1/4 -translate-x-1/2 -translate-y-1/2 h-[450px] w-[550px] rounded-full bg-gradient-to-tr from-orange-500/15 via-amber-500/5 to-transparent blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/2 right-10 h-[350px] w-[450px] rounded-full bg-gradient-to-tr from-blue-500/10 via-cyan-500/5 to-transparent blur-3xl pointer-events-none"></div>

        <div class="grid lg:grid-cols-12 gap-10 items-center">
            <!-- Left Hero Content -->
            <div class="lg:col-span-6 space-y-6 text-left">
                
                <!-- Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-slate-900 dark:text-white leading-[1.1]">
                    Rent Smarter.<br>
                    Play Better.
                </h1>

                <!-- Subtitle -->
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed max-w-lg">
                    Premium service for PlayStation console gaming rental with AI smart recommendations, instant delivery, and flexible durations at Bakso Console.
                </p>

                <!-- Trust Badges (Capsules) -->
                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <div class="inline-flex items-center gap-2 rounded-full border border-emerald-300 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10 px-3.5 py-1 text-xs font-bold text-emerald-800 dark:text-emerald-300">
                        <i class="fa-solid fa-circle-check text-emerald-600 dark:text-emerald-400 text-xs"></i>
                        <span>Garansi Unit Original</span>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-full border border-amber-300 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10 px-3.5 py-1 text-xs font-bold text-amber-800 dark:text-amber-300">
                        <i class="fa-solid fa-star text-amber-500 dark:text-amber-400 text-xs"></i>
                        <span>Premium Access</span>
                    </div>
                </div>

                <!-- Call to Action Buttons -->
                <div class="flex flex-wrap items-center gap-4 pt-3">
                    <a href="{{ route('catalogue') }}" class="rounded-full bg-gradient-to-r from-[#f95721] to-amber-600 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-orange-500/30 hover:brightness-110 active:scale-95 transition">
                        Mulai Cari Konsol
                    </a>
                    <a href="{{ route('catalogue') }}?players=4" class="rounded-full border border-slate-300 dark:border-white/20 bg-white dark:bg-slate-900/90 px-8 py-3 text-sm font-semibold text-slate-800 dark:text-white hover:border-orange-500 hover:text-[#f95721] shadow-sm transition">
                        Mode Mabar 4P
                    </a>
                </div>
            </div>

            <!-- Right Hero Image (Console & Controller Mockup) -->
            <div class="lg:col-span-6 relative">
                <div class="relative rounded-3xl overflow-hidden border border-slate-200 dark:border-white/10 shadow-2xl bg-gradient-to-b from-white to-slate-100 dark:from-slate-900/80 dark:to-slate-950/90 p-2 group">
                    <img src="{{ asset('images/hero_ps5.png') }}" alt="PlayStation 5 Console Setup" class="w-full h-auto object-cover rounded-2xl transition-transform duration-500 group-hover:scale-[1.02]">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/40 dark:from-[#0b0f19]/60 via-transparent to-transparent pointer-events-none"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Keunggulan Eksklusif (8 Features Grid) matching screen.png -->
    <section class="py-16 border-t border-slate-200 dark:border-white/10">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-xs font-black uppercase tracking-[0.25em] text-slate-800 dark:text-white">
                KEUNGGULAN EKSKLUSIF
            </h2>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- 1. SmartPick Recommendation -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-orange-500/40 hover:shadow-lg hover:shadow-orange-500/5 transition duration-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/15 text-[#f95721] text-base border border-orange-500/20 mb-4">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">SmartPick Recommendation</h3>
                <p class="mt-2 text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Algoritma cerdas yang merekomendasikan unit paling pas sesuai durasi, kapasitas pemain, dan budget.
                </p>
            </div>

            <!-- 2. Bakso Rank Loyalty -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-orange-500/40 hover:shadow-lg hover:shadow-orange-500/5 transition duration-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/15 text-amber-500 text-base border border-amber-500/20 mb-4">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Bakso Rank Loyalty</h3>
                <p class="mt-2 text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Akumulasi hari rental untuk naik ke level Bronze, Silver, Gold, Platinum hingga Bakso Lord.
                </p>
            </div>

            <!-- 3. Smart Rental Timer -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-orange-500/40 hover:shadow-lg hover:shadow-orange-500/5 transition duration-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500/15 text-sky-500 text-base border border-sky-500/20 mb-4">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Smart Rental Timer</h3>
                <p class="mt-2 text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Countdown akurat masa sewa aktif dalam satuan hari untuk memudahkan pemantauan jadwal pengembalian.
                </p>
            </div>

            <!-- 4. Rental Warning System -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-orange-500/40 hover:shadow-lg hover:shadow-orange-500/5 transition duration-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-500/15 text-rose-500 text-base border border-rose-500/20 mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Rental Warning System</h3>
                <p class="mt-2 text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Notifikasi preventif otomatis saat mendekati jatuh tempo untuk mencegah denda keterlambatan.
                </p>
            </div>

            <!-- 5. Live Console Availability -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-orange-500/40 hover:shadow-lg hover:shadow-orange-500/5 transition duration-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 text-base border border-emerald-500/20 mb-4">
                    <i class="fa-solid fa-signal"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Live Console Availability</h3>
                <p class="mt-2 text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Status ketersediaan unit konsol terupdate real-time (Available, Booked, Rented, Maintenance).
                </p>
            </div>

            <!-- 6. Mabar Capacity Filter -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-orange-500/40 hover:shadow-lg hover:shadow-orange-500/5 transition duration-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/15 text-purple-600 dark:text-purple-400 text-base border border-purple-500/20 mb-4">
                    <i class="fa-solid fa-sliders"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Mabar Capacity Filter</h3>
                <p class="mt-2 text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Filter cepat konsol siap mabar dengan dukungan 2 hingga 4 controller stick original.
                </p>
            </div>

            <!-- 7. Rental Heatmap & Insight -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-orange-500/40 hover:shadow-lg hover:shadow-orange-500/5 transition duration-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/15 text-amber-500 text-base border border-amber-500/20 mb-4">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Rental Heatmap & Insight</h3>
                <p class="mt-2 text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Analisis data jam sibuk, tren konsol terfavorit, dan utilisasi unit untuk efisiensi maksimal.
                </p>
            </div>

            <!-- 8. Pickup & Delivery Service -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-orange-500/40 hover:shadow-lg hover:shadow-orange-500/5 transition duration-300">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/15 text-[#f95721] text-base border border-orange-500/20 mb-4">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pickup & Delivery Service</h3>
                <p class="mt-2 text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">
                    Pilihan fleksibel: ambil di toko terdekat atau diantar dan dijemput kurir langsung ke rumah.
                </p>
            </div>

        </div>
    </section>

    <!-- 3. Katalog Unit Konsol Populer matching screen.png -->
    <section class="py-16 border-t border-slate-200 dark:border-white/10">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white">Unit Konsol Unggulan</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pilih konsol PlayStation terawat dengan game terpopuler.</p>
            </div>
            <a href="{{ route('catalogue') }}" class="text-xs font-bold text-[#f95721] hover:underline flex items-center gap-1">
                <span>Lihat Semua Unit</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($units->take(4) as $unit)
                <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/80 p-4 shadow-sm dark:shadow-none hover:border-orange-500/40 transition duration-300 flex flex-col justify-between">
                    
                    <!-- Console Preview Card -->
                    <div class="rounded-xl bg-slate-50 dark:bg-slate-950/80 p-6 flex items-center justify-center min-h-[160px] border border-slate-200/60 dark:border-white/5 relative overflow-hidden">
                        <div class="text-slate-400 dark:text-slate-600 text-4xl text-center select-none transform hover:scale-110 transition duration-300">
                            <i class="fa-solid fa-gamepad text-[#f95721] text-5xl"></i>
                        </div>
                        <span class="absolute top-2.5 left-2.5 font-mono text-[9px] font-bold uppercase tracking-wider text-[#f95721] bg-orange-500/10 px-2 py-0.5 rounded border border-orange-500/20">
                            #{{ $unit->code }}
                        </span>
                        <span class="absolute top-2.5 right-2.5 rounded px-2 py-0.5 text-[9px] font-bold uppercase flex items-center gap-1 {{ $unit->firmware_type?->value === 'jailbreak' ? 'bg-purple-500/20 text-purple-700 dark:text-purple-300 border border-purple-500/30' : 'bg-blue-500/20 text-blue-700 dark:text-blue-300 border border-blue-500/30' }}">
                            <i class="fa-solid {{ $unit->firmware_type?->value === 'jailbreak' ? 'fa-hard-drive' : 'fa-globe' }} text-[9px]"></i>
                            <span>{{ $unit->firmware_type?->value === 'jailbreak' ? 'Jailbreak' : 'Online' }}</span>
                        </span>
                    </div>

                    <!-- Title & Details -->
                    <div class="mt-4 space-y-1">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $unit->name }}</h3>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-2">
                            <span>👥 {{ $unit->max_players }} Pemain</span>
                            @if($unit->model_number)
                                <span>&middot; {{ $unit->model_number }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Price & Status Badge -->
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 block">Tarif Sewa</span>
                            <span class="text-sm font-black text-[#f95721]">
                                Rp{{ number_format($unit->daily_price, 0, ',', '.') }}<span class="text-[10px] font-normal text-slate-500 dark:text-slate-400">/hari</span>
                            </span>
                        </div>

                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                            Available
                        </span>
                    </div>

                    <!-- Action Link -->
                    <a href="{{ route('catalogue') }}?q={{ urlencode($unit->code) }}" class="mt-3 block text-center rounded-xl bg-slate-100 dark:bg-white/5 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-[#f95721] hover:text-white transition">
                        Reservasi Unit
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 4. Paket Bundling • Bakso Combo matching screen.png -->
    <section class="py-16 border-t border-slate-200 dark:border-white/10">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
                Paket Bundling &middot; <span class="text-[#f95721]">Bakso Combo</span>
            </h2>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Pilihan paket rental hemat siap main untuk mabar seru bersama teman & keluarga.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            @forelse($combos as $combo)
                <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-6 flex flex-col justify-between hover:border-orange-500/40 transition duration-300 shadow-md dark:shadow-xl">
                    <div class="space-y-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#f95721]">Paket Hemat</span>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white mt-1">{{ $combo->name }}</h3>
                        </div>

                        <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-slate-950/60 p-3.5 rounded-2xl border border-slate-200/60 dark:border-white/5">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-2 font-bold uppercase tracking-wider">Isi Paket:</p>
                            <p class="text-xs text-slate-800 dark:text-slate-200">{{ $combo->description }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-white/10 space-y-3">
                        <div>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">Total Harga Paket</span>
                            <div class="text-2xl font-black text-[#f95721]">
                                Rp{{ number_format($combo->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <a href="{{ route('catalogue') }}" class="block w-full text-center rounded-full bg-[#f95721] py-2.5 text-xs font-bold text-white shadow-md hover:bg-[#ea4810] transition">
                            Pilih Paket Ini
                        </a>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-6 flex flex-col justify-between hover:border-orange-500/40 transition duration-300 shadow-md dark:shadow-xl">
                    <div class="space-y-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#f95721]">Paket Hemat</span>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white mt-1">Bakso Mabar 4P</h3>
                        </div>

                        <div class="text-xs space-y-1.5 bg-slate-50 dark:bg-slate-950/60 p-3.5 rounded-2xl border border-slate-200/60 dark:border-white/5">
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase">Include:</p>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; 1x PlayStation 5 Console</div>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; 4x DualSense Wireless Stick</div>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; eFootball 2024 & Tekken 8</div>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; Durasi: 2 Hari Sewa</div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-white/10 space-y-3">
                        <div>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">Harga Paket Bundling</span>
                            <div class="text-2xl font-black text-[#f95721]">Rp 150.000</div>
                        </div>

                        <a href="{{ route('catalogue') }}" class="block w-full text-center rounded-full bg-[#f95721] py-2.5 text-xs font-bold text-white shadow-md hover:bg-[#ea4810] transition">
                            Pilih Paket Ini
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-6 flex flex-col justify-between hover:border-orange-500/40 transition duration-300 shadow-md dark:shadow-xl">
                    <div class="space-y-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#f95721]">Paket Hemat</span>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white mt-1">Bakso Family Fun</h3>
                        </div>

                        <div class="text-xs space-y-1.5 bg-slate-50 dark:bg-slate-950/60 p-3.5 rounded-2xl border border-slate-200/60 dark:border-white/5">
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase">Include:</p>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; 1x PS4 Slim 1TB + 2 Stick</div>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; Paket 250+ Game Family</div>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; Bebas Ongkir Antar Jemput</div>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; Durasi: 3 Hari Sewa</div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-white/10 space-y-3">
                        <div>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">Harga Paket Bundling</span>
                            <div class="text-2xl font-black text-[#f95721]">Rp 120.000</div>
                        </div>

                        <a href="{{ route('catalogue') }}" class="block w-full text-center rounded-full bg-[#f95721] py-2.5 text-xs font-bold text-white shadow-md hover:bg-[#ea4810] transition">
                            Pilih Paket Ini
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-6 flex flex-col justify-between hover:border-orange-500/40 transition duration-300 shadow-md dark:shadow-xl">
                    <div class="space-y-4">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#f95721]">Paket Hemat</span>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white mt-1">Bakso Weekend Marathon</h3>
                        </div>

                        <div class="text-xs space-y-1.5 bg-slate-50 dark:bg-slate-950/60 p-3.5 rounded-2xl border border-slate-200/60 dark:border-white/5">
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase">Include:</p>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; 1x PS5 Disc Edition + 2 Stick</div>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; God of War & Spider-Man 2</div>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; Garansi Support 24 Jam</div>
                            <div class="text-xs text-slate-800 dark:text-slate-200">&bull; Durasi: 4 Hari Sewa</div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-white/10 space-y-3">
                        <div>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">Harga Paket Bundling</span>
                            <div class="text-2xl font-black text-[#f95721]">Rp 200.000</div>
                        </div>

                        <a href="{{ route('catalogue') }}" class="block w-full text-center rounded-full bg-[#f95721] py-2.5 text-xs font-bold text-white shadow-md hover:bg-[#ea4810] transition">
                            Pilih Paket Ini
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- 5. Tingkatan & Benefit Bakso Rank matching screen.png -->
    <section class="py-16 border-t border-slate-200 dark:border-white/10">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
                Tingkatan & Benefit <span class="text-[#f95721]">Bakso Rank</span>
            </h2>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Tingkatkan akumulasi hari rental Anda untuk membuka berbagai diskon dan privilege eksklusif.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            
            <!-- Tier 1: Bronze -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-amber-600/40 transition duration-300 flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Tier 1</span>
                    <h3 class="text-lg font-black text-amber-600 dark:text-amber-500 mt-1">Bronze Tier</h3>
                    <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-2">Member baru (0 - 4 hari sewa). Akses reguler ke semua katalog unit.</p>
                </div>
                <a href="{{ route('leaderboard') }}" class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-orange-500 transition">
                    <span>Bakso Rank</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- Tier 2: Silver -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-slate-400/40 transition duration-300 flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Tier 2</span>
                    <h3 class="text-lg font-black text-slate-700 dark:text-slate-200 mt-1">Silver Gamer</h3>
                    <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-2">Akumulasi 5 - 14 hari sewa. Diskon 5% untuk semua sewa berikutnya.</p>
                </div>
                <a href="{{ route('leaderboard') }}" class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-orange-500 transition">
                    <span>Bakso Rank</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- Tier 3: Gold -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-sm dark:shadow-none hover:border-yellow-500/40 transition duration-300 flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Tier 3</span>
                    <h3 class="text-lg font-black text-yellow-600 dark:text-yellow-400 mt-1">Gold Master</h3>
                    <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-2">Akumulasi 15 - 29 hari sewa. Diskon 10% dan prioritas pengantaran kurir.</p>
                </div>
                <a href="{{ route('leaderboard') }}" class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300 hover:text-orange-500 transition">
                    <span>Bakso Rank</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <!-- Tier 4: Bakso Lord -->
            <div class="rounded-2xl border border-orange-500/40 bg-white dark:bg-gradient-to-b dark:from-slate-900/90 dark:to-slate-950 p-5 shadow-md dark:shadow-lg dark:shadow-orange-500/10 hover:border-orange-500 transition duration-300 flex flex-col justify-between">
                <div>
                    <span class="text-[10px] font-bold uppercase text-[#f95721] tracking-wider">Tier 4 &middot; Elite</span>
                    <h3 class="text-lg font-black text-[#f95721] mt-1 flex items-center gap-1.5">
                        <span>Bakso Lord</span>
                        <i class="fa-solid fa-crown text-amber-400 text-sm"></i>
                    </h3>
                    <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-2">Akumulasi 30+ hari sewa. Diskon 15%, gratis 1 hari sewa, dan merchandise eksklusif.</p>
                </div>
                <a href="{{ route('leaderboard') }}" class="mt-6 inline-flex items-center gap-1.5 text-xs font-bold text-[#f95721] hover:underline transition">
                    <span>Bakso Rank</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

        </div>
    </section>
</x-layouts.app>

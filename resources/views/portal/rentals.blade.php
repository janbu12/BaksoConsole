<x-layouts.app title="Rental Aktif & Smart Timer">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="text-xs font-bold uppercase tracking-wider text-orange-400">Pemantauan Masa Sewa</div>
            <h1 class="text-3xl font-black text-white mt-1">Rental Aktif & Smart Timer</h1>
            <p class="text-sm text-slate-400 mt-0.5">Pantau hitung mundur sisa hari masa sewa, ajukan perpanjangan, atau atur penjemputan unit oleh kurir.</p>
        </div>
        <div class="rounded-2xl bg-white/5 px-4 py-2 text-xs border border-white/10 text-slate-300">
            Maksimal <b class="text-orange-400">2 Unit Aktif</b> per Member
        </div>
    </div>

    <!-- Rentals List -->
    <div class="mt-8 space-y-8">
        @forelse($rentals as $rental)
            @php
                $warn = $warnings[$rental->id];
                $deliveryOut = $rental->deliveries->firstWhere('type.value', 'delivery_out');
                $deliveryReturn = $rental->deliveries->firstWhere('type.value', 'delivery_return');
            @endphp
            <article class="rounded-3xl border p-6 sm:p-8 {{ $warn['bg_card'] }} shadow-2xl transition">
                <!-- Top Bar: Code, Unit, and Warning Badge -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-white/10">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-bold text-orange-400 bg-orange-500/10 px-2.5 py-1 rounded-lg border border-orange-500/20">
                                {{ $rental->rental_code }}
                            </span>
                            <span class="text-xs text-slate-400">Mulai: {{ $rental->start_date->format('d M Y') }}</span>
                        </div>
                        <h2 class="text-2xl font-black text-white mt-2">{{ $rental->unit->name }} ({{ $rental->unit->code }})</h2>
                        <div class="text-xs text-slate-300 mt-1">
                            Batas Pengembalian (Due Date): <b class="text-white text-sm">{{ $rental->due_date->format('d M Y') }}</b> &middot; Durasi: <b>{{ $rental->duration_days }} Hari</b>
                        </div>
                    </div>

                    <!-- Smart Timer Countdown & Warning -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <div class="rounded-2xl bg-slate-950 p-4 border border-white/10 text-center sm:text-right">
                            <div class="text-[10px] uppercase font-bold text-slate-400">⏳ Smart Rental Timer</div>
                            <div class="text-lg font-black text-white mt-0.5">
                                @if($warn['remaining_days'] < 0)
                                    <span class="text-red-400">Lewat {{ abs($warn['remaining_days']) }} Hari</span>
                                @elseif($warn['remaining_days'] === 0)
                                    <span class="text-yellow-400">Hari ini Terakhir!</span>
                                @else
                                    <span class="text-emerald-400">{{ $warn['remaining_days'] }} Hari Tersisa</span>
                                @endif
                            </div>
                        </div>

                        <span class="rounded-2xl px-4 py-3.5 text-xs font-bold {{ $warn['badge_class'] }} text-center">
                            {{ $warn['code'] }}
                        </span>
                    </div>
                </div>

                <!-- Warning Message Box (Rental Warning System) -->
                <div class="mt-4 flex items-center gap-3 rounded-2xl bg-slate-950/80 p-4 text-xs text-slate-200 border border-white/5">
                    <span class="text-2xl">{{ $warn['icon'] }}</span>
                    <div>
                        <div class="font-bold text-white">{{ $warn['title'] }}</div>
                        <p class="mt-0.5 text-slate-300">{{ $warn['message'] }}</p>
                    </div>
                </div>

                @if($deliveryOut?->method->value === 'delivery')
                    @php
                        $deliveryStatus = $deliveryOut->status->value;
                        $deliveryStep = match($deliveryStatus) {
                            'waiting' => 1,
                            'in_transit' => 2,
                            'received' => 3,
                            default => 0,
                        };
                        $deliveryStatusText = match($deliveryStatus) {
                            'waiting' => 'Menunggu Kurir',
                            'in_transit' => 'Dalam Perjalanan',
                            'received' => 'Sudah Diterima',
                            'cancelled' => 'Pengiriman Dibatalkan',
                            default => ucwords(str_replace('_', ' ', $deliveryStatus)),
                        };
                    @endphp

                    <section class="mt-6 rounded-2xl border {{ $deliveryStatus === 'cancelled' ? 'border-red-500/30 bg-red-950/20' : 'border-blue-500/30 bg-blue-950/20' }} p-5" aria-label="Status pengiriman unit">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-truck-fast text-blue-400"></i>
                                    <h3 class="text-sm font-bold text-white">Status Pengiriman ke Rumah</h3>
                                </div>
                                <p class="mt-1 text-[11px] text-slate-400">Pantau perjalanan {{ $rental->unit->name }} menuju alamat Anda.</p>
                            </div>
                            <span class="w-fit rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-wide {{ match($deliveryStatus) {
                                'waiting' => 'border-slate-500/30 bg-slate-500/15 text-slate-300',
                                'in_transit' => 'border-amber-500/30 bg-amber-500/15 text-amber-300',
                                'received' => 'border-emerald-500/30 bg-emerald-500/15 text-emerald-300',
                                'cancelled' => 'border-red-500/30 bg-red-500/15 text-red-300',
                                default => 'border-blue-500/30 bg-blue-500/15 text-blue-300',
                            } }}">
                                {{ $deliveryStatusText }}
                            </span>
                        </div>

                        @if($deliveryStatus !== 'cancelled')
                            <div class="mt-5 grid grid-cols-3 gap-2" role="list" aria-label="Tahapan pengiriman">
                                @foreach([
                                    1 => ['Menunggu Kurir', 'Pesanan disiapkan'],
                                    2 => ['Dalam Perjalanan', 'Kurir menuju rumah'],
                                    3 => ['Sudah Diterima', 'Unit tiba di tujuan'],
                                ] as $step => [$title, $description])
                                    <div class="relative text-center" role="listitem">
                                        @if($step < 3)
                                            <div class="absolute left-1/2 top-3 h-0.5 w-full {{ $deliveryStep > $step ? 'bg-blue-400' : 'bg-white/10' }}"></div>
                                        @endif
                                        <div class="relative mx-auto flex h-6 w-6 items-center justify-center rounded-full border text-[10px] font-black {{ $deliveryStep >= $step ? 'border-blue-400 bg-blue-500 text-white' : 'border-white/15 bg-slate-900 text-slate-500' }}">
                                            @if($deliveryStep > $step || ($deliveryStep === 3 && $step === 3))
                                                <i class="fa-solid fa-check"></i>
                                            @else
                                                {{ $step }}
                                            @endif
                                        </div>
                                        <div class="mt-2 text-[10px] font-bold {{ $deliveryStep >= $step ? 'text-white' : 'text-slate-500' }}">{{ $title }}</div>
                                        <div class="mt-0.5 hidden text-[9px] text-slate-500 sm:block">{{ $description }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-4 rounded-xl border border-red-500/20 bg-red-500/10 p-3 text-xs text-red-200">
                                Pengiriman dibatalkan. Silakan hubungi admin Bakso Console untuk informasi lebih lanjut.
                            </div>
                        @endif

                        <div class="mt-5 grid gap-3 border-t border-white/10 pt-4 text-[11px] sm:grid-cols-3">
                            <div>
                                <span class="block text-[9px] font-bold uppercase tracking-wide text-slate-500">Alamat tujuan</span>
                                <strong class="mt-1 block text-slate-200">{{ $deliveryOut->address ?: 'Alamat belum tersedia' }}</strong>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold uppercase tracking-wide text-slate-500">Kurir</span>
                                <strong class="mt-1 block {{ $deliveryOut->courier_name ? 'text-slate-200' : 'text-amber-300' }}">{{ $deliveryOut->courier_name ?: 'Belum ditugaskan' }}</strong>
                            </div>
                            <div>
                                <span class="block text-[9px] font-bold uppercase tracking-wide text-slate-500">Terakhir diperbarui</span>
                                <strong class="mt-1 block text-slate-200">{{ $deliveryOut->updated_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }} WIB</strong>
                            </div>
                        </div>
                    </section>
                @endif

                <!-- Financial & Service Breakdown -->
                <div class="mt-6 grid gap-4 sm:grid-cols-3 text-xs">
                    <div class="rounded-2xl bg-slate-950/60 p-4 border border-white/5">
                        <span class="text-slate-400 block mb-1">Subtotal Sewa</span>
                        <b class="text-base text-white">Rp{{ number_format($rental->subtotal, 0, ',', '.') }}</b>
                    </div>
                    <div class="rounded-2xl bg-slate-950/60 p-4 border border-white/5 flex flex-col justify-between">
                        <div>
                            <span class="text-slate-400 block mb-1">Status Transaksi</span>
                            <b class="text-base uppercase {{ $rental->transaction?->status->value === 'paid' ? 'text-emerald-400' : 'text-amber-400' }}">
                                {{ $rental->transaction?->status->value ?? 'Pending' }}
                            </b>
                        </div>
                        @if($rental->transaction?->status->value === 'pending')
                            @php
                                $midtransEnabled = !empty(config('services.midtrans.server_key'));
                            @endphp

                            <div class="mt-2.5 space-y-1.5">
                                @if($midtransEnabled)
                                    <form method="POST" action="/rentals/{{ $rental->id }}/pay">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 py-2 text-[11px] font-bold text-white shadow-md shadow-blue-500/20 hover:brightness-110 transition">
                                            <i class="fa-solid fa-credit-card"></i> Bayar via Midtrans (Online)
                                        </button>
                                    </form>

                                    <form method="POST" action="/rentals/{{ $rental->id }}/simulate-pay">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-1 rounded-lg border border-white/10 bg-slate-900 py-1 text-[10px] font-semibold text-slate-300 hover:bg-orange-500 hover:text-white hover:border-orange-500 transition">
                                            <span>⚡</span> Mode Simulasi Bayar
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="/rentals/{{ $rental->id }}/simulate-pay">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 py-2 text-[11px] font-bold text-white shadow-md shadow-orange-500/20 hover:brightness-110 transition">
                                            <span>⚡</span> Simulasi Bayar Lunas
                                        </button>
                                    </form>
                                    <span class="block text-center text-[9px] text-slate-500">Mode Simulasi (Midtrans Key Belum Terisi)</span>
                                @endif
                            </div>
                        @endif
                        @if($rental->transaction)
                            <a href="{{ route('rentals.invoice.download', $rental) }}" class="mt-2 flex w-full items-center justify-center gap-1.5 rounded-lg border border-white/10 bg-white/5 py-1.5 text-[10px] font-bold text-slate-200 transition hover:border-orange-500/40 hover:text-orange-300">
                                <i class="fa-solid fa-file-arrow-down"></i> Download Invoice PDF
                            </a>
                        @endif
                    </div>
                    <div class="rounded-2xl bg-slate-950/60 p-4 border border-white/5">
                        <span class="text-slate-400 block mb-1">Total Pembayaran</span>
                        <b class="text-base text-orange-400">Rp{{ number_format($rental->transaction?->total_amount ?? $rental->subtotal, 0, ',', '.') }}</b>
                        <div class="text-[10px] text-slate-500 mt-1">(Sudah termasuk ongkir & denda)</div>
                    </div>
                </div>

                <!-- Interactive Features: Rental Extension & Delivery Return -->
                @if(in_array($rental->status->value, ['active', 'overdue']))
                    <div class="mt-8 grid gap-6 md:grid-cols-2 pt-6 border-t border-white/10">
                        <!-- Rental Extension Form (Feature #15) -->
                        <div class="rounded-2xl border border-white/10 bg-slate-950/80 p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg">🔄</span>
                                <h3 class="font-bold text-white text-sm">Ajukan Perpanjangan Sewa</h3>
                            </div>
                            <p class="text-xs text-slate-400 mb-4">Ingin main lebih lama? Masukkan tanggal pengembalian baru untuk ditinjau oleh Admin.</p>

                            <form method="POST" action="/rentals/{{ $rental->id }}/extensions" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Perpanjang Sampai Tanggal:</label>
                                    <input type="date" name="requested_due_date" min="{{ $rental->due_date->addDay()->toDateString() }}" class="w-full rounded-xl border border-white/10 bg-slate-900 px-3 py-2 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Alasan Perpanjangan (Opsional):</label>
                                    <input name="reason" placeholder="Contoh: Belum tamat game / ada mabar akhir pekan" class="w-full rounded-xl border border-white/10 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none">
                                </div>
                                <button type="submit" class="w-full rounded-xl bg-orange-500 py-2.5 text-xs font-bold text-white hover:bg-orange-600 transition">
                                    Kirim Pengajuan Perpanjangan
                                </button>
                            </form>

                            <!-- Extensions History -->
                            @if($rental->extensions->isNotEmpty())
                                <div class="mt-4 pt-3 border-t border-white/5 space-y-2">
                                    <span class="text-[10px] font-bold uppercase text-slate-400 block">Riwayat Pengajuan:</span>
                                    @foreach($rental->extensions as $ext)
                                        <div class="flex items-center justify-between text-xs p-2 rounded-lg bg-white/5">
                                            <span>Sampai {{ $ext->requested_due_date->format('d M Y') }} (+{{ $ext->additional_days }} Hari)</span>
                                            <span class="font-bold uppercase {{ $ext->status->value === 'approved' ? 'text-emerald-400' : ($ext->status->value === 'rejected' ? 'text-red-400' : 'text-yellow-400') }}">
                                                {{ $ext->status->value }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Pickup & Delivery Return Form (Feature #24) -->
                        <div class="rounded-2xl border border-white/10 bg-slate-950/80 p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg">🚚</span>
                                <h3 class="font-bold text-white text-sm">Metode Pengembalian Unit</h3>
                            </div>
                            <p class="text-xs text-slate-400 mb-4">Pilih apakah Anda akan mengantar unit sendiri ke outlet atau meminta kurir menjemput ke rumah.</p>

                            <form method="POST" action="/rentals/{{ $rental->id }}/deliveries" class="space-y-3">
                                @csrf
                                <input type="hidden" name="type" value="delivery_return">

                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Pilihan Layanan Pengembalian:</label>
                                    <select name="method" class="w-full rounded-xl border border-white/10 bg-slate-900 px-3 py-2 text-xs text-white focus:border-orange-500 focus:outline-none">
                                        <option value="pickup" @selected($deliveryReturn?->method->value === 'pickup')>🏪 Antar Sendiri ke Outlet (Gratis)</option>
                                        <option value="delivery" @selected($deliveryReturn?->method->value === 'delivery')>🛵 Dijemput Kurir ke Rumah (+Rp15.000)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Alamat Penjemputan (Jika Kurir):</label>
                                    <input name="address" value="{{ $deliveryReturn?->address ?? auth()->user()->profile?->address }}" placeholder="Alamat lengkap penjemputan konsol" class="w-full rounded-xl border border-white/10 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nomor Kontak / WhatsApp:</label>
                                    <input name="contact_number" value="{{ $deliveryReturn?->contact_number ?? auth()->user()->profile?->phone }}" placeholder="081234567890" class="w-full rounded-xl border border-white/10 bg-slate-900 px-3 py-2 text-xs text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none">
                                </div>

                                <button type="submit" class="w-full rounded-xl bg-teal-600 py-2.5 text-xs font-bold text-white hover:bg-teal-500 transition">
                                    Simpan Metode Pengembalian
                                </button>
                            </form>

                            @if($deliveryReturn)
                                <div class="mt-4 pt-3 border-t border-white/5 text-xs">
                                    <span class="text-slate-400">Status Penjemputan:</span>
                                    <b class="text-teal-400 capitalize">{{ str_replace('_', ' ', $deliveryReturn->status->value) }}</b>
                                    @if($deliveryReturn->courier_name)
                                        <span class="text-slate-400">&middot; Kurir: <b>{{ $deliveryReturn->courier_name }}</b></span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </article>
        @empty
            <div class="py-16 text-center text-slate-500 rounded-3xl bg-slate-900/50 border border-white/5">
                <div class="text-4xl mb-2">🎮</div>
                <h3 class="text-lg font-bold text-white">Tidak Ada Rental Aktif</h3>
                <p class="text-xs text-slate-400 mt-1">Anda saat ini sedang tidak menyewa unit konsol apapun.</p>
                <a href="{{ route('catalogue') }}" class="mt-4 inline-block rounded-xl bg-orange-500 px-5 py-2.5 text-xs font-bold text-white hover:bg-orange-600 transition">
                    Cari Konsol di Katalog
                </a>
            </div>
        @endforelse
    </div>
</x-layouts.app>

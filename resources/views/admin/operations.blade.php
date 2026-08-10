<x-layouts.app title="Admin Operations Hub — Bakso Insight">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-orange-400">
                <span class="flex h-2 w-2 rounded-full bg-orange-400 animate-pulse"></span>
                Operations & Analytics Hub &middot; Admin Panel
            </div>
            <h1 class="text-3xl font-black text-white mt-1">Bakso Insight & Operations</h1>
            <p class="text-sm text-slate-400 mt-0.5">Pusat kendali seluruh operasional unit, anggota, transaksi, pengembalian, dan layanan delivery.</p>
        </div>
            <a href="{{ route('admin.history.print') }}" target="_blank" class="rounded-xl bg-white px-5 py-2.5 text-xs font-bold text-slate-950 shadow-lg hover:bg-slate-200 transition flex items-center gap-2">
                <span><i class="fa-solid fa-print"></i></span> Cetak Laporan Transaksi
            </a>
        </div>
    </div>

    <!-- Section 1: Bakso Insight & Analytics Cards (Selling Point #7) -->
    <div class="mt-8">
        <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Statistik Bisnis & Operasional</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($stats as $label => $value)
                <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 shadow-xl">
                    <span class="text-xs text-slate-400 font-medium">{{ $label }}</span>
                    <div class="text-2xl font-black text-white mt-1">
                        @if(str_contains(strtolower($label), 'pendapatan') || str_contains(strtolower($label), 'denda') || str_contains(strtolower($label), 'transaksi'))
                            Rp{{ number_format($value, 0, ',', '.') }}
                        @elseif(str_contains(strtolower($label), 'hari'))
                            {{ number_format($value) }} <span class="text-xs font-normal text-slate-400">Hari</span>
                        @else
                            {{ number_format($value) }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Highlight Widgets: Popular Unit, Active Member, Delivery Ratio -->
            <!-- Popular Unit -->
            <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 shadow-xl">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider"><i class="fa-solid fa-gamepad"></i> Unit Paling Laris</div>
                <div class="mt-2 text-xl font-bold text-white">{{ $popularUnit?->name ?? 'Belum ada data' }}</div>
                <div class="text-xs text-orange-400 font-semibold mt-0.5">
                    {{ $popularUnit?->rentals_count ?? 0 }} Total Penyewaan
                </div>
            </div>

            <!-- Active Member -->
            <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 shadow-xl">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider"><i class="fa-solid fa-user"></i> Anggota paling aktif</div>
                <div class="mt-2 text-xl font-bold text-white">{{ $activeMember?->name ?? 'Belum ada data' }}</div>
                <div class="text-xs text-emerald-400 font-semibold mt-0.5">
                    {{ $activeMember?->rentals_count ?? 0 }} Kali Sewa
                </div>
            </div>

            <!-- Delivery vs Pickup Mix (Feature #24 Analytics) -->
            <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 shadow-xl">
                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider"><i class="fa-solid fa-truck"></i> Proporsi Pickup vs Delivery</div>
                <div class="mt-2 flex items-center justify-between text-xs font-bold text-white">
                    <span>Pickup: {{ $deliveryMix['pickup'] }} ({{ $deliveryMix['pickup_percent'] }}%)</span>
                    <span>Delivery: {{ $deliveryMix['delivery'] }} ({{ $deliveryMix['delivery_percent'] }}%)</span>
                </div>
                <div class="mt-2 h-2.5 w-full rounded-full bg-slate-950 overflow-hidden flex border border-white/5">
                    <div class="h-full bg-orange-500" style="width: {{ $deliveryMix['pickup_percent'] }}%" title="Pickup"></div>
                    <div class="h-full bg-teal-400" style="width: {{ $deliveryMix['delivery_percent'] }}%" title="Delivery"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Rental Heatmap & Peak Period (Selling Point #6) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-orange-400">Pola Permintaan Harian</span>
                <h2 class="text-xl font-bold text-white mt-0.5"><i class="fa-solid fa-chart-simple"></i> Rental Heatmap (Aktivitas Harian)</h2>
                <p class="text-xs text-slate-400">Distribusi jumlah penyewaan per tanggal untuk mengidentifikasi lonjakan permintaan rental.</p>
            </div>

            @if($peakDay)
                <div class="rounded-2xl bg-gradient-to-r from-orange-500/20 to-amber-500/20 border border-orange-500/30 px-4 py-2.5">
                    <div class="text-[10px] font-bold uppercase text-orange-400"><i class="fa-solid fa-fire"></i> Peak Rental Period</div>
                    <div class="text-xs font-bold text-white">
                        Tanggal {{ $peakDay->start_date }} &middot; <span class="text-orange-300">{{ $peakDay->total }} Penyewaan</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8">
            @forelse($heatmap as $day)
                <div class="rounded-2xl border border-white/10 bg-slate-950 p-3 text-center hover:border-orange-500/40 transition">
                    <div class="text-[11px] text-slate-400">{{ date('d M', strtotime($day->start_date)) }}</div>
                    <div class="text-xl font-black text-orange-400 mt-1">{{ $day->total }}</div>
                    <div class="text-[9px] uppercase tracking-wider text-slate-500 mt-0.5">{{ $day->total_days }} Hari Total</div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center text-xs text-slate-500">
                    Belum ada data pola penyewaan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Section 3: Management Grid Forms -->
    <div class="mt-12">
        <h2 class="text-xl font-bold text-white mb-6"><i class="fa-solid fa-wrench"></i> Tambah Data Operasional Baru</h2>

        <div class="grid gap-6 lg:grid-cols-4">
            <!-- 1. Tambah Unit -->
            <form method="POST" action="/admin/units" class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 space-y-3 shadow-xl">
                @csrf
                <div class="font-bold text-sm text-white flex items-center gap-1.5">
                    <span><i class="fa-solid fa-gamepad"></i></span> Tambah Unit Konsol
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nama Konsol</label>
                    <input name="name" placeholder="PlayStation 5" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Kode Unit Unik</label>
                    <input name="code" placeholder="PS5-004" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white uppercase focus:border-orange-500 focus:outline-none" required>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Tarif/Hari (Rp)</label>
                        <input name="daily_price" type="number" placeholder="50000" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Maks Pemain</label>
                        <input name="max_players" type="number" min="1" max="8" value="4" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Kategori Konsol</label>
                    <div class="flex flex-wrap gap-2 max-h-24 overflow-y-auto p-1.5 bg-slate-950 rounded-xl border border-white/5 text-xs">
                        @foreach($categories as $c)
                            <label class="flex items-center gap-1 text-[11px] text-slate-300">
                                <input type="checkbox" name="category_ids[]" value="{{ $c->id }}"> {{ $c->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full rounded-xl bg-orange-500 py-2.5 text-xs font-bold text-white hover:bg-orange-600 transition">
                    Simpan Unit Baru
                </button>
            </form>

            <!-- 2. Tambah Anggota -->
            <form method="POST" action="/admin/members" class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 space-y-3 shadow-xl">
                @csrf
                <div class="font-bold text-sm text-white flex items-center gap-1.5">
                    <span><i class="fa-solid fa-user-plus"></i></span> Tambah Anggota Baru
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nama Lengkap</label>
                    <input name="name" placeholder="Nama member" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Email</label>
                    <input name="email" type="email" placeholder="email@member.com" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Telepon / WA</label>
                    <input name="phone" placeholder="081234567890" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Password</label>
                        <input type="password" name="password" placeholder="••••••••" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Konfirmasi</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-xl bg-orange-500 py-2.5 text-xs font-bold text-white hover:bg-orange-600 transition">
                    Daftarkan Anggota
                </button>
            </form>

            <!-- 3. Tambah Kategori -->
            <form method="POST" action="/admin/categories" class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 space-y-3 shadow-xl">
                @csrf
                <div class="font-bold text-sm text-white flex items-center gap-1.5">
                    <span><i class="fa-solid fa-tags"></i></span> Tambah Kategori
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nama Kategori</label>
                    <input name="name" placeholder="Misal: Racing, RPG, Multiplayer" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Keterangan kategori" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none"></textarea>
                </div>

                <button type="submit" class="w-full rounded-xl bg-orange-500 py-2.5 text-xs font-bold text-white hover:bg-orange-600 transition">
                    Simpan Kategori
                </button>
            </form>

            <!-- 4. Tambah Combo -->
            <form method="POST" action="/admin/combos" class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 space-y-3 shadow-xl">
                @csrf
                <div class="font-bold text-sm text-white flex items-center gap-1.5">
                    <span><i class="fa-solid fa-box"></i></span> Tambah Bakso Combo
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Nama Paket</label>
                    <input name="name" placeholder="Bakso Weekend" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Durasi (Hari)</label>
                        <input name="duration_days" type="number" min="1" max="5" value="3" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Jml Controller</label>
                        <input name="controller_count" type="number" min="1" max="4" value="4" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Harga Paket (Rp)</label>
                    <input name="price" type="number" placeholder="150000" class="w-full rounded-xl border border-white/10 bg-slate-950 p-2.5 text-xs text-white focus:border-orange-500 focus:outline-none" required>
                </div>

                <button type="submit" class="w-full rounded-xl bg-amber-600 py-2.5 text-xs font-bold text-white hover:bg-amber-500 transition">
                    Buat Paket Combo
                </button>
            </form>
        </div>
    </div>

    <!-- Section 4: Units Management Table -->
    <div class="mt-12">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-white"><i class="fa-solid fa-gamepad"></i> Kelola Unit Konsol & Status Ketersediaan</h2>
            <span class="text-xs text-slate-400">{{ $units->count() }} Total Unit</span>
        </div>

        <div class="overflow-x-auto rounded-3xl border border-white/10 bg-slate-900/90 shadow-2xl">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-white/10 bg-slate-950/60 text-slate-400 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-4">Kode Unit</th>
                        <th class="p-4">Nama Konsol</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Tarif/Hari</th>
                        <th class="p-4">Kapasitas</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                    @foreach($units as $unit)
                        <tr class="hover:bg-white/5 transition">
                            <td class="p-4 font-mono font-bold text-orange-400">{{ $unit->code }}</td>
                            <td class="p-4 font-bold text-white">{{ $unit->name }}</td>
                            <td class="p-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($unit->categories as $c)
                                        <span class="bg-white/5 px-2 py-0.5 rounded text-[10px]">{{ $c->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="p-4 font-semibold">Rp{{ number_format($unit->daily_price) }}</td>
                            <td class="p-4"><i class="fa-solid fa-users"></i> {{ $unit->max_players }}P</td>
                            <td class="p-4">
                                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase
                                    {{ $unit->status->value === 'available' ? 'bg-emerald-500/20 text-emerald-300' : '' }}
                                    {{ $unit->status->value === 'booked' ? 'bg-yellow-500/20 text-yellow-300' : '' }}
                                    {{ $unit->status->value === 'rented' ? 'bg-red-500/20 text-red-300' : '' }}
                                    {{ $unit->status->value === 'maintenance' ? 'bg-slate-500/20 text-slate-400' : '' }}">
                                    {{ $unit->status->value }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <form method="POST" action="/admin/units/{{ $unit->id }}" class="inline-block" onsubmit="return confirm('Hapus unit ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-400 hover:underline text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 5: Member Management (Feature #2) -->
    <div class="mt-12">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-white"><i class="fa-solid fa-users"></i> Manajemen Anggota (Member Management)</h2>
                <p class="text-xs text-slate-400">Kelola identitas, alamat pengantaran, dan lihat riwayat sewa masing-masing anggota.</p>
            </div>
            <span class="text-xs text-slate-400">{{ $members->count() }} Anggota</span>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            @foreach($members as $m)
                <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-5 shadow-xl">
                    <form method="POST" action="/admin/members/{{ $m->id }}" class="space-y-3">
                        @csrf @method('PUT')
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-orange-400 uppercase tracking-wider">ID #{{ $m->id }}</span>
                            <span class="text-xs text-slate-400">{{ $m->rentals->count() }} Kali Sewa</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] text-slate-400 font-bold uppercase mb-0.5">Nama</label>
                                <input name="name" value="{{ $m->name }}" class="w-full rounded-lg border border-white/10 bg-slate-950 p-2 text-xs text-white">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 font-bold uppercase mb-0.5">Email</label>
                                <input name="email" value="{{ $m->email }}" class="w-full rounded-lg border border-white/10 bg-slate-950 p-2 text-xs text-white">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] text-slate-400 font-bold uppercase mb-0.5">Telepon</label>
                                <input name="phone" value="{{ $m->profile?->phone }}" placeholder="08..." class="w-full rounded-lg border border-white/10 bg-slate-950 p-2 text-xs text-white">
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-400 font-bold uppercase mb-0.5">Alamat</label>
                                <input name="address" value="{{ $m->profile?->address }}" placeholder="Alamat domisili" class="w-full rounded-lg border border-white/10 bg-slate-950 p-2 text-xs text-white">
                            </div>
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <button type="submit" class="rounded-lg bg-orange-500/20 border border-orange-500/30 px-3 py-1.5 text-xs font-bold text-orange-400 hover:bg-orange-500 hover:text-white transition">
                                Simpan Perubahan
                            </button>
                            <button formmethod="POST" formaction="/admin/members/{{ $m->id }}" name="_method" value="DELETE" onclick="return confirm('Hapus anggota ini?')" class="text-xs text-red-400 hover:underline">
                                Hapus Anggota
                            </button>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Section 6: Bookings & Active Rentals Flow (Features #6, #7, #15) -->
    <div class="mt-12 grid gap-8 lg:grid-cols-2">
        <!-- Bookings Management -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
            <h2 class="text-lg font-bold text-white mb-4"><i class="fa-regular fa-calendar"></i> Reservasi Booking Masuk</h2>

            <div class="space-y-3">
                @forelse($bookings as $b)
                    <div class="rounded-2xl border border-white/10 bg-slate-950 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="font-mono text-xs font-bold text-orange-400">{{ $b->booking_code }}</span>
                                <h3 class="font-bold text-white text-sm mt-0.5">{{ $b->user->name }} &middot; {{ $b->unit->name }}</h3>
                                <div class="text-xs text-slate-400 mt-1">
                                    {{ $b->start_date->format('d M') }} &ndash; {{ $b->end_date->format('d M Y') }} ({{ $b->duration_days }} Hari)
                                </div>
                            </div>
                            <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $b->status->value === 'confirmed' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-yellow-500/20 text-yellow-300' }}">
                                {{ $b->status->value }}
                            </span>
                        </div>

                        @if($b->status->value === 'pending')
                            <div class="mt-4 pt-3 border-t border-white/10 flex gap-2">
                                <form method="POST" action="/admin/bookings/{{ $b->id }}/confirm" class="flex-1">
                                    @csrf
                                    <button class="w-full rounded-xl bg-white/10 py-2 text-xs font-bold text-white hover:bg-white/20 transition">
                                        Konfirmasi Booking
                                    </button>
                                </form>
                                <form method="POST" action="/admin/bookings/{{ $b->id }}/start" class="flex-1">
                                    @csrf
                                    <button class="w-full rounded-xl bg-orange-500 py-2 text-xs font-bold text-white hover:bg-orange-600 transition">
                                        Mulai Rental Langsung
                                    </button>
                                </form>
                            </div>
                        @elseif($b->status->value === 'confirmed')
                            <form method="POST" action="/admin/bookings/{{ $b->id }}/start" class="mt-4 pt-3 border-t border-white/10">
                                @csrf
                                <button class="w-full rounded-xl bg-orange-500 py-2 text-xs font-bold text-white hover:bg-orange-600 transition">
                                    Mulai Rental (Unit Diserahkan ke User)
                                </button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="py-8 text-center text-xs text-slate-500">Tidak ada reservasi aktif.</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $bookings->links() }}</div>
        </div>

        <!-- Rental Extensions Review -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
            <h2 class="text-lg font-bold text-white mb-4"><i class="fa-solid fa-rotate"></i> Pengajuan Perpanjangan Sewa (Extension)</h2>

            <div class="space-y-3">
                @php
                    $pendingExtensions = \App\Models\RentalExtension::where('status', 'pending')->with('rental.user', 'rental.unit')->get();
                @endphp
                @forelse($pendingExtensions as $ext)
                    <div class="rounded-2xl border border-yellow-500/30 bg-slate-950 p-4">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs font-bold text-orange-400">{{ $ext->rental->rental_code }}</span>
                            <span class="text-xs text-yellow-400 font-bold">+{{ $ext->additional_days }} Hari Tambahan</span>
                        </div>
                        <h4 class="font-bold text-white text-sm mt-1">{{ $ext->rental->user->name }} &middot; {{ $ext->rental->unit->name }}</h4>
                        <div class="text-xs text-slate-400 mt-1">
                            Perpanjang sampai: <b class="text-white">{{ $ext->requested_due_date->format('d M Y') }}</b>
                        </div>
                        @if($ext->reason)
                            <div class="text-xs text-slate-300 mt-1 bg-white/5 p-2 rounded-lg">Alasan: {{ $ext->reason }}</div>
                        @endif

                        <form method="POST" action="/admin/extensions/{{ $ext->id }}" class="mt-3 flex gap-2 pt-2 border-t border-white/10">
                            @csrf
                            <button name="status" value="approved" class="flex-1 rounded-lg bg-emerald-600 py-1.5 text-xs font-bold text-white hover:bg-emerald-500 transition">
                                Setujui
                            </button>
                            <button name="status" value="rejected" class="flex-1 rounded-lg bg-red-600/80 py-1.5 text-xs font-bold text-white hover:bg-red-500 transition">
                                Tolak
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="py-8 text-center text-xs text-slate-500">Tidak ada pengajuan perpanjangan yang menunggu persetujuan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Section 7: Return Management & Fines (Feature #16 & #17) -->
    <div class="mt-12">
        <h2 class="text-xl font-bold text-white mb-4"><i class="fa-solid fa-rotate-left"></i> Manajemen Pengembalian (Return) & Denda</h2>

        <div class="overflow-x-auto rounded-3xl border border-white/10 bg-slate-900/90 shadow-2xl">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-white/10 bg-slate-950/60 text-slate-400 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-4">Rental Code & User</th>
                        <th class="p-4">Unit Konsol</th>
                        <th class="p-4">Jatuh Tempo</th>
                        <th class="p-4">Total Tagihan</th>
                        <th class="p-4">Proses Pengembalian</th>
                        <th class="p-4">Denda Kerusakan</th>
                        <th class="p-4 text-right">Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                    @foreach($rentals as $r)
                        <tr class="hover:bg-white/5 transition">
                            <td class="p-4">
                                <div class="font-mono font-bold text-orange-400">{{ $r->rental_code }}</div>
                                <div class="font-bold text-white">{{ $r->user->name }}</div>
                            </td>
                            <td class="p-4 font-bold text-white">{{ $r->unit->name }} ({{ $r->unit->code }})</td>
                            <td class="p-4">
                                <div>{{ $r->due_date->format('d M Y') }}</div>
                                <span class="text-[10px] uppercase font-bold {{ $r->status->value === 'returned' ? 'text-emerald-400' : ($r->status->value === 'overdue' ? 'text-red-400' : 'text-slate-400') }}">
                                    {{ $r->status->value }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-white">
                                Rp{{ number_format($r->transaction?->total_amount ?? $r->subtotal) }}
                            </td>
                            <td class="p-4">
                                @if(in_array($r->status->value, ['active', 'overdue']))
                                    <form method="POST" action="/admin/rentals/{{ $r->id }}/return" class="space-y-1.5">
                                        @csrf
                                        <input type="date" name="returned_at" value="{{ date('Y-m-d') }}" class="w-full rounded bg-slate-950 p-1.5 text-xs text-white border border-white/10">
                                        <input name="daily_fine" placeholder="Denda/hari (Rp)" value="20000" class="w-full rounded bg-slate-950 p-1.5 text-xs text-white border border-white/10">
                                        <button class="w-full rounded bg-orange-500 py-1 text-xs font-bold text-white hover:bg-orange-600 transition">
                                            Selesaikan Kembali
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-emerald-400 font-bold">✓ Sudah Kembali</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if(in_array($r->status->value, ['active', 'overdue', 'returned']))
                                    <form method="POST" action="/admin/rentals/{{ $r->id }}/fines" class="space-y-1.5">
                                        @csrf
                                        <input name="amount" placeholder="Nominal denda" class="w-full rounded bg-slate-950 p-1.5 text-xs text-white border border-white/10">
                                        <input name="reason" placeholder="Alasan denda rusak" class="w-full rounded bg-slate-950 p-1.5 text-xs text-white border border-white/10">
                                        <button class="w-full rounded bg-red-600/80 py-1 text-xs font-bold text-white hover:bg-red-600 transition">
                                            + Tambah Denda
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                @if($r->transaction && $r->transaction->status->value !== 'paid')
                                    <form method="POST" action="/admin/transactions/{{ $r->transaction->id }}/pay">
                                        @csrf
                                        <button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-emerald-500 transition">
                                            Konfirmasi Lunas
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-emerald-400 font-bold">Lunas</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $rentals->links() }}</div>
    </div>

    <!-- Section 8: Pickup & Delivery Management (Feature #24) -->
    <div class="mt-12">
        <h2 class="text-xl font-bold text-white mb-4"><i class="fa-solid fa-truck"></i> Manajemen Pickup & Delivery Service</h2>

        <div class="overflow-x-auto rounded-3xl border border-white/10 bg-slate-900/90 shadow-2xl">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-white/10 bg-slate-950/60 text-slate-400 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-4">Rental & Anggota</th>
                        <th class="p-4">Tipe & Metode</th>
                        <th class="p-4">Alamat & Kontak</th>
                        <th class="p-4">Kurir Ditugaskan</th>
                        <th class="p-4">Biaya Ongkir (Rp)</th>
                        <th class="p-4">Status Pengantaran</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                    @forelse($deliveries as $d)
                        <tr class="hover:bg-white/5 transition">
                            <td class="p-4">
                                <div class="font-mono font-bold text-orange-400">{{ $d->rental->rental_code }}</div>
                                <div class="font-bold text-white">{{ $d->rental->user->name }}</div>
                            </td>
                            <td class="p-4">
                                <span class="rounded bg-white/5 px-2 py-0.5 uppercase font-bold text-[10px]">
                                    {!! $d->type->value === 'delivery_out' ? '<i class="fa-solid fa-box"></i> Pengantaran Awal' : '<i class="fa-solid fa-rotate-left"></i> Penjemputan Kembali' !!}
                                </span>
                                <div class="text-[11px] text-slate-400 mt-1 capitalize">Metode: {{ $d->method->value }}</div>
                            </td>
                            <td class="p-4 max-w-xs">
                                <div class="truncate text-slate-300">{{ $d->address ?? 'Ambil di Outlet' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $d->contact_number ?? '-' }}</div>
                            </td>
                            <form method="POST" action="/admin/deliveries/{{ $d->id }}">
                                @csrf
                                <td class="p-4">
                                    <input name="courier_name" value="{{ $d->courier_name }}" placeholder="Nama kurir" class="rounded bg-slate-950 p-1.5 text-xs text-white border border-white/10 w-28">
                                </td>
                                <td class="p-4">
                                    <input name="delivery_fee" value="{{ $d->delivery_fee }}" type="number" class="rounded bg-slate-950 p-1.5 text-xs text-white border border-white/10 w-24">
                                </td>
                                <td class="p-4">
                                    <select name="status" class="rounded bg-slate-950 p-1.5 text-xs text-white border border-white/10">
                                        @foreach(App\Enums\DeliveryStatus::cases() as $status)
                                            <option value="{{ $status->value }}" @selected($d->status === $status)>
                                                {{ str_replace('_', ' ', $status->value) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-4 text-right">
                                    <button class="rounded-lg bg-orange-500 px-3 py-1.5 text-xs font-bold text-white hover:bg-orange-600 transition">
                                        Update
                                    </button>
                                </td>
                            </form>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-xs text-slate-500">
                                Belum ada riwayat layanan pickup/delivery.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $deliveries->links() }}</div>
    </div>
</x-layouts.app>

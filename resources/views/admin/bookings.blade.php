<x-layouts.admin title="Reservasi & Rental Aktif">
    <x-slot:header><i class="fa-regular fa-calendar"></i> Reservasi Booking & Pengajuan Perpanjangan</x-slot:header>
    <x-slot:subtitle>Konfirmasi reservasi booking masuk menjadi rental aktif dan review pengajuan perpanjangan masa sewa.</x-slot:subtitle>

    <div class="space-y-8">
        <!-- Section 1: Bookings Management -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span><i class="fa-solid fa-inbox"></i></span> Antrean Reservasi Booking Masuk ({{ $bookings->total() ?? $bookings->count() }})
                    </h2>
                    <p class="text-xs text-slate-400">Konfirmasi booking dan aktifkan rental dengan opsi pengambilan mandiri atau antar kurir.</p>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($bookings as $booking)
                    <div class="rounded-2xl border border-white/5 bg-slate-950/70 p-4 transition hover:border-orange-500/30">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-orange-400 text-sm">#{{ $booking->booking_code }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase
                                        @if($booking->status->value === 'pending') bg-amber-500/20 text-amber-300 border border-amber-500/30
                                        @elseif($booking->status->value === 'confirmed') bg-emerald-500/20 text-emerald-300 border border-emerald-500/30
                                        @else bg-slate-500/20 text-slate-300 @endif">
                                        {{ $booking->status->value }}
                                    </span>
                                </div>
                                <div class="text-xs text-white font-bold">{{ $booking->user->name }} &middot; <span class="text-slate-400 font-normal"><i class="fa-solid fa-phone"></i> {{ $booking->user->profile?->phone ?? '-' }}</span></div>
                                <div class="text-xs text-slate-300">
                                    <i class="fa-solid fa-gamepad"></i> <span class="font-semibold">{{ $booking->unit->name }}</span> ({{ $booking->unit->code }}) &middot;
                                    <i class="fa-solid fa-hourglass-half"></i> <span class="text-amber-400 font-semibold">{{ $booking->duration_days }} Hari</span> ({{ $booking->start_date->format('d M') }} &ndash; {{ $booking->end_date->format('d M Y') }})
                                </div>
                                @if($booking->notes)
                                    <div class="text-[11px] text-slate-400 italic">"{{ $booking->notes }}"</div>
                                @endif
                            </div>

                            <!-- Start Rental Form (Feature #7 & #24) -->
                            <div class="flex flex-wrap items-center gap-2">
                                @if($booking->status->value === 'pending' || $booking->status->value === 'confirmed')
                                    <details class="group">
                                        <summary class="cursor-pointer rounded-xl bg-orange-600 px-4 py-2 text-xs font-bold text-white shadow-lg shadow-orange-600/20 hover:bg-orange-500 transition list-none flex items-center gap-1.5">
                                            <span><i class="fa-solid fa-bolt"></i> Konfirmasi & Mulai Rental</span>
                                            <span class="group-open:rotate-180 transition-transform">▼</span>
                                        </summary>
                                        
                                        <form method="POST" action="/admin/rentals" class="mt-3 w-80 space-y-3 rounded-2xl bg-slate-900 p-4 border border-white/10 shadow-2xl absolute right-8 z-20">
                                            @csrf
                                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                                            <input type="hidden" name="user_id" value="{{ $booking->user_id }}">
                                            <input type="hidden" name="unit_id" value="{{ $booking->unit_id }}">
                                            <input type="hidden" name="start_date" value="{{ $booking->start_date->format('Y-m-d') }}">
                                            <input type="hidden" name="end_date" value="{{ $booking->end_date->format('Y-m-d') }}">

                                            <div class="font-bold text-white text-xs border-b border-white/10 pb-1">
                                                Opsi Penyerahan Unit:
                                            </div>

                                            <div>
                                                <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Metode Serah Terima</label>
                                                <select name="delivery_method" class="w-full rounded-lg border border-white/10 bg-slate-950 p-2 text-xs text-white focus:border-orange-500 focus:outline-none">
                                                    <option value="pickup">Ambil Sendiri di Outlet (Rp 0)</option>
                                                    <option value="delivery">Antar ke Rumah Pelanggan (Delivery Kurir)</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Alamat Pengantaran</label>
                                                <input name="address" type="text" value="{{ $booking->user->profile?->address ?? '' }}" placeholder="Alamat lengkap tujuan..." class="w-full rounded-lg border border-white/10 bg-slate-950 p-2 text-xs text-white focus:border-orange-500 focus:outline-none">
                                            </div>

                                            <div class="grid grid-cols-2 gap-2">
                                                <div>
                                                    <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Kontak</label>
                                                    <input name="contact_number" type="text" value="{{ $booking->user->profile?->phone ?? '' }}" class="w-full rounded-lg border border-white/10 bg-slate-950 p-2 text-xs text-white focus:border-orange-500 focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Ongkir (Rp)</label>
                                                    <input name="delivery_fee" type="number" value="15000" class="w-full rounded-lg border border-white/10 bg-slate-950 p-2 text-xs text-white focus:border-orange-500 focus:outline-none">
                                                </div>
                                            </div>

                                            <button type="submit" class="w-full rounded-xl bg-emerald-600 py-2 text-xs font-bold text-white hover:bg-emerald-500 transition">
                                                ✓ Aktifkan Masa Rental
                                            </button>
                                        </form>
                                    </details>
                                @endif

                                @if($booking->status->value === 'pending')
                                    <form method="POST" action="/bookings/{{ $booking->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Batalkan booking ini?');" class="rounded-xl border border-white/10 bg-slate-800 px-3 py-2 text-xs font-bold text-slate-400 hover:bg-red-500/20 hover:text-red-400 transition">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="py-12 text-center text-xs text-slate-500">Tidak ada reservasi booking aktif saat ini.</p>
                @endforelse
            </div>
            
            @if(method_exists($bookings, 'links'))
                <div class="mt-4">{{ $bookings->links() }}</div>
            @endif
        </div>

        <!-- Section 2: Rental Extensions Review (Feature #15) -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span><i class="fa-solid fa-rotate"></i></span> Pengajuan Perpanjangan Sewa (Extension Requests)
                    </h2>
                    <p class="text-xs text-slate-400">Review dan konfirmasi perpanjangan durasi sewa yang diajukan oleh pelanggan.</p>
                </div>
            </div>

            <div class="space-y-3">
                @php
                    $pendingExtensions = \App\Models\RentalExtension::where('status', \App\Enums\ExtensionStatus::Pending)
                        ->with(['rental.unit', 'rental.user'])
                        ->latest()
                        ->get();
                @endphp

                @forelse($pendingExtensions as $ext)
                    <div class="rounded-2xl border border-white/5 bg-slate-950/70 p-4 transition hover:border-orange-500/30">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div>
                                <div class="font-bold text-white text-xs">
                                    Rental <span class="font-mono text-orange-400">#{{ $ext->rental->rental_code }}</span> &middot; {{ $ext->rental->user->name }}
                                </div>
                                <div class="text-xs text-slate-300 mt-1">
                                    Unit: <span class="font-semibold">{{ $ext->rental->unit->name }}</span> &middot;
                                    Tambahan: <span class="text-amber-400 font-bold">+{{ $ext->additional_days }} Hari</span> (Sampai: {{ $ext->requested_due_date->format('d M Y') }})
                                </div>
                                <div class="text-xs text-emerald-400 font-bold mt-0.5">
                                    Biaya Tambahan: Rp {{ number_format($ext->additional_cost, 0, ',', '.') }}
                                </div>
                                @if($ext->reason)
                                    <div class="text-[11px] text-slate-400 italic mt-1">Alasan: "{{ $ext->reason }}"</div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <form method="POST" action="/admin/extensions/{{ $ext->id }}">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-lg shadow-emerald-600/20">
                                        ✓ Setujui Perpanjangan
                                    </button>
                                </form>

                                <form method="POST" action="/admin/extensions/{{ $ext->id }}">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="rounded-xl bg-red-600/20 border border-red-600/30 px-3 py-2 text-xs font-bold text-red-300 hover:bg-red-600 hover:text-white transition">
                                        ✕ Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="py-12 text-center text-xs text-slate-500">Tidak ada pengajuan perpanjangan yang menunggu persetujuan admin.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>

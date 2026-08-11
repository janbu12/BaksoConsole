<x-layouts.admin title="Antrean Serah Terima">
    <x-slot:header><i class="fa-regular fa-handshake"></i> Antrean Serah Terima Unit</x-slot:header>
    <x-slot:subtitle>Konfirmasi penyerahan unit kepada pelanggan untuk rental yang sudah lunas dibayar.</x-slot:subtitle>

    <div class="space-y-8">
        <!-- Section 1: Bookings Management -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span><i class="fa-solid fa-box"></i></span> Menunggu Diserahkan ({{ $rentals->total() ?? $rentals->count() }})
                    </h2>
                    <p class="text-xs text-slate-400">Unit-unit ini sudah dilunasi. Lakukan penyerahan barang (diambil/diantar).</p>
                </div>
            </div>

            <div class="space-y-3">
                @forelse($rentals as $rental)
                    <div class="rounded-2xl border border-white/5 bg-slate-950/70 p-4 transition hover:border-orange-500/30">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-orange-400 text-sm">#{{ $rental->rental_code }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30">
                                        {{ $rental->status->value }}
                                    </span>
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                        {{ $rental->transaction?->status->value }}
                                    </span>
                                </div>
                                <div class="text-xs text-white font-bold">{{ $rental->user->name }} &middot; <span class="text-slate-400 font-normal"><i class="fa-solid fa-phone"></i> {{ $rental->user->profile?->phone ?? '-' }}</span></div>
                                <div class="text-xs text-slate-300">
                                    <i class="fa-solid fa-gamepad"></i> <span class="font-semibold">{{ $rental->unit->name }}</span> ({{ $rental->unit->code }}) &middot;
                                    <i class="fa-solid fa-hourglass-half"></i> <span class="text-amber-400 font-semibold">{{ $rental->duration_days }} Hari</span> ({{ $rental->start_date->format('d M') }} &ndash; {{ $rental->due_date->format('d M Y') }})
                                </div>
                                @if($rental->booking && $rental->booking->notes)
                                    <div class="text-[11px] text-slate-400 italic">"{{ $rental->booking->notes }}"</div>
                                @endif
                            </div>

                            <!-- Handover Form -->
                            <div class="flex flex-wrap items-center gap-2">
                                @if($rental->status->value === 'pending')
                                    <details class="group relative">
                                        <summary class="cursor-pointer rounded-xl bg-orange-600 px-4 py-2 text-xs font-bold text-white shadow-lg shadow-orange-600/20 hover:bg-orange-500 transition list-none flex items-center gap-1.5">
                                            <span><i class="fa-solid fa-hand-holding"></i> Serah Terima Barang</span>
                                            <span class="group-open:rotate-180 transition-transform">▼</span>
                                        </summary>
                                        
                                        <form method="POST" action="/admin/rentals/{{ $rental->id }}/handover" class="mt-3 w-80 space-y-3 rounded-2xl bg-slate-900 p-4 border border-white/10 shadow-2xl absolute right-0 z-20">
                                            @csrf

                                            <div class="font-bold text-white text-xs border-b border-white/10 pb-1">
                                                Informasi Pengiriman (Pesanan):
                                            </div>
                                            
                                            <div class="text-xs text-slate-300">
                                                @php $delivery = $rental->booking ?? $rental->deliveries->first(); @endphp
                                                <div class="mb-1">
                                                    <span class="text-slate-500">Metode:</span> 
                                                    <strong class="text-orange-400 uppercase">{{ $delivery && $delivery->delivery_method === 'delivery' ? 'Antar Kurir' : 'Ambil di Toko' }}</strong>
                                                </div>
                                                @if($delivery && $delivery->delivery_method === 'delivery')
                                                    <div class="mb-1">
                                                        <span class="text-slate-500">Alamat:</span> 
                                                        <span>{{ $delivery->delivery_address ?? $delivery->address }}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <span class="text-slate-500">Kontak:</span> 
                                                        <span>{{ $delivery->contact_number }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <button type="submit" class="w-full rounded-xl bg-emerald-600 py-2 text-xs font-bold text-white hover:bg-emerald-500 transition">
                                                ✓ Konfirmasi Barang Diserahkan
                                            </button>
                                        </form>
                                    </details>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="py-12 text-center text-xs text-slate-500">Tidak ada antrean serah terima saat ini.</p>
                @endforelse
            </div>
            
            @if(method_exists($rentals, 'links'))
                <div class="mt-6">
                    {{ $rentals->links() }}
                </div>
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

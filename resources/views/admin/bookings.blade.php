<x-layouts.admin title="Antrean Serah Terima">
    <x-slot:header><i class="fa-regular fa-handshake"></i> Antrean Serah Terima Unit</x-slot:header>
    <x-slot:subtitle>Konfirmasi penyerahan unit kepada pelanggan untuk rental yang sudah lunas dibayar.</x-slot:subtitle>

    <div class="space-y-6">
        <!-- Section 1: Bookings Handover Queue -->
        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 overflow-hidden shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-white/10">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-box text-orange-500"></i> Menunggu Diserahkan ({{ $rentals->total() ?? $rentals->count() }})
                    </h2>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Unit sudah dilunasi. Lakukan penyerahan barang (ambil/antar).</p>
                </div>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($rentals as $rental)
                    <div class="p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-white/[0.02] transition">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4">
                            <!-- Rental Info -->
                            <div class="flex-1 space-y-2">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-mono font-bold text-orange-500 text-sm">#{{ $rental->rental_code }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $rental->unit->firmware_type?->value === 'jailbreak' ? 'bg-purple-500/15 text-purple-700 dark:text-purple-300 border border-purple-500/30' : 'bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30' }}">
                                        {{ $rental->unit->firmware_type?->value === 'jailbreak' ? 'Jailbreak' : 'Online Ready' }}
                                    </span>
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                        {{ $rental->status->value }}
                                    </span>
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                        {{ $rental->transaction?->status->value }}
                                    </span>
                                </div>

                                <div class="text-xs text-slate-900 dark:text-white font-bold">
                                    {{ $rental->user->name }}
                                    <span class="text-slate-500 dark:text-slate-400 font-normal ml-1.5">
                                        <i class="fa-solid fa-phone text-[10px]"></i> {{ $rental->user->profile?->phone ?? '-' }}
                                    </span>
                                </div>

                                <div class="text-xs text-slate-600 dark:text-slate-300">
                                    <i class="fa-solid fa-gamepad text-orange-500 mr-1"></i>
                                    <span class="font-semibold">{{ $rental->unit->name }}</span> ({{ $rental->unit->code }})
                                    <span class="mx-1 text-slate-400">·</span>
                                    <span class="text-amber-600 dark:text-amber-400 font-semibold">{{ $rental->duration_days }} Hari</span>
                                    <span class="text-slate-500">({{ $rental->start_date->format('d M') }} – {{ $rental->due_date->format('d M Y') }})</span>
                                </div>

                                @if($rental->booking && !empty($rental->booking->requested_games))
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                        <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400">Game Dipilih:</span>
                                        @foreach($rental->booking->requested_games as $rg)
                                            <span class="bg-orange-500/10 border border-orange-500/20 rounded px-1.5 py-0.5 text-[10px] text-orange-600 dark:text-orange-300">{{ $rg }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                @if($rental->booking && $rental->booking->notes)
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 italic">"{{ $rental->booking->notes }}"</div>
                                @endif
                            </div>

                            <!-- Action Button -->
                            @if($rental->status->value === 'pending')
                                <div class="shrink-0">
                                    @php $delivery = $rental->booking ?? $rental->deliveries->first(); @endphp
                                    <div class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950/60 p-3 space-y-2 text-xs min-w-[200px]">
                                        <div class="font-bold text-slate-900 dark:text-white text-[11px] pb-1 border-b border-slate-200 dark:border-white/10">
                                            Info Pengiriman
                                        </div>
                                        <div>
                                            <span class="text-slate-500 dark:text-slate-400">Metode:</span>
                                            <strong class="text-orange-500 uppercase ml-1">{{ $delivery && $delivery->delivery_method === 'delivery' ? 'Antar Kurir' : 'Ambil di Toko' }}</strong>
                                        </div>
                                        @if($delivery && $delivery->delivery_method === 'delivery')
                                            <div class="text-slate-600 dark:text-slate-300 leading-relaxed">
                                                <span class="text-slate-400">Alamat:</span> {{ $delivery->delivery_address ?? $delivery->address ?? '-' }}
                                            </div>
                                        @endif
                                        <form method="POST" action="/admin/rentals/{{ $rental->id }}/handover">
                                            @csrf
                                            <button type="submit" class="w-full rounded-lg bg-emerald-600 py-2 text-xs font-bold text-white hover:bg-emerald-500 transition cursor-pointer">
                                                <i class="fa-solid fa-check mr-1"></i> Konfirmasi Diserahkan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-xs text-slate-500">
                        <i class="fa-solid fa-check-circle text-3xl text-emerald-500 mb-3 block"></i>
                        Tidak ada antrean serah terima saat ini.
                    </div>
                @endforelse
            </div>

            @if(method_exists($rentals, 'links'))
                <div class="px-5 py-4 border-t border-slate-200 dark:border-white/10">
                    {{ $rentals->links() }}
                </div>
            @endif
        </div>

        <!-- Section 2: Rental Extensions Review -->
        <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 overflow-hidden shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-white/10">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-rotate text-blue-500"></i> Pengajuan Perpanjangan Sewa
                    </h2>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Review dan konfirmasi perpanjangan durasi sewa yang diajukan pelanggan.</p>
                </div>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-white/5">
                @php
                    $pendingExtensions = \App\Models\RentalExtension::where('status', \App\Enums\ExtensionStatus::Pending)
                        ->with(['rental.unit', 'rental.user'])
                        ->latest()
                        ->get();
                @endphp

                @forelse($pendingExtensions as $ext)
                    <div class="p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-white/[0.02] transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="text-xs font-bold text-slate-900 dark:text-white">
                                    Rental <span class="font-mono text-orange-500">#{{ $ext->rental->rental_code }}</span>
                                    <span class="text-slate-500 dark:text-slate-400 font-normal mx-1">·</span>
                                    {{ $ext->rental->user->name }}
                                </div>
                                <div class="text-xs text-slate-600 dark:text-slate-300">
                                    Unit: <span class="font-semibold">{{ $ext->rental->unit->name }}</span>
                                    <span class="mx-1 text-slate-400">·</span>
                                    Tambahan: <span class="text-amber-600 dark:text-amber-400 font-bold">+{{ $ext->additional_days }} Hari</span>
                                    (Sampai: {{ $ext->requested_due_date->format('d M Y') }})
                                </div>
                                <div class="text-xs text-emerald-600 dark:text-emerald-400 font-bold">
                                    Biaya Tambahan: Rp {{ number_format($ext->additional_cost, 0, ',', '.') }}
                                </div>
                                @if($ext->reason)
                                    <div class="text-[11px] text-slate-500 italic">Alasan: "{{ $ext->reason }}"</div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <form method="POST" action="/admin/extensions/{{ $ext->id }}">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-500 transition shadow-sm cursor-pointer">
                                        <i class="fa-solid fa-check mr-1"></i> Setujui
                                    </button>
                                </form>
                                <form method="POST" action="/admin/extensions/{{ $ext->id }}">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="rounded-xl bg-red-500/10 border border-red-500/30 px-3 py-2 text-xs font-bold text-red-600 dark:text-red-300 hover:bg-red-600 hover:text-white transition cursor-pointer">
                                        <i class="fa-solid fa-xmark mr-1"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-xs text-slate-500">
                        <i class="fa-solid fa-folder-open text-2xl mb-2 block opacity-50"></i>
                        Tidak ada pengajuan perpanjangan yang menunggu persetujuan.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>

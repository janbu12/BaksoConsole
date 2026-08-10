<x-layouts.app title="Booking Saya">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="text-xs font-bold uppercase tracking-wider text-orange-400">Reservasi Konsol</div>
            <h1 class="text-3xl font-black text-white mt-1">Daftar Booking Saya</h1>
            <p class="text-sm text-slate-400 mt-0.5">Pantau status reservasi jadwal sewa konsol impianmu sebelum masa sewa dimulai.</p>
        </div>
        <a href="{{ route('catalogue') }}" class="rounded-xl bg-orange-500 px-5 py-2.5 text-xs font-bold text-white shadow-lg shadow-orange-500/25 hover:bg-orange-600 transition flex items-center gap-2">
            <span>+</span> Buat Booking Baru
        </a>
    </div>

    <!-- Bookings List -->
    <div class="mt-8 space-y-4">
        @forelse($bookings as $booking)
            <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-xl hover:border-orange-500/30 transition">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-500/10 text-2xl text-orange-400 border border-orange-500/20">
                        🎮
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-xs font-bold text-orange-400 bg-orange-500/10 px-2 py-0.5 rounded border border-orange-500/20">
                                {{ $booking->booking_code }}
                            </span>
                            <span class="text-xs text-slate-400">Dibuat {{ $booking->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mt-1">{{ $booking->unit->name }} ({{ $booking->unit->code }})</h3>
                        <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-300">
                            <span>📅 <b>{{ $booking->start_date->format('d M Y') }}</b> &ndash; <b>{{ $booking->end_date->format('d M Y') }}</b></span>
                            <span>⏳ Durasi: <b>{{ $booking->duration_days }} Hari</b></span>
                            <span>💰 Estimasi: <b>Rp{{ number_format($booking->unit->daily_price * $booking->duration_days, 0, ',', '.') }}</b></span>
                        </div>

                        @if($booking->notes)
                            <div class="mt-2 text-xs text-slate-400 bg-slate-950/60 p-2 rounded-xl border border-white/5">
                                Catatan: {{ $booking->notes }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status & Action -->
                <div class="flex items-center gap-4 justify-between md:justify-end border-t md:border-t-0 border-white/10 pt-4 md:pt-0">
                    <div class="text-right">
                        <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">Status Reservasi:</div>
                        @if($booking->status->value === 'pending')
                            <span class="rounded-full bg-yellow-500/20 border border-yellow-500/30 px-3 py-1 text-xs font-bold text-yellow-300">
                                🟡 Menunggu Konfirmasi
                            </span>
                        @elseif($booking->status->value === 'confirmed')
                            <span class="rounded-full bg-emerald-500/20 border border-emerald-500/30 px-3 py-1 text-xs font-bold text-emerald-300">
                                🟢 Booking Dikonfirmasi
                            </span>
                        @elseif($booking->status->value === 'completed')
                            <span class="rounded-full bg-blue-500/20 border border-blue-500/30 px-3 py-1 text-xs font-bold text-blue-300">
                                🔵 Rental Dimulai (Selesai)
                            </span>
                        @else
                            <span class="rounded-full bg-slate-500/20 border border-slate-500/30 px-3 py-1 text-xs font-bold text-slate-400">
                                ⚪ Dibatalkan
                            </span>
                        @endif
                    </div>

                    @if($booking->status->value === 'pending')
                        <form method="POST" action="/bookings/{{ $booking->id }}">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin ingin membatalkan booking ini?')" class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-2 text-xs font-bold text-red-300 hover:bg-red-500 hover:text-white transition">
                                Batalkan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-16 text-center text-slate-500 rounded-3xl bg-slate-900/50 border border-white/5">
                <div class="text-4xl mb-2">📅</div>
                <h3 class="text-lg font-bold text-white">Belum Ada Reservasi Booking</h3>
                <p class="text-xs text-slate-400 mt-1">Pilih konsol impianmu dan buat reservasi sekarang agar jadwal tidak bentrok.</p>
                <a href="{{ route('catalogue') }}" class="mt-4 inline-block rounded-xl bg-orange-500 px-5 py-2.5 text-xs font-bold text-white hover:bg-orange-600 transition">
                    Eksplor Katalog Konsol
                </a>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    </div>
</x-layouts.app>

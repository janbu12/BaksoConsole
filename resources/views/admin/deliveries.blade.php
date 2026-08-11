<x-layouts.admin title="Pickup & Delivery Service">
    <x-slot:header><i class="fa-solid fa-truck"></i> Manajemen Antar & Jemput (Pickup & Delivery)</x-slot:header>
    <x-slot:subtitle>Kelola penugasan kurir, biaya pengantaran, dan pantau status kurir secara real-time.</x-slot:subtitle>

    <div class="space-y-4">

        <!-- Desktop Table (md+) -->
        <div class="hidden md:block rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-bold text-[10px] tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Rental & Anggota</th>
                            <th class="px-4 py-3">Tipe Layanan</th>
                            <th class="px-4 py-3">Alamat & Kontak</th>
                            <th class="px-4 py-3">Kurir</th>
                            <th class="px-4 py-3">Ongkir</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Update</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-200">
                        @forelse($deliveries as $delivery)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition">
                                <td class="px-4 py-3.5">
                                    <div class="font-mono font-bold text-orange-500">#{{ $delivery->rental->rental_code }}</div>
                                    <div class="font-semibold text-slate-900 dark:text-white text-xs mt-0.5">{{ $delivery->rental->user->name }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400">{{ $delivery->rental->unit->name }}</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    @if($delivery->type->value === 'delivery_out')
                                        <span class="rounded-full bg-orange-500/15 text-orange-700 dark:text-orange-300 border border-orange-500/30 px-2 py-0.5 text-[10px] font-bold">
                                            <i class="fa-solid fa-box mr-1"></i>Antar ke Rumah
                                        </span>
                                    @else
                                        <span class="rounded-full bg-blue-500/15 text-blue-700 dark:text-blue-300 border border-blue-500/30 px-2 py-0.5 text-[10px] font-bold">
                                            <i class="fa-solid fa-house mr-1"></i>Jemput dari Rumah
                                        </span>
                                    @endif
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 capitalize">{{ $delivery->method->value }}</div>
                                </td>
                                <td class="px-4 py-3.5 max-w-[180px]">
                                    <div class="text-slate-900 dark:text-white font-medium text-xs line-clamp-2" title="{{ $delivery->address ?? 'Di outlet' }}">{{ $delivery->address ?? 'Ambil/kembalikan di outlet' }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400 font-mono mt-0.5"><i class="fa-solid fa-phone"></i> {{ $delivery->contact_number ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="font-bold text-amber-600 dark:text-amber-400 text-xs">{{ $delivery->courier_name ?? '— Belum —' }}</span>
                                </td>
                                <td class="px-4 py-3.5 font-bold text-slate-900 dark:text-white">
                                    Rp {{ number_format($delivery->delivery_fee, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5">
                                    @php
                                        $badge = match($delivery->status->value) {
                                            'ready_for_pickup' => 'bg-blue-500/15 text-blue-700 dark:text-blue-300 border-blue-500/30',
                                            'waiting'          => 'bg-slate-200 dark:bg-slate-500/20 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-white/10',
                                            'in_transit'       => 'bg-amber-500/15 text-amber-700 dark:text-amber-300 border-amber-500/30 animate-pulse',
                                            'received'         => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30',
                                            'picked_up'        => 'bg-indigo-500/15 text-indigo-700 dark:text-indigo-300 border-indigo-500/30',
                                            'returned_to_outlet' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30',
                                            'cancelled'        => 'bg-red-500/15 text-red-700 dark:text-red-300 border-red-500/30',
                                            default            => 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-300',
                                        };
                                    @endphp
                                    <span class="rounded-full px-2 py-0.5 text-[9px] font-bold border uppercase {{ $badge }}">{{ $delivery->status->value }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <form method="POST" action="/admin/deliveries/{{ $delivery->id }}" class="flex flex-col items-end gap-1.5">
                                        @csrf
                                        <input name="courier_name" type="text" value="{{ $delivery->courier_name }}" placeholder="Nama kurir" class="w-28 rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-1.5 text-[10px] text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                                        <div class="flex items-center gap-1.5">
                                            <select name="status" class="rounded-lg border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 p-1.5 text-[10px] text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                                                <option value="waiting" {{ $delivery->status->value === 'waiting' ? 'selected' : '' }}>Waiting</option>
                                                <option value="in_transit" {{ $delivery->status->value === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                                <option value="received" {{ $delivery->status->value === 'received' ? 'selected' : '' }}>Received</option>
                                                <option value="picked_up" {{ $delivery->status->value === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                                <option value="returned_to_outlet" {{ $delivery->status->value === 'returned_to_outlet' ? 'selected' : '' }}>Returned</option>
                                                <option value="ready_for_pickup" {{ $delivery->status->value === 'ready_for_pickup' ? 'selected' : '' }}>Ready Pickup</option>
                                            </select>
                                            <button type="submit" class="rounded-lg bg-orange-500 px-2.5 py-1.5 text-[10px] font-bold text-white hover:bg-orange-400 transition cursor-pointer">
                                                Save
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-xs text-slate-500">
                                    <i class="fa-solid fa-truck-fast text-2xl mb-2 block opacity-30"></i>
                                    Belum ada riwayat layanan pickup atau delivery.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Card List (< md) -->
        <div class="md:hidden space-y-3">
            @forelse($deliveries as $delivery)
                @php
                    $badge = match($delivery->status->value) {
                        'in_transit' => 'bg-amber-500/15 text-amber-700 dark:text-amber-300 border-amber-500/30',
                        'received','returned_to_outlet' => 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-300 border-emerald-500/30',
                        'cancelled'  => 'bg-red-500/15 text-red-700 dark:text-red-300 border-red-500/30',
                        default      => 'bg-slate-200 dark:bg-slate-500/20 text-slate-600 dark:text-slate-300 border-slate-300 dark:border-white/10',
                    };
                @endphp
                <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 overflow-hidden shadow-sm">
                    <!-- Card Header -->
                    <div class="flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-slate-950/60 border-b border-slate-200 dark:border-white/10">
                        <div>
                            <span class="font-mono font-bold text-orange-500 text-sm">#{{ $delivery->rental->rental_code }}</span>
                            <div class="text-xs font-semibold text-slate-900 dark:text-white mt-0.5">{{ $delivery->rental->user->name }}</div>
                        </div>
                        <span class="rounded-full px-2 py-0.5 text-[9px] font-bold border uppercase {{ $badge }}">{{ $delivery->status->value }}</span>
                    </div>

                    <!-- Card Body -->
                    <div class="px-4 py-3 space-y-3 text-xs">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Tipe</div>
                                @if($delivery->type->value === 'delivery_out')
                                    <span class="text-orange-600 dark:text-orange-300 font-semibold">Antar ke Rumah</span>
                                @else
                                    <span class="text-blue-600 dark:text-blue-300 font-semibold">Jemput dari Rumah</span>
                                @endif
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Ongkir</div>
                                <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($delivery->delivery_fee, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider mb-1">Alamat</div>
                            <div class="text-slate-700 dark:text-slate-300">{{ $delivery->address ?? 'Di outlet' }}</div>
                            @if($delivery->contact_number)
                                <div class="text-[10px] text-slate-500 font-mono mt-0.5"><i class="fa-solid fa-phone"></i> {{ $delivery->contact_number }}</div>
                            @endif
                        </div>

                        <!-- Update Form -->
                        <form method="POST" action="/admin/deliveries/{{ $delivery->id }}" class="space-y-2 pt-2 border-t border-slate-200 dark:border-white/10">
                            @csrf
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">Update Status Kurir</div>
                            <input name="courier_name" type="text" value="{{ $delivery->courier_name }}" placeholder="Nama kurir" class="w-full rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                            <div class="flex items-center gap-2">
                                <select name="status" class="flex-1 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-slate-950 px-3 py-2 text-xs text-slate-900 dark:text-white focus:border-orange-500 focus:outline-none">
                                    <option value="waiting" {{ $delivery->status->value === 'waiting' ? 'selected' : '' }}>Waiting</option>
                                    <option value="in_transit" {{ $delivery->status->value === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="received" {{ $delivery->status->value === 'received' ? 'selected' : '' }}>Received</option>
                                    <option value="picked_up" {{ $delivery->status->value === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                    <option value="returned_to_outlet" {{ $delivery->status->value === 'returned_to_outlet' ? 'selected' : '' }}>Returned</option>
                                    <option value="ready_for_pickup" {{ $delivery->status->value === 'ready_for_pickup' ? 'selected' : '' }}>Ready Pickup</option>
                                </select>
                                <button type="submit" class="rounded-xl bg-orange-500 px-4 py-2 text-xs font-bold text-white hover:bg-orange-400 transition cursor-pointer shrink-0">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 p-10 text-center text-xs text-slate-500">
                    <i class="fa-solid fa-truck-fast text-3xl mb-3 block opacity-30"></i>
                    Belum ada riwayat layanan pickup atau delivery.
                </div>
            @endforelse
        </div>

        @if(method_exists($deliveries, 'links'))
            <div>{{ $deliveries->links() }}</div>
        @endif
    </div>
</x-layouts.admin>

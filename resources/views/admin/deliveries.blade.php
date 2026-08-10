<x-layouts.admin title="Pickup & Delivery Service">
    <x-slot:header><i class="fa-solid fa-truck"></i> Manajemen Antar & Jemput (Pickup & Delivery)</x-slot:header>
    <x-slot:subtitle>Kelola penugasan kurir, biaya pengantaran, dan pantau status kurir secara real-time.</x-slot:subtitle>

    <div class="space-y-6">
        <div class="overflow-x-auto rounded-3xl border border-white/10 bg-slate-900/90 shadow-2xl">
            <table class="w-full text-left text-xs">
                <thead class="border-b border-white/10 bg-slate-950/60 text-slate-400 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-4">Rental & Anggota</th>
                        <th class="p-4">Tipe Layanan</th>
                        <th class="p-4">Alamat & Kontak</th>
                        <th class="p-4">Kurir Ditugaskan</th>
                        <th class="p-4">Biaya Ongkir (Rp)</th>
                        <th class="p-4">Status Pengantaran</th>
                        <th class="p-4 text-right">Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-200">
                    @forelse($deliveries as $delivery)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="p-4">
                                <div class="font-mono font-bold text-orange-400">#{{ $delivery->rental->rental_code }}</div>
                                <div class="font-semibold text-white text-xs mt-0.5">{{ $delivery->rental->user->name }}</div>
                                <div class="text-[10px] text-slate-400">Unit: {{ $delivery->rental->unit->name }}</div>
                            </td>
                            <td class="p-4">
                                @if($delivery->type->value === 'delivery_out')
                                    <span class="rounded-full bg-orange-500/20 text-orange-300 border border-orange-500/30 px-2.5 py-0.5 text-[10px] font-bold">
                                        <i class="fa-solid fa-box"></i> Antar Unit ke Rumah
                                    </span>
                                @else
                                    <span class="rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30 px-2.5 py-0.5 text-[10px] font-bold">
                                        <i class="fa-solid fa-house"></i> Jemput Unit dari Rumah
                                    </span>
                                @endif
                                <div class="text-[10px] text-slate-400 mt-1 capitalize font-medium">Metode: {{ $delivery->method->value }}</div>
                            </td>
                            <td class="p-4 max-w-xs">
                                <div class="text-white font-medium truncate">{{ $delivery->address ?? 'Ambil/kembalikan di outlet' }}</div>
                                <div class="text-[10px] text-slate-400 font-mono"><i class="fa-solid fa-phone"></i> {{ $delivery->contact_number ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-amber-400 text-xs">
                                    {{ $delivery->courier_name ?? '— Belum ditugaskan —' }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-white">
                                Rp {{ number_format($delivery->delivery_fee, 0, ',', '.') }}
                            </td>
                            <td class="p-4">
                                @php
                                    $delStatusBadge = match($delivery->status->value) {
                                        'ready_for_pickup' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                                        'waiting' => 'bg-slate-500/20 text-slate-300 border-slate-500/30',
                                        'in_transit' => 'bg-amber-500/20 text-amber-300 border-amber-500/30 animate-pulse',
                                        'received' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                        'picked_up' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                                        'returned_to_outlet' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                        'cancelled' => 'bg-red-500/20 text-red-300 border-red-500/30',
                                        default => 'bg-slate-800 text-slate-300',
                                    };
                                @endphp
                                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold border uppercase {{ $delStatusBadge }}">
                                    {{ $delivery->status->value }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <!-- Update Courier & Status Form (Feature #24) -->
                                <form method="POST" action="/admin/deliveries/{{ $delivery->id }}" class="inline-flex items-center gap-1.5 justify-end">
                                    @csrf
                                    <input name="courier_name" type="text" value="{{ $delivery->courier_name }}" placeholder="Nama kurir" class="w-24 rounded-lg border border-white/10 bg-slate-950 p-1.5 text-[10px] text-white focus:border-orange-500 focus:outline-none">
                                    
                                    <select name="status" class="rounded-lg border border-white/10 bg-slate-950 p-1.5 text-[10px] text-white focus:border-orange-500 focus:outline-none">
                                        <option value="waiting" {{ $delivery->status->value === 'waiting' ? 'selected' : '' }}>Waiting</option>
                                        <option value="in_transit" {{ $delivery->status->value === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                        <option value="received" {{ $delivery->status->value === 'received' ? 'selected' : '' }}>Received</option>
                                        <option value="picked_up" {{ $delivery->status->value === 'picked_up' ? 'selected' : '' }}>Picked Up</option>
                                        <option value="returned_to_outlet" {{ $delivery->status->value === 'returned_to_outlet' ? 'selected' : '' }}>Returned to Outlet</option>
                                        <option value="ready_for_pickup" {{ $delivery->status->value === 'ready_for_pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                                    </select>

                                    <button type="submit" class="rounded-lg bg-orange-600 px-2.5 py-1.5 text-[10px] font-bold text-white hover:bg-orange-500 transition shadow-md shadow-orange-600/20">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-xs text-slate-500">
                                Belum ada riwayat layanan pickup atau delivery kurir.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($deliveries, 'links'))
            <div class="mt-4">{{ $deliveries->links() }}</div>
        @endif
    </div>
</x-layouts.admin>

<x-layouts.admin title="Leaderboard Members">
    <x-slot:header><i class="fa-solid fa-trophy"></i> Leaderboard Member Teraktif</x-slot:header>
    <x-slot:subtitle>Pantau peringkat member berdasarkan akumulasi total hari penyewaan konsol.</x-slot:subtitle>

    <div class="rounded-2xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900/90 overflow-hidden shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between px-5 py-4 border-b border-slate-200 dark:border-white/10 gap-3">
            <div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-ranking-star text-amber-500"></i> Top 50 Pelanggan Setia
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Member dengan loyalitas tertinggi (berdasarkan hari sewa sukses).</p>
            </div>
            <a href="{{ route('leaderboard') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 px-3.5 py-2 text-xs font-bold text-slate-700 dark:text-white shadow-sm hover:bg-slate-200 dark:hover:bg-white/5 transition shrink-0">
                <i class="fa-solid fa-external-link-alt text-[10px]"></i> Tampilan Publik
            </a>
        </div>

        <!-- Top 3 Podium (mobile first, always visible) -->
        @if($topMembers->count() >= 3)
            <div class="grid grid-cols-3 gap-3 p-4 bg-gradient-to-b from-amber-500/5 to-transparent border-b border-slate-200 dark:border-white/10">
                @foreach($topMembers->take(3) as $index => $member)
                    @php
                        $podiumStyles = [
                            0 => ['order' => 'order-2', 'size' => 'h-14 w-14', 'bg' => 'bg-amber-500', 'label' => 'text-amber-600 dark:text-amber-400', 'icon' => 'fa-trophy'],
                            1 => ['order' => 'order-1', 'size' => 'h-12 w-12', 'bg' => 'bg-slate-400', 'label' => 'text-slate-500 dark:text-slate-300', 'icon' => 'fa-medal'],
                            2 => ['order' => 'order-3', 'size' => 'h-12 w-12', 'bg' => 'bg-orange-600', 'label' => 'text-orange-600 dark:text-orange-400', 'icon' => 'fa-award'],
                        ];
                        $s = $podiumStyles[$index];
                    @endphp
                    <div class="flex flex-col items-center gap-2 text-center {{ $s['order'] }}">
                        <div class="flex {{ $s['size'] }} items-center justify-center rounded-full {{ $s['bg'] }} shadow-lg">
                            <i class="fa-solid {{ $s['icon'] }} text-white text-xl"></i>
                        </div>
                        <div class="text-xs font-bold text-slate-900 dark:text-white truncate max-w-[80px] sm:max-w-full">{{ $member->name }}</div>
                        <div class="text-[10px] font-bold {{ $s['label'] }}">{{ $member->total_days }} Hari</div>
                        <span class="rounded-full px-2 py-0.5 text-[9px] font-bold uppercase {{ $member->rank['badge_class'] }}">{{ $member->rank['name'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Full Table (Desktop) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-950/50 text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400 border-y border-slate-200 dark:border-white/10 tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-center w-16">Rank</th>
                        <th class="px-4 py-3">Member</th>
                        <th class="px-4 py-3">Kontak</th>
                        <th class="px-4 py-3 hidden lg:table-cell">Bergabung</th>
                        <th class="px-4 py-3 text-center">Total Hari</th>
                        <th class="px-4 py-3">Bakso Rank</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($topMembers as $index => $member)
                        <tr class="transition hover:bg-slate-50 dark:hover:bg-white/5 {{ $index < 3 ? 'bg-amber-500/5 dark:bg-amber-500/5' : '' }}">
                            <td class="px-4 py-3.5 text-center">
                                @if($index === 0)
                                    <i class="fa-solid fa-trophy text-amber-500 text-lg"></i>
                                @elseif($index === 1)
                                    <i class="fa-solid fa-medal text-slate-400 text-lg"></i>
                                @elseif($index === 2)
                                    <i class="fa-solid fa-award text-orange-600 text-lg"></i>
                                @else
                                    <span class="font-bold text-slate-500 dark:text-slate-400 text-xs">#{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="font-semibold text-slate-900 dark:text-white text-xs">{{ $member->name }}</div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400">{{ $member->email }}</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="text-[10px] text-slate-600 dark:text-slate-300 font-mono"><i class="fa-solid fa-phone"></i> {{ $member->profile?->phone ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3.5 hidden lg:table-cell text-xs text-slate-600 dark:text-slate-400">
                                {{ $member->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="font-black text-lg text-slate-900 dark:text-white">{{ $member->total_days }}</span>
                                <div class="text-[9px] text-slate-500">hari</div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $member->rank['badge_class'] }}">
                                    {!! $member->rank['badge'] !!}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-xs text-slate-500">
                                <i class="fa-solid fa-folder-open text-2xl mb-2 block opacity-30"></i>
                                Belum ada data member dan rental.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile List (< md) -->
        <div class="md:hidden divide-y divide-slate-100 dark:divide-white/5">
            @forelse($topMembers as $index => $member)
                <div class="flex items-center gap-3 px-4 py-3 {{ $index < 3 ? 'bg-amber-500/5 dark:bg-amber-500/5' : '' }}">
                    <div class="w-8 text-center shrink-0">
                        @if($index === 0)
                            <i class="fa-solid fa-trophy text-amber-500 text-base"></i>
                        @elseif($index === 1)
                            <i class="fa-solid fa-medal text-slate-400 text-base"></i>
                        @elseif($index === 2)
                            <i class="fa-solid fa-award text-orange-600 text-base"></i>
                        @else
                            <span class="font-bold text-slate-500 text-xs">#{{ $index + 1 }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ $member->name }}</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 truncate">{{ $member->email }}</div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="font-black text-slate-900 dark:text-white text-sm">{{ $member->total_days }}</div>
                        <div class="text-[9px] text-slate-500">hari</div>
                    </div>
                    <span class="rounded-full px-2 py-0.5 text-[9px] font-bold uppercase {{ $member->rank['badge_class'] }} shrink-0">
                        {!! $member->rank['badge'] !!}
                    </span>
                </div>
            @empty
                <div class="py-10 text-center text-xs text-slate-500">Belum ada data member.</div>
            @endforelse
        </div>
    </div>
</x-layouts.admin>

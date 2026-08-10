<x-layouts.admin title="Leaderboard Members">
    <x-slot:header><i class="fa-solid fa-trophy"></i> Leaderboard Member Teraktif</x-slot:header>
    <x-slot:subtitle>Pantau peringkat member berdasarkan akumulasi total hari penyewaan konsol.</x-slot:subtitle>

    <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-ranking-star text-amber-400"></i> Top 50 Pelanggan Setia
                </h2>
                <p class="text-xs text-slate-400">Daftar member dengan loyalitas tertinggi (berdasarkan hari sewa sukses).</p>
            </div>
            
            <a href="{{ route('leaderboard') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-slate-800 px-4 py-2 text-xs font-bold text-white shadow-lg border border-white/10 hover:bg-slate-700 transition">
                <i class="fa-solid fa-external-link-alt"></i> Lihat Tampilan Publik
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="bg-slate-950/50 text-xs font-bold uppercase text-slate-400 border-y border-white/10">
                    <tr>
                        <th class="p-4 text-center w-16">Rank</th>
                        <th class="p-4">Member</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4">Bergabung</th>
                        <th class="p-4 text-center">Total Hari Sewa</th>
                        <th class="p-4">Bakso Rank</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($topMembers as $index => $member)
                        <tr class="transition hover:bg-white/5 @if($index < 3) bg-amber-500/5 @endif">
                            <td class="p-4 text-center">
                                @if($index === 0)
                                    <i class="fa-solid fa-trophy text-amber-400 text-xl"></i>
                                @elseif($index === 1)
                                    <i class="fa-solid fa-medal text-slate-300 text-lg"></i>
                                @elseif($index === 2)
                                    <i class="fa-solid fa-award text-orange-600 text-lg"></i>
                                @else
                                    <span class="font-bold text-slate-500">#{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-white">{{ $member->name }}</div>
                                <div class="text-[10px] text-slate-400">{{ $member->email }}</div>
                            </td>
                            <td class="p-4">
                                <div class="text-[10px] text-slate-300 font-mono"><i class="fa-solid fa-phone"></i> {{ $member->profile?->phone ?? '-' }}</div>
                            </td>
                            <td class="p-4 text-xs">
                                {{ $member->created_at->format('d M Y') }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="font-bold text-lg text-white">{{ $member->total_days }}</span>
                            </td>
                            <td class="p-4">
                                <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $member->rank['badge_class'] }}">
                                    {!! $member->rank['badge'] !!}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 opacity-50"></i>
                                <p>Belum ada data member dan rental.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>

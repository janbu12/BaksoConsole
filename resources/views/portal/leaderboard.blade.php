<x-layouts.app title="Leaderboard - Top Members">
    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-black text-white flex items-center justify-center gap-3 mb-4">
                <i class="fa-solid fa-trophy text-amber-400"></i> Bakso Console Leaderboard
            </h1>
            <p class="text-slate-400">Peringkat member teraktif berdasarkan total hari penyewaan konsol.</p>
        </div>

        <div class="space-y-4">
            @foreach($topMembers as $index => $member)
                @php
                    // Styling for Top 3
                    $bgClass = 'bg-slate-900/60 border-white/10 hover:border-orange-500/30';
                    $rankColor = 'text-slate-500';
                    $rankIcon = '<span class="text-xl font-black">#'.($index+1).'</span>';

                    if ($index === 0) {
                        $bgClass = 'bg-gradient-to-r from-amber-500/10 to-amber-900/20 border-amber-500/30 shadow-lg shadow-amber-500/10 scale-105 transform z-10 relative';
                        $rankColor = 'text-amber-400';
                        $rankIcon = '<i class="fa-solid fa-trophy text-3xl"></i>';
                    } elseif ($index === 1) {
                        $bgClass = 'bg-gradient-to-r from-slate-300/10 to-slate-600/20 border-slate-300/30 shadow-lg shadow-slate-300/5';
                        $rankColor = 'text-slate-300';
                        $rankIcon = '<i class="fa-solid fa-medal text-2xl"></i>';
                    } elseif ($index === 2) {
                        $bgClass = 'bg-gradient-to-r from-orange-700/10 to-orange-900/20 border-orange-700/30 shadow-lg shadow-orange-700/5';
                        $rankColor = 'text-orange-600';
                        $rankIcon = '<i class="fa-solid fa-award text-2xl"></i>';
                    }
                @endphp

                <div class="flex items-center justify-between rounded-3xl border {{ $bgClass }} p-6 transition-all duration-300">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 flex items-center justify-center {{ $rankColor }}">
                            {!! $rankIcon !!}
                        </div>
                        
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="text-lg font-bold text-white">{{ $member->name }}</h3>
                                @if($index === 0)
                                    <span class="rounded bg-amber-500/20 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-300 border border-amber-500/30">
                                        Sang Raja Rental 👑
                                    </span>
                                @endif
                            </div>
                            <div class="text-sm text-slate-400 mt-1">
                                Bergabung sejak {{ $member->created_at->format('M Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <div class="text-2xl font-black text-white">
                            {{ $member->total_days }} <span class="text-sm text-slate-500 font-normal">Hari</span>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $member->rank['badge_class'] }}">
                            {!! $member->rank['badge'] !!}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        @if($topMembers->isEmpty())
            <div class="text-center py-12">
                <i class="fa-solid fa-ghost text-6xl text-slate-700 mb-4"></i>
                <h3 class="text-xl font-bold text-white mb-2">Belum ada data rental</h3>
                <p class="text-slate-400">Jadilah yang pertama menyewa dan duduki peringkat satu!</p>
            </div>
        @endif
    </div>
</x-layouts.app>

<x-layouts.admin title="Resource & Server Monitor" header="Resource & Server Health Monitor" subtitle="Pemantauan penggunaan CPU, RAM, Disk, dan Latensi Database secara real-time.">
    <div x-data="resourceMonitor({{ Js::from($metrics) }})" x-init="initMonitor()" class="space-y-6">

        <!-- Top Status Bar & Controls -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 rounded-2xl bg-white dark:bg-slate-900/90 p-5 border border-slate-200 dark:border-white/10 shadow-md dark:shadow-xl">
            <div class="flex items-center gap-3.5">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 shadow-lg shadow-orange-500/20">
                    <i class="fa-solid fa-server text-white text-xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-black text-slate-900 dark:text-white">Live System Metrics</h2>
                        <span class="flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30"
                              :class="isOnline ? 'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30' : 'bg-red-50 dark:bg-red-500/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-500/30'">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse" :class="isOnline ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'"></span>
                            <span x-text="isOnline ? 'Active Monitor' : 'Offline'">Active Monitor</span>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Pembaruan Terakhir: <span class="font-mono text-[#f95721] font-semibold" x-text="data.timestamp">{{ $metrics['timestamp'] }}</span> WIB
                    </p>
                </div>
            </div>

            <!-- Auto-Refresh Toggle & Manual Refresh -->
            <div class="flex items-center gap-3 self-end sm:self-center">
                <label class="flex items-center gap-2 cursor-pointer select-none rounded-xl bg-slate-100 dark:bg-slate-950 px-3 py-2 border border-slate-200 dark:border-white/10 hover:border-orange-500/40 transition">
                    <input type="checkbox" x-model="autoRefresh" class="rounded border-slate-300 dark:border-white/20 bg-white dark:bg-slate-900 text-orange-500 focus:ring-orange-500">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                        <i class="fa-solid fa-rotate text-[11px] text-[#f95721]" :class="autoRefresh ? 'animate-spin' : ''"></i>
                        <span>Auto-Refresh (3s)</span>
                    </span>
                </label>

                <button @click="fetchMetrics()" :disabled="isLoading" class="flex items-center gap-1.5 rounded-xl bg-orange-500 px-4 py-2 text-xs font-bold text-white shadow-md shadow-orange-500/20 hover:bg-orange-600 transition disabled:opacity-50">
                    <i class="fa-solid fa-arrows-rotate" :class="isLoading ? 'animate-spin' : ''"></i>
                    <span>Refresh</span>
                </button>
            </div>
        </div>

        <!-- 4 Primary Resource Metric Cards -->
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">

            <!-- 1. CPU Usage -->
            <div class="rounded-3xl bg-white dark:bg-slate-900/80 p-5 border border-slate-200 dark:border-white/10 shadow-md dark:shadow-lg relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-orange-500/20 text-[#f95721] border border-orange-500/30">
                            <i class="fa-solid fa-microchip text-base"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400 tracking-wider">CPU Usage</span>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500" x-text="data.cpu.cores + ' Cores Logical'">{{ $metrics['cpu']['cores'] }} Cores Logical</div>
                        </div>
                    </div>
                    <span class="rounded px-2 py-0.5 text-[9px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30"
                          :class="{
                              'bg-emerald-50 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/30': data.cpu.status === 'healthy',
                              'bg-amber-50 dark:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-500/30': data.cpu.status === 'warning',
                              'bg-red-50 dark:bg-red-500/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-500/30': data.cpu.status === 'critical'
                          }" x-text="data.cpu.status">{{ $metrics['cpu']['status'] }}</span>
                </div>

                <div class="my-4 flex items-baseline justify-between">
                    <div class="text-3xl font-black text-slate-900 dark:text-white font-mono">
                        <span x-text="data.cpu.usage_percentage">{{ $metrics['cpu']['usage_percentage'] }}</span><span class="text-lg text-[#f95721]">%</span>
                    </div>
                    <div class="text-right text-[11px] text-slate-500 dark:text-slate-400 truncate max-w-[140px]" :title="data.cpu.model" x-text="data.cpu.model">
                        {{ $metrics['cpu']['model'] }}
                    </div>
                </div>

                    <div class="mt-1.5 flex justify-between text-[10px] text-slate-500 font-mono">
                        <span>0%</span>
                        <span>100%</span>
                    </div>
                </div>
            </div>

            <!-- 2. Memory / RAM Usage -->
            <div class="rounded-3xl bg-slate-900/80 p-5 border border-white/10 shadow-lg relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            <i class="fa-solid fa-memory text-base"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">System RAM</span>
                            <div class="text-[10px] text-slate-500" x-text="'Total ' + data.memory.system_total_gb + ' GB'">Total {{ $metrics['memory']['system_total_gb'] }} GB</div>
                        </div>
                    </div>
                    <span class="rounded px-2 py-0.5 text-[9px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30"
                          :class="{
                              'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30': data.memory.status === 'healthy',
                              'bg-amber-500/20 text-amber-300 border border-amber-500/30': data.memory.status === 'warning',
                              'bg-red-500/20 text-red-300 border border-red-500/30': data.memory.status === 'critical'
                          }" x-text="data.memory.status">{{ $metrics['memory']['status'] }}</span>
                </div>

                <div class="my-4 flex items-baseline justify-between">
                    <div class="text-3xl font-black text-white font-mono">
                        <span x-text="data.memory.system_usage_percentage">{{ $metrics['memory']['system_usage_percentage'] }}</span><span class="text-lg text-blue-400">%</span>
                    </div>
                    <div class="text-right text-[11px] text-slate-400 font-mono">
                        <span class="text-white font-bold" x-text="data.memory.system_used_gb">{{ $metrics['memory']['system_used_gb'] }}</span> / <span x-text="data.memory.system_total_gb + ' GB'">{{ $metrics['memory']['system_total_gb'] }} GB</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div>
                    <div class="h-2 w-full rounded-full bg-slate-950 overflow-hidden border border-white/5">
                        <div class="h-full transition-all duration-500 rounded-full {{ $metrics['memory']['system_usage_percentage'] < 75 ? 'bg-blue-500' : ($metrics['memory']['system_usage_percentage'] < 90 ? 'bg-amber-500' : 'bg-red-500') }}"
                             :class="{
                                 'bg-blue-500': data.memory.system_usage_percentage < 75,
                                 'bg-amber-500': data.memory.system_usage_percentage >= 75 && data.memory.system_usage_percentage < 90,
                                 'bg-red-500': data.memory.system_usage_percentage >= 90
                             }"
                             :style="`width: ${data.memory.system_usage_percentage}%`"
                             style="width: {{ $metrics['memory']['system_usage_percentage'] }}%"></div>
                    </div>
                    <div class="mt-1.5 flex justify-between text-[10px] text-slate-500 font-mono">
                        <span>Free: <strong class="text-slate-300" x-text="data.memory.system_free_gb + ' GB'">{{ $metrics['memory']['system_free_gb'] }} GB</strong></span>
                        <span>PHP: <strong class="text-blue-300" x-text="data.memory.php_used_mb + ' MB'">{{ $metrics['memory']['php_used_mb'] }} MB</strong></span>
                    </div>
                </div>
            </div>

            <!-- 3. Storage / Disk Drive -->
            <div class="rounded-3xl bg-slate-900/80 p-5 border border-white/10 shadow-lg relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-500/20 text-purple-400 border border-purple-500/30">
                            <i class="fa-solid fa-hard-drive text-base"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Storage Disk</span>
                            <div class="text-[10px] text-slate-500" x-text="'Drive ' + data.storage.total_gb + ' GB'">Drive {{ $metrics['storage']['total_gb'] }} GB</div>
                        </div>
                    </div>
                    <span class="rounded px-2 py-0.5 text-[9px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30"
                          :class="{
                              'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30': data.storage.status === 'healthy',
                              'bg-amber-500/20 text-amber-300 border border-amber-500/30': data.storage.status === 'warning',
                              'bg-red-500/20 text-red-300 border border-red-500/30': data.storage.status === 'critical'
                          }" x-text="data.storage.status">{{ $metrics['storage']['status'] }}</span>
                </div>

                <div class="my-4 flex items-baseline justify-between">
                    <div class="text-3xl font-black text-white font-mono">
                        <span x-text="data.storage.usage_percentage">{{ $metrics['storage']['usage_percentage'] }}</span><span class="text-lg text-purple-400">%</span>
                    </div>
                    <div class="text-right text-[11px] text-slate-400 font-mono">
                        <span class="text-white font-bold" x-text="data.storage.used_gb">{{ $metrics['storage']['used_gb'] }}</span> / <span x-text="data.storage.total_gb + ' GB'">{{ $metrics['storage']['total_gb'] }} GB</span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div>
                    <div class="h-2 w-full rounded-full bg-slate-950 overflow-hidden border border-white/5">
                        <div class="h-full transition-all duration-500 rounded-full {{ $metrics['storage']['usage_percentage'] < 75 ? 'bg-purple-500' : ($metrics['storage']['usage_percentage'] < 90 ? 'bg-amber-500' : 'bg-red-500') }}"
                             :class="{
                                 'bg-purple-500': data.storage.usage_percentage < 75,
                                 'bg-amber-500': data.storage.usage_percentage >= 75 && data.storage.usage_percentage < 90,
                                 'bg-red-500': data.storage.usage_percentage >= 90
                             }"
                             :style="`width: ${data.storage.usage_percentage}%`"
                             style="width: {{ $metrics['storage']['usage_percentage'] }}%"></div>
                    </div>
                    <div class="mt-1.5 flex justify-between text-[10px] text-slate-500 font-mono">
                        <span>Free: <strong class="text-slate-300" x-text="data.storage.free_gb + ' GB'">{{ $metrics['storage']['free_gb'] }} GB</strong></span>
                        <span>Used: <strong class="text-purple-300" x-text="data.storage.used_gb + ' GB'">{{ $metrics['storage']['used_gb'] }} GB</strong></span>
                    </div>
                </div>
            </div>

            <!-- 4. Database Ping Latency -->
            <div class="rounded-3xl bg-slate-900/80 p-5 border border-white/10 shadow-lg relative overflow-hidden flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                            <i class="fa-solid fa-database text-base"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Database Ping</span>
                            <div class="text-[10px] text-slate-500" x-text="data.database.driver + ' (' + data.database.database_name + ')'">{{ $metrics['database']['driver'] }} ({{ $metrics['database']['database_name'] }})</div>
                        </div>
                    </div>
                    <span class="rounded px-2 py-0.5 text-[9px] font-black uppercase tracking-wider {{ $metrics['database']['connected'] ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30' }}"
                          :class="data.database.connected ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30'"
                          x-text="data.database.connected ? 'Connected' : 'Offline'">{{ $metrics['database']['connected'] ? 'Connected' : 'Offline' }}</span>
                </div>

                <div class="my-4 flex items-baseline justify-between">
                    <div class="text-3xl font-black text-white font-mono">
                        <span x-text="data.database.latency_ms">{{ $metrics['database']['latency_ms'] }}</span><span class="text-lg text-emerald-400"> ms</span>
                    </div>
                    <div class="text-right text-[11px] text-slate-400 font-mono truncate max-w-[120px]" :title="data.database.version" x-text="'v' + data.database.version">
                        v{{ $metrics['database']['version'] }}
                    </div>
                </div>

                <!-- Status details -->
                <div class="pt-2 border-t border-white/5 flex items-center justify-between text-[10px] text-slate-400">
                    <span>Query Response:</span>
                    <strong class="font-mono text-emerald-400" x-text="data.database.latency_ms < 50 ? '⚡ Sangat Cepat' : 'Normal'">
                        {{ $metrics['database']['latency_ms'] < 50 ? '⚡ Sangat Cepat' : 'Normal' }}
                    </strong>
                </div>
            </div>

        </div>

        <!-- Detailed Runtime & Server Environment Breakdown -->
        <div class="grid gap-6 lg:grid-cols-2">

            <!-- PHP Process & Memory Deep Dive -->
            <div class="rounded-3xl bg-slate-900/80 p-6 border border-white/10 shadow-xl space-y-4">
                <div class="flex items-center gap-2.5 border-b border-white/10 pb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500/20 text-indigo-400">
                        <i class="fa-brands fa-php text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">PHP Runtime & Process Allocation</h3>
                        <p class="text-[11px] text-slate-400">Statistik memori script dan alokasi proses aplikasi</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="rounded-2xl bg-slate-950 p-3.5 border border-white/5">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">PHP Process Memory</span>
                        <div class="text-lg font-black text-white font-mono" x-text="data.memory.php_used_mb + ' MB'">{{ $metrics['memory']['php_used_mb'] }} MB</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Memori aktif saat eksekusi</div>
                    </div>

                    <div class="rounded-2xl bg-slate-950 p-3.5 border border-white/5">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">PHP Peak Memory</span>
                        <div class="text-lg font-black text-indigo-400 font-mono" x-text="data.memory.php_peak_mb + ' MB'">{{ $metrics['memory']['php_peak_mb'] }} MB</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Puncak konsumsi memori</div>
                    </div>

                    <div class="rounded-2xl bg-slate-950 p-3.5 border border-white/5">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">PHP Memory Limit</span>
                        <div class="text-lg font-black text-emerald-400 font-mono" x-text="data.memory.php_limit">{{ $metrics['memory']['php_limit'] }}</div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Batas alokasi per request</div>
                    </div>

                    <div class="rounded-2xl bg-slate-950 p-3.5 border border-white/5">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block mb-1">OPcache Acceleration</span>
                        <div class="text-lg font-black font-mono flex items-center gap-1.5 {{ $metrics['environment']['opcache_enabled'] ? 'text-emerald-400' : 'text-amber-400' }}"
                             :class="data.environment.opcache_enabled ? 'text-emerald-400' : 'text-amber-400'">
                            <span x-text="data.environment.opcache_enabled ? 'Active ⚡' : 'Disabled'">{{ $metrics['environment']['opcache_enabled'] ? 'Active ⚡' : 'Disabled' }}</span>
                        </div>
                        <div class="text-[10px] text-slate-500 mt-0.5">Bytecode caching status</div>
                    </div>
                </div>
            </div>

            <!-- Server Stack & Configuration Overview -->
            <div class="rounded-3xl bg-slate-900/80 p-6 border border-white/10 shadow-xl space-y-4">
                <div class="flex items-center gap-2.5 border-b border-white/10 pb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-500/20 text-orange-400">
                        <i class="fa-solid fa-sliders text-base"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-white">Application & Server Environment</h3>
                        <p class="text-[11px] text-slate-400">Konfigurasi framework dan arsitektur server</p>
                    </div>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between rounded-xl bg-slate-950 p-3 border border-white/5">
                        <span class="text-slate-400"><i class="fa-solid fa-code-branch text-orange-400 mr-2"></i> Laravel Version:</span>
                        <strong class="font-mono text-white" x-text="'v' + data.environment.laravel_version">v{{ $metrics['environment']['laravel_version'] }}</strong>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-slate-950 p-3 border border-white/5">
                        <span class="text-slate-400"><i class="fa-brands fa-php text-blue-400 mr-2"></i> PHP Engine:</span>
                        <strong class="font-mono text-white" x-text="'PHP ' + data.environment.php_version">PHP {{ $metrics['environment']['php_version'] }}</strong>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-slate-950 p-3 border border-white/5">
                        <span class="text-slate-400"><i class="fa-solid fa-laptop-code text-purple-400 mr-2"></i> Operating System:</span>
                        <strong class="font-mono text-white" x-text="data.environment.os">{{ $metrics['environment']['os'] }}</strong>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-slate-950 p-3 border border-white/5">
                        <span class="text-slate-400"><i class="fa-solid fa-bolt text-amber-400 mr-2"></i> Cache & Queue Driver:</span>
                        <strong class="font-mono text-orange-300" x-text="data.environment.cache_driver + ' / ' + data.environment.queue_driver">{{ $metrics['environment']['cache_driver'] }} / {{ $metrics['environment']['queue_driver'] }}</strong>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-slate-950 p-3 border border-white/5">
                        <span class="text-slate-400"><i class="fa-solid fa-clock text-emerald-400 mr-2"></i> Timezone:</span>
                        <strong class="font-mono text-white" x-text="data.environment.timezone">{{ $metrics['environment']['timezone'] }}</strong>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
        window.resourceMonitor = function(initialData) {
            return {
                data: initialData,
                autoRefresh: true,
                isLoading: false,
                isOnline: true,
                timer: null,

                initMonitor() {
                    this.timer = setInterval(() => {
                        if (this.autoRefresh) {
                            this.fetchMetrics();
                        }
                    }, 3000);
                },

                async fetchMetrics() {
                    this.isLoading = true;
                    try {
                        const res = await fetch('{{ route('admin.resources.metrics') }}');
                        if (res.ok) {
                            this.data = await res.json();
                            this.isOnline = true;
                        } else {
                            this.isOnline = false;
                        }
                    } catch (e) {
                        this.isOnline = false;
                    } finally {
                        this.isLoading = false;
                    }
                }
            };
        };
    </script>
</x-layouts.admin>

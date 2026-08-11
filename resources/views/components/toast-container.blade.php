<div x-data="toastManager()"
     @toast.window="addToast($event.detail)"
     class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-md w-full pointer-events-none px-4 sm:px-0">
    
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-[-10px] scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-[-10px] scale-95"
             class="pointer-events-auto relative overflow-hidden rounded-2xl border p-4 shadow-2xl backdrop-blur-xl transition-all duration-300"
             :class="{
                 'border-emerald-500/40 bg-white/95 dark:bg-slate-900/95 text-emerald-950 dark:text-emerald-300 shadow-emerald-500/10': toast.type === 'success',
                 'border-red-500/40 bg-white/95 dark:bg-slate-900/95 text-red-950 dark:text-red-300 shadow-red-500/10': toast.type === 'error',
                 'border-amber-500/40 bg-white/95 dark:bg-slate-900/95 text-amber-950 dark:text-amber-300 shadow-amber-500/10': toast.type === 'warning',
                 'border-blue-500/40 bg-white/95 dark:bg-slate-900/95 text-blue-950 dark:text-blue-300 shadow-blue-500/10': toast.type === 'info'
             }">
            
            <div class="flex items-start gap-3.5">
                <!-- Icon -->
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-base"
                     :class="{
                         'bg-emerald-500/20 text-emerald-600 dark:text-emerald-400': toast.type === 'success',
                         'bg-red-500/20 text-red-600 dark:text-red-400': toast.type === 'error',
                         'bg-amber-500/20 text-amber-600 dark:text-amber-400': toast.type === 'warning',
                         'bg-blue-500/20 text-blue-600 dark:text-blue-400': toast.type === 'info'
                     }">
                    <template x-if="toast.type === 'success'">
                        <i class="fa-solid fa-circle-check"></i>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </template>
                    <template x-if="toast.type === 'warning'">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <i class="fa-solid fa-circle-info"></i>
                    </template>
                </div>

                <!-- Text Content -->
                <div class="flex-1 min-w-0">
                    <h4 class="text-xs font-black uppercase tracking-wider"
                        :class="{
                            'text-emerald-700 dark:text-emerald-400': toast.type === 'success',
                            'text-red-700 dark:text-red-400': toast.type === 'error',
                            'text-amber-700 dark:text-amber-400': toast.type === 'warning',
                            'text-blue-700 dark:text-blue-400': toast.type === 'info'
                        }"
                        x-text="toast.title"></h4>
                    <p class="mt-1 text-xs text-slate-700 dark:text-slate-300 leading-relaxed break-words" x-text="toast.message"></p>
                </div>

                <!-- Close Button -->
                <button @click="removeToast(toast.id)" type="button" class="text-slate-400 hover:text-slate-700 dark:hover:text-white transition p-1">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <!-- Auto-dismiss Progress Bar -->
            <div class="absolute bottom-0 left-0 h-1 bg-current opacity-30 transition-all ease-linear"
                 :style="'width: ' + toast.progress + '%; transition-duration: 50ms;'"></div>
        </div>
    </template>
</div>

<script>
    function toastManager() {
        return {
            toasts: [],
            init() {
                // Laravel Flash Session Hooks
                @if(session('success'))
                    this.addToast({ type: 'success', title: 'Berhasil', message: @js(session('success')) });
                @endif
                @if(session('error'))
                    this.addToast({ type: 'error', title: 'Kendala', message: @js(session('error')) });
                @endif
                @if(session('warning'))
                    this.addToast({ type: 'warning', title: 'Peringatan', message: @js(session('warning')) });
                @endif
                @if($errors->any())
                    @foreach($errors->all() as $error)
                        this.addToast({ type: 'error', title: 'Input Tidak Valid', message: @js($error) });
                    @endforeach
                @endif
            },
            addToast({ type = 'info', title = '', message = '', duration = 5000 }) {
                const id = Date.now() + Math.random();
                if (!title) {
                    title = type === 'success' ? 'Berhasil' : (type === 'error' ? 'Gagal' : 'Informasi');
                }
                const toast = { id, type, title, message, visible: true, progress: 100 };
                this.toasts.push(toast);

                const step = 50;
                const decrement = (step / duration) * 100;
                const timer = setInterval(() => {
                    toast.progress -= decrement;
                    if (toast.progress <= 0) {
                        clearInterval(timer);
                        this.removeToast(id);
                    }
                }, step);
            },
            removeToast(id) {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index !== -1) {
                    this.toasts[index].visible = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 300);
                }
            }
        }
    }
</script>

@props([
    'name',
    'title' => '',
    'subtitle' => '',
    'icon' => 'fa-solid fa-layer-group',
    'maxWidth' => '2xl'
])

@php
$maxWidthClass = match ($maxWidth) {
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '3xl' => 'sm:max-w-3xl',
    '4xl' => 'sm:max-w-4xl',
    '5xl' => 'sm:max-w-5xl',
    default => 'sm:max-w-2xl',
};
@endphp

<div x-data="{ show: false }"
     x-show="show"
     x-cloak
     x-on:close-modal.window="if ($event.detail === '{{ $name }}') show = false"
     x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true"
     x-on:keydown.escape.window="show = false"
     class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
    
    <!-- Backdrop Overlay -->
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="show = false"
         class="fixed inset-0 bg-slate-950/75 backdrop-blur-md transition-opacity"></div>

    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative w-full {{ $maxWidthClass }} transform overflow-hidden rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-slate-900 shadow-2xl transition-all">
            
            <!-- Modal Header -->
            @if($title)
                <div class="flex items-start justify-between border-b border-slate-200 dark:border-white/10 px-6 py-4 bg-slate-50 dark:bg-slate-950/60">
                    <div class="flex items-center gap-3">
                        @if($icon)
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/15 text-[#f95721] border border-orange-500/20 text-base">
                                <i class="{{ $icon }}"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-base font-black text-slate-900 dark:text-white">{{ $title }}</h3>
                            @if($subtitle)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>

                    <button @click="show = false" type="button" class="rounded-xl border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-slate-800 p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-white/10 hover:text-slate-900 dark:hover:text-white transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif

            <!-- Modal Content Body -->
            <div class="p-6 max-h-[80vh] overflow-y-auto scrollbar-thin">
                {{ $slot }}
            </div>

            <!-- Modal Footer (Optional) -->
            @if(isset($footer))
                <div class="flex items-center justify-end gap-3 border-t border-slate-200 dark:border-white/10 px-6 py-4 bg-slate-50 dark:bg-slate-950/60">
                    {{ $footer }}
                </div>
            @endif

        </div>
    </div>
</div>

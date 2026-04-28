@props(['icon', 'value', 'label', 'color' => 'blue', 'href' => null])

@php
    $colorClasses = match($color) {
        'orange', 'red' => ['icon' => 'text-primary-600 bg-primary-50', 'border' => 'border-l-primary-500'],
        'blue'          => ['icon' => 'text-blue-600 bg-blue-50',       'border' => 'border-l-blue-500'],
        'green'         => ['icon' => 'text-emerald-600 bg-emerald-50', 'border' => 'border-l-emerald-500'],
        'yellow'        => ['icon' => 'text-amber-600 bg-amber-50',     'border' => 'border-l-amber-500'],
        'purple'        => ['icon' => 'text-violet-600 bg-violet-50',   'border' => 'border-l-violet-500'],
        'cyan'          => ['icon' => 'text-cyan-600 bg-cyan-50',       'border' => 'border-l-cyan-500'],
        default         => ['icon' => 'text-slate-600 bg-slate-50',     'border' => 'border-l-slate-500'],
    };
@endphp

@if($href)
    <a href="{{ $href }}"
       class="bg-white rounded-xl shadow-sm border border-slate-200 border-l-4 {{ $colorClasses['border'] }}
              p-4 sm:p-5 flex items-center gap-3 sm:gap-4
              transition-all hover:shadow-card hover:-translate-y-0.5 group block">
@else
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 border-l-4 {{ $colorClasses['border'] }}
                p-4 sm:p-5 flex items-center gap-3 sm:gap-4
                transition-all hover:shadow-card group">
@endif
        {{-- Icon --}}
        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center flex-shrink-0
                    {{ $colorClasses['icon'] }} transition-transform group-hover:scale-110">
            {!! $icon !!}
        </div>

        {{-- Info --}}
        <div class="min-w-0 flex-1">
            <p class="text-xl sm:text-2xl font-extrabold text-slate-800 leading-none truncate">
                {{ $value }}
            </p>
            <p class="text-[11px] sm:text-xs font-semibold text-slate-400 mt-1 leading-tight
                      line-clamp-2 sm:truncate">
                {{ $label }}
            </p>
        </div>

        {{-- Arrow icon if href --}}
        @if($href)
            <div class="flex-shrink-0 w-7 h-7 rounded-full bg-slate-50 flex items-center justify-center
                        text-slate-400 group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        @endif

@if($href)
    </a>
@else
    </div>
@endif

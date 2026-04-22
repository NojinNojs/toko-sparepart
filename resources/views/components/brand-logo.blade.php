@props(['size' => 'md'])

@php
    $sizes = [
        'sm' => ['icon' => 'w-6 h-6', 'text' => 'text-lg', 'dot' => 'w-1 h-1'],
        'md' => ['icon' => 'w-8 h-8', 'text' => 'text-xl', 'dot' => 'w-1.5 h-1.5'],
        'lg' => ['icon' => 'w-10 h-10', 'text' => 'text-2xl', 'dot' => 'w-2 h-2'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2 group']) }}>
    {{-- Gear Icon --}}
    <div class="{{ $s['icon'] }} flex-shrink-0 text-primary-600 transition-transform duration-300 group-hover:rotate-90">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
    {{-- Brand Text --}}
    <div class="flex items-baseline">
        <span class="font-extrabold {{ $s['text'] }} tracking-tighter text-slate-800">Toko</span>
        <span class="font-bold {{ $s['text'] }} tracking-tighter text-primary-600 ml-0.5">Sparepart</span>
        <div class="{{ $s['dot'] }} bg-primary-600 rounded-full ml-0.5 mt-auto mb-1.5 flex-shrink-0"></div>
    </div>
</div>

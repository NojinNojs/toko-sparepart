@props(['status'])

@php
    $class = match($status) {
        'dikonfirmasi' => 'badge-success',
        'ditolak' => 'badge-danger',
        'pending' => 'badge-warning',
        default => 'badge-neutral'
    };
    
    $icon = match($status) {
        'dikonfirmasi' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>',
        'ditolak' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>',
        'pending' => '<svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        default => ''
    };
@endphp

<span class="badge {{ $class }}">
    {!! $icon !!}
    {{ ucfirst($status) }}
</span>

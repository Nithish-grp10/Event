@props([
    'padding' => 'md', // none, sm, md, lg
    'shadow' => 'card', // none, subtle, card, floating
])

@php
    $paddingClasses = match($padding) {
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
        default => 'p-6',
    };

    $shadowClasses = match($shadow) {
        'none' => '',
        'subtle' => 'shadow-subtle',
        'card' => 'shadow-card',
        'floating' => 'shadow-floating',
        default => 'shadow-card',
    };
@endphp

<div {{ $attributes->merge(['class' => "bg-white rounded-2xl {$shadowClasses} overflow-hidden"]) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-neutral-200 bg-neutral-50/50">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $paddingClasses }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 border-t border-neutral-200 bg-neutral-50/50">
            {{ $footer }}
        </div>
    @endif
</div>

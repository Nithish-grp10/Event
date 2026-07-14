@props([
    'variant' => 'primary', // primary, secondary, danger, ghost, outline
    'size' => 'md', // sm, md, lg
    'type' => 'button',
    'href' => null,
    'block' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-base',
        default => 'px-4 py-2 text-sm',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 shadow-subtle',
        'secondary' => 'bg-neutral-100 text-neutral-800 hover:bg-neutral-200 focus:ring-neutral-500',
        'danger' => 'bg-danger-600 text-white hover:bg-danger-700 focus:ring-danger-500 shadow-subtle',
        'ghost' => 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900 focus:ring-neutral-500',
        'outline' => 'border border-neutral-300 text-neutral-700 hover:bg-neutral-50 focus:ring-neutral-500',
        default => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500',
    };

    $blockClass = $block ? 'w-full flex justify-center' : '';
    
    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses} {$blockClass}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if(isset($iconLeft))
            <span class="mr-2 -ml-1 w-4 h-4 flex items-center justify-center">{{ $iconLeft }}</span>
        @endif
        
        {{ $slot }}

        @if(isset($iconRight))
            <span class="ml-2 -mr-1 w-4 h-4 flex items-center justify-center">{{ $iconRight }}</span>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if(isset($iconLeft))
            <span class="mr-2 -ml-1 w-4 h-4 flex items-center justify-center">{{ $iconLeft }}</span>
        @endif
        
        {{ $slot }}

        @if(isset($iconRight))
            <span class="ml-2 -mr-1 w-4 h-4 flex items-center justify-center">{{ $iconRight }}</span>
        @endif
    </button>
@endif

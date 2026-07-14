@props([
    'variant' => 'primary', // primary, neutral, success, warning, danger, outline
    'size' => 'md', // sm, md
    'rounded' => 'full', // full, default
])

@php
    $baseClasses = 'inline-flex items-center font-medium whitespace-nowrap';
    
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
        default => 'px-2.5 py-1 text-sm',
    };

    $roundedClasses = match($rounded) {
        'full' => 'rounded-full',
        'default' => 'rounded-md',
        default => 'rounded-full',
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-primary-100 text-primary-800',
        'neutral' => 'bg-neutral-100 text-neutral-800',
        'success' => 'bg-success-100 text-success-800',
        'warning' => 'bg-warning-100 text-warning-800',
        'danger' => 'bg-danger-100 text-danger-800',
        'outline' => 'border border-neutral-300 text-neutral-700 bg-white',
        default => 'bg-primary-100 text-primary-800',
    };
    
    $classes = "{$baseClasses} {$sizeClasses} {$roundedClasses} {$variantClasses}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($dot))
        <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ match($variant) {
            'success' => 'text-success-500',
            'warning' => 'text-warning-500',
            'danger' => 'text-danger-500',
            'primary' => 'text-primary-500',
            default => 'text-neutral-500',
        } }}" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
        </svg>
    @endif
    
    @if(isset($iconLeft))
        <span class="-ml-0.5 mr-1 h-3.5 w-3.5 inline-flex items-center">{{ $iconLeft }}</span>
    @endif

    {{ $slot }}

    @if(isset($iconRight))
        <span class="-mr-0.5 ml-1 h-3.5 w-3.5 inline-flex items-center">{{ $iconRight }}</span>
    @endif
</span>

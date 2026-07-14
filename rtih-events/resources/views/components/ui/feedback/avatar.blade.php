@props([
    'src' => null,
    'name' => null,
    'size' => 'md', // sm, md, lg, xl
    'rounded' => 'full', // full, md
    'border' => false,
])

@php
    $sizeClasses = match($size) {
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-12 w-12 text-base',
        'xl' => 'h-16 w-16 text-xl',
        default => 'h-10 w-10 text-sm',
    };

    $roundedClasses = match($rounded) {
        'full' => 'rounded-full',
        'md' => 'rounded-xl',
        default => 'rounded-full',
    };

    $borderClasses = $border ? 'ring-2 ring-white' : '';
    
    $classes = "inline-flex items-center justify-center bg-primary-100 text-primary-700 font-bold overflow-hidden {$sizeClasses} {$roundedClasses} {$borderClasses}";
    
    $initials = '';
    if ($name) {
        $words = explode(' ', $name);
        $initials = count($words) > 1 
            ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1))
            : strtoupper(substr($name, 0, 1));
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($src)
        <img src="{{ $src }}" alt="{{ $name }}" class="h-full w-full object-cover">
    @else
        <span>{{ $initials ?: '?' }}</span>
    @endif
</div>

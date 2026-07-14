@props([
    'primary' => false,
    'actions' => false,
])

@php
    $classes = $primary 
        ? 'px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900' 
        : 'px-6 py-4 whitespace-nowrap text-sm text-neutral-500';
        
    if ($actions) {
        $classes = 'px-6 py-4 whitespace-nowrap text-right text-sm font-medium';
    }
@endphp

<td {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</td>

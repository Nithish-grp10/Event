@props([
    'title',
    'description',
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-12 px-4 rounded-2xl border-2 border-dashed border-neutral-200 bg-neutral-50']) }}>
    @if(isset($icon))
        <div class="mx-auto h-12 w-12 text-neutral-400 mb-4 flex items-center justify-center">
            {{ $icon }}
        </div>
    @endif
    
    <h3 class="mt-2 text-sm font-semibold text-neutral-900">{{ $title }}</h3>
    
    <p class="mt-1 text-sm text-neutral-500 max-w-sm mx-auto">
        {{ $description }}
    </p>
    
    @if(isset($action))
        <div class="mt-6">
            {{ $action }}
        </div>
    @endif
</div>

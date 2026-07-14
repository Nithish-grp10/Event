@props([
    'type' => 'text', // text, avatar, card, table-row
    'lines' => 1,
])

@if($type === 'text')
    <div {{ $attributes->merge(['class' => 'space-y-2']) }}>
        @for($i = 0; $i < $lines; $i++)
            <div class="h-4 bg-neutral-200 rounded animate-pulse {{ $i === $lines - 1 && $lines > 1 ? 'w-2/3' : 'w-full' }}"></div>
        @endfor
    </div>
@elseif($type === 'avatar')
    <div {{ $attributes->merge(['class' => 'rounded-full bg-neutral-200 animate-pulse']) }}></div>
@elseif($type === 'card')
    <div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-subtle p-6 space-y-4']) }}>
        <div class="flex items-center space-x-4">
            <div class="h-12 w-12 rounded-full bg-neutral-200 animate-pulse"></div>
            <div class="space-y-2 flex-1">
                <div class="h-4 bg-neutral-200 rounded animate-pulse w-1/3"></div>
                <div class="h-3 bg-neutral-200 rounded animate-pulse w-1/4"></div>
            </div>
        </div>
        <div class="space-y-2">
            <div class="h-4 bg-neutral-200 rounded animate-pulse w-full"></div>
            <div class="h-4 bg-neutral-200 rounded animate-pulse w-5/6"></div>
        </div>
    </div>
@elseif($type === 'table-row')
    <div {{ $attributes->merge(['class' => 'flex items-center space-x-4 py-4 border-b border-neutral-100']) }}>
        <div class="h-4 bg-neutral-200 rounded animate-pulse w-1/4"></div>
        <div class="h-4 bg-neutral-200 rounded animate-pulse w-1/4"></div>
        <div class="h-4 bg-neutral-200 rounded animate-pulse w-1/4"></div>
        <div class="h-8 bg-neutral-200 rounded-xl animate-pulse w-16 ml-auto"></div>
    </div>
@endif

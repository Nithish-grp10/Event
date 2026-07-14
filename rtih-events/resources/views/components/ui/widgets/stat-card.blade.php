@props([
    'title',
    'value',
    'icon' => null,
    'color' => 'indigo',
    'trend' => null,
    'trendValue' => null
])

<x-ui.card class="flex items-center gap-4 p-6 hover:shadow-md transition-shadow">
    @if($icon)
        <div class="p-3 rounded-lg bg-{{ $color }}-50 text-{{ $color }}-600 shrink-0">
            {{ $icon }}
        </div>
    @endif
    
    <div class="flex-1 overflow-hidden">
        <p class="text-sm font-medium text-gray-500 truncate">{{ $title }}</p>
        <div class="flex items-baseline gap-2 mt-1">
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
            @if($trend === 'up')
                <span class="text-sm font-medium text-emerald-600 flex items-center">
                    <x-heroicon-m-arrow-trending-up class="w-4 h-4 mr-1"/>
                    {{ $trendValue }}
                </span>
            @elseif($trend === 'down')
                <span class="text-sm font-medium text-red-600 flex items-center">
                    <x-heroicon-m-arrow-trending-down class="w-4 h-4 mr-1"/>
                    {{ $trendValue }}
                </span>
            @endif
        </div>
    </div>
</x-ui.card>

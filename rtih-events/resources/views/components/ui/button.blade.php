<div>
    @php
        $variants = [
            'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-[0_1px_2px_rgba(0,0,0,0.1),inset_0_1px_0_rgba(255,255,255,0.1)] focus-visible:ring-indigo-500 border border-transparent',
            'secondary' => 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-200/80 shadow-sm focus-visible:ring-gray-200',
            'danger' => 'bg-red-600 text-white hover:bg-red-700 shadow-sm focus-visible:ring-red-500 border border-transparent',
            'ghost' => 'bg-transparent text-gray-600 hover:bg-gray-100/80 hover:text-gray-900 border border-transparent focus-visible:ring-gray-200',
        ];
        
        $sizes = [
            'sm' => 'px-2.5 py-1.5 text-xs',
            'md' => 'px-4 py-2 text-sm',
            'lg' => 'px-5 py-2.5 text-base',
        ];
        
        $classes = 'inline-flex justify-center items-center font-medium rounded-lg transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none active:scale-[0.98]';
        
        $variantClass = $variants[$variant ?? 'primary'];
        $sizeClass = $sizes[$size ?? 'md'];
    @endphp

    @if($attributes->has('href'))
        <a {{ $attributes->merge(['class' => "$classes $variantClass $sizeClass"]) }}>
            {{ $slot }}
        </a>
    @else
        <button {{ $attributes->merge(['class' => "$classes $variantClass $sizeClass", 'type' => 'button']) }}>
            {{ $slot }}
        </button>
    @endif
</div>
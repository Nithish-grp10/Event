<div>
    @php
        $variants = [
            'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 border border-transparent shadow-sm',
            'secondary' => 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 shadow-sm',
            'danger' => 'bg-red-600 text-white hover:bg-red-700 border border-transparent shadow-sm',
            'ghost' => 'bg-transparent text-gray-600 hover:bg-gray-100 hover:text-gray-900',
        ];
        
        $sizes = [
            'sm' => 'px-3 py-1.5 text-xs',
            'md' => 'px-4 py-2 text-sm',
            'lg' => 'px-6 py-3 text-base',
        ];
        
        $classes = 'inline-flex justify-center items-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:pointer-events-none';
        
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
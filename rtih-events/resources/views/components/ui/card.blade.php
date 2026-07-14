<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05),0_1px_2px_rgba(0,0,0,0.02)] border border-gray-200/60 overflow-hidden relative']) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/30 flex items-center justify-between">
            {{ $header }}
        </div>
    @endif
    
    <div class="p-6">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center">
            {{ $footer }}
        </div>
    @endif
</div>
<div>
    @if(isset($label))
        <label for="{{ $attributes->get('id') ?? $name }}" class="block text-sm font-semibold text-gray-700 mb-1.5">
            {{ $label }}
            @if($attributes->has('required'))
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input 
            {{ $attributes->merge([
                'type' => 'text', 
                'name' => $name,
                'id' => $attributes->get('id') ?? $name,
                'class' => 'block w-full rounded-lg border border-gray-200/80 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white ' . ($errors->has($name) ? ' border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : 'hover:border-gray-300')
            ]) }}
        >
        
        @if($errors->has($name))
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <x-heroicon-s-exclamation-circle class="h-5 w-5 text-red-500" />
            </div>
        @endif
    </div>

    @error($name)
        <p class="mt-1.5 text-sm text-red-600 font-medium flex items-center gap-1">
            {{ $message }}
        </p>
    @enderror
    
    @if(isset($hint) && !$errors->has($name))
        <p class="mt-1.5 text-sm text-gray-500">{{ $hint }}</p>
    @endif
</div>
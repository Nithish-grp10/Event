@props([
    'type' => 'text',
    'id' => null,
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'label' => null,
    'error' => null,
    'hint' => null,
])

@php
    $id = $id ?? $name ?? uniqid('input-');
    $hasError = $error || ($name && $errors->has($name));
    $errorMessage = $error ?? ($name ? $errors->first($name) : null);
    
    $inputClasses = 'block w-full rounded-xl sm:text-sm transition-colors focus:outline-none';
    
    if ($hasError) {
        $inputClasses .= ' border-danger-300 text-danger-900 placeholder-danger-300 focus:border-danger-500 focus:ring-danger-500';
    } else {
        $inputClasses .= ' border-neutral-300 text-neutral-900 placeholder-neutral-400 focus:border-primary-500 focus:ring-primary-500 disabled:bg-neutral-50 disabled:text-neutral-500';
    }
@endphp

<div class="{{ $attributes->get('class') }}">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-neutral-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-danger-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if(isset($iconLeft))
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-neutral-400">
                {{ $iconLeft }}
            </div>
        @endif
        
        <input 
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->except('class')->merge([
                'class' => $inputClasses . (isset($iconLeft) ? ' pl-10' : '') . (isset($iconRight) ? ' pr-10' : '')
            ]) }}
        >

        @if(isset($iconRight))
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-neutral-400">
                {{ $iconRight }}
            </div>
        @endif
    </div>

    @if($hasError)
        <p class="mt-1 text-sm text-danger-600 animate-fade-in" id="{{ $id }}-error">
            {{ $errorMessage }}
        </p>
    @elseif($hint)
        <p class="mt-1 text-sm text-neutral-500" id="{{ $id }}-hint">
            {{ $hint }}
        </p>
    @endif
</div>

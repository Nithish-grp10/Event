@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600 bg-green-50 border border-green-200 p-4 rounded-xl']) }}>
        <div class="flex items-center gap-2">
            <x-heroicon-o-check-circle class="w-5 h-5 text-green-500"/>
            {{ $status }}
        </div>
    </div>
@endif

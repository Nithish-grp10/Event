@props([
    'headers' => [],
    'hasActions' => false,
])

<div class="overflow-x-auto rounded-2xl shadow-subtle border border-neutral-200 bg-white">
    <table class="min-w-full divide-y divide-neutral-200">
        <thead class="bg-neutral-50">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                @endforeach
                
                @if($hasActions)
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                @endif
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-neutral-200">
            {{ $slot }}
        </tbody>
    </table>
    
    @if(isset($pagination))
        <div class="px-6 py-4 border-t border-neutral-200 bg-white">
            {{ $pagination }}
        </div>
    @endif
</div>

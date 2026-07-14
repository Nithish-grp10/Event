<div class="overflow-x-auto shadow-sm ring-1 ring-black ring-opacity-5 rounded-lg">
    <table class="min-w-full divide-y divide-gray-300">
        @if(isset($head))
            <thead class="bg-gray-50">
                <tr>
                    {{ $head }}
                </tr>
            </thead>
        @endif
        <tbody class="divide-y divide-gray-200 bg-white">
            {{ $slot }}
        </tbody>
    </table>
</div>
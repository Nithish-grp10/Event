<div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden" x-data="{
    selected: [],
    selectAll: false,
    toggleAll() {
        if (this.selectAll) {
            this.selected = [...document.querySelectorAll('.row-checkbox')].map(cb => cb.value);
        } else {
            this.selected = [];
        }
    }
}">
    <!-- Table Toolbar (Search, Filter, Export, Column Visibility) -->
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
        <!-- Search and Bulk Actions -->
        <div class="flex items-center gap-3 w-full sm:w-auto">
            @if(isset($search))
                <div class="relative max-w-sm w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-heroicon-o-magnifying-glass class="h-4 w-4 text-gray-400" />
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="{{ $searchPlaceholder ?? 'Search...' }}">
                </div>
            @endif

            <div x-show="selected.length > 0" class="flex items-center gap-2" x-transition x-cloak>
                <span class="text-sm font-medium text-gray-600"><span x-text="selected.length"></span> selected</span>
                @if(isset($bulkActions))
                    {{ $bulkActions }}
                @endif
            </div>
        </div>
        
        <!-- Utilities (Export, Import, Column Vis) -->
        <div class="flex items-center gap-2 shrink-0">
            @if(isset($actions))
                {{ $actions }}
            @endif
        </div>
    </div>

    <!-- Table Wrapper for Sticky Header & Responsiveness -->
    <div class="overflow-x-auto relative min-h-[200px]">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm">
                <tr>
                    @if(isset($selectable) && $selectable)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12 bg-gray-50">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        </th>
                    @endif
                    {{ $head }}
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                {{ $slot }}
            </tbody>
        </table>
        
        @if(empty(trim($slot->toHtml())) && isset($empty))
            <div class="p-8 flex flex-col items-center justify-center text-gray-500">
                {{ $empty }}
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if(isset($pagination))
        <div class="px-6 py-4 border-t border-gray-100 bg-white">
            {{ $pagination }}
        </div>
    @endif
</div>

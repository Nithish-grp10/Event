<x-app-layout>
    <x-slot name="header">
        Events
    </x-slot>
    <x-slot name="actions">
        <x-ui.button href="{{ route('events.create') }}" variant="primary">
            <x-heroicon-o-plus class="w-4 h-4 mr-2"/>
            New Event
        </x-ui.button>
    </x-slot>

    @if($events->count() > 0)
        <x-ui.data-table search="true" searchPlaceholder="Search events...">
            <x-slot name="head">
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Event Details</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </x-slot>

            @foreach($events as $event)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 border border-indigo-100">
                                <x-heroicon-o-calendar class="w-5 h-5"/>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $event->title }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $event->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($event->start_date)->format('g:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($event->status === 'published')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                                Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-1.5"></span>
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <x-ui.button href="{{ route('events.show', $event) }}" variant="ghost" size="sm">
                            View
                        </x-ui.button>
                        @can('update', $event)
                        <x-ui.button href="{{ route('events.edit', $event) }}" variant="secondary" size="sm">
                            Edit
                        </x-ui.button>
                        @endcan
                    </td>
                </tr>
            @endforeach

            @if($events->hasPages())
                <x-slot name="pagination">
                    {{ $events->links() }}
                </x-slot>
            @endif
        </x-ui.data-table>
    @else
        <!-- Empty State -->
        <x-ui.card class="flex flex-col items-center justify-center p-16 text-center">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 shadow-sm border border-indigo-100">
                <x-heroicon-o-calendar class="w-8 h-8"/>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No events found</h3>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto text-sm">Get started by creating your first event. You can manage dates, forms, and attendees all in one place.</p>
            <x-ui.button href="{{ route('events.create') }}" variant="primary">
                <x-heroicon-o-plus class="w-4 h-4 mr-2"/>
                Create First Event
            </x-ui.button>
        </x-ui.card>
    @endif
</x-app-layout>

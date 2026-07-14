<x-app-layout>
    <x-slot name="header">
        Events
    </x-slot>
    <x-slot name="actions">
        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <x-heroicon-o-plus class="w-5 h-5"/>
            New Event
        </a>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($events->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-500 uppercase tracking-wider">
                            <th class="p-4 font-semibold">Title</th>
                            <th class="p-4 font-semibold">Date</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($events as $event)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <div class="font-medium text-gray-900">{{ $event->title }}</div>
                                <div class="text-sm text-gray-500">{{ $event->slug }}</div>
                            </td>
                            <td class="p-4 text-gray-700">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                            </td>
                            <td class="p-4">
                                @if($event->status === 'published')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right space-x-3">
                                <a href="{{ route('events.show', $event) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">View</a>
                                @can('update', $event)
                                <a href="{{ route('events.edit', $event) }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm">Edit</a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($events->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $events->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                    <x-heroicon-o-calendar class="w-8 h-8"/>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No events yet</h3>
                <p class="text-gray-500 mb-6 max-w-sm mx-auto">Get started by creating your first event. You can manage dates, forms, and attendees all in one place.</p>
                <a href="{{ route('events.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    <x-heroicon-o-plus class="w-5 h-5"/>
                    Create First Event
                </a>
            </div>
        @endif
    </div>
</x-app-layout>

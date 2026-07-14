<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
            {{ __('Staff Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <x-ui.widgets.stat-card title="Assigned Events" :value="$stats['assigned_events']" color="indigo">
            <x-slot name="icon"><x-heroicon-o-calendar class="w-6 h-6"/></x-slot>
        </x-ui.widgets.stat-card>
        
        <x-ui.widgets.stat-card title="Applications to Review" :value="$stats['applications_to_review']" color="amber">
            <x-slot name="icon"><x-heroicon-o-clipboard-document-list class="w-6 h-6"/></x-slot>
        </x-ui.widgets.stat-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Assigned Events -->
        <x-ui.card class="lg:col-span-2">
            <x-slot name="header">
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Assigned Events</h3>
            </x-slot>
            
            @if($assignedEvents->count() > 0)
                <div class="space-y-4">
                    @foreach($assignedEvents as $event)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 bg-white rounded-xl border border-gray-200 shadow-sm hover:border-indigo-300 transition-colors gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="text-base font-bold text-gray-900">{{ $event->title }}</h4>
                                    <x-ui.feedback.badge :color="$event->status === 'published' ? 'emerald' : 'gray'">
                                        {{ ucfirst($event->status) }}
                                    </x-ui.feedback.badge>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-500 font-medium">
                                    <span class="flex items-center gap-1"><x-heroicon-o-calendar class="w-4 h-4"/> {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</span>
                                    <span class="flex items-center gap-1"><x-heroicon-o-map-pin class="w-4 h-4"/> {{ $event->location ?? 'TBD' }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2 w-full sm:w-auto">
                                <x-ui.button href="{{ route('events.show', $event) }}" variant="secondary" class="flex-1 sm:flex-none justify-center">
                                    View
                                </x-ui.button>
                                <x-ui.button href="{{ route('events.attendance.scan', $event) }}" variant="primary" class="flex-1 sm:flex-none justify-center">
                                    <x-heroicon-o-qr-code class="w-4 h-4 mr-2"/> Scan
                                </x-ui.button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-ui.feedback.empty-state
                    title="No events assigned"
                    description="You currently have no events assigned to you."
                    icon="calendar"
                />
            @endif
        </x-ui.card>

        <!-- Quick Links -->
        <x-ui.card>
            <x-slot name="header">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Quick Actions</h3>
            </x-slot>
            
            <div class="space-y-3">
                <a href="{{ route('applications.index') }}" class="flex items-center p-4 rounded-xl border border-gray-100 bg-gray-50 hover:bg-white hover:border-indigo-200 hover:shadow-sm transition-all group">
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 mr-4 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <x-heroicon-o-check-badge class="w-5 h-5"/>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Review Applications</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Process pending approvals</p>
                    </div>
                </a>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>

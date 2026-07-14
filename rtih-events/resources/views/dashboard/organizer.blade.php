<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
                {{ __('Organizer Dashboard') }}
            </h2>
            <div class="flex gap-2">
                <x-ui.button href="{{ route('events.create') }}" variant="primary" class="shadow-sm">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2"/> New Event
                </x-ui.button>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-ui.widgets.stat-card title="My Events" :value="$stats['total_events']" color="indigo">
            <x-slot name="icon"><x-heroicon-o-calendar class="w-6 h-6"/></x-slot>
        </x-ui.widgets.stat-card>
        <x-ui.widgets.stat-card title="Total Applications" :value="$stats['total_applications']" color="blue">
            <x-slot name="icon"><x-heroicon-o-document-text class="w-6 h-6"/></x-slot>
        </x-ui.widgets.stat-card>
        <x-ui.widgets.stat-card title="Pending Review" :value="$stats['pending_applications']" color="amber">
            <x-slot name="icon"><x-heroicon-o-clock class="w-6 h-6"/></x-slot>
        </x-ui.widgets.stat-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Events -->
        <x-ui.card class="lg:col-span-2">
            <x-slot name="header">
                <div class="flex justify-between items-center w-full">
                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">Upcoming Events</h3>
                    <a href="{{ route('events.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">View All &rarr;</a>
                </div>
            </x-slot>
            
            @if($upcomingEvents->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingEvents as $event)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-indigo-200 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-white border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center">
                                    <span class="text-[10px] font-bold text-red-500 uppercase">{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</span>
                                    <span class="text-lg font-black text-gray-900 leading-none">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-gray-900"><a href="{{ route('events.show', $event) }}" class="hover:text-indigo-600">{{ $event->title }}</a></h4>
                                    <p class="text-sm text-gray-500 flex items-center gap-1 mt-0.5">
                                        <x-heroicon-o-map-pin class="w-3.5 h-3.5"/> {{ $event->location ?? 'TBD' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-ui.feedback.badge :color="$event->status === 'published' ? 'emerald' : 'gray'">
                                    {{ ucfirst($event->status) }}
                                </x-ui.feedback.badge>
                                <a href="{{ route('events.edit', $event) }}" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors rounded-lg hover:bg-indigo-50">
                                    <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-ui.feedback.empty-state
                    title="No upcoming events"
                    description="You don't have any upcoming events scheduled."
                    icon="calendar"
                    action-label="Create Event"
                    :action-href="route('events.create')"
                />
            @endif
        </x-ui.card>

        <div class="space-y-6">
            <!-- Action Panel -->
            <x-ui.card>
                <x-slot name="header">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Event Management</h3>
                </x-slot>
                
                <div class="space-y-3">
                    <a href="{{ route('applications.index') }}" class="flex items-start p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 mr-3 group-hover:bg-indigo-100 transition-colors">
                            <x-heroicon-o-clipboard-document-check class="w-5 h-5"/>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">Review Applications</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Approve or reject attendee requests</p>
                        </div>
                    </a>
                    
                    <a href="{{ route('events.index') }}" class="flex items-start p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 mr-3 group-hover:bg-purple-100 transition-colors">
                            <x-heroicon-o-calendar-days class="w-5 h-5"/>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">Manage Events</h4>
                            <p class="text-xs text-gray-500 mt-0.5">Edit details, dates, and locations</p>
                        </div>
                    </a>
                </div>
            </x-ui.card>

            <!-- Small Chart -->
            <x-ui.card>
                <x-slot name="header">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Application Trend</h3>
                </x-slot>
                <div style="height: 180px;" class="-mx-2 mt-2">
                    <canvas id="applicationsChart"></canvas>
                </div>
            </x-ui.card>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            const ctx = document.getElementById('applicationsChart').getContext('2d');
            const data = @json($chartData);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Applications',
                        data: data.data,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#4f46e5',
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { display: false, min: 0 },
                        x: { grid: { display: false, drawBorder: false }, ticks: { color: '#9ca3af', font: { size: 10 } } }
                    },
                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#111827', padding: 8, cornerRadius: 6 } }
                }
            });
        });
    </script>
</x-app-layout>

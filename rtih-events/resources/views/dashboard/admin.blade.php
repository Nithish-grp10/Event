<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <div class="flex gap-2">
                <x-ui.button href="{{ route('events.create') }}" variant="primary" class="shadow-sm">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2"/> New Event
                </x-ui.button>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <x-ui.widgets.stat-card title="Total Events" :value="$stats['total_events']" color="indigo">
            <x-slot name="icon"><x-heroicon-o-calendar class="w-6 h-6"/></x-slot>
        </x-ui.widgets.stat-card>
        <x-ui.widgets.stat-card title="Total Applications" :value="$stats['total_applications']" color="blue">
            <x-slot name="icon"><x-heroicon-o-inbox-arrow-down class="w-6 h-6"/></x-slot>
        </x-ui.widgets.stat-card>
        <x-ui.widgets.stat-card title="Approved" :value="$stats['approved_applications']" color="emerald">
            <x-slot name="icon"><x-heroicon-o-check-circle class="w-6 h-6"/></x-slot>
        </x-ui.widgets.stat-card>
        <x-ui.widgets.stat-card title="Rejected" :value="$stats['rejected_applications']" color="red">
            <x-slot name="icon"><x-heroicon-o-x-circle class="w-6 h-6"/></x-slot>
        </x-ui.widgets.stat-card>
        <x-ui.widgets.stat-card title="Total Forms" :value="$stats['total_forms']" color="purple">
            <x-slot name="icon"><x-heroicon-o-clipboard-document-list class="w-6 h-6"/></x-slot>
        </x-ui.widgets.stat-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <x-ui.card class="lg:col-span-2">
            <x-slot name="header">
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">System Applications Overview</h3>
            </x-slot>
            <div style="height: 350px;" class="-mx-2 mt-4">
                <canvas id="applicationsChart"></canvas>
            </div>
        </x-ui.card>

        <div class="space-y-6">
            <!-- Welcome Panel -->
            <x-ui.card class="bg-gradient-to-br from-indigo-900 to-indigo-700 text-white border-0 shadow-lg">
                <div class="p-2">
                    <h3 class="text-lg font-bold mb-2">Welcome, {{ auth()->user()->name }}</h3>
                    <p class="text-indigo-200 text-sm mb-6">You're logged in as a System Administrator. You have full access to manage all aspects of the platform.</p>
                    
                    <h4 class="text-xs font-semibold text-indigo-300 uppercase tracking-wider mb-3">Quick Actions</h4>
                    <div class="space-y-2">
                        <a href="{{ route('forms.create') }}" class="flex items-center gap-2 p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors text-sm font-medium">
                            <x-heroicon-o-document-plus class="w-4 h-4"/> Build New Form
                        </a>
                        <a href="{{ route('admins.index') }}" class="flex items-center gap-2 p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors text-sm font-medium">
                            <x-heroicon-o-users class="w-4 h-4"/> Manage Users
                        </a>
                    </div>
                </div>
            </x-ui.card>

            <!-- Recent Events -->
            <x-ui.card>
                <x-slot name="header">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Recent Events</h3>
                </x-slot>
                
                <div class="divide-y divide-gray-100">
                    @forelse($recentEvents as $event)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $event->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</p>
                            </div>
                            <x-ui.feedback.badge :color="$event->status === 'published' ? 'emerald' : 'gray'">
                                {{ ucfirst($event->status) }}
                            </x-ui.feedback.badge>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 py-4 text-center">No recent events found.</p>
                    @endforelse
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
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Applications Received',
                        data: data.data,
                        backgroundColor: '#4f46e5',
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f3f4f6', drawBorder: false },
                            ticks: { precision: 0, color: '#6b7280' }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#6b7280' }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111827', padding: 12, cornerRadius: 8,
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>

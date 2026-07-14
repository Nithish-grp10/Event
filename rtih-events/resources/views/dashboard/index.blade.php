<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <x-ui.widgets.stat-card 
            title="Total Events" 
            :value="$stats['total_events']" 
            color="indigo"
        >
            <x-slot name="icon">
                <x-heroicon-o-calendar class="w-6 h-6"/>
            </x-slot>
        </x-ui.widgets.stat-card>

        <x-ui.widgets.stat-card 
            title="Total Applications" 
            :value="$stats['total_applications']" 
            color="blue"
        >
            <x-slot name="icon">
                <x-heroicon-o-inbox-arrow-down class="w-6 h-6"/>
            </x-slot>
        </x-ui.widgets.stat-card>

        <x-ui.widgets.stat-card 
            title="Approved Applications" 
            :value="$stats['approved_applications']" 
            color="emerald"
        >
            <x-slot name="icon">
                <x-heroicon-o-check-circle class="w-6 h-6"/>
            </x-slot>
        </x-ui.widgets.stat-card>

        <x-ui.widgets.stat-card 
            title="Rejected Applications" 
            :value="$stats['rejected_applications']" 
            color="red"
        >
            <x-slot name="icon">
                <x-heroicon-o-x-circle class="w-6 h-6"/>
            </x-slot>
        </x-ui.widgets.stat-card>

        <x-ui.widgets.stat-card 
            title="Total Forms" 
            :value="$stats['total_forms']" 
            color="purple"
        >
            <x-slot name="icon">
                <x-heroicon-o-clipboard-document-list class="w-6 h-6"/>
            </x-slot>
        </x-ui.widgets.stat-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <x-ui.card class="lg:col-span-2">
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">Applications Overview</h3>
            </x-slot>
            <div style="height: 400px;" class="-mx-2">
                <canvas id="applicationsChart"></canvas>
            </div>
        </x-ui.card>

        <!-- Welcome Panel -->
        <x-ui.card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">Welcome, {{ auth()->user()->name }}</h3>
            </x-slot>
            
            <p class="text-gray-600 mb-6">You're logged in as <strong class="text-gray-900">{{ ucfirst(auth()->user()->roles->first()->name ?? 'User') }}</strong>.</p>
            
            <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Quick Actions</h4>
            <div class="space-y-3">
                @if(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
                <x-ui.button href="{{ route('events.create') }}" variant="secondary" class="w-full justify-start border-gray-200">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2 text-indigo-500"/>
                    Create New Event
                </x-ui.button>
                <x-ui.button href="{{ route('forms.create') }}" variant="secondary" class="w-full justify-start border-gray-200">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2 text-indigo-500"/>
                    Build New Form
                </x-ui.button>
                @endif
                <x-ui.button href="{{ route('applications.index') }}" variant="primary" class="w-full justify-start">
                    <x-heroicon-o-clipboard-document-check class="w-4 h-4 mr-2 opacity-75"/>
                    Review Applications
                </x-ui.button>
            </div>
        </x-ui.card>
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
                            grid: {
                                color: '#f3f4f6',
                                drawBorder: false,
                            },
                            ticks: { precision: 0, color: '#6b7280' }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: { color: '#6b7280' }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111827',
                            padding: 12,
                            titleFont: { family: 'Plus Jakarta Sans', size: 13 },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 13 },
                            cornerRadius: 8,
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                <x-heroicon-o-calendar class="w-8 h-8"/>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Events</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_events'] }}</p>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                <x-heroicon-o-inbox-arrow-down class="w-8 h-8"/>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Applications</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_applications'] }}</p>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                <x-heroicon-o-check-circle class="w-8 h-8"/>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Approved Applications</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['approved_applications'] }}</p>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-red-50 text-red-600 rounded-lg">
                <x-heroicon-o-x-circle class="w-8 h-8"/>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Rejected Applications</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['rejected_applications'] }}</p>
            </div>
        </div>

        <!-- Stat Card 5 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center gap-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                <x-heroicon-o-clipboard-document-list class="w-8 h-8"/>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Total Forms</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_forms'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Applications Overview</h3>
            </div>
            <div class="p-6" style="height: 400px;">
                <canvas id="applicationsChart"></canvas>
            </div>
        </div>

        <!-- Welcome Panel -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Welcome, {{ auth()->user()->name }}</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">You're logged in as <strong>{{ ucfirst(auth()->user()->roles->first()->name ?? 'User') }}</strong>.</p>
                
                <h4 class="font-medium text-gray-900 mt-6 mb-2">Quick Actions</h4>
                <div class="space-y-2">
                    @if(auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
                    <a href="{{ route('events.create') }}" class="block px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100 transition-colors">
                        + Create New Event
                    </a>
                    <a href="{{ route('forms.create') }}" class="block px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100 transition-colors">
                        + Build New Form
                    </a>
                    @endif
                    <a href="{{ route('applications.index') }}" class="block px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-medium hover:bg-indigo-100 transition-colors">
                        Review Applications
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('applicationsChart').getContext('2d');
            const data = @json($chartData);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Applications Received',
                        data: data.data,
                        backgroundColor: 'rgba(79, 70, 229, 0.8)', // indigo-600
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
</x-app-layout>

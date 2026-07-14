<x-app-layout>
    <x-slot name="header">
        Applications
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-neutral-900">Applications</h1>
                <p class="text-sm text-neutral-500 mt-1">Manage registrations, approvals, and attendance across all events.</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.buttons.button variant="secondary" href="{{ route('applications.export', request()->query()) }}">
                    <x-slot name="iconLeft">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16v2a2 2 0 002 2h14a2 2 0 002-2v-2m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    </x-slot>
                    Export CSV
                </x-ui.buttons.button>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-ui.layout.card padding="none" class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Total Applications</p>
                        <p class="text-3xl font-bold tracking-tight text-neutral-900">{{ number_format($kpis['total']) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center text-primary-600">
                        <x-heroicon-o-inbox-arrow-down class="w-5 h-5" />
                    </div>
                </div>
            </x-ui.layout.card>

            <x-ui.layout.card padding="none" class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Pending Review</p>
                        <p class="text-3xl font-bold tracking-tight text-neutral-900">{{ number_format($kpis['pending']) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-warning-50 flex items-center justify-center text-warning-600">
                        <x-heroicon-o-clock class="w-5 h-5" />
                    </div>
                </div>
            </x-ui.layout.card>

            <x-ui.layout.card padding="none" class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Approved</p>
                        <p class="text-3xl font-bold tracking-tight text-neutral-900">{{ number_format($kpis['approved']) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-success-50 flex items-center justify-center text-success-600">
                        <x-heroicon-o-check-circle class="w-5 h-5" />
                    </div>
                </div>
            </x-ui.layout.card>

            <x-ui.layout.card padding="none" class="p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500 mb-1">Rejected</p>
                        <p class="text-3xl font-bold tracking-tight text-neutral-900">{{ number_format($kpis['rejected']) }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-danger-50 flex items-center justify-center text-danger-600">
                        <x-heroicon-o-x-circle class="w-5 h-5" />
                    </div>
                </div>
            </x-ui.layout.card>
        </div>

        <!-- Main Data Section -->
        <div x-data="{ selected: [] }" class="bg-white rounded-2xl shadow-subtle border border-neutral-200 overflow-hidden">
            
            <!-- Unified Header & Toolbar -->
            <div class="border-b border-neutral-200 px-6 py-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-neutral-50/50">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-semibold text-neutral-900">Application List</h2>
                    <x-ui.feedback.badge variant="neutral" rounded="default">{{ $applications->total() }} records</x-ui.feedback.badge>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('applications.index') }}" class="flex w-full sm:w-auto items-center gap-2">
                        <div class="relative w-full sm:w-48">
                            <x-ui.forms.input type="text" name="event_id" placeholder="Event ID" value="{{ request('event_id') }}" class="!py-1.5" />
                        </div>
                        <div class="relative w-full sm:w-48">
                            <x-ui.forms.input type="text" name="category" placeholder="Category" value="{{ request('category') }}" class="!py-1.5" />
                        </div>
                        <x-ui.buttons.button type="submit" variant="secondary" size="sm">Filter</x-ui.buttons.button>
                        @if(request()->hasAny(['event_id', 'category']))
                            <x-ui.buttons.button href="{{ route('applications.index') }}" variant="ghost" size="sm">Clear</x-ui.buttons.button>
                        @endif
                    </form>

                    <!-- Bulk Actions -->
                    <div x-show="selected.length > 0" x-cloak x-transition class="flex items-center gap-2 pl-3 border-l border-neutral-200">
                        <form method="POST" action="{{ route('applications.bulk') }}" class="flex gap-2 m-0">
                            @csrf
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="application_ids[]" :value="id">
                            </template>
                            <x-ui.buttons.button type="submit" name="action" value="approve" variant="primary" size="sm">Approve</x-ui.buttons.button>
                            <x-ui.buttons.button type="submit" name="action" value="reject" variant="danger" size="sm">Reject</x-ui.buttons.button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table Container (removed outer rounding/shadow from the table component via css class overrides or we just use native html here for full control since it's inside our unified card) -->
            @if($applications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200">
                        <thead class="bg-neutral-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider w-12">
                                    <input type="checkbox" @change="selected = $event.target.checked ? {{ json_encode($applications->pluck('id')->toArray()) }} : []" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Applicant</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Event</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Applied On</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-neutral-200">
                            @foreach($applications as $application)
                                <x-ui.tables.row>
                                    <x-ui.tables.cell class="w-12">
                                        <input type="checkbox" x-model="selected" value="{{ $application->id }}" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                    </x-ui.tables.cell>
                                    
                                    <x-ui.tables.cell primary="true">
                                        <div class="flex items-center gap-3">
                                            <x-ui.feedback.avatar name="{{ $application->applicant_name }}" size="md" />
                                            <div>
                                                <div class="font-semibold text-neutral-900">{{ $application->applicant_name }}</div>
                                                <div class="text-sm font-normal text-neutral-500">{{ $application->applicant_email }}</div>
                                            </div>
                                        </div>
                                    </x-ui.tables.cell>

                                    <x-ui.tables.cell>
                                        <div class="flex items-center gap-2 font-medium text-neutral-700">
                                            <x-heroicon-s-calendar class="w-4 h-4 text-neutral-400" />
                                            {{ Str::limit($application->event->title ?? 'Unknown Event', 30) }}
                                        </div>
                                    </x-ui.tables.cell>

                                    <x-ui.tables.cell>
                                        @php
                                            $statusVariant = match($application->status) {
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                default => 'primary',
                                            };
                                        @endphp
                                        <x-ui.feedback.badge variant="{{ $statusVariant }}" dot="true">
                                            {{ ucfirst($application->status) }}
                                        </x-ui.feedback.badge>
                                    </x-ui.tables.cell>

                                    <x-ui.tables.cell>
                                        <span class="text-neutral-700">{{ $application->created_at->format('M j, Y') }}</span>
                                        <div class="text-xs text-neutral-400 mt-0.5">{{ $application->created_at->format('g:i A') }}</div>
                                    </x-ui.tables.cell>

                                    <x-ui.tables.cell actions="true">
                                        <x-ui.buttons.button variant="secondary" size="sm" x-on:click="$dispatch('open-modal', 'application-details-{{ $application->id }}')">
                                            View
                                        </x-ui.buttons.button>
                                    </x-ui.tables.cell>
                                </x-ui.tables.row>

                    <!-- Application Details Modal (using the modal component) -->
                    <x-ui.layout.modal name="application-details-{{ $application->id }}" title="Application Details" maxWidth="lg">
                        <div class="space-y-6">
                            <!-- Applicant Info -->
                            <div class="flex items-center gap-4">
                                <x-ui.feedback.avatar name="{{ $application->applicant_name }}" size="lg" />
                                <div>
                                    <h3 class="text-lg font-bold text-neutral-900">{{ $application->applicant_name }}</h3>
                                    <p class="text-sm text-neutral-500">{{ $application->applicant_email }}</p>
                                </div>
                            </div>
                            
                            <hr class="border-neutral-100">

                            <!-- Event Details -->
                            <div>
                                <h4 class="text-sm font-semibold text-neutral-900 mb-2">Event Applied For</h4>
                                <div class="bg-neutral-50 rounded-xl p-4 border border-neutral-100 flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-neutral-900">{{ $application->event->title ?? 'N/A' }}</div>
                                        <div class="text-sm text-neutral-500">Event ID: {{ $application->event_id }}</div>
                                    </div>
                                    <x-ui.feedback.badge variant="{{ $statusVariant }}" dot="true">
                                        {{ ucfirst($application->status) }}
                                    </x-ui.feedback.badge>
                                </div>
                            </div>

                            <!-- Custom Form Data -->
                            @if($application->data)
                            <div>
                                <h4 class="text-sm font-semibold text-neutral-900 mb-2">Submitted Responses</h4>
                                <div class="bg-neutral-50 rounded-xl p-4 border border-neutral-100 space-y-3">
                                    @foreach($application->data as $key => $value)
                                        <div>
                                            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">{{ str_replace('_', ' ', $key) }}</p>
                                            <p class="text-sm text-neutral-900">{{ is_array($value) ? implode(', ', $value) : $value }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <x-slot name="footer">
                            <div class="flex justify-between items-center w-full">
                                <x-ui.buttons.button variant="outline" x-on:click="$dispatch('close-modal', 'application-details-{{ $application->id }}')">
                                    Close
                                </x-ui.buttons.button>
                                
                                <div class="flex gap-2">
                                    @if($application->status !== 'rejected')
                                    <form method="POST" action="{{ route('applications.status', [$application, 'rejected']) }}">
                                        @csrf
                                        <x-ui.buttons.button type="submit" variant="danger">Reject</x-ui.buttons.button>
                                    </form>
                                    @endif
                                    @if($application->status !== 'approved')
                                    <form method="POST" action="{{ route('applications.status', [$application, 'approved']) }}">
                                        @csrf
                                        <x-ui.buttons.button type="submit" variant="primary">Approve</x-ui.buttons.button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </x-slot>
                    </x-ui.layout.modal>
                @endforeach

                        </tbody>
                    </table>
                </div>
                @if($applications->hasPages())
                    <div class="px-6 py-4 border-t border-neutral-200 bg-neutral-50/50">
                        {{ $applications->links() }}
                    </div>
                @endif
        @else
            <x-ui.feedback.empty-state 
                title="No applications found" 
                description="When users apply to events, their applications will appear here."
            >
                <x-slot name="icon">
                    <x-heroicon-o-inbox-arrow-down class="w-8 h-8" />
                </x-slot>
                @if(request()->hasAny(['event_id', 'category']))
                    <x-slot name="action">
                        <x-ui.buttons.button href="{{ route('applications.index') }}" variant="outline">Clear Filters</x-ui.buttons.button>
                    </x-slot>
                @endif
            </x-ui.feedback.empty-state>
        @endif
    </div>
</x-app-layout>

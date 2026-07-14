<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
            {{ __('Application Management') }}
        </h2>
    </x-slot>
    <x-slot name="actions">
        <x-ui.button href="{{ route('applications.export', request()->query()) }}" variant="secondary" class="shadow-sm">
            <x-heroicon-o-arrow-down-tray class="w-4 h-4 mr-2"/>
            Export CSV
        </x-ui.button>
    </x-slot>

    <div class="space-y-6">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-ui.widgets.stat-card title="Total Applications" :value="number_format($kpis['total'])" color="indigo">
                <x-slot name="icon"><x-heroicon-o-inbox-stack class="w-6 h-6"/></x-slot>
            </x-ui.widgets.stat-card>
            <x-ui.widgets.stat-card title="Pending Review" :value="number_format($kpis['pending'])" color="amber">
                <x-slot name="icon"><x-heroicon-o-clock class="w-6 h-6"/></x-slot>
            </x-ui.widgets.stat-card>
            <x-ui.widgets.stat-card title="Approved" :value="number_format($kpis['approved'])" color="emerald">
                <x-slot name="icon"><x-heroicon-o-check-badge class="w-6 h-6"/></x-slot>
            </x-ui.widgets.stat-card>
            <x-ui.widgets.stat-card title="Rejected" :value="number_format($kpis['rejected'])" color="red">
                <x-slot name="icon"><x-heroicon-o-x-circle class="w-6 h-6"/></x-slot>
            </x-ui.widgets.stat-card>
        </div>

        <!-- Main Data Section -->
        <x-ui.card>
            <div class="flex flex-col sm:flex-row justify-between items-center p-4 border-b border-gray-100 gap-4 bg-gray-50/50 rounded-t-2xl">
                <!-- Advanced Filters & Search -->
                <form method="GET" action="{{ route('applications.index') }}" class="flex flex-wrap w-full sm:w-auto items-center gap-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-magnifying-glass class="h-4 w-4 text-gray-400"/>
                        </div>
                        <input type="text" name="search" placeholder="Search applicant..." value="{{ request('search') }}" class="block w-full sm:w-64 pl-10 rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm bg-white">
                    </div>
                    
                    <select name="status" class="block w-36 rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm bg-white">
                        <option value="">All Statuses</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>

                    <input type="text" name="event_id" placeholder="Event ID" value="{{ request('event_id') }}" class="block w-28 rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm bg-white">
                    
                    <x-ui.button type="submit" variant="secondary" size="sm">Filter</x-ui.button>
                    @if(request()->hasAny(['event_id', 'category', 'search', 'status']))
                        <a href="{{ route('applications.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">Clear</a>
                    @endif
                </form>
            </div>

            @if($applications->count() > 0)
                <div x-data="{ selected: [], allSelected: false, toggleAll() { this.allSelected = !this.allSelected; this.selected = this.allSelected ? Array.from(document.querySelectorAll('.app-checkbox')).map(cb => cb.value) : []; } }">
                    <!-- Bulk Actions Toolbar (Appears when items are selected) -->
                    <div x-show="selected.length > 0" x-transition.opacity class="bg-indigo-50 border-b border-indigo-100 p-3 flex items-center justify-between" style="display: none;">
                        <span class="text-sm font-bold text-indigo-800"><span x-text="selected.length"></span> applications selected</span>
                        
                        <form method="POST" action="{{ route('applications.bulk') }}" class="flex items-center gap-2">
                            @csrf
                            <template x-for="id in selected" :key="id">
                                <input type="hidden" name="application_ids[]" :value="id">
                            </template>
                            
                            <x-ui.button type="submit" name="action" value="approve" size="sm" class="bg-emerald-600 hover:bg-emerald-700 text-white border-emerald-600 focus:ring-emerald-500 shadow-sm">
                                <x-heroicon-o-check class="w-4 h-4 mr-1.5"/> Approve
                            </x-ui.button>
                            <x-ui.button type="submit" name="action" value="reject" size="sm" class="bg-red-600 hover:bg-red-700 text-white border-red-600 focus:ring-red-500 shadow-sm">
                                <x-heroicon-o-x-mark class="w-4 h-4 mr-1.5"/> Reject
                            </x-ui.button>
                            <x-ui.button type="submit" name="action" value="email" size="sm" class="bg-blue-600 hover:bg-blue-700 text-white border-blue-600 focus:ring-blue-500 shadow-sm">
                                <x-heroicon-o-envelope class="w-4 h-4 mr-1.5"/> Email
                            </x-ui.button>
                            <x-ui.button type="submit" name="action" value="delete" size="sm" variant="secondary" class="border-red-200 text-red-600 hover:bg-red-50" onclick="return confirm('Delete selected applications?')">
                                <x-heroicon-o-trash class="w-4 h-4 mr-1.5"/> Delete
                            </x-ui.button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">
                                        <input type="checkbox" @click="toggleAll()" :checked="allSelected" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applicant</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Event</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Assigned To</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applied</th>
                                    <th scope="col" class="relative px-6 py-3 w-10"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($applications as $application)
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" value="{{ $application->id }}" x-model="selected" class="app-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                                                    {{ substr($application->applicant_name, 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900">{{ $application->applicant_name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $application->applicant_email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 flex items-center gap-1.5">
                                                <x-heroicon-o-calendar class="w-4 h-4 text-gray-400"/>
                                                {{ Str::limit($application->event->title ?? 'Unknown Event', 30) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($application->assignee)
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-gray-200 border border-gray-300 overflow-hidden">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($application->assignee->name) }}&background=f3f4f6&color=374151" alt="">
                                                    </div>
                                                    <span class="text-sm font-medium text-gray-700">{{ $application->assignee->name }}</span>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-400 font-medium italic">Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <x-ui.feedback.badge :color="$application->status === 'approved' ? 'emerald' : ($application->status === 'rejected' ? 'red' : 'amber')">
                                                {{ ucfirst($application->status) }}
                                            </x-ui.feedback.badge>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $application->created_at->format('M j, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $application->created_at->format('g:i A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('applications.show', $application) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold p-2 rounded-lg hover:bg-indigo-50 transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100">
                                                Review &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($applications->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center p-16 text-center">
                    <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center mb-4 border border-gray-100">
                        <x-heroicon-o-magnifying-glass class="w-8 h-8"/>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">No applications found</h3>
                    <p class="text-gray-500 max-w-sm mx-auto text-sm">No applications match your current filters or search query.</p>
                </div>
            @endif
        </x-ui.card>
    </div>
</x-app-layout>

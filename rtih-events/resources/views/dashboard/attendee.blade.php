<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto space-y-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Applications Submitted</p>
                    <p class="text-3xl font-black text-gray-900">{{ $stats['applications_submitted'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                    <x-heroicon-o-document-text class="w-6 h-6"/>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Approved Events</p>
                    <p class="text-3xl font-black text-gray-900">{{ $stats['approved_events'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <x-heroicon-o-check-badge class="w-6 h-6"/>
                </div>
            </div>
        </div>

        <x-ui.card>
            <x-slot name="header">
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">My Applications</h3>
            </x-slot>

            @if($myApplications->count() > 0)
                <div class="divide-y divide-gray-100 -mx-6 px-6">
                    @foreach($myApplications as $application)
                        <div class="py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:bg-gray-50 -mx-6 px-6 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-lg bg-white border border-gray-200 shadow-sm flex flex-col items-center justify-center text-center shrink-0">
                                    <span class="text-[10px] font-bold text-indigo-500 uppercase">{{ \Carbon\Carbon::parse($application->event->start_date)->format('M') }}</span>
                                    <span class="text-lg font-black text-gray-900 leading-none">{{ \Carbon\Carbon::parse($application->event->start_date)->format('d') }}</span>
                                </div>
                                <div>
                                    <h4 class="text-base font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $application->event->title }}</h4>
                                    <p class="text-sm text-gray-500 flex items-center gap-1 mt-0.5">
                                        <x-heroicon-o-clock class="w-3.5 h-3.5"/> Applied {{ $application->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                @if($application->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        <x-heroicon-o-check-circle class="w-3.5 h-3.5 mr-1"/> Approved
                                    </span>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                        <x-heroicon-o-x-circle class="w-3.5 h-3.5 mr-1"/> Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                        <x-heroicon-o-clock class="w-3.5 h-3.5 mr-1"/> Under Review
                                    </span>
                                @endif
                                
                                <a href="{{ route('public.apply.show', $application->event) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 whitespace-nowrap">View Event</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <x-ui.feedback.empty-state
                    title="No applications yet"
                    description="You haven't applied to any events yet. Check out the public event listings to get started."
                    icon="document-text"
                />
            @endif
        </x-ui.card>
    </div>
</x-app-layout>

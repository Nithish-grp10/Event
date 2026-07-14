<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
                {{ __('Application Details') }}
            </h2>
            <div class="flex items-center gap-3">
                <x-ui.button href="{{ route('applications.index') }}" variant="secondary">
                    <x-heroicon-o-arrow-left class="w-4 h-4 mr-2"/> Back
                </x-ui.button>
                
                @if($application->status !== 'approved')
                <form method="POST" action="{{ route('applications.status', ['application' => $application, 'status' => 'approved']) }}">
                    @csrf
                    <x-ui.button type="submit" variant="primary" class="bg-emerald-600 hover:bg-emerald-700 focus-visible:ring-emerald-500 border-emerald-600 shadow-sm">
                        <x-heroicon-o-check class="w-4 h-4 mr-2"/> Approve
                    </x-ui.button>
                </form>
                @endif

                @if($application->status !== 'rejected')
                <form method="POST" action="{{ route('applications.status', ['application' => $application, 'status' => 'rejected']) }}">
                    @csrf
                    <x-ui.button type="submit" variant="danger">
                        <x-heroicon-o-x-mark class="w-4 h-4 mr-2"/> Reject
                    </x-ui.button>
                </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details (Left Column) -->
        <div class="lg:col-span-2 space-y-6">
            <x-ui.card>
                <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white text-2xl font-bold shadow-sm">
                            {{ substr($application->applicant_name, 0, 1) }}
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $application->applicant_name }}</h2>
                            <div class="flex items-center gap-4 mt-1">
                                <a href="mailto:{{ $application->applicant_email }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center gap-1"><x-heroicon-o-envelope class="w-4 h-4"/> {{ $application->applicant_email }}</a>
                                <span class="text-sm text-gray-500 flex items-center gap-1"><x-heroicon-o-clock class="w-4 h-4"/> Applied {{ $application->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <x-ui.feedback.badge :color="$application->status === 'approved' ? 'emerald' : ($application->status === 'rejected' ? 'red' : 'amber')" class="px-3 py-1 text-sm">
                            <span class="w-1.5 h-1.5 rounded-full mr-2 {{ $application->status === 'approved' ? 'bg-emerald-500' : ($application->status === 'rejected' ? 'bg-red-500' : 'bg-amber-400') }}"></span>
                            {{ ucfirst($application->status) }}
                        </x-ui.feedback.badge>
                    </div>
                </div>

                <div class="pt-2">
                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Application Responses</h4>
                    
                    @if($application->form && $application->data)
                        <dl class="space-y-6">
                            @foreach($application->form->fields as $field)
                                <div>
                                    <dt class="text-sm font-semibold text-gray-700 mb-2">{{ $field->label }}</dt>
                                    @if(is_array($application->data[$field->id] ?? null))
                                        <dd class="text-sm font-medium text-gray-900 bg-gray-50 p-4 rounded-xl border border-gray-200">
                                            <ul class="list-disc pl-5 space-y-1">
                                                @foreach($application->data[$field->id] as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </dd>
                                    @else
                                        <dd class="text-sm font-medium text-gray-900 whitespace-pre-wrap bg-gray-50 p-4 rounded-xl border border-gray-200">{{ $application->data[$field->id] ?? 'N/A' }}</dd>
                                    @endif
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <div class="p-6 bg-gray-50 rounded-xl text-center border border-gray-200 border-dashed">
                            <x-heroicon-o-document-minus class="w-8 h-8 text-gray-400 mx-auto mb-2"/>
                            <p class="text-sm font-medium text-gray-500">No dynamic form data available.</p>
                        </div>
                    @endif
                </div>
            </x-ui.card>
            
            <!-- Internal Notes -->
            <x-ui.card>
                <x-slot name="header">
                    <div class="flex items-center gap-2 text-gray-900">
                        <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5 text-indigo-500"/>
                        <h3 class="text-lg font-bold tracking-tight">Internal Notes</h3>
                    </div>
                </x-slot>
                
                <form action="{{ route('applications.notes', $application) }}" method="POST">
                    @csrf
                    <textarea name="internal_notes" rows="4" class="w-full rounded-xl border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-gray-50 hover:bg-white px-4 py-3" placeholder="Add private notes about this application...">{{ old('internal_notes', $application->internal_notes) }}</textarea>
                    <div class="mt-3 flex justify-end">
                        <x-ui.button type="submit" variant="secondary" size="sm">Save Notes</x-ui.button>
                    </div>
                </form>
            </x-ui.card>
        </div>

        <!-- Sidebar Details (Right Column) -->
        <div class="space-y-6">
            <!-- Event & Assignment Summary -->
            <x-ui.card>
                <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Event Information</h3>
                <div class="p-4 bg-indigo-50/50 rounded-xl border border-indigo-100 mb-6">
                    <div class="font-bold text-gray-900 flex items-center gap-2 mb-1">
                        <x-heroicon-o-calendar class="w-4 h-4 text-indigo-500"/>
                        <a href="{{ route('events.show', $application->event) }}" class="hover:text-indigo-600 transition-colors">{{ $application->event->title ?? 'Unknown Event' }}</a>
                    </div>
                    <div class="text-xs font-medium text-gray-500 flex items-center gap-1">
                        <x-heroicon-o-hashtag class="w-3.5 h-3.5"/> Event ID: {{ $application->event_id }}
                    </div>
                </div>

                <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Assignment</h3>
                <form action="{{ route('applications.assign', $application) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex gap-2">
                        <select name="user_id" class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm bg-gray-50">
                            <option value="">Unassigned</option>
                            @foreach($assignableUsers as $user)
                                <option value="{{ $user->id }}" {{ $application->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <x-ui.button type="submit" variant="secondary" size="sm">Assign</x-ui.button>
                    </div>
                </form>

                <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Metadata</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="font-medium text-gray-500">Submitted At</dt>
                        <dd class="font-semibold text-gray-900">{{ $application->created_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    @if($application->created_at->notEqualTo($application->updated_at))
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="font-medium text-gray-500">Last Updated</dt>
                        <dd class="font-semibold text-gray-900">{{ $application->updated_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    @endif
                    <div class="pt-2">
                        <dt class="font-medium text-gray-500 mb-2">Barcode Token</dt>
                        <dd class="font-mono text-xs bg-gray-50 p-2.5 rounded-lg border border-gray-200 break-all text-gray-700 text-center">{{ $application->barcode_token }}</dd>
                    </div>
                </dl>
            </x-ui.card>
            
            <!-- Timeline / Activity Log -->
            <x-ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">Activity Log</h3>
                </x-slot>

                <div class="flow-root mt-2">
                    <ul role="list" class="-mb-8">
                        @foreach($application->activities as $index => $activity)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            @if($activity->type === 'status_changed')
                                                <span class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center ring-8 ring-white border border-blue-200">
                                                    <x-heroicon-o-arrow-path class="h-4 w-4 text-blue-500" />
                                                </span>
                                            @elseif($activity->type === 'note_updated')
                                                <span class="h-8 w-8 rounded-full bg-gray-50 flex items-center justify-center ring-8 ring-white border border-gray-200">
                                                    <x-heroicon-o-pencil-square class="h-4 w-4 text-gray-500" />
                                                </span>
                                            @elseif($activity->type === 'assigned')
                                                <span class="h-8 w-8 rounded-full bg-purple-50 flex items-center justify-center ring-8 ring-white border border-purple-200">
                                                    <x-heroicon-o-user-plus class="h-4 w-4 text-purple-500" />
                                                </span>
                                            @elseif($activity->type === 'email_sent')
                                                <span class="h-8 w-8 rounded-full bg-emerald-50 flex items-center justify-center ring-8 ring-white border border-emerald-200">
                                                    <x-heroicon-o-paper-airplane class="h-4 w-4 text-emerald-500" />
                                                </span>
                                            @else
                                                <span class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center ring-8 ring-white border border-gray-200">
                                                    <x-heroicon-o-bars-3 class="h-4 w-4 text-gray-500" />
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div>
                                                <p class="text-sm text-gray-600 font-medium">{{ $activity->description }}</p>
                                                <p class="text-xs text-gray-400 mt-1">by <span class="font-semibold text-gray-700">{{ $activity->user->name ?? 'System' }}</span></p>
                                            </div>
                                            <div class="whitespace-nowrap text-right text-xs text-gray-500 font-medium">
                                                <time datetime="{{ $activity->created_at }}">{{ $activity->created_at->diffForHumans() }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        
                        <!-- Initial Creation Activity -->
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center ring-8 ring-white border border-indigo-200">
                                            <x-heroicon-o-document-plus class="h-4 w-4 text-indigo-500" />
                                        </span>
                                    </div>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm text-gray-600 font-medium">Application Submitted</p>
                                            <p class="text-xs text-gray-400 mt-1">by <span class="font-semibold text-gray-700">{{ $application->applicant_name }}</span></p>
                                        </div>
                                        <div class="whitespace-nowrap text-right text-xs text-gray-500 font-medium">
                                            <time datetime="{{ $application->created_at }}">{{ $application->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </x-ui.card>
            
            <form method="POST" action="{{ route('applications.destroy', $application) }}" onsubmit="return confirm('Are you sure you want to permanently delete this application?');">
                @csrf
                @method('DELETE')
                <x-ui.button type="submit" variant="danger" class="w-full">
                    <x-heroicon-o-trash class="w-4 h-4 mr-2"/>
                    Delete Application
                </x-ui.button>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        Application Details: {{ $application->applicant_name }}
    </x-slot>
    <x-slot name="actions">
        <div class="flex gap-3">
            <a href="{{ route('applications.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 shadow-sm">
                Back
            </a>
            
            @if($application->status !== 'approved')
            <form method="POST" action="{{ route('applications.status', ['application' => $application, 'status' => 'approved']) }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 shadow-sm">
                    Approve
                </button>
            </form>
            @endif

            @if($application->status !== 'rejected')
            <form method="POST" action="{{ route('applications.status', ['application' => $application, 'status' => 'rejected']) }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 shadow-sm">
                    Reject
                </button>
            </form>
            @endif
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Application Answers</h2>
                        <p class="text-gray-500 mt-1">Submitted for: {{ $application->event->title ?? 'Unknown Event' }}</p>
                    </div>
                    <div>
                        @if($application->status === 'approved')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Approved</span>
                        @elseif($application->status === 'rejected')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Rejected</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Submitted</span>
                        @endif
                    </div>
                </div>
                
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Applicant Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $application->applicant_name }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $application->applicant_email }}</dd>
                        </div>
                        <div class="sm:col-span-2 border-t pt-4">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Form Responses</h4>
                        </div>
                        
                        @if($application->form && $application->data)
                            @foreach($application->form->fields as $field)
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">{{ $field->label }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $application->data[$field->id] ?? 'N/A' }}</dd>
                                </div>
                            @endforeach
                        @else
                            <div class="sm:col-span-2">
                                <p class="text-sm text-gray-500 italic">No dynamic form data available.</p>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sidebar Details -->
        <div class="space-y-6">
            <!-- Event Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tracking Info</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500">Submitted At</dt>
                        <dd class="font-medium text-gray-900">{{ $application->created_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    @if($application->created_at->notEqualTo($application->updated_at))
                    <div>
                        <dt class="text-gray-500">Last Updated</dt>
                        <dd class="font-medium text-gray-900">{{ $application->updated_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    @endif
                    <div class="pt-3 border-t">
                        <dt class="text-gray-500">Barcode Token</dt>
                        <dd class="font-mono text-xs mt-1 bg-gray-50 p-2 rounded break-all">{{ $application->barcode_token }}</dd>
                    </div>
                </dl>
            </div>
            
            <form method="POST" action="{{ route('applications.destroy', $application) }}" onsubmit="return confirm('Are you sure you want to permanently delete this application?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full px-4 py-2 bg-white border border-red-300 text-red-600 font-medium rounded-lg hover:bg-red-50 transition-colors shadow-sm text-sm">
                    Delete Application
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

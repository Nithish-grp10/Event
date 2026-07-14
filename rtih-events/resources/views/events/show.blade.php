<x-app-layout>
    <x-slot name="header">
        Event Details
    </x-slot>
    <x-slot name="actions">
        <div class="flex gap-3">
            <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                Back
            </a>
            @can('update', $event)
            <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                <x-heroicon-o-pencil class="w-4 h-4"/>
                Edit Event
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h2>
                        <p class="text-gray-500 mt-1">/events/{{ $event->slug }}/apply</p>
                    </div>
                    <div>
                        @if($event->status === 'published')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                Draft
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-6 prose max-w-none text-gray-700">
                    {!! nl2br(e($event->description)) !!}
                </div>
                <div class="bg-gray-50 p-6 border-t border-gray-100 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Dates</p>
                        <p class="mt-1 font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                            @if($event->end_date)
                                - {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Location</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $event->location ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Category</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ ucfirst($event->category ?? '') ?: 'None' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Capacity</p>
                        <p class="mt-1 font-semibold text-gray-900">{{ $event->capacity ?: 'Unlimited' }}</p>
                    </div>
                </div>
            </div>

            <!-- Forms attached -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Attached Forms</h3>
                </div>
                <div class="p-6">
                    @if($event->forms->count() > 0)
                        <ul class="divide-y divide-gray-100">
                            @foreach($event->forms as $form)
                                <li class="py-3 flex justify-between items-center">
                                    <span class="font-medium text-gray-900">{{ $form->title }}</span>
                                    <a href="{{ route('forms.show', $form) }}" class="text-indigo-600 hover:underline text-sm font-medium">View Form</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 italic text-sm">No forms attached yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Applications Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Applications</h3>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-3xl font-bold text-indigo-600">{{ App\Models\Application::where('event_id', $event->id)->count() }}</span>
                    <span class="text-gray-500">Total received</span>
                </div>
                <a href="{{ route('applications.index', ['event_id' => $event->id]) }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 font-medium rounded-lg hover:bg-indigo-100 transition-colors">
                    View All Applications
                </a>
            </div>

            <!-- Publish/Unpublish -->
            @can('publish', $event)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Visibility</h3>
                @if($event->status === 'draft')
                    <form method="POST" action="{{ route('events.publish', $event) }}">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                            <x-heroicon-o-eye class="w-5 h-5"/>
                            Publish Event
                        </button>
                    </form>
                    <p class="text-xs text-gray-500 mt-3">Publishing will make the public application page accessible.</p>
                @else
                    <form method="POST" action="{{ route('events.unpublish', $event) }}">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-yellow-600 text-white font-medium rounded-lg hover:bg-yellow-700 transition-colors shadow-sm">
                            <x-heroicon-o-eye-slash class="w-5 h-5"/>
                            Unpublish to Draft
                        </button>
                    </form>
                @endif
            </div>
            @endcan

            <!-- Attendance Scanner -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Attendance</h3>
                <a href="{{ route('events.attendance.scan', $event) }}" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2 bg-gray-900 text-white font-medium rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                    <x-heroicon-o-qr-code class="w-5 h-5"/>
                    Open Scanner
                </a>
            </div>
        </div>
    </div>
</x-app-layout>

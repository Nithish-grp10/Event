<x-app-layout>
    <x-slot name="header">
        Event Details
    </x-slot>
    <x-slot name="actions">
        <div class="flex items-center gap-3">
            <x-ui.button href="{{ route('events.index') }}" variant="secondary">
                <x-heroicon-o-arrow-left class="w-4 h-4 mr-2"/>
                Back
            </x-ui.button>
            @can('update', $event)
            <x-ui.button href="{{ route('events.edit', $event) }}" variant="primary">
                <x-heroicon-o-pencil class="w-4 h-4 mr-2"/>
                Edit Event
            </x-ui.button>
            @endcan
        </div>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <x-ui.card>
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $event->title }}</h2>
                        <a href="/events/{{ $event->slug }}/apply" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center mt-1">
                            /events/{{ $event->slug }}/apply
                            <x-heroicon-o-arrow-top-right-on-square class="w-3.5 h-3.5 ml-1"/>
                        </a>
                    </div>
                    <div>
                        @if($event->status === 'published')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2"></span>
                                Published
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                <span class="w-1.5 h-1.5 bg-gray-400 rounded-full mr-2"></span>
                                Draft
                            </span>
                        @endif
                    </div>
                </div>

                <div class="prose max-w-none text-gray-700 text-sm leading-relaxed mb-8">
                    {!! nl2br(e($event->description)) !!}
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-6 border-t border-gray-100">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Dates</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                            @if($event->end_date)
                                <br><span class="text-gray-500">to</span> {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Location</p>
                        <p class="text-sm font-medium text-gray-900">{{ $event->location ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Category</p>
                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($event->category ?? '') ?: 'None' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Capacity</p>
                        <p class="text-sm font-medium text-gray-900">{{ $event->capacity ?: 'Unlimited' }}</p>
                    </div>
                </div>
            </x-ui.card>

            <!-- Forms attached -->
            <x-ui.card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-gray-900">Attached Application Forms</h3>
                </x-slot>
                
                @if($event->forms->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($event->forms as $form)
                            <div class="py-4 flex justify-between items-center first:pt-0 last:pb-0">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 mr-4">
                                        <x-heroicon-o-document-text class="w-5 h-5"/>
                                    </div>
                                    <div>
                                        <span class="block font-medium text-gray-900">{{ $form->title }}</span>
                                        <span class="block text-xs text-gray-500">ID: {{ $form->id }}</span>
                                    </div>
                                </div>
                                <x-ui.button href="{{ route('forms.show', $form) }}" variant="ghost" size="sm">
                                    View Form
                                </x-ui.button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <x-heroicon-o-document-minus class="mx-auto h-12 w-12 text-gray-300 mb-3"/>
                        <p class="text-sm text-gray-500">No application forms attached to this event yet.</p>
                    </div>
                @endif
            </x-ui.card>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Applications Summary -->
            <x-ui.card>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wider">Applications</h3>
                <div class="flex items-end justify-between mb-6">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-extrabold text-indigo-600">{{ App\Models\Application::where('event_id', $event->id)->count() }}</span>
                        <span class="text-sm font-medium text-gray-500">Received</span>
                    </div>
                </div>
                <x-ui.button href="{{ route('applications.index', ['event_id' => $event->id]) }}" variant="secondary" class="w-full">
                    <x-heroicon-o-inbox-arrow-down class="w-4 h-4 mr-2 text-indigo-500"/>
                    View All Applications
                </x-ui.button>
            </x-ui.card>

            <!-- Publish/Unpublish -->
            @can('publish', $event)
            <x-ui.card>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wider">Visibility</h3>
                @if($event->status === 'draft')
                    <form method="POST" action="{{ route('events.publish', $event) }}">
                        @csrf
                        <x-ui.button type="submit" variant="primary" class="w-full bg-emerald-600 hover:bg-emerald-700 focus-visible:ring-emerald-500 border-emerald-600 shadow-[0_1px_2px_rgba(0,0,0,0.1),inset_0_1px_0_rgba(255,255,255,0.2)]">
                            <x-heroicon-o-eye class="w-4 h-4 mr-2"/>
                            Publish Event
                        </x-ui.button>
                    </form>
                    <p class="text-xs text-gray-500 mt-3 text-center leading-relaxed">Publishing will make the public application page accessible to users.</p>
                @else
                    <form method="POST" action="{{ route('events.unpublish', $event) }}">
                        @csrf
                        <x-ui.button type="submit" variant="secondary" class="w-full border-amber-200 text-amber-700 hover:bg-amber-50 focus-visible:ring-amber-500">
                            <x-heroicon-o-eye-slash class="w-4 h-4 mr-2 text-amber-500"/>
                            Unpublish to Draft
                        </x-ui.button>
                    </form>
                @endif
            </x-ui.card>
            @endcan

            <!-- Attendance Scanner -->
            <x-ui.card>
                <h3 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wider">Attendance</h3>
                <x-ui.button href="{{ route('events.attendance.scan', $event) }}" variant="primary" class="w-full bg-gray-900 hover:bg-black focus-visible:ring-gray-900 border-gray-900">
                    <x-heroicon-o-qr-code class="w-4 h-4 mr-2 text-gray-400"/>
                    Open QR Scanner
                </x-ui.button>
                <p class="text-xs text-gray-500 mt-3 text-center leading-relaxed">Use the scanner to check-in approved attendees at the door.</p>
            </x-ui.card>
        </div>
    </div>
</x-app-layout>

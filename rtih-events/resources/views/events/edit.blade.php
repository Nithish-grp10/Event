<x-app-layout>
    <x-slot name="header">
        Edit Event: {{ $event->title }}
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        <x-ui.card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">Event Details</h3>
            </x-slot>

            <x-ui.form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" class="space-y-8">
                @method('PUT')

                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <x-ui.input id="title" name="title" :value="old('title', $event->title)" required autofocus label="Event Title" />
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                        <textarea id="description" name="description" rows="4" class="block w-full rounded-lg border border-gray-200/80 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-ui.input id="category" name="category" :value="old('category', $event->category)" label="Category" />
                    </div>

                    <div>
                        <x-ui.input id="location" name="location" :value="old('location', $event->location)" label="Venue / Location" />
                    </div>
                </div>

                <!-- Timings -->
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wider">Timings & Registration</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-ui.input type="date" id="start_date" name="start_date" :value="old('start_date', $event->start_date)" required label="Start Date" />
                        </div>
                        <div>
                            <x-ui.input type="date" id="end_date" name="end_date" :value="old('end_date', $event->end_date)" label="End Date" />
                        </div>
                        <div>
                            <x-ui.input type="time" id="start_time" name="start_time" :value="old('start_time', $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('H:i') : '')" label="Start Time" />
                        </div>
                        <div>
                            <x-ui.input type="time" id="end_time" name="end_time" :value="old('end_time', $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('H:i') : '')" label="End Time" />
                        </div>
                        
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-ui.input type="date" id="registration_start" name="registration_start" :value="old('registration_start', $event->registration_start)" label="Registration Start" />
                            </div>
                            <div>
                                <x-ui.input type="date" id="registration_end" name="registration_end" :value="old('registration_end', $event->registration_end)" label="Registration End" />
                            </div>
                            <div>
                                <x-ui.input type="number" id="max_seats" name="max_seats" min="1" :value="old('max_seats', $event->max_seats)" label="Max Seats" hint="Leave blank for unlimited" />
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->hasRole('super-admin'))
                <!-- Event Assignment -->
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wider">Administration</h4>
                    <div class="md:col-span-2 max-w-md">
                        <label for="assigned_admin_id" class="block text-sm font-semibold text-gray-700 mb-1.5">Assign to Admin/Staff</label>
                        <select id="assigned_admin_id" name="assigned_admin_id" class="block w-full rounded-lg border border-gray-200/80 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300">
                            <option value="">Unassigned (Super Admin Only)</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ old('assigned_admin_id', $event->assigned_admin_id) == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }} ({{ $admin->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_admin_id')
                            <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endif

                <!-- Organizer & Media -->
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wider">Organizer & Media</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-ui.input id="organizer_name" name="organizer_name" :value="old('organizer_name', $event->organizer_name)" label="Organizer Name" />
                        </div>
                        <div>
                            <x-ui.input id="contact_number" name="contact_number" :value="old('contact_number', $event->contact_number)" label="Contact Number" />
                        </div>
                        <div class="md:col-span-2">
                            <x-ui.input type="email" id="email" name="email" :value="old('email', $event->email)" label="Support Email" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Event Banner Image</label>
                            @if($event->banner_image_path)
                                <div class="mb-2 relative w-32 h-20 rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ Storage::url($event->banner_image_path) }}" alt="Banner" class="object-cover w-full h-full">
                                </div>
                            @endif
                            <input name="banner_image" type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors" />
                            @error('banner_image') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Agenda PDF</label>
                            @if($event->agenda_pdf_path)
                                <div class="mb-3">
                                    <a href="{{ Storage::url($event->agenda_pdf_path) }}" target="_blank" class="inline-flex items-center text-indigo-600 text-sm font-medium hover:text-indigo-800">
                                        <x-heroicon-o-document-text class="w-4 h-4 mr-1"/> View Current PDF
                                    </a>
                                </div>
                            @endif
                            <input name="agenda_pdf" type="file" accept="application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors" />
                            @error('agenda_pdf') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Attachment -->
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wider">Application Form</h4>
                    @if($forms->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-2xl">
                            @foreach($forms as $form)
                                <label class="flex items-start p-3 border border-gray-200 rounded-xl hover:bg-gray-50/50 cursor-pointer transition-colors bg-white shadow-sm">
                                    <div class="flex items-center h-5">
                                        <input type="checkbox" name="form_ids[]" value="{{ $form->id }}" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ $event->forms->contains($form->id) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <span class="font-medium text-gray-900">{{ $form->title }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('form_ids')
                            <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    @else
                        <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <x-heroicon-s-exclamation-triangle class="h-5 w-5 text-amber-400"/>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-amber-800">No forms available</h3>
                                    <div class="mt-2 text-sm text-amber-700">
                                        <p>You need to <a href="{{ route('forms.create') }}" class="font-bold underline hover:text-amber-900">create a form</a> before you can attach it to this event and publish.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                    <x-ui.button type="submit" variant="primary" x-bind:disabled="isSubmitting">
                        <span x-show="!isSubmitting">Update Event</span>
                        <span x-show="isSubmitting">Saving...</span>
                    </x-ui.button>
                    <x-ui.button href="{{ route('events.show', $event) }}" variant="secondary">
                        Cancel
                    </x-ui.button>
                </div>
            </x-ui.form>
        </x-ui.card>

        <!-- Actions -->
        <x-ui.card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">Event Actions</h3>
            </x-slot>
            
            <div class="flex flex-wrap items-center gap-4">
                <form method="POST" action="{{ route('events.duplicate', $event) }}">
                    @csrf
                    <x-ui.button type="submit" variant="secondary">
                        <x-heroicon-o-document-duplicate class="w-4 h-4 mr-2 text-gray-500"/> Duplicate
                    </x-ui.button>
                </form>

                <form method="POST" action="{{ route('events.status', ['event' => $event, 'status' => 'cancelled']) }}">
                    @csrf
                    <x-ui.button type="submit" class="bg-amber-100 hover:bg-amber-200 text-amber-800 focus-visible:ring-amber-500 border border-transparent">
                        <x-heroicon-o-x-circle class="w-4 h-4 mr-2"/> Cancel Event
                    </x-ui.button>
                </form>

                <form method="POST" action="{{ route('events.status', ['event' => $event, 'status' => 'completed']) }}">
                    @csrf
                    <x-ui.button type="submit" class="bg-emerald-100 hover:bg-emerald-200 text-emerald-800 focus-visible:ring-emerald-500 border border-transparent">
                        <x-heroicon-o-check-circle class="w-4 h-4 mr-2"/> Mark Completed
                    </x-ui.button>
                </form>
                
                <div class="flex-1"></div>

                <form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Are you sure you want to permanently delete this event?');">
                    @csrf
                    @method('DELETE')
                    <x-ui.button type="submit" variant="danger">
                        <x-heroicon-o-trash class="w-4 h-4 mr-2"/> Delete Event
                    </x-ui.button>
                </form>
            </div>
        </x-ui.card>
    </div>
</x-app-layout>

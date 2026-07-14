<x-app-layout>
    <x-slot name="header">
        Edit Event: {{ $event->title }}
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden max-w-4xl">
        <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="md:col-span-2">
                    <x-input-label for="title" value="Event Title" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $event->title)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $event->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div>
                    <x-input-label for="category" value="Category" />
                    <x-text-input id="category" name="category" type="text" class="mt-1 block w-full" :value="old('category', $event->category)" />
                    <x-input-error class="mt-2" :messages="$errors->get('category')" />
                </div>

                <div>
                    <x-input-label for="location" value="Venue / Location" />
                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location', $event->location)" />
                    <x-input-error class="mt-2" :messages="$errors->get('location')" />
                </div>

                <!-- Timings -->
                <div>
                    <x-input-label for="start_date" value="Start Date" />
                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', $event->start_date)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                </div>

                <div>
                    <x-input-label for="end_date" value="End Date" />
                    <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date', $event->end_date)" />
                    <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                </div>

                <div>
                    <x-input-label for="start_time" value="Start Time" />
                    <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" :value="old('start_time', $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('H:i') : '')" />
                    <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                </div>

                <div>
                    <x-input-label for="end_time" value="End Time" />
                    <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" :value="old('end_time', $event->end_time ? \Carbon\Carbon::parse($event->end_time)->format('H:i') : '')" />
                    <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                </div>

                <!-- Registration -->
                <div>
                    <x-input-label for="registration_start" value="Registration Start Date" />
                    <x-text-input id="registration_start" name="registration_start" type="date" class="mt-1 block w-full" :value="old('registration_start', $event->registration_start)" />
                    <x-input-error class="mt-2" :messages="$errors->get('registration_start')" />
                </div>

                <div>
                    <x-input-label for="registration_end" value="Registration End Date" />
                    <x-text-input id="registration_end" name="registration_end" type="date" class="mt-1 block w-full" :value="old('registration_end', $event->registration_end)" />
                    <x-input-error class="mt-2" :messages="$errors->get('registration_end')" />
                </div>

                <div>
                    <x-input-label for="max_seats" value="Maximum Seats (Leave blank for unlimited)" />
                    <x-text-input id="max_seats" name="max_seats" type="number" min="1" class="mt-1 block w-full" :value="old('max_seats', $event->max_seats)" />
                    <x-input-error class="mt-2" :messages="$errors->get('max_seats')" />
                </div>

                <div></div> <!-- spacing filler -->

                <!-- Assign Admin -->
                @if(auth()->user()->hasRole('super-admin'))
                <div class="md:col-span-2 border-t pt-4 border-gray-200 mt-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Event Assignment</h3>
                </div>

                <div>
                    <x-input-label for="assigned_admin_id" value="Assign to Admin/Staff" />
                    <select id="assigned_admin_id" name="assigned_admin_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Unassigned (Super Admin Only)</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" {{ old('assigned_admin_id', $event->assigned_admin_id) == $admin->id ? 'selected' : '' }}>
                                {{ $admin->name }} ({{ $admin->email }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('assigned_admin_id')" />
                </div>
                @endif

                <!-- Organizer Details -->
                <div class="md:col-span-2 border-t pt-4 border-gray-200 mt-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Organizer Details</h3>
                </div>

                <div>
                    <x-input-label for="organizer_name" value="Organizer Name" />
                    <x-text-input id="organizer_name" name="organizer_name" type="text" class="mt-1 block w-full" :value="old('organizer_name', $event->organizer_name)" />
                    <x-input-error class="mt-2" :messages="$errors->get('organizer_name')" />
                </div>

                <div>
                    <x-input-label for="contact_number" value="Contact Number" />
                    <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full" :value="old('contact_number', $event->contact_number)" />
                    <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />
                </div>

                <div>
                    <x-input-label for="email" value="Support Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $event->email)" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <!-- Attachments -->
                <div class="md:col-span-2 border-t pt-4 border-gray-200 mt-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Media & Attachments</h3>
                </div>

                <div>
                    <x-input-label for="banner_image" value="Event Banner Image" />
                    @if($event->banner_image_path)
                        <div class="mb-2">
                            <img src="{{ Storage::url($event->banner_image_path) }}" alt="Banner" class="h-20 rounded border">
                        </div>
                    @endif
                    <input id="banner_image" name="banner_image" type="file" accept="image/*" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm" />
                    <x-input-error class="mt-2" :messages="$errors->get('banner_image')" />
                </div>

                <div>
                    <x-input-label for="agenda_pdf" value="Agenda PDF" />
                    @if($event->agenda_pdf_path)
                        <div class="mb-2">
                            <a href="{{ Storage::url($event->agenda_pdf_path) }}" target="_blank" class="text-indigo-600 text-sm hover:underline">View Current PDF</a>
                        </div>
                    @endif
                    <input id="agenda_pdf" name="agenda_pdf" type="file" accept="application/pdf" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm" />
                    <x-input-error class="mt-2" :messages="$errors->get('agenda_pdf')" />
                </div>

                <!-- Form Attachment -->
                <div class="md:col-span-2 border-t pt-4 border-gray-200 mt-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Application Form</h3>
                    
                    @if($forms->count() > 0)
                        <div class="space-y-3">
                            @foreach($forms as $form)
                                <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" name="form_ids[]" value="{{ $form->id }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" 
                                        {{ $event->forms->contains($form->id) ? 'checked' : '' }}>
                                    <span class="ml-3 text-gray-700 font-medium">{{ $form->title }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('form_ids')" />
                    @else
                        <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg">
                            <p>No forms available. You need to <a href="{{ route('forms.create') }}" class="underline font-bold">create a form</a> before you can attach it to this event and publish.</p>
                        </div>
                    @endif
                </div>

            </div>

            <div class="flex items-center gap-4 border-t border-gray-200 pt-6 mt-6">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    Update Event
                </button>
                <a href="{{ route('events.show', $event) }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>

        <!-- Actions & Deletion -->
        <div class="p-6 bg-gray-50 border-t border-gray-200 flex gap-4">
            <form method="POST" action="{{ route('events.duplicate', $event) }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors shadow-sm">
                    Duplicate Event
                </button>
            </form>

            <form method="POST" action="{{ route('events.status', ['event' => $event, 'status' => 'cancelled']) }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-yellow-100 text-yellow-700 font-medium rounded-lg hover:bg-yellow-200 transition-colors shadow-sm">
                    Cancel Event
                </button>
            </form>

            <form method="POST" action="{{ route('events.status', ['event' => $event, 'status' => 'completed']) }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-100 text-green-700 font-medium rounded-lg hover:bg-green-200 transition-colors shadow-sm">
                    Mark Completed
                </button>
            </form>
            
            <div class="flex-1"></div>

            <form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Are you sure you want to delete this event?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                    Delete Event
                </button>
            </form>
        </div>
    </div>
</x-app-layout>

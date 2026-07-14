<x-app-layout>
    <x-slot name="header">
        Create Event
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden max-w-4xl">
        <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="md:col-span-2">
                    <x-input-label for="title" value="Event Title" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="description" value="Description" />
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div>
                    <x-input-label for="category" value="Category" />
                    <x-text-input id="category" name="category" type="text" class="mt-1 block w-full" :value="old('category')" />
                    <x-input-error class="mt-2" :messages="$errors->get('category')" />
                </div>

                <div>
                    <x-input-label for="location" value="Venue / Location" />
                    <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" :value="old('location')" />
                    <x-input-error class="mt-2" :messages="$errors->get('location')" />
                </div>

                <!-- Timings -->
                <div>
                    <x-input-label for="start_date" value="Start Date" />
                    <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                </div>

                <div>
                    <x-input-label for="end_date" value="End Date" />
                    <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full" :value="old('end_date')" />
                    <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                </div>

                <div>
                    <x-input-label for="start_time" value="Start Time" />
                    <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full" :value="old('start_time')" />
                    <x-input-error class="mt-2" :messages="$errors->get('start_time')" />
                </div>

                <div>
                    <x-input-label for="end_time" value="End Time" />
                    <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" :value="old('end_time')" />
                    <x-input-error class="mt-2" :messages="$errors->get('end_time')" />
                </div>

                <!-- Registration -->
                <div>
                    <x-input-label for="registration_start" value="Registration Start Date" />
                    <x-text-input id="registration_start" name="registration_start" type="date" class="mt-1 block w-full" :value="old('registration_start')" />
                    <x-input-error class="mt-2" :messages="$errors->get('registration_start')" />
                </div>

                <div>
                    <x-input-label for="registration_end" value="Registration End Date" />
                    <x-text-input id="registration_end" name="registration_end" type="date" class="mt-1 block w-full" :value="old('registration_end')" />
                    <x-input-error class="mt-2" :messages="$errors->get('registration_end')" />
                </div>

                <div>
                    <x-input-label for="max_seats" value="Maximum Seats (Leave blank for unlimited)" />
                    <x-text-input id="max_seats" name="max_seats" type="number" min="1" class="mt-1 block w-full" :value="old('max_seats')" />
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
                            <option value="{{ $admin->id }}" {{ old('assigned_admin_id') == $admin->id ? 'selected' : '' }}>
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
                    <x-text-input id="organizer_name" name="organizer_name" type="text" class="mt-1 block w-full" :value="old('organizer_name')" />
                    <x-input-error class="mt-2" :messages="$errors->get('organizer_name')" />
                </div>

                <div>
                    <x-input-label for="contact_number" value="Contact Number" />
                    <x-text-input id="contact_number" name="contact_number" type="text" class="mt-1 block w-full" :value="old('contact_number')" />
                    <x-input-error class="mt-2" :messages="$errors->get('contact_number')" />
                </div>

                <div>
                    <x-input-label for="email" value="Support Email" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <!-- Attachments -->
                <div class="md:col-span-2 border-t pt-4 border-gray-200 mt-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Media & Attachments</h3>
                </div>

                <div>
                    <x-input-label for="banner_image" value="Event Banner Image" />
                    <input id="banner_image" name="banner_image" type="file" accept="image/*" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm" />
                    <x-input-error class="mt-2" :messages="$errors->get('banner_image')" />
                </div>

                <div>
                    <x-input-label for="agenda_pdf" value="Agenda PDF" />
                    <input id="agenda_pdf" name="agenda_pdf" type="file" accept="application/pdf" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm" />
                    <x-input-error class="mt-2" :messages="$errors->get('agenda_pdf')" />
                </div>

            </div>

            <div class="flex items-center gap-4 border-t border-gray-200 pt-6 mt-6">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    Create Event
                </button>
                <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        Create Event
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <x-ui.card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">Event Details</h3>
            </x-slot>

            <x-ui.form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" class="space-y-8">
                
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <x-ui.input id="title" name="title" :value="old('title')" required autofocus label="Event Title" />
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                        <textarea id="description" name="description" rows="4" class="block w-full rounded-lg border border-gray-200/80 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-ui.input id="category" name="category" :value="old('category')" label="Category" />
                    </div>

                    <div>
                        <x-ui.input id="location" name="location" :value="old('location')" label="Venue / Location" />
                    </div>
                </div>

                <!-- Timings -->
                <div class="border-t border-gray-100 pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 mb-4 uppercase tracking-wider">Timings & Registration</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-ui.input type="date" id="start_date" name="start_date" :value="old('start_date')" required label="Start Date" />
                        </div>
                        <div>
                            <x-ui.input type="date" id="end_date" name="end_date" :value="old('end_date')" label="End Date" />
                        </div>
                        <div>
                            <x-ui.input type="time" id="start_time" name="start_time" :value="old('start_time')" label="Start Time" />
                        </div>
                        <div>
                            <x-ui.input type="time" id="end_time" name="end_time" :value="old('end_time')" label="End Time" />
                        </div>
                        
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-ui.input type="date" id="registration_start" name="registration_start" :value="old('registration_start')" label="Registration Start" />
                            </div>
                            <div>
                                <x-ui.input type="date" id="registration_end" name="registration_end" :value="old('registration_end')" label="Registration End" />
                            </div>
                            <div>
                                <x-ui.input type="number" id="max_seats" name="max_seats" min="1" :value="old('max_seats')" label="Max Seats" hint="Leave blank for unlimited" />
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
                                <option value="{{ $admin->id }}" {{ old('assigned_admin_id') == $admin->id ? 'selected' : '' }}>
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
                            <x-ui.input id="organizer_name" name="organizer_name" :value="old('organizer_name')" label="Organizer Name" />
                        </div>
                        <div>
                            <x-ui.input id="contact_number" name="contact_number" :value="old('contact_number')" label="Contact Number" />
                        </div>
                        <div class="md:col-span-2">
                            <x-ui.input type="email" id="email" name="email" :value="old('email')" label="Support Email" />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Event Banner Image</label>
                            <input name="banner_image" type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors" />
                            @error('banner_image') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Agenda PDF</label>
                            <input name="agenda_pdf" type="file" accept="application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors" />
                            @error('agenda_pdf') <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <x-ui.button type="submit" variant="primary" x-bind:disabled="isSubmitting">
                        <span x-show="!isSubmitting">Create Event</span>
                        <span x-show="isSubmitting">Saving...</span>
                    </x-ui.button>
                    <x-ui.button href="{{ route('events.index') }}" variant="secondary">
                        Cancel
                    </x-ui.button>
                </div>
            </x-ui.form>
        </x-ui.card>
    </div>
</x-app-layout>

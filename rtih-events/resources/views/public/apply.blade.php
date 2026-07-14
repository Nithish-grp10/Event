<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Apply - {{ $event->title }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans antialiased text-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            
            <div class="w-full sm:max-w-2xl mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
                <div class="mb-6 border-b pb-4">
                    <h1 class="text-3xl font-bold text-gray-800">{{ $event->title }}</h1>
                    @if($event->description)
                        <p class="mt-2 text-gray-600">{{ $event->description }}</p>
                    @endif
                    <div class="mt-4 text-sm text-gray-500">
                        <p><strong>Dates:</strong> {{ $event->start_date }} @if($event->end_date) to {{ $event->end_date }} @endif</p>
                        @if($event->location)
                            <p><strong>Location:</strong> {{ $event->location }}</p>
                        @endif
                    </div>
                </div>

                <h2 class="text-xl font-semibold mb-4">{{ $form->title }}</h2>
                @if($form->description)
                    <p class="mb-6 text-gray-600">{{ $form->description }}</p>
                @endif

                @if(session('duplicate_warning'))
                    <div class="mb-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800">
                        <p class="font-bold">Notice</p>
                        <p>{{ session('duplicate_warning') }}</p>
                        <form method="POST" action="{{ route('public.apply.store', $event) }}" class="mt-4">
                            @csrf
                            <input type="hidden" name="confirm_overwrite" value="1">
                            <!-- Retain previous inputs -->
                            <input type="hidden" name="applicant_name" value="{{ old('applicant_name') }}">
                            <input type="hidden" name="applicant_email" value="{{ old('applicant_email') }}">
                            @foreach($form->fields as $field)
                                @if(is_array(old('field_' . $field->id)))
                                    @foreach(old('field_' . $field->id) as $val)
                                        <input type="hidden" name="field_{{ $field->id }}[]" value="{{ $val }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="field_{{ $field->id }}" value="{{ old('field_' . $field->id) }}">
                                @endif
                            @endforeach
                            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">Confirm and Overwrite</button>
                            <a href="{{ route('public.apply.show', $event) }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
                        </form>
                    </div>
                @endif

                @if(!session('duplicate_warning'))
                <form method="POST" action="{{ route('public.apply.store', $event) }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Base Fields -->
                    <div>
                        <label class="block font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="applicant_name" value="{{ old('applicant_name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('applicant_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="applicant_email" value="{{ old('applicant_email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('applicant_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <hr class="my-6">

                    <!-- Dynamic Fields -->
                    @foreach($form->fields as $field)
                        <div>
                            <label class="block font-medium text-gray-700">
                                {{ $field->label }} 
                                @if($field->is_required) <span class="text-red-500">*</span> @endif
                            </label>

                            @if($field->field_type === 'text')
                                <input type="text" name="field_{{ $field->id }}" value="{{ old('field_' . $field->id) }}" {{ $field->is_required ? 'required' : '' }} class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            
                            @elseif($field->field_type === 'email')
                                <input type="email" name="field_{{ $field->id }}" value="{{ old('field_' . $field->id) }}" {{ $field->is_required ? 'required' : '' }} class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            
                            @elseif($field->field_type === 'textarea')
                                <textarea name="field_{{ $field->id }}" rows="3" {{ $field->is_required ? 'required' : '' }} class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('field_' . $field->id) }}</textarea>
                            
                            @elseif($field->field_type === 'select')
                                <select name="field_{{ $field->id }}" {{ $field->is_required ? 'required' : '' }} class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Select an option --</option>
                                    @if($field->options)
                                        @foreach($field->options as $option)
                                            <option value="{{ $option }}" {{ old('field_' . $field->id) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            
                            @elseif($field->field_type === 'radio')
                                <div class="mt-2 space-y-2">
                                    @if($field->options)
                                        @foreach($field->options as $option)
                                            <div class="flex items-center">
                                                <input type="radio" name="field_{{ $field->id }}" value="{{ $option }}" {{ old('field_' . $field->id) == $option ? 'checked' : '' }} {{ $field->is_required ? 'required' : '' }} class="border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-gray-700">{{ $option }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            
                            @elseif($field->field_type === 'checkbox')
                                <div class="mt-2 space-y-2">
                                    @if($field->options)
                                        @foreach($field->options as $option)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="field_{{ $field->id }}[]" value="{{ $option }}" {{ is_array(old('field_' . $field->id)) && in_array($option, old('field_' . $field->id)) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-gray-700">{{ $option }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @elseif($field->field_type === 'file')
                                <input type="file" name="field_{{ $field->id }}" {{ $field->is_required ? 'required' : '' }} class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            @endif

                            @error('field_' . $field->id) <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endforeach

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Application
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </body>
</html>

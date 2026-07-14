<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Apply - {{ $event->title }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="bg-[#fafafa] font-sans antialiased text-gray-900 selection:bg-indigo-500 selection:text-white min-h-screen flex flex-col">
        
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 sticky top-0 z-10 shadow-sm">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-gray-900">RTIH</span>
                </div>
            </div>
        </header>

        <main class="flex-grow flex items-start justify-center pt-10 pb-20 px-4 sm:px-6">
            <div class="w-full max-w-2xl">
                
                <!-- Event Header Card -->
                <div class="bg-white rounded-2xl shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05),0_1px_4px_-1px_rgba(0,0,0,0.02)] border border-gray-200/80 overflow-hidden mb-8 relative">
                    <div class="h-2 w-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500"></div>
                    <div class="p-8 sm:p-10">
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100 mb-4 uppercase tracking-wider">
                            Event Application
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-3">{{ $event->title }}</h1>
                        @if($event->description)
                            <p class="text-gray-600 leading-relaxed mb-6">{{ $event->description }}</p>
                        @endif
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-6 border-t border-gray-100">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</p>
                                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }} @if($event->end_date) - {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }} @endif</p>
                                </div>
                            </div>
                            @if($event->location)
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</p>
                                    <p class="text-sm font-medium text-gray-900 mt-0.5">{{ $event->location }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-2xl shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05),0_1px_4px_-1px_rgba(0,0,0,0.02)] border border-gray-200/80 p-8 sm:p-10">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $form->title }}</h2>
                    @if($form->description)
                        <p class="mb-8 text-gray-500 text-sm">{{ $form->description }}</p>
                    @endif

                    @if(session('duplicate_warning'))
                        <div class="mb-8 p-5 bg-amber-50 border border-amber-200 rounded-xl">
                            <div class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <div>
                                    <h3 class="text-sm font-bold text-amber-800">Application Exists</h3>
                                    <p class="text-sm text-amber-700 mt-1">{{ session('duplicate_warning') }}</p>
                                    
                                    <form method="POST" action="{{ route('public.apply.store', $event) }}" class="mt-4 flex items-center gap-3">
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
                                        <button type="submit" class="px-4 py-2 bg-amber-600 text-white text-sm font-semibold rounded-lg hover:bg-amber-700 transition-colors shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">Confirm Overwrite</button>
                                        <a href="{{ route('public.apply.show', $event) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!session('duplicate_warning'))
                    <form method="POST" action="{{ route('public.apply.store', $event) }}" class="space-y-8" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Base Fields -->
                        <div class="space-y-6">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2">Your Details</h3>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="applicant_name" value="{{ old('applicant_name') }}" required class="block w-full rounded-lg border border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3">
                                @error('applicant_name') <span class="text-red-500 text-xs font-medium mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="applicant_email" value="{{ old('applicant_email') }}" required class="block w-full rounded-lg border border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3" placeholder="you@example.com">
                                @error('applicant_email') <span class="text-red-500 text-xs font-medium mt-1.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Dynamic Fields -->
                        @if($form->fields->count() > 0)
                        <div class="space-y-8 pt-6 mt-6 border-t border-gray-100">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2 mb-6">Additional Information</h3>
                            
                            @foreach($form->fields as $field)
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                                        {{ $field->label }} 
                                        @if($field->is_required) <span class="text-red-500">*</span> @endif
                                    </label>

                                    @if($field->field_type === 'text')
                                        <input type="text" name="field_{{ $field->id }}" value="{{ old('field_' . $field->id) }}" {{ $field->is_required ? 'required' : '' }} class="block w-full rounded-lg border border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3">
                                    
                                    @elseif($field->field_type === 'email')
                                        <input type="email" name="field_{{ $field->id }}" value="{{ old('field_' . $field->id) }}" {{ $field->is_required ? 'required' : '' }} class="block w-full rounded-lg border border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3">
                                    
                                    @elseif($field->field_type === 'textarea')
                                        <textarea name="field_{{ $field->id }}" rows="4" {{ $field->is_required ? 'required' : '' }} class="block w-full rounded-lg border border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3">{{ old('field_' . $field->id) }}</textarea>
                                    
                                    @elseif($field->field_type === 'select')
                                        <select name="field_{{ $field->id }}" {{ $field->is_required ? 'required' : '' }} class="block w-full rounded-lg border border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3">
                                            <option value="">Choose an option...</option>
                                            @if($field->options)
                                                @foreach($field->options as $option)
                                                    <option value="{{ $option }}" {{ old('field_' . $field->id) == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    
                                    @elseif($field->field_type === 'radio')
                                        <div class="mt-3 space-y-3">
                                            @if($field->options)
                                                @foreach($field->options as $option)
                                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <input type="radio" name="field_{{ $field->id }}" value="{{ $option }}" {{ old('field_' . $field->id) == $option ? 'checked' : '' }} {{ $field->is_required ? 'required' : '' }} class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                        <span class="ml-3 text-sm font-medium text-gray-700">{{ $option }}</span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                    
                                    @elseif($field->field_type === 'checkbox')
                                        <div class="mt-3 space-y-3">
                                            @if($field->options)
                                                @foreach($field->options as $option)
                                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <input type="checkbox" name="field_{{ $field->id }}[]" value="{{ $option }}" {{ is_array(old('field_' . $field->id)) && in_array($option, old('field_' . $field->id)) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                        <span class="ml-3 text-sm font-medium text-gray-700">{{ $option }}</span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </div>
                                    @elseif($field->field_type === 'file')
                                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-indigo-400 hover:bg-indigo-50/50 transition-colors bg-gray-50">
                                            <div class="space-y-1 text-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                                <div class="flex text-sm text-gray-600 justify-center">
                                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500 px-1">
                                                        <span>Upload a file</span>
                                                        <input type="file" name="field_{{ $field->id }}" {{ $field->is_required ? 'required' : '' }} class="sr-only">
                                                    </label>
                                                </div>
                                                <p class="text-xs text-gray-500">PDF, PNG, JPG up to 10MB</p>
                                            </div>
                                        </div>
                                    @endif

                                    @error('field_' . $field->id) <span class="text-red-500 text-xs font-medium mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="pt-8 border-t border-gray-100 mt-8">
                            <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_1px_2px_rgba(0,0,0,0.1),inset_0_1px_0_rgba(255,255,255,0.1)] text-base font-semibold text-white bg-gray-900 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors">
                                Submit Application
                                <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                            <p class="text-center text-xs text-gray-500 mt-4">By submitting, you agree to our Terms and Privacy Policy.</p>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </main>
        
        <footer class="mt-auto py-6 border-t border-gray-200 bg-white">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} RTIH Events. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>

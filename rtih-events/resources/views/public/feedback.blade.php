<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback - {{ $event->title }}</title>
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
        <div class="max-w-3xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="font-bold text-xl tracking-tight text-gray-900">RTIH</span>
            </div>
            <span class="text-sm font-medium text-gray-500">Event Feedback</span>
        </div>
    </header>

    <main class="flex-grow flex items-start justify-center pt-8 pb-16 px-4 sm:px-6">
        <div class="w-full max-w-2xl">
            
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $event->title }}</h1>
                <p class="text-gray-500 mt-2">We value your opinion! Please take a moment to review the event.</p>
            </div>

            <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05),0_1px_4px_-1px_rgba(0,0,0,0.02)] border border-gray-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
                
                <form method="POST" action="{{ route('public.feedback.store', $event) }}" class="space-y-8">
                    @csrf
                    <input type="hidden" name="application_id" value="{{ request('application_id') }}">

                    <!-- Overall Rating -->
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-100 text-center">
                        <label class="block text-base font-bold text-gray-900 mb-4">Overall Event Rating <span class="text-red-500">*</span></label>
                        <div class="flex justify-center gap-4 sm:gap-6">
                            @for($i=1; $i<=5; $i++)
                            <label class="flex flex-col items-center cursor-pointer group">
                                <div class="w-12 h-12 rounded-full border-2 border-gray-200 bg-white flex items-center justify-center mb-2 group-hover:border-indigo-400 group-hover:bg-indigo-50 transition-colors relative">
                                    <input type="radio" name="overall_rating" value="{{ $i }}" class="absolute opacity-0 w-full h-full cursor-pointer peer" required>
                                    <span class="text-lg font-bold text-gray-500 peer-checked:text-indigo-600">{{ $i }}</span>
                                    <div class="absolute inset-0 rounded-full border-2 border-indigo-600 scale-0 peer-checked:scale-100 transition-transform"></div>
                                </div>
                            </label>
                            @endfor
                        </div>
                        <div class="flex justify-between max-w-[280px] mx-auto mt-2 px-2 text-xs text-gray-500 font-medium">
                            <span>Poor</span>
                            <span>Excellent</span>
                        </div>
                        @error('overall_rating')<p class="text-red-500 text-sm font-medium mt-3">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <!-- Speaker Rating -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3 text-center">Speaker/Host</label>
                            <div class="flex justify-center gap-3">
                                @for($i=1; $i<=5; $i++)
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="speaker_rating" value="{{ $i }}" class="absolute opacity-0 w-full h-full cursor-pointer peer">
                                    <div class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-sm font-medium text-gray-500 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white group-hover:border-indigo-400 transition-colors">{{ $i }}</div>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Venue Rating -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3 text-center">Venue/Logistics</label>
                            <div class="flex justify-center gap-3">
                                @for($i=1; $i<=5; $i++)
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="venue_rating" value="{{ $i }}" class="absolute opacity-0 w-full h-full cursor-pointer peer">
                                    <div class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-sm font-medium text-gray-500 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white group-hover:border-indigo-400 transition-colors">{{ $i }}</div>
                                </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Content Rating -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3 text-center">Content/Topic</label>
                            <div class="flex justify-center gap-3">
                                @for($i=1; $i<=5; $i++)
                                <label class="cursor-pointer relative group">
                                    <input type="radio" name="content_rating" value="{{ $i }}" class="absolute opacity-0 w-full h-full cursor-pointer peer">
                                    <div class="w-8 h-8 rounded border border-gray-200 flex items-center justify-center text-sm font-medium text-gray-500 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white group-hover:border-indigo-400 transition-colors">{{ $i }}</div>
                                </label>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-6 border-t border-gray-100">
                        <div>
                            <label for="comments" class="block text-sm font-semibold text-gray-900 mb-1.5 flex items-center"><svg class="w-4 h-4 mr-1.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg> What did you like the most?</label>
                            <textarea id="comments" name="comments" rows="3" class="block w-full rounded-lg border border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3"></textarea>
                        </div>

                        <div>
                            <label for="suggestions" class="block text-sm font-semibold text-gray-900 mb-1.5 flex items-center"><svg class="w-4 h-4 mr-1.5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg> Any suggestions for improvement?</label>
                            <textarea id="suggestions" name="suggestions" rows="3" class="block w-full rounded-lg border border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3"></textarea>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_1px_2px_rgba(0,0,0,0.1),inset_0_1px_0_rgba(255,255,255,0.1)] text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <footer class="mt-auto py-6 bg-white border-t border-gray-200">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} RTIH Events. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

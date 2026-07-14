<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback - {{ $event->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans flex flex-col min-h-screen">
    <div class="bg-indigo-600 text-white shadow-md">
        <div class="max-w-3xl mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold">Feedback for {{ $event->title }}</h1>
            <p class="text-indigo-100 mt-1">We value your opinion!</p>
        </div>
    </div>

    <main class="flex-grow flex items-start justify-center p-4 py-12">
        <div class="w-full max-w-3xl bg-white p-8 rounded-xl shadow-lg border border-gray-100">
            <form method="POST" action="{{ route('public.feedback.store', $event) }}" class="space-y-6">
                @csrf
                <input type="hidden" name="application_id" value="{{ request('application_id') }}">

                <!-- Overall Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overall Rating (1 to 5)</label>
                    <div class="flex gap-4">
                        @for($i=1; $i<=5; $i++)
                        <label class="flex flex-col items-center cursor-pointer">
                            <input type="radio" name="overall_rating" value="{{ $i }}" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300" required>
                            <span class="mt-1 text-sm text-gray-500">{{ $i }}</span>
                        </label>
                        @endfor
                    </div>
                    @error('overall_rating')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Speaker Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Speaker/Host Rating</label>
                    <div class="flex gap-4">
                        @for($i=1; $i<=5; $i++)
                        <label class="flex flex-col items-center cursor-pointer">
                            <input type="radio" name="speaker_rating" value="{{ $i }}" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="mt-1 text-sm text-gray-500">{{ $i }}</span>
                        </label>
                        @endfor
                    </div>
                </div>

                <!-- Venue Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Venue/Logistics Rating</label>
                    <div class="flex gap-4">
                        @for($i=1; $i<=5; $i++)
                        <label class="flex flex-col items-center cursor-pointer">
                            <input type="radio" name="venue_rating" value="{{ $i }}" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="mt-1 text-sm text-gray-500">{{ $i }}</span>
                        </label>
                        @endfor
                    </div>
                </div>

                <!-- Content Rating -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Content/Topic Rating</label>
                    <div class="flex gap-4">
                        @for($i=1; $i<=5; $i++)
                        <label class="flex flex-col items-center cursor-pointer">
                            <input type="radio" name="content_rating" value="{{ $i }}" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                            <span class="mt-1 text-sm text-gray-500">{{ $i }}</span>
                        </label>
                        @endfor
                    </div>
                </div>

                <div>
                    <label for="comments" class="block text-sm font-medium text-gray-700 mb-1">What did you like the most?</label>
                    <textarea id="comments" name="comments" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div>
                    <label for="suggestions" class="block text-sm font-medium text-gray-700 mb-1">Any suggestions for improvement?</label>
                    <textarea id="suggestions" name="suggestions" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>

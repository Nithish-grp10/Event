<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Application Received - {{ $event->title }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 font-sans antialiased text-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            
            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg text-center">
                <div class="mb-4 text-green-500 flex justify-center">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Application Received</h1>
                <p class="text-gray-600 mb-6">Thanks for applying to <strong>{{ $event->title }}</strong>. We've received your application.</p>
                <a href="{{ url('/') }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Return Home</a>
            </div>
            
        </div>
    </body>
</html>

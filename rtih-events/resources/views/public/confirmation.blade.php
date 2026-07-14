<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Application Received - {{ $event->title }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
        </style>
    </head>
    <body class="bg-[#fafafa] font-sans antialiased text-gray-900 min-h-screen flex flex-col">
        
        <header class="bg-white border-b border-gray-200">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-gray-900">RTIH</span>
                </div>
            </div>
        </header>

        <main class="flex-grow flex items-center justify-center p-4">
            <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05),0_1px_4px_-1px_rgba(0,0,0,0.02)] border border-gray-200/80 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500"></div>
                
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-50 border border-emerald-100 mb-6">
                    <svg class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-900 mb-3 tracking-tight">Application Received</h1>
                
                <div class="bg-gray-50 rounded-xl p-4 mb-8 border border-gray-100">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Thank you for applying to <strong class="font-semibold text-gray-900">{{ $event->title }}</strong>. We have successfully received your application.
                    </p>
                </div>

                <a href="{{ url('/') }}" class="inline-flex justify-center items-center py-3 px-6 border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm w-full">
                    Return Home
                </a>
            </div>
        </main>
    </body>
</html>

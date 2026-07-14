<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RTIH Events') }} - Authentication</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-[#fafafa] flex min-h-screen selection:bg-indigo-500 selection:text-white">
    
    <!-- Left side: Branding / Hero image (hidden on small screens) -->
    <div class="hidden lg:flex lg:w-1/2 bg-gray-900 relative overflow-hidden items-center justify-center">
        <!-- Abstract background pattern -->
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
        
        <!-- Glowing Orbs -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-96 h-96 bg-pink-600 rounded-full mix-blend-multiply filter blur-[128px] opacity-40 animate-blob animation-delay-4000"></div>

        <div class="relative z-10 px-16 max-w-2xl">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white mb-8 shadow-lg shadow-indigo-500/30">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h1 class="text-5xl font-extrabold text-white tracking-tight mb-6 leading-tight">Manage events with <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">precision.</span></h1>
            <p class="text-lg text-gray-400 max-w-lg leading-relaxed">The enterprise platform to seamlessly manage your events, attendees, and applications all in one place with unparalleled performance.</p>
            
            <div class="mt-12 flex items-center gap-4 text-sm font-medium text-gray-400">
                <div class="flex -space-x-3">
                    <img class="w-10 h-10 rounded-full border-2 border-gray-900" src="https://ui-avatars.com/api/?name=Alex&background=4f46e5&color=fff" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-gray-900" src="https://ui-avatars.com/api/?name=Sam&background=ec4899&color=fff" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-gray-900" src="https://ui-avatars.com/api/?name=Jordan&background=8b5cf6&color=fff" alt="User">
                </div>
                <p>Trusted by 10,000+ event organizers</p>
            </div>
        </div>
    </div>

    <!-- Right side: Auth Form -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 relative">
        <div class="w-full max-w-[420px]">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center gap-3 mb-10">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white shadow-sm">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="text-2xl font-bold text-gray-900 tracking-tight">RTIH</span>
            </div>

            <div class="bg-white px-8 sm:px-10 py-10 shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05),0_1px_4px_-1px_rgba(0,0,0,0.02)] border border-gray-200/80 rounded-3xl w-full">
                {{ $slot }}
            </div>
            
            <!-- Footer links -->
            <div class="mt-10 text-center text-sm font-medium text-gray-500 flex items-center justify-center gap-4">
                <a href="/" class="hover:text-gray-900 transition-colors">Terms of Service</a>
                <span class="text-gray-300">&bull;</span>
                <a href="/" class="hover:text-gray-900 transition-colors">Privacy Policy</a>
            </div>
        </div>
    </div>

</body>
</html>

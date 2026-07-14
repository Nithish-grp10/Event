<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Alpine.js (included in Breeze app.js usually, but we ensure it's available) -->
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">

            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 border-b border-gray-200 px-6">
                    <span class="text-2xl font-bold text-indigo-700">RTIH Events</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <x-heroicon-o-home class="w-5 h-5"/>
                        Dashboard
                    </a>
                    
                    <a href="{{ route('events.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('events.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <x-heroicon-o-calendar class="w-5 h-5"/>
                        Events
                    </a>

                    <a href="{{ route('forms.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('forms.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <x-heroicon-o-clipboard-document-list class="w-5 h-5"/>
                        Forms
                    </a>

                    <a href="{{ route('applications.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('applications.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <x-heroicon-o-inbox-arrow-down class="w-5 h-5"/>
                        Applications
                    </a>
                    <a href="{{ route('feedback.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('feedback.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:text-indigo-700 hover:bg-gray-50' }}">
                        <x-heroicon-o-chat-bubble-left-ellipsis class="mr-3 h-5 w-5 {{ request()->routeIs('feedback.*') ? 'text-indigo-700' : 'text-gray-400 group-hover:text-indigo-700' }}" />
                        Feedback
                    </a>
                    
                    @role('super-admin')
                    <a href="{{ route('admins.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admins.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:text-indigo-700 hover:bg-gray-50' }}">
                        <x-heroicon-o-users class="mr-3 h-5 w-5 {{ request()->routeIs('admins.*') ? 'text-indigo-700' : 'text-gray-400 group-hover:text-indigo-700' }}" />
                        Admin Users
                    </a>
                    @endrole
                </nav>

                <!-- User profile -->
                <div class="p-4 border-t border-gray-200">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                            <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4"/>
                            Log Out
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <!-- Topbar -->
                <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0 relative z-20">
                    <div class="flex items-center gap-4 flex-1">
                        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                            <x-heroicon-o-bars-3 class="w-6 h-6"/>
                        </button>
                        
                        @if (isset($header))
                            <div class="text-xl font-semibold text-gray-800 hidden md:block w-48">
                                {{ $header }}
                            </div>
                        @endif

                        <!-- Global Search -->
                        <div class="flex-1 max-w-xl px-4" x-data="globalSearch()">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-heroicon-o-magnifying-glass class="h-5 w-5 text-gray-400" />
                                </div>
                                <input x-model="query" @input.debounce.300ms="search" @focus="open = true" @click.away="open = false" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors" placeholder="Search events, forms, applications...">
                                
                                <!-- Search Results Dropdown -->
                                <div x-show="open && (results.length > 0 || query.length > 0)" class="absolute z-50 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-200 py-1 max-h-96 overflow-y-auto" x-cloak>
                                    <div x-show="loading" class="px-4 py-2 text-sm text-gray-500">Searching...</div>
                                    <div x-show="!loading && results.length === 0 && query.length > 0" class="px-4 py-2 text-sm text-gray-500">No results found</div>
                                    
                                    <template x-for="result in results" :key="result.url">
                                        <a :href="result.url" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-3">
                                            <div class="p-1 bg-gray-100 rounded text-gray-500">
                                                <template x-if="result.icon === 'calendar'"><x-heroicon-o-calendar class="w-4 h-4"/></template>
                                                <template x-if="result.icon === 'clipboard-document-list'"><x-heroicon-o-clipboard-document-list class="w-4 h-4"/></template>
                                                <template x-if="result.icon === 'inbox-arrow-down'"><x-heroicon-o-inbox-arrow-down class="w-4 h-4"/></template>
                                            </div>
                                            <div>
                                                <span class="font-medium" x-text="result.title"></span>
                                                <span class="text-xs text-gray-400 ml-2" x-text="result.type"></span>
                                            </div>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-500">
                                <span class="sr-only">View notifications</span>
                                <x-heroicon-o-bell class="w-6 h-6"/>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-1 right-1 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
                                @endif
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false" x-transition x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-20 border border-gray-200">
                                <div class="py-2">
                                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                                @csrf
                                                <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-900">Mark all read</button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                            <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-4 py-3 hover:bg-gray-50 {{ $notification->read_at ? 'opacity-75' : 'bg-indigo-50' }}">
                                                <p class="text-sm text-gray-800">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            </a>
                                        @empty
                                            <div class="px-4 py-3 text-sm text-gray-500">No notifications yet.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (isset($actions))
                            {{ $actions }}
                        @endif
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative z-10">
                    {{ $slot }}
                </main>
            </div>

        </div>

        <x-ui.feedback.toast />

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('globalSearch', () => ({
                    query: '',
                    results: [],
                    open: false,
                    loading: false,
                    
                    async search() {
                        if (this.query.length < 2) {
                            this.results = [];
                            return;
                        }
                        
                        this.loading = true;
                        try {
                            const response = await fetch(`/search?q=${encodeURIComponent(this.query)}`);
                            if (response.ok) {
                                this.results = await response.json();
                                this.open = true;
                            }
                        } catch (error) {
                            console.error('Search failed:', error);
                        } finally {
                            this.loading = false;
                        }
                    }
                }));
            });
        </script>
    </body>
</html>

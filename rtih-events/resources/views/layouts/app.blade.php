<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'RTIH Events') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#fbfbfb] text-gray-900" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">

            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" x-transition.opacity x-cloak></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200/80 transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
                <!-- Logo -->
                <div class="flex items-center h-16 px-6 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-sm">
                            <span class="text-white font-bold text-sm tracking-tighter">RT</span>
                        </div>
                        <span class="text-lg font-bold text-gray-900 tracking-tight">RTIH Events</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4 first:mt-0">Overview</p>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50/80 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                        <x-heroicon-o-home class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400' }}"/>
                        Dashboard
                    </a>
                    
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Management</p>
                    <a href="{{ route('events.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('events.*') ? 'bg-indigo-50/80 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                        <x-heroicon-o-calendar class="w-5 h-5 {{ request()->routeIs('events.*') ? 'text-indigo-600' : 'text-gray-400' }}"/>
                        Events
                    </a>

                    <a href="{{ route('applications.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('applications.*') ? 'bg-indigo-50/80 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                        <x-heroicon-o-inbox-arrow-down class="w-5 h-5 {{ request()->routeIs('applications.*') ? 'text-indigo-600' : 'text-gray-400' }}"/>
                        Applications
                    </a>

                    <a href="{{ route('forms.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('forms.*') ? 'bg-indigo-50/80 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                        <x-heroicon-o-document-duplicate class="w-5 h-5 {{ request()->routeIs('forms.*') ? 'text-indigo-600' : 'text-gray-400' }}"/>
                        Forms
                    </a>

                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">System</p>
                    <a href="{{ route('feedback.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('feedback.*') ? 'bg-indigo-50/80 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                        <x-heroicon-o-chat-bubble-left-ellipsis class="w-5 h-5 {{ request()->routeIs('feedback.*') ? 'text-indigo-600' : 'text-gray-400' }}"/>
                        Feedback
                    </a>
                    
                    @role('super-admin')
                    <a href="{{ route('admins.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admins.*') ? 'bg-indigo-50/80 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                        <x-heroicon-o-users class="w-5 h-5 {{ request()->routeIs('admins.*') ? 'text-indigo-600' : 'text-gray-400' }}"/>
                        Admin Users
                    </a>
                    @endrole
                </nav>

                <!-- User profile -->
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    <div class="flex items-center gap-3 mb-3 px-2">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden flex-1">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('profile.edit') }}" class="flex-1 flex justify-center items-center py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition-colors shadow-sm">
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full flex justify-center items-center py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-md hover:bg-gray-50 hover:text-red-600 transition-colors shadow-sm">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
                <!-- Topbar -->
                <header class="bg-white/80 backdrop-blur-md border-b border-gray-200/80 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0 z-30 sticky top-0">
                    <div class="flex items-center gap-4 flex-1">
                        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
                            <x-heroicon-o-bars-3 class="w-6 h-6"/>
                        </button>
                        
                        <!-- Breadcrumbs or Page Title Slot -->
                        <div class="flex items-center text-sm font-medium text-gray-500 hidden sm:flex">
                            @if(isset($header))
                                {{ $header }}
                            @else
                                <span class="text-gray-900 font-semibold">{{ config('app.name') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Global Search Command Palette Style -->
                        <div class="relative hidden md:block" x-data="globalSearch()">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-magnifying-glass class="h-4 w-4 text-gray-400" />
                            </div>
                            <input x-model="query" @input.debounce.300ms="search" @focus="open = true" @click.away="open = false" type="text" class="block w-64 pl-9 pr-3 py-1.5 border border-gray-200 rounded-lg text-sm bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all shadow-sm" placeholder="Search... (⌘K)">
                            
                            <!-- Search Results Dropdown -->
                            <div x-show="open && (results.length > 0 || query.length > 0)" class="absolute right-0 mt-2 w-80 bg-white shadow-xl rounded-xl border border-gray-100 py-2 max-h-96 overflow-y-auto z-50" x-cloak>
                                <div x-show="loading" class="px-4 py-3 text-sm text-gray-500 flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Searching...
                                </div>
                                <div x-show="!loading && results.length === 0 && query.length > 0" class="px-4 py-3 text-sm text-gray-500">No results found for "<span x-text="query"></span>"</div>
                                
                                <template x-for="result in results" :key="result.url">
                                    <a :href="result.url" class="block px-4 py-2 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <div class="p-1.5 bg-gray-100 rounded-md text-gray-500">
                                                <template x-if="result.icon === 'calendar'"><x-heroicon-o-calendar class="w-4 h-4"/></template>
                                                <template x-if="result.icon === 'document'"><x-heroicon-o-document-duplicate class="w-4 h-4"/></template>
                                                <template x-if="result.icon === 'inbox'"><x-heroicon-o-inbox-arrow-down class="w-4 h-4"/></template>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900" x-text="result.title"></div>
                                                <div class="text-xs text-gray-400 mt-0.5" x-text="result.type"></div>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors rounded-full hover:bg-gray-100">
                                <span class="sr-only">View notifications</span>
                                <x-heroicon-o-bell class="w-5 h-5"/>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                                @endif
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false" x-transition.opacity.duration.200ms x-cloak class="absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-xl overflow-hidden z-50 border border-gray-100 origin-top-right">
                                <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                    @if(auth()->user()->unreadNotifications->count() > 0)
                                        <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Mark all read</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="max-h-[320px] overflow-y-auto">
                                    @forelse(auth()->user()->notifications()->take(5)->get() as $notification)
                                        <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-50 last:border-0 {{ $notification->read_at ? 'opacity-75' : 'bg-indigo-50/30' }}">
                                            <p class="text-sm text-gray-800 leading-snug">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                            <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                                                <x-heroicon-o-clock class="w-3 h-3"/>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </p>
                                        </a>
                                    @empty
                                        <div class="px-4 py-8 text-center flex flex-col items-center justify-center text-gray-500">
                                            <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                                <x-heroicon-o-bell-slash class="w-6 h-6 text-gray-400"/>
                                            </div>
                                            <span class="text-sm">You're all caught up!</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        @if (isset($actions))
                            <div class="pl-4 border-l border-gray-200">
                                {{ $actions }}
                            </div>
                        @endif
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative z-10 scroll-smooth">
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
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

                // CMD+K shortcut
                document.addEventListener('keydown', (e) => {
                    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                        e.preventDefault();
                        const searchInput = document.querySelector('[x-data="globalSearch()"] input');
                        if (searchInput) searchInput.focus();
                    }
                });
            });
        </script>
    </body>
</html>

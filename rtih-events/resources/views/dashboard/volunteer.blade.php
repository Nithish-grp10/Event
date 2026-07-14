<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
            {{ __('Volunteer Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl -mr-20 -mt-20"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ auth()->user()->name }}!</h3>
                    <p class="text-emerald-100 max-w-md">Thank you for volunteering. Your help is what makes our events possible and successful.</p>
                </div>
                <div class="flex gap-4 text-center">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 min-w-[100px]">
                        <p class="text-3xl font-black">{{ $stats['shifts_completed'] }}</p>
                        <p class="text-xs font-semibold text-emerald-100 uppercase tracking-wider mt-1">Shifts</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 min-w-[100px]">
                        <p class="text-3xl font-black">{{ $stats['hours_logged'] }}</p>
                        <p class="text-xs font-semibold text-emerald-100 uppercase tracking-wider mt-1">Hours</p>
                    </div>
                </div>
            </div>
        </div>

        <x-ui.card>
            <x-slot name="header">
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Your Tasks</h3>
            </x-slot>

            <x-ui.feedback.empty-state
                title="No tasks assigned yet"
                description="Check back later for your next volunteer assignment."
                icon="clipboard-document-check"
            />
        </x-ui.card>
    </div>
</x-app-layout>

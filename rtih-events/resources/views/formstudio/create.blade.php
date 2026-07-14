<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
            {{ __('Create New Form') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <x-ui.card>
            <x-slot name="header">
                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Form Details</h3>
            </x-slot>
            
            <x-ui.form method="POST" action="{{ route('form-studio.store') }}" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-1.5">Form Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus class="block w-full rounded-xl border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3" placeholder="e.g. Mentor Application 2026">
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="3" class="block w-full rounded-xl border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3" placeholder="Briefly describe what this form is for..."></textarea>
                    @error('description')
                        <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-6 border-t border-gray-100">
                    <x-ui.button type="submit" variant="primary" class="shadow-sm bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500 border-indigo-600">
                        <x-heroicon-o-sparkles class="w-4 h-4 mr-1.5"/> Create & Open Builder
                    </x-ui.button>
                    <x-ui.button href="{{ route('form-studio.index') }}" variant="secondary">
                        Cancel
                    </x-ui.button>
                </div>
            </x-ui.form>
        </x-ui.card>
    </div>
</x-app-layout>

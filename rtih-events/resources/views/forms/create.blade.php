<x-app-layout>
    <x-slot name="header">
        Create New Form
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <x-ui.card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-900">Form Details</h3>
            </x-slot>
            
            <x-ui.form method="POST" action="{{ route('forms.store') }}" class="space-y-6">
                <div>
                    <x-ui.input id="title" name="title" :value="old('title')" required autofocus label="Form Title" placeholder="e.g. Exhibitor Registration 2026" />
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="3" class="block w-full rounded-lg border border-gray-200/80 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300" placeholder="Optional internal description for this form...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <x-ui.button type="submit" variant="primary" x-bind:disabled="isSubmitting">
                        <span x-show="!isSubmitting">Start Building Form</span>
                        <span x-show="isSubmitting">Saving...</span>
                    </x-ui.button>
                    <x-ui.button href="{{ route('forms.index') }}" variant="secondary">
                        Cancel
                    </x-ui.button>
                </div>
            </x-ui.form>
        </x-ui.card>
    </div>
</x-app-layout>

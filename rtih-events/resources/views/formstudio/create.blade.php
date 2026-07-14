<x-app-layout>
    <div class="max-w-2xl mx-auto space-y-6 pt-10">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Create New Form</h1>
            <p class="text-sm text-neutral-500 mt-1">Start by giving your form a name and description.</p>
        </div>

        <x-ui.layout.card padding="lg">
            <form method="POST" action="{{ route('form-studio.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700">Form Name <span class="text-danger-500">*</span></label>
                    <x-ui.forms.input type="text" name="name" id="name" required class="mt-1" placeholder="e.g. Mentor Application 2026" autofocus />
                    @error('name')
                        <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-neutral-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-xl border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm transition-colors" placeholder="Briefly describe what this form is for..."></textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-neutral-100">
                    <x-ui.buttons.button type="submit" variant="primary">Create & Open Builder</x-ui.buttons.button>
                    <x-ui.buttons.button href="{{ route('form-studio.index') }}" variant="ghost">Cancel</x-ui.buttons.button>
                </div>
            </form>
        </x-ui.layout.card>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Form Studio</h1>
                <p class="text-sm text-neutral-500 mt-1">Design dynamic, reusable forms for your events and workflows.</p>
            </div>
            <x-ui.buttons.button variant="primary" href="{{ route('form-studio.create') }}">
                Create New Form
            </x-ui.buttons.button>
        </div>

        <div class="bg-white rounded-2xl shadow-subtle border border-neutral-200 overflow-hidden">
            @if($forms->count() > 0)
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Form Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Version</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Created By</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-neutral-500 uppercase tracking-wider">Created On</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($forms as $form)
                            <x-ui.tables.row>
                                <x-ui.tables.cell primary="true">
                                    <div class="font-semibold text-neutral-900">{{ $form->name }}</div>
                                    <div class="text-sm text-neutral-500">{{ Str::limit($form->description, 50) }}</div>
                                </x-ui.tables.cell>
                                <x-ui.tables.cell>
                                    @php
                                        $statusVariant = match($form->status) {
                                            'published' => 'success',
                                            'archived' => 'neutral',
                                            default => 'warning',
                                        };
                                    @endphp
                                    <x-ui.feedback.badge variant="{{ $statusVariant }}" dot="true">
                                        {{ ucfirst($form->status) }}
                                    </x-ui.feedback.badge>
                                </x-ui.tables.cell>
                                <x-ui.tables.cell>
                                    v{{ $form->latestVersion->version_number ?? 1 }}
                                </x-ui.tables.cell>
                                <x-ui.tables.cell>
                                    <div class="flex items-center gap-2">
                                        <x-ui.feedback.avatar name="{{ $form->creator->name ?? 'Unknown' }}" size="sm" />
                                        <span class="text-sm font-medium text-neutral-700">{{ $form->creator->name ?? 'Unknown' }}</span>
                                    </div>
                                </x-ui.tables.cell>
                                <x-ui.tables.cell>
                                    {{ $form->created_at->format('M j, Y') }}
                                </x-ui.tables.cell>
                                <x-ui.tables.cell actions="true">
                                    <x-ui.buttons.button variant="secondary" size="sm" href="{{ route('form-studio.builder', $form->id) }}">
                                        Open Builder
                                    </x-ui.buttons.button>
                                </x-ui.tables.cell>
                            </x-ui.tables.row>
                        @endforeach
                    </tbody>
                </table>
                @if($forms->hasPages())
                    <div class="px-6 py-4 border-t border-neutral-200 bg-neutral-50/50">
                        {{ $forms->links() }}
                    </div>
                @endif
            @else
                <x-ui.feedback.empty-state 
                    title="No forms found" 
                    description="Create your first dynamic form to start collecting data."
                >
                    <x-slot name="icon">
                        <x-heroicon-o-document-plus class="w-8 h-8" />
                    </x-slot>
                    <x-slot name="action">
                        <x-ui.buttons.button variant="primary" href="{{ route('form-studio.create') }}">Create Form</x-ui.buttons.button>
                    </x-slot>
                </x-ui.feedback.empty-state>
            @endif
        </div>
    </div>
</x-app-layout>

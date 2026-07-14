<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight tracking-tight">
            {{ __('Form Studio Pro') }}
        </h2>
    </x-slot>
    <x-slot name="actions">
        <x-ui.button href="{{ route('form-studio.create') }}" variant="primary" class="shadow-sm">
            <x-heroicon-o-plus class="w-4 h-4 mr-2"/>
            Create New Form
        </x-ui.button>
    </x-slot>

    <div class="space-y-6">
        @if($forms->count() > 0)
            <x-ui.card>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Form Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Version</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created By</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created On</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($forms as $form)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                                <x-heroicon-o-document-duplicate class="w-5 h-5"/>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">{{ $form->name }}</div>
                                                <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($form->description, 40) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($form->status === 'published')
                                            <x-ui.feedback.badge color="emerald">Published</x-ui.feedback.badge>
                                        @elseif($form->status === 'archived')
                                            <x-ui.feedback.badge color="gray">Archived</x-ui.feedback.badge>
                                        @else
                                            <x-ui.feedback.badge color="amber">Draft</x-ui.feedback.badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-800">
                                            v{{ $form->latestVersion->version_number ?? 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-6 w-6 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold mr-2 shadow-sm">
                                                {{ substr($form->creator->name ?? 'U', 0, 1) }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">{{ $form->creator->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $form->created_at->format('M j, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <x-ui.button href="{{ route('form-studio.builder', $form->id) }}" variant="secondary" size="sm">
                                            <x-heroicon-o-wrench-screwdriver class="w-4 h-4 mr-1 text-gray-500"/>
                                            Builder
                                        </x-ui.button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($forms->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $forms->links() }}
                    </div>
                @endif
            </x-ui.card>
        @else
            <!-- Empty State -->
            <x-ui.card class="flex flex-col items-center justify-center p-16 text-center border-dashed">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 shadow-sm border border-indigo-100">
                    <x-heroicon-o-document-plus class="w-8 h-8"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">No forms created yet</h3>
                <p class="text-gray-500 mb-6 max-w-sm mx-auto text-sm">Create your first dynamic form to start collecting data.</p>
                <x-ui.button href="{{ route('form-studio.create') }}" variant="primary" class="shadow-sm">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2"/>
                    Create Form
                </x-ui.button>
            </x-ui.card>
        @endif
    </div>
</x-app-layout>

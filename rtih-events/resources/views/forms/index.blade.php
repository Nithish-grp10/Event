<x-app-layout>
    <x-slot name="header">
        Forms
    </x-slot>
    <x-slot name="actions">
        <x-ui.button href="{{ route('forms.create') }}" variant="primary">
            <x-heroicon-o-plus class="w-4 h-4 mr-2"/>
            New Form
        </x-ui.button>
    </x-slot>

    @if($forms->count() > 0)
        <x-ui.data-table search="false">
            <x-slot name="head">
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Form Title</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fields</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </x-slot>

            @foreach($forms as $form)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 border border-indigo-100">
                                <x-heroicon-o-document-duplicate class="w-5 h-5"/>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $form->title }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($form->description ?? 'No description', 40) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                            {{ $form->fields()->count() }} Fields
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $form->created_at->format('M j, Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $form->created_at->format('g:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <x-ui.button href="{{ route('forms.show', $form) }}" variant="secondary" size="sm">
                            <x-heroicon-o-wrench-screwdriver class="w-4 h-4 mr-1.5 text-gray-400"/>
                            Build / View
                        </x-ui.button>
                        @can('update', $form)
                        <x-ui.button href="{{ route('forms.edit', $form) }}" variant="ghost" size="sm">
                            Edit Meta
                        </x-ui.button>
                        @endcan
                    </td>
                </tr>
            @endforeach

            @if($forms->hasPages())
                <x-slot name="pagination">
                    {{ $forms->links() }}
                </x-slot>
            @endif
        </x-ui.data-table>
    @else
        <!-- Empty State -->
        <x-ui.card class="flex flex-col items-center justify-center p-16 text-center">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 shadow-sm border border-indigo-100">
                <x-heroicon-o-clipboard-document-list class="w-8 h-8"/>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">No forms created yet</h3>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto text-sm">Create an application form to attach to your events for data collection.</p>
            <x-ui.button href="{{ route('forms.create') }}" variant="primary">
                <x-heroicon-o-plus class="w-4 h-4 mr-2"/>
                Create First Form
            </x-ui.button>
        </x-ui.card>
    @endif
</x-app-layout>

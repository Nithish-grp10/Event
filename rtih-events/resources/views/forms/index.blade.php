<x-app-layout>
    <x-slot name="header">
        Forms
    </x-slot>
    <x-slot name="actions">
        <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
            <x-heroicon-o-plus class="w-5 h-5"/>
            New Form
        </a>
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($forms->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-sm text-gray-500 uppercase tracking-wider">
                            <th class="p-4 font-semibold">Title</th>
                            <th class="p-4 font-semibold">Fields</th>
                            <th class="p-4 font-semibold">Created</th>
                            <th class="p-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($forms as $form)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4 font-medium text-gray-900">{{ $form->title }}</td>
                            <td class="p-4 text-gray-700">{{ $form->fields()->count() }} Fields</td>
                            <td class="p-4 text-gray-700">{{ $form->created_at->format('M d, Y') }}</td>
                            <td class="p-4 text-right space-x-3">
                                <a href="{{ route('forms.show', $form) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">View/Build</a>
                                @can('update', $form)
                                <a href="{{ route('forms.edit', $form) }}" class="text-gray-600 hover:text-gray-900 font-medium text-sm">Edit</a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($forms->hasPages())
                <div class="p-4 border-t border-gray-200 bg-gray-50">
                    {{ $forms->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4 text-gray-400">
                    <x-heroicon-o-clipboard-document-list class="w-8 h-8"/>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No forms yet</h3>
                <p class="text-gray-500 mb-6 max-w-sm mx-auto">Create a form to attach to your events for data collection.</p>
                <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    <x-heroicon-o-plus class="w-5 h-5"/>
                    Create First Form
                </a>
            </div>
        @endif
    </div>
</x-app-layout>

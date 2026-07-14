<x-app-layout>
    <x-slot name="header">
        Form Preview
    </x-slot>
    <x-slot name="actions">
        <div class="flex items-center gap-3">
            <x-ui.button href="{{ route('forms.index') }}" variant="secondary">
                <x-heroicon-o-arrow-left class="w-4 h-4 mr-2"/> Back
            </x-ui.button>
            <x-ui.button href="{{ route('forms.edit', $form) }}" variant="primary">
                <x-heroicon-o-pencil class="w-4 h-4 mr-2"/> Edit Form
            </x-ui.button>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto pb-12">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden relative mb-6">
            <div class="h-3 w-full bg-indigo-600"></div>
            <div class="p-8 border-b border-gray-100">
                <h3 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $form->title }}</h3>
                @if($form->description)
                    <p class="text-gray-600 mt-3 text-sm leading-relaxed">{{ $form->description }}</p>
                @endif
                <p class="text-xs text-red-500 font-medium mt-4">* Indicates required question</p>
            </div>
            
            <div class="p-8 space-y-8 bg-gray-50/30">
                @forelse($form->fields()->orderBy('sort_order')->get() as $field)
                    <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-sm">
                        <label class="block text-base font-medium text-gray-900 mb-4">
                            {{ $field->label }}
                            @if($field->is_required)
                                <span class="text-red-500 ml-1">*</span>
                            @endif
                        </label>
                        
                        <!-- Preview Render -->
                        @if($field->field_type === 'text' || $field->field_type === 'email')
                            <input type="text" class="block w-full sm:w-1/2 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-500 bg-gray-50" placeholder="Your answer" disabled>
                        @elseif($field->field_type === 'textarea')
                            <textarea class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-500 bg-gray-50" rows="3" placeholder="Your answer" disabled></textarea>
                        @elseif($field->field_type === 'file')
                            <div class="flex items-center gap-2 text-indigo-600 bg-indigo-50 px-4 py-2 rounded-lg border border-indigo-100 w-max text-sm font-medium"><x-heroicon-o-cloud-arrow-up class="w-5 h-5"/> Upload File</div>
                        @elseif($field->field_type === 'select')
                            <select class="block w-full sm:w-1/2 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-gray-500 bg-gray-50" disabled>
                                <option>Choose</option>
                                @if($field->options)
                                    @foreach($field->options as $option)
                                        <option>{{ $option }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @elseif(in_array($field->field_type, ['checkbox', 'radio']) && $field->options)
                            <div class="space-y-3">
                                @foreach($field->options as $option)
                                    <div class="flex items-center">
                                        <input type="{{ $field->field_type }}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 {{ $field->field_type === 'radio' ? 'rounded-full' : 'rounded' }}" disabled>
                                        <label class="ml-3 block text-sm font-medium text-gray-700">
                                            {{ $option }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-xl border border-gray-200 border-dashed">
                        <x-heroicon-o-document-minus class="mx-auto h-12 w-12 text-gray-300 mb-3"/>
                        <p class="text-sm text-gray-500 font-medium">No fields have been added to this form yet.</p>
                        <x-ui.button href="{{ route('forms.edit', $form) }}" variant="secondary" class="mt-4">
                            Go to Builder
                        </x-ui.button>
                    </div>
                @endforelse

                @if($form->fields()->count() > 0)
                <div class="pt-4">
                    <x-ui.button type="button" variant="primary" class="w-32" disabled>
                        Submit
                    </x-ui.button>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

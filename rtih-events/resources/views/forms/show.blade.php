<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Form Preview') }}
            </h2>
            <a href="{{ route('forms.index') }}" class="text-blue-600 hover:underline">Back to Forms</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-2xl font-bold">{{ $form->title }}</h3>
                        @if($form->description)
                            <p class="text-gray-600 mt-2">{{ $form->description }}</p>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <h4 class="text-lg font-semibold text-gray-700">Fields Preview</h4>
                        
                        @forelse($form->fields()->orderBy('sort_order')->get() as $field)
                            <div class="p-4 border rounded-md bg-gray-50">
                                <label class="block font-medium text-gray-800">
                                    {{ $field->label }}
                                    @if($field->is_required)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                
                                <div class="mt-2 text-sm text-gray-500">
                                    Type: <span class="font-mono bg-gray-200 px-1 rounded">{{ $field->field_type }}</span>
                                </div>
                                
                                @if(in_array($field->field_type, ['select', 'checkbox', 'radio']) && $field->options)
                                    <div class="mt-2 text-sm">
                                        <span class="text-gray-500">Options:</span>
                                        <ul class="list-disc list-inside ml-2">
                                            @foreach($field->options as $option)
                                                <li>{{ $option }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-500 italic">No fields have been added to this form yet.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

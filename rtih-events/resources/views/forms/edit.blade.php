<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Form Builder') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0ebf8] min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('forms.update', $form) }}" id="form-builder" x-data="formBuilder({{ json_encode(old('fields', $form->fields->map(function($field) {
                    return [
                        'label' => $field->label,
                        'field_type' => $field->field_type,
                        'options' => $field->options ? implode(\"\n\", $field->options) : '',
                        'is_required' => $field->is_required ? 1 : 0,
                    ];
                })->toArray())) }})">
                @csrf
                @method('PUT')

                <!-- Floating Save Button -->
                <div class="fixed bottom-6 right-6 z-50 flex gap-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-full shadow-lg flex items-center gap-2">
                        <x-heroicon-o-check class="w-5 h-5"/> Save Form
                    </button>
                </div>
                
                <!-- Floating Action Bar (Google Forms style) -->
                <div class="fixed top-1/2 -translate-y-1/2 right-[calc(50%-24rem)] bg-white rounded-lg shadow-md border border-gray-200 p-2 flex flex-col gap-3 z-40 hidden xl:flex">
                    <button type="button" @click="addField('text')" class="p-2 text-gray-500 hover:bg-gray-100 rounded-full tooltip-btn" title="Add short answer">
                        <x-heroicon-o-plus-circle class="w-6 h-6"/>
                    </button>
                    <button type="button" @click="addField('textarea')" class="p-2 text-gray-500 hover:bg-gray-100 rounded-full tooltip-btn" title="Add paragraph">
                        <x-heroicon-o-bars-3-bottom-left class="w-6 h-6"/>
                    </button>
                    <button type="button" @click="addField('radio')" class="p-2 text-gray-500 hover:bg-gray-100 rounded-full tooltip-btn" title="Add multiple choice">
                        <x-heroicon-o-check-circle class="w-6 h-6"/>
                    </button>
                    <button type="button" @click="addField('file')" class="p-2 text-gray-500 hover:bg-gray-100 rounded-full tooltip-btn" title="Add file upload">
                        <x-heroicon-o-arrow-up-tray class="w-6 h-6"/>
                    </button>
                </div>

                <!-- Form Header Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-4 relative" @click="activeField = -1" :class="{ 'ring-2 ring-indigo-500 ring-offset-2': activeField === -1 }">
                    <div class="h-3 w-full bg-indigo-600"></div>
                    <div class="p-6">
                        <input type="text" name="title" value="{{ old('title', $form->title) }}" class="w-full text-3xl font-normal text-gray-900 border-b border-transparent hover:border-gray-300 focus:border-indigo-500 focus:ring-0 px-0 pb-2 mb-2 transition-colors placeholder-gray-400" placeholder="Form title" required>
                        <textarea name="description" rows="1" class="w-full text-sm text-gray-600 border-b border-transparent hover:border-gray-300 focus:border-indigo-500 focus:ring-0 px-0 pb-1 resize-none placeholder-gray-400" placeholder="Form description">{{ old('description', $form->description) }}</textarea>
                    </div>
                </div>

                <!-- Fields Array -->
                <div class="space-y-4 pb-20">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 relative transition-shadow" 
                            @click="activeField = index"
                            :class="{ 'ring-1 ring-indigo-500 shadow-md border-indigo-500': activeField === index }">
                            
                            <!-- Drag handle (visual only) -->
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-6 h-4 cursor-move flex justify-center opacity-30 hover:opacity-100" @click.stop="activeField = index">
                                <svg width="24" height="12" viewBox="0 0 24 12" fill="currentColor" class="text-gray-400"><path d="M8 3c0-1.1-.9-2-2-2S4 1.9 4 3s.9 2 2 2 2-.9 2-2zm8 0c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2zM8 9c0-1.1-.9-2-2-2S4 7.9 4 9s.9 2 2 2 2-.9 2-2zm8 0c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2z"></path></svg>
                            </div>

                            <input type="hidden" :name="`fields[${index}][sort_order]`" :value="index">

                            <!-- Active View (Edit Mode) -->
                            <div x-show="activeField === index">
                                <div class="flex gap-4 mb-4">
                                    <div class="flex-grow">
                                        <input type="text" x-model="field.label" :name="`fields[${index}][label]`" class="w-full text-base font-medium text-gray-900 border border-transparent border-b-gray-300 bg-gray-50 focus:bg-gray-100 focus:border-indigo-500 focus:ring-0 px-4 py-3 rounded-t-md transition-colors placeholder-gray-500" placeholder="Question" required>
                                    </div>
                                    <div class="w-64 shrink-0">
                                        <select x-model="field.field_type" :name="`fields[${index}][field_type]`" class="w-full border-gray-300 text-sm rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3">
                                            <option value="text">Short answer</option>
                                            <option value="textarea">Paragraph</option>
                                            <option value="radio">Multiple choice</option>
                                            <option value="checkbox">Checkboxes</option>
                                            <option value="select">Dropdown</option>
                                            <option value="file">File upload</option>
                                            <option value="email">Email address</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Field type specific preview -->
                                <div class="mb-6 ml-2">
                                    <div x-show="field.field_type === 'text' || field.field_type === 'email'" class="text-gray-400 border-b border-gray-200 border-dotted w-1/2 pb-1">Short answer text</div>
                                    <div x-show="field.field_type === 'textarea'" class="text-gray-400 border-b border-gray-200 border-dotted w-full pb-1">Long answer text</div>
                                    <div x-show="field.field_type === 'file'" class="flex items-center gap-2 text-gray-500 bg-gray-50 px-4 py-2 rounded-md border border-gray-200 w-max"><x-heroicon-o-cloud-arrow-up class="w-5 h-5"/> File upload</div>
                                    
                                    <div x-show="['select', 'checkbox', 'radio'].includes(field.field_type)">
                                        <textarea x-model="field.options" :name="`fields[${index}][options]`" rows="4" class="w-full text-sm rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Enter options here (one per line)"></textarea>
                                    </div>
                                </div>

                                <!-- Toolbar footer -->
                                <div class="flex items-center justify-end gap-4 border-t pt-4">
                                    <button type="button" @click="moveUp(index)" :disabled="index === 0" class="p-2 text-gray-400 hover:text-gray-700 disabled:opacity-20"><x-heroicon-o-arrow-up class="w-5 h-5"/></button>
                                    <button type="button" @click="moveDown(index)" :disabled="index === fields.length - 1" class="p-2 text-gray-400 hover:text-gray-700 disabled:opacity-20"><x-heroicon-o-arrow-down class="w-5 h-5"/></button>
                                    <div class="h-6 w-px bg-gray-300"></div>
                                    <button type="button" @click="removeField(index)" class="p-2 text-gray-400 hover:text-red-600"><x-heroicon-o-trash class="w-5 h-5"/></button>
                                    <div class="h-6 w-px bg-gray-300"></div>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <span class="text-sm text-gray-600 font-medium">Required</span>
                                        <input type="hidden" :name="`fields[${index}][is_required]`" value="0">
                                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input type="checkbox" x-model="field.is_required" :name="`fields[${index}][is_required]`" value="1" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer focus:outline-none focus:ring-0 border-gray-300 checked:right-0 checked:border-indigo-600"/>
                                            <label class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"></label>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Inactive View (Preview Mode) -->
                            <div x-show="activeField !== index">
                                <div class="flex items-center gap-2 mb-4">
                                    <h4 class="text-base font-medium text-gray-900" x-text="field.label || 'Untitled Question'"></h4>
                                    <span x-show="field.is_required" class="text-red-500">*</span>
                                </div>
                                <div class="ml-2">
                                    <div x-show="field.field_type === 'text' || field.field_type === 'email'" class="text-gray-400 border-b border-gray-200 w-1/2 pb-1 text-sm">Short answer text</div>
                                    <div x-show="field.field_type === 'textarea'" class="text-gray-400 border-b border-gray-200 w-full pb-1 text-sm">Long answer text</div>
                                    <div x-show="field.field_type === 'file'" class="flex items-center gap-2 text-gray-500 bg-gray-50 px-4 py-2 rounded-md border border-gray-200 w-max text-sm"><x-heroicon-o-cloud-arrow-up class="w-4 h-4"/> File upload</div>
                                    
                                    <div x-show="['select', 'checkbox', 'radio'].includes(field.field_type)" class="text-gray-500 text-sm space-y-2">
                                        <template x-for="option in field.options.split('\n').filter(o => o.trim())">
                                            <div class="flex items-center gap-3">
                                                <div class="w-4 h-4 border border-gray-300 rounded-sm" :class="field.field_type === 'radio' ? 'rounded-full' : ''"></div>
                                                <span x-text="option"></span>
                                            </div>
                                        </template>
                                        <div x-show="!field.options.trim()" class="text-gray-400 italic">No options defined</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </template>
                </div>
                
                <!-- Mobile Add Field Button -->
                <div class="xl:hidden flex justify-center mb-10">
                    <button type="button" @click="addField('text')" class="bg-white border border-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md shadow-sm flex items-center gap-2 hover:bg-gray-50">
                        <x-heroicon-o-plus class="w-5 h-5"/> Add Question
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine Logic -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formBuilder', (initialFields) => ({
                fields: initialFields.length ? initialFields : [{
                    label: 'Untitled Question',
                    field_type: 'text',
                    options: '',
                    is_required: 0
                }],
                activeField: initialFields.length ? -1 : 0,
                
                addField(type = 'text') {
                    this.fields.push({
                        label: 'Untitled Question',
                        field_type: type,
                        options: '',
                        is_required: 0
                    });
                    this.activeField = this.fields.length - 1;
                },
                
                removeField(index) {
                    this.fields.splice(index, 1);
                    if (this.activeField === index) this.activeField = -1;
                    else if (this.activeField > index) this.activeField--;
                },
                
                moveUp(index) {
                    if (index > 0) {
                        const temp = this.fields[index];
                        this.fields[index] = this.fields[index - 1];
                        this.fields[index - 1] = temp;
                        if (this.activeField === index) this.activeField--;
                    }
                },
                
                moveDown(index) {
                    if (index < this.fields.length - 1) {
                        const temp = this.fields[index];
                        this.fields[index] = this.fields[index + 1];
                        this.fields[index + 1] = temp;
                        if (this.activeField === index) this.activeField++;
                    }
                }
            }));
        });
    </script>
    <style>
        .toggle-checkbox:checked {
            right: 0;
            border-color: #4F46E5;
        }
        .toggle-checkbox:checked + .toggle-label {
            background-color: #818CF8;
        }
        .toggle-checkbox {
            right: 20px;
            z-index: 1;
            transition: all 0.2s;
        }
        .toggle-label {
            width: 40px;
            transition: all 0.2s;
        }
    </style>
</x-app-layout>

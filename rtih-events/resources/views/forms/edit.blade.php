<x-app-layout>
    <x-slot name="header">
        Form Builder
    </x-slot>

    <div class="max-w-4xl mx-auto pb-24">
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
            <div class="fixed bottom-6 right-6 z-50">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-full shadow-lg flex items-center gap-2 transition-transform hover:scale-105 active:scale-95 focus:ring-4 focus:ring-indigo-500/30">
                    <x-heroicon-o-check class="w-5 h-5"/> Save Form
                </button>
            </div>
            
            <!-- Floating Action Bar (Google Forms style) -->
            <div class="fixed top-1/2 -translate-y-1/2 right-[calc(50%-28rem)] bg-white rounded-xl shadow-lg border border-gray-100 p-2 flex flex-col gap-2 z-40 hidden xl:flex">
                <button type="button" @click="addField('text')" class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Add short answer">
                    <x-heroicon-o-plus-circle class="w-6 h-6"/>
                </button>
                <button type="button" @click="addField('textarea')" class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Add paragraph">
                    <x-heroicon-o-bars-3-bottom-left class="w-6 h-6"/>
                </button>
                <button type="button" @click="addField('radio')" class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Add multiple choice">
                    <x-heroicon-o-check-circle class="w-6 h-6"/>
                </button>
                <button type="button" @click="addField('file')" class="p-2.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Add file upload">
                    <x-heroicon-o-arrow-up-tray class="w-6 h-6"/>
                </button>
            </div>

            <!-- Form Header Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 overflow-hidden mb-6 relative cursor-pointer transition-shadow" @click="activeField = -1" :class="{ 'ring-2 ring-indigo-500 border-transparent shadow-md': activeField === -1 }">
                <div class="h-3 w-full bg-indigo-600"></div>
                <div class="p-8">
                    <input type="text" name="title" value="{{ old('title', $form->title) }}" class="w-full text-3xl font-semibold text-gray-900 border-b-2 border-transparent hover:border-gray-200 focus:border-indigo-500 focus:ring-0 px-0 pb-2 mb-3 transition-colors placeholder-gray-300 bg-transparent" placeholder="Form title" required>
                    <textarea name="description" rows="2" class="w-full text-sm text-gray-600 border-b-2 border-transparent hover:border-gray-200 focus:border-indigo-500 focus:ring-0 px-0 pb-2 resize-none placeholder-gray-400 bg-transparent" placeholder="Form description">{{ old('description', $form->description) }}</textarea>
                </div>
            </div>

            <!-- Fields Array -->
            <div class="space-y-4">
                <template x-for="(field, index) in fields" :key="index">
                    <div class="bg-white rounded-xl shadow-sm border p-6 relative transition-all duration-200 cursor-pointer" 
                        @click="activeField = index"
                        :class="activeField === index ? 'border-l-4 border-l-indigo-500 border-y-transparent border-r-transparent shadow-md ring-1 ring-black/5 bg-white' : 'border-gray-200 hover:border-gray-300 bg-gray-50/30'">
                        
                        <!-- Drag handle (visual only) -->
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-6 flex justify-center items-center opacity-30 hover:opacity-100 transition-opacity">
                            <x-heroicon-s-bars-2 class="w-5 h-5 text-gray-400"/>
                        </div>

                        <input type="hidden" :name="`fields[${index}][sort_order]`" :value="index">

                        <!-- Active View (Edit Mode) -->
                        <div x-show="activeField === index" x-cloak>
                            <div class="flex flex-col sm:flex-row gap-4 mb-6 mt-2">
                                <div class="flex-grow">
                                    <input type="text" x-model="field.label" :name="`fields[${index}][label]`" class="w-full text-base font-medium text-gray-900 border-b-2 border-transparent hover:border-gray-200 focus:border-indigo-500 bg-gray-50 focus:bg-gray-100 focus:ring-0 px-4 py-3 rounded-t-md transition-colors placeholder-gray-400" placeholder="Question" required>
                                </div>
                                <div class="sm:w-64 shrink-0">
                                    <select x-model="field.field_type" :name="`fields[${index}][field_type]`" class="w-full border-gray-200 text-sm rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-3 bg-white">
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
                            <div class="mb-8 px-4">
                                <div x-show="field.field_type === 'text' || field.field_type === 'email'" class="text-gray-400 border-b-2 border-gray-200 border-dotted w-1/2 pb-1 text-sm">Short answer text</div>
                                <div x-show="field.field_type === 'textarea'" class="text-gray-400 border-b-2 border-gray-200 border-dotted w-full pb-1 text-sm">Long answer text</div>
                                <div x-show="field.field_type === 'file'" class="flex items-center gap-2 text-gray-500 bg-white px-4 py-3 rounded-lg border border-gray-200 w-max text-sm shadow-sm"><x-heroicon-o-cloud-arrow-up class="w-5 h-5"/> File upload area</div>
                                
                                <div x-show="['select', 'checkbox', 'radio'].includes(field.field_type)">
                                    <textarea x-model="field.options" :name="`fields[${index}][options]`" rows="4" class="w-full text-sm rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm" placeholder="Enter options here (one option per line)"></textarea>
                                </div>
                            </div>

                            <!-- Toolbar footer -->
                            <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                                <button type="button" @click="moveUp(index)" :disabled="index === 0" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg disabled:opacity-30 disabled:hover:bg-transparent transition-colors" title="Move Up"><x-heroicon-o-arrow-up class="w-5 h-5"/></button>
                                <button type="button" @click="moveDown(index)" :disabled="index === fields.length - 1" class="p-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg disabled:opacity-30 disabled:hover:bg-transparent transition-colors" title="Move Down"><x-heroicon-o-arrow-down class="w-5 h-5"/></button>
                                <div class="h-6 w-px bg-gray-200 mx-1"></div>
                                <button type="button" @click="removeField(index)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Question"><x-heroicon-o-trash class="w-5 h-5"/></button>
                                <div class="h-6 w-px bg-gray-200 mx-1"></div>
                                <label class="flex items-center gap-3 cursor-pointer p-2 hover:bg-gray-50 rounded-lg transition-colors">
                                    <span class="text-sm text-gray-700 font-medium select-none">Required</span>
                                    <input type="hidden" :name="`fields[${index}][is_required]`" value="0">
                                    <div class="relative inline-block w-10 align-middle select-none">
                                        <input type="checkbox" x-model="field.is_required" :name="`fields[${index}][is_required]`" value="1" class="toggle-checkbox absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer focus:outline-none focus:ring-0 border-gray-300 checked:right-0 checked:border-indigo-600"/>
                                        <label class="toggle-label block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"></label>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Inactive View (Preview Mode) -->
                        <div x-show="activeField !== index" class="mt-2">
                            <div class="flex items-center gap-2 mb-5">
                                <h4 class="text-base font-medium text-gray-900" x-text="field.label || 'Untitled Question'"></h4>
                                <span x-show="field.is_required" class="text-red-500 font-bold">*</span>
                            </div>
                            <div class="px-2">
                                <div x-show="field.field_type === 'text' || field.field_type === 'email'" class="text-gray-400 border-b border-gray-200 w-1/2 pb-1 text-sm">Short answer text</div>
                                <div x-show="field.field_type === 'textarea'" class="text-gray-400 border-b border-gray-200 w-full pb-1 text-sm">Long answer text</div>
                                <div x-show="field.field_type === 'file'" class="flex items-center gap-2 text-gray-500 bg-white px-4 py-2 rounded-lg border border-gray-200 w-max text-sm shadow-sm"><x-heroicon-o-cloud-arrow-up class="w-4 h-4"/> File upload</div>
                                
                                <div x-show="['select', 'checkbox', 'radio'].includes(field.field_type)" class="text-gray-600 text-sm space-y-3">
                                    <template x-for="option in field.options.split('\n').filter(o => o.trim())">
                                        <div class="flex items-center gap-3">
                                            <div class="w-4 h-4 border border-gray-300 flex-shrink-0" :class="field.field_type === 'radio' ? 'rounded-full' : 'rounded-sm'"></div>
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
            <div class="xl:hidden flex justify-center mt-6">
                <x-ui.button type="button" @click="addField('text')" variant="secondary" class="shadow-sm">
                    <x-heroicon-o-plus class="w-5 h-5 mr-2 text-indigo-500"/> Add Question
                </x-ui.button>
            </div>
        </form>
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
                    this.$nextTick(() => {
                        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                    });
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

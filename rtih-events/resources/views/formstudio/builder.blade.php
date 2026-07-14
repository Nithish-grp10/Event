<x-app-layout>
    <!-- Alpine JS Form Builder Application -->
    <div x-data="formBuilder(@js($form->id), @js($schema))" class="flex flex-col h-[calc(100vh-4rem)] -mt-8 -mx-4 sm:-mx-6 lg:-mx-8 overflow-hidden bg-neutral-100">
        
        <!-- Header -->
        <header class="bg-white border-b border-neutral-200 px-6 py-3 flex justify-between items-center z-10 shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('form-studio.index') }}" class="text-neutral-400 hover:text-neutral-600 transition-colors">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h1 class="text-lg font-bold text-neutral-900">{{ $form->name }}</h1>
                    <div class="text-xs text-neutral-500 flex items-center gap-2">
                        <span>Version {{ $version->version_number }}</span>
                        <span x-text="saveStatus" class="italic"></span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.buttons.button variant="secondary" size="sm" @click="saveDraft" x-bind:disabled="isSaving">
                    <span x-show="!isSaving">Save Draft</span>
                    <span x-show="isSaving">Saving...</span>
                </x-ui.buttons.button>
                <x-ui.buttons.button variant="primary" size="sm" @click="publish">
                    Publish
                </x-ui.buttons.button>
            </div>
        </header>

        <!-- 3-Panel Workspace -->
        <div class="flex flex-1 overflow-hidden">
            
            <!-- LEFT PANEL: Components (Drag Source) -->
            <aside class="w-64 bg-white border-r border-neutral-200 flex flex-col z-10 shrink-0">
                <div class="p-4 border-b border-neutral-100">
                    <h2 class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Form Elements</h2>
                </div>
                <div class="flex-1 overflow-y-auto p-4 space-y-2" id="components-list">
                    <!-- Standard Fields -->
                    <template x-for="component in availableComponents" :key="component.type">
                        <div class="flex items-center gap-3 p-3 bg-neutral-50 rounded-xl border border-neutral-200 cursor-move hover:border-primary-300 hover:shadow-subtle transition-all builder-component" :data-type="component.type">
                            <div class="text-neutral-400" x-html="component.icon"></div>
                            <span class="text-sm font-medium text-neutral-700" x-text="component.label"></span>
                        </div>
                    </template>
                </div>
            </aside>

            <!-- CENTER PANEL: Canvas (Drop Target) -->
            <main class="flex-1 overflow-y-auto p-8 flex justify-center">
                <div class="w-full max-w-3xl">
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-200 p-8 min-h-[500px]">
                        <!-- Form Title (Readonly in canvas) -->
                        <div class="mb-8 border-b border-neutral-100 pb-6">
                            <h2 class="text-2xl font-bold text-neutral-900">{{ $form->name }}</h2>
                            <p class="text-neutral-500 mt-2">{{ $form->description }}</p>
                        </div>

                        <!-- Canvas area -->
                        <div id="form-canvas" class="space-y-4 min-h-[300px]">
                            <!-- Sortable items will be rendered here -->
                            <template x-for="(field, index) in schema.fields" :key="field.id">
                                <div 
                                    class="group relative p-5 bg-white rounded-xl border-2 transition-all cursor-pointer"
                                    :class="selectedFieldId === field.id ? 'border-primary-500 shadow-subtle' : 'border-transparent hover:border-neutral-200'"
                                    @click="selectField(field.id)"
                                    :data-id="field.id"
                                >
                                    <!-- Drag Handle -->
                                    <div class="absolute left-0 top-1/2 -translate-y-1/2 -ml-3 opacity-0 group-hover:opacity-100 cursor-move p-1 text-neutral-400 hover:text-neutral-600 transition-opacity drag-handle">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                    </div>

                                    <!-- Delete Button -->
                                    <button @click.stop="deleteField(field.id)" class="absolute right-3 top-3 opacity-0 group-hover:opacity-100 text-neutral-400 hover:text-danger-500 transition-opacity p-1">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                    </button>

                                    <!-- Field Preview -->
                                    <div class="pointer-events-none">
                                        <label class="block text-sm font-semibold text-neutral-900 mb-1">
                                            <span x-text="field.label"></span>
                                            <span x-show="field.is_required" class="text-danger-500">*</span>
                                        </label>
                                        <p x-show="field.help_text" class="text-xs text-neutral-500 mb-2" x-text="field.help_text"></p>
                                        
                                        <!-- Mock Inputs based on type -->
                                        <template x-if="field.type === 'text'">
                                            <input type="text" disabled class="block w-full rounded-lg border-neutral-300 bg-neutral-50 sm:text-sm">
                                        </template>
                                        <template x-if="field.type === 'textarea'">
                                            <textarea rows="3" disabled class="block w-full rounded-lg border-neutral-300 bg-neutral-50 sm:text-sm"></textarea>
                                        </template>
                                        <template x-if="field.type === 'select'">
                                            <select disabled class="block w-full rounded-lg border-neutral-300 bg-neutral-50 sm:text-sm">
                                                <option>Select an option...</option>
                                            </select>
                                        </template>
                                        <template x-if="['radio', 'checkbox'].includes(field.type)">
                                            <div class="space-y-2">
                                                <template x-for="opt in field.options">
                                                    <div class="flex items-center gap-2">
                                                        <input :type="field.type" disabled class="rounded-sm border-neutral-300">
                                                        <span class="text-sm text-neutral-700" x-text="opt.label"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Empty State for Canvas -->
                            <div x-show="schema.fields.length === 0" class="text-center py-12 border-2 border-dashed border-neutral-200 rounded-xl">
                                <div class="text-neutral-400 mb-2"><x-heroicon-o-plus-circle class="w-8 h-8 mx-auto" /></div>
                                <h3 class="text-sm font-medium text-neutral-900">No fields yet</h3>
                                <p class="text-xs text-neutral-500 mt-1">Drag components from the left panel to build your form.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- RIGHT PANEL: Properties -->
            <aside class="w-80 bg-white border-l border-neutral-200 flex flex-col z-10 shrink-0">
                <div class="p-4 border-b border-neutral-100 flex justify-between items-center">
                    <h2 class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Field Properties</h2>
                </div>
                
                <div class="flex-1 overflow-y-auto p-5">
                    <template x-if="selectedField">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Field Label</label>
                                <x-ui.forms.input type="text" x-model="selectedField.label" @input="debouncedSave" />
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-neutral-500 uppercase tracking-wider mb-1">Help Text (Optional)</label>
                                <textarea x-model="selectedField.help_text" @input="debouncedSave" rows="2" class="block w-full rounded-lg border-neutral-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
                            </div>

                            <div class="flex items-center justify-between py-2 border-y border-neutral-100">
                                <label class="text-sm font-medium text-neutral-700">Required Field</label>
                                <input type="checkbox" x-model="selectedField.is_required" @change="saveDraft" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500 h-4 w-4">
                            </div>

                            <!-- Options Editor for Select/Radio/Checkbox -->
                            <template x-if="['select', 'radio', 'checkbox'].includes(selectedField.type)">
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 uppercase tracking-wider mb-2">Options</label>
                                    <div class="space-y-2">
                                        <template x-for="(opt, optIndex) in selectedField.options" :key="optIndex">
                                            <div class="flex items-center gap-2">
                                                <x-ui.forms.input type="text" x-model="opt.label" @input="debouncedSave" class="flex-1 !py-1 !text-sm" />
                                                <button @click="removeOption(optIndex)" class="text-neutral-400 hover:text-danger-500 p-1">
                                                    <x-heroicon-o-x-mark class="w-4 h-4" />
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <x-ui.buttons.button variant="outline" size="sm" class="mt-3 w-full" @click="addOption">
                                        <x-slot name="iconLeft"><x-heroicon-o-plus /></x-slot>
                                        Add Option
                                    </x-ui.buttons.button>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="!selectedField">
                        <div class="text-center py-12 text-neutral-400">
                            <x-heroicon-o-cursor-arrow-rays class="w-8 h-8 mx-auto mb-2" />
                            <p class="text-sm">Select a field on the canvas to edit its properties.</p>
                        </div>
                    </template>
                </div>
            </aside>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formBuilder', (formId, initialSchema) => ({
                formId: formId,
                schema: initialSchema?.fields ? initialSchema : { fields: [] },
                selectedFieldId: null,
                isSaving: false,
                saveStatus: '',
                saveTimeout: null,

                availableComponents: [
                    { type: 'text', label: 'Short Text', icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>' },
                    { type: 'textarea', label: 'Paragraph', icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>' },
                    { type: 'select', label: 'Dropdown', icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>' },
                    { type: 'radio', label: 'Single Choice', icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' },
                    { type: 'checkbox', label: 'Multiple Choice', icon: '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>' },
                ],

                init() {
                    this.initSortable();
                    // Ensure basic structure exists if empty
                    if (!this.schema.fields) {
                        this.schema.fields = [];
                    }
                },

                get selectedField() {
                    if (!this.selectedFieldId) return null;
                    return this.schema.fields.find(f => f.id === this.selectedFieldId);
                },

                selectField(id) {
                    this.selectedFieldId = id;
                },

                deleteField(id) {
                    this.schema.fields = this.schema.fields.filter(f => f.id !== id);
                    if (this.selectedFieldId === id) {
                        this.selectedFieldId = null;
                    }
                    this.saveDraft();
                },

                addOption() {
                    if (this.selectedField) {
                        if (!this.selectedField.options) this.selectedField.options = [];
                        this.selectedField.options.push({ label: 'New Option', value: 'New Option' });
                        this.saveDraft();
                    }
                },

                removeOption(index) {
                    if (this.selectedField) {
                        this.selectedField.options.splice(index, 1);
                        this.saveDraft();
                    }
                },

                generateId() {
                    return 'f_' + Math.random().toString(36).substr(2, 9);
                },

                initSortable() {
                    // Left Panel (Clone Source)
                    new Sortable(document.getElementById('components-list'), {
                        group: {
                            name: 'shared',
                            pull: 'clone',
                            put: false 
                        },
                        sort: false,
                        animation: 150
                    });

                    // Canvas (Drop Target)
                    new Sortable(document.getElementById('form-canvas'), {
                        group: 'shared',
                        animation: 150,
                        handle: '.drag-handle',
                        onAdd: (evt) => {
                            const type = evt.item.getAttribute('data-type');
                            evt.item.remove(); // Remove the DOM element Sortable added

                            const newField = {
                                id: this.generateId(),
                                type: type,
                                label: 'New ' + type,
                                is_required: false,
                                help_text: ''
                            };

                            if (['select', 'radio', 'checkbox'].includes(type)) {
                                newField.options = [
                                    { label: 'Option 1', value: 'Option 1' },
                                    { label: 'Option 2', value: 'Option 2' },
                                ];
                            }

                            // Insert into Alpine data at the dropped index
                            this.schema.fields.splice(evt.newIndex, 0, newField);
                            this.selectField(newField.id);
                            this.saveDraft();
                        },
                        onUpdate: (evt) => {
                            // Reorder within canvas
                            const item = this.schema.fields.splice(evt.oldIndex, 1)[0];
                            this.schema.fields.splice(evt.newIndex, 0, item);
                            this.saveDraft();
                        }
                    });
                },

                debouncedSave() {
                    clearTimeout(this.saveTimeout);
                    this.saveStatus = 'Unsaved changes...';
                    this.saveTimeout = setTimeout(() => {
                        this.saveDraft();
                    }, 1000);
                },

                saveDraft() {
                    this.isSaving = true;
                    this.saveStatus = 'Saving...';
                    
                    // We structure it properly with 'sections' as per backend expectation
                    const payload = {
                        schema: {
                            sections: [
                                {
                                    title: "Default Section",
                                    fields: this.schema.fields
                                }
                            ]
                        }
                    };

                    fetch(`/form-studio/${this.formId}/draft`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isSaving = false;
                        this.saveStatus = 'Saved ' + new Date().toLocaleTimeString();
                    })
                    .catch(err => {
                        this.isSaving = false;
                        this.saveStatus = 'Error saving';
                        console.error(err);
                    });
                },

                publish() {
                    if (confirm('Are you sure you want to publish this form? This will lock the schema structure for responses.')) {
                        this.isSaving = true;
                        fetch(`/form-studio/${this.formId}/publish`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if(data.success) {
                                alert('Form published successfully!');
                                window.location.href = "{{ route('form-studio.index') }}";
                            } else {
                                alert(data.message || 'Error publishing');
                                this.isSaving = false;
                            }
                        })
                        .catch(err => {
                            this.isSaving = false;
                            alert('Error publishing');
                        });
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>

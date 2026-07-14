<x-app-layout>
    <div x-data="formBuilder(@js($form->id), @js($schema))" class="flex flex-col h-[calc(100vh-4rem)] -mt-8 -mx-4 sm:-mx-6 lg:-mx-8 overflow-hidden bg-gray-50 text-sm font-sans">
        
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-6 py-3 flex justify-between items-center z-20 shrink-0 shadow-sm relative">
            <div class="flex items-center gap-4">
                <a href="{{ route('form-studio.index') }}" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <x-heroicon-o-arrow-left class="w-5 h-5" />
                </a>
                <div>
                    <h1 class="text-base font-bold text-gray-900 tracking-tight">{{ $form->name }}</h1>
                    <div class="text-xs text-gray-500 flex items-center gap-2 mt-0.5">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">v{{ $version->version_number }}</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span x-text="saveStatus" class="font-medium flex items-center gap-1"></span>
                    </div>
                </div>
            </div>

            <!-- Toolbar (Undo/Redo, Preview) -->
            <div class="flex items-center gap-1 bg-gray-50 p-1 rounded-lg border border-gray-200">
                <button @click="undo()" :disabled="!canUndo" class="p-1.5 rounded-md text-gray-500 hover:text-gray-900 hover:bg-white hover:shadow-sm disabled:opacity-50 disabled:hover:bg-transparent disabled:hover:shadow-none transition-all" title="Undo">
                    <x-heroicon-o-arrow-uturn-left class="w-4 h-4" />
                </button>
                <button @click="redo()" :disabled="!canRedo" class="p-1.5 rounded-md text-gray-500 hover:text-gray-900 hover:bg-white hover:shadow-sm disabled:opacity-50 disabled:hover:bg-transparent disabled:hover:shadow-none transition-all" title="Redo">
                    <x-heroicon-o-arrow-uturn-right class="w-4 h-4" />
                </button>
                <div class="w-px h-4 bg-gray-300 mx-1"></div>
                <button @click="togglePreview()" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md font-medium transition-all" :class="isPreview ? 'bg-indigo-100 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-white hover:text-gray-900 hover:shadow-sm'">
                    <x-heroicon-o-eye class="w-4 h-4" />
                    <span x-text="isPreview ? 'Exit Preview' : 'Preview'"></span>
                </button>
            </div>

            <div class="flex items-center gap-3">
                <x-ui.button variant="secondary" size="sm" @click="saveDraft(true)" x-bind:disabled="isSaving" class="shadow-sm">
                    <span x-show="!isSaving">Save Draft</span>
                    <span x-show="isSaving" class="flex items-center gap-1.5"><x-heroicon-o-arrow-path class="w-3.5 h-3.5 animate-spin"/> Saving...</span>
                </x-ui.button>
                <x-ui.button variant="primary" size="sm" @click="publish" class="shadow-sm bg-emerald-600 hover:bg-emerald-700 border-emerald-600 focus:ring-emerald-500">
                    <x-heroicon-o-rocket-launch class="w-4 h-4 mr-1.5"/> Publish
                </x-ui.button>
            </div>
        </header>

        <!-- 3-Panel Workspace -->
        <div class="flex flex-1 overflow-hidden relative">
            
            <!-- LEFT PANEL: Components (Hidden in Preview) -->
            <aside class="w-64 bg-white border-r border-gray-200 flex flex-col z-10 shrink-0 transition-transform duration-300" :class="isPreview ? '-translate-x-full absolute h-full' : 'translate-x-0 relative'">
                <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Form Elements</h2>
                </div>
                <div class="flex-1 overflow-y-auto p-4" id="components-list">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Layout</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex flex-col items-center justify-center p-3 bg-white rounded-xl border border-gray-200 cursor-move hover:border-indigo-300 hover:shadow-sm transition-all builder-component text-center h-20" data-type="section">
                                    <x-heroicon-o-rectangle-group class="w-6 h-6 text-indigo-500 mb-1" />
                                    <span class="text-xs font-medium text-gray-700">Section</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Basic Fields</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <template x-for="component in basicComponents" :key="component.type">
                                    <div class="flex flex-col items-center justify-center p-3 bg-white rounded-xl border border-gray-200 cursor-move hover:border-indigo-300 hover:shadow-sm transition-all builder-component text-center h-20" :data-type="component.type">
                                        <div class="text-indigo-500 mb-1" x-html="component.icon"></div>
                                        <span class="text-xs font-medium text-gray-700 leading-tight" x-text="component.label"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-2 px-1">Advanced Fields</h3>
                            <div class="grid grid-cols-2 gap-2">
                                <template x-for="component in advancedComponents" :key="component.type">
                                    <div class="flex flex-col items-center justify-center p-3 bg-white rounded-xl border border-gray-200 cursor-move hover:border-indigo-300 hover:shadow-sm transition-all builder-component text-center h-20" :data-type="component.type">
                                        <div class="text-indigo-500 mb-1" x-html="component.icon"></div>
                                        <span class="text-xs font-medium text-gray-700 leading-tight" x-text="component.label"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- CENTER PANEL: Canvas -->
            <main class="flex-1 overflow-y-auto p-8 flex justify-center bg-gray-50/50 transition-all duration-300">
                <div class="w-full max-w-3xl transition-all duration-300">
                    <div class="bg-white rounded-2xl shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] border border-gray-200 p-8 min-h-[600px] mb-20 relative">
                        
                        <!-- Form Header -->
                        <div class="mb-8 border-b border-gray-100 pb-6 group relative">
                            <h2 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $form->name }}</h2>
                            <p class="text-gray-500 mt-2 text-base">{{ $form->description }}</p>
                            
                            <div x-show="!isPreview" class="absolute top-0 right-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('form-studio.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2.5 py-1 rounded-md">Edit Details</a>
                            </div>
                        </div>

                        <!-- Canvas Area (Sections) -->
                        <div id="form-canvas" class="space-y-6 min-h-[400px]">
                            <template x-for="(section, sIndex) in schema.sections" :key="section.id">
                                <div class="section-container relative p-6 bg-white rounded-xl border-2 transition-all"
                                     :class="selectedSectionId === section.id ? 'border-indigo-200 bg-indigo-50/30' : 'border-dashed border-gray-200 hover:border-indigo-100'"
                                     @click.stop="selectSection(section.id)"
                                     :data-id="section.id">
                                     
                                    <div x-show="!isPreview" class="absolute -left-3 top-6 opacity-0 hover:opacity-100 section-drag-handle cursor-move p-1.5 text-gray-400 hover:text-indigo-600 bg-white rounded-full shadow-md border border-gray-200 z-10 transition-all">
                                        <x-heroicon-s-bars-2 class="w-4 h-4"/>
                                    </div>

                                    <div x-show="!isPreview" class="absolute -top-3 right-6 bg-white border border-gray-200 rounded-md shadow-sm flex items-center overflow-hidden z-10">
                                        <button @click.stop="deleteSection(section.id)" class="px-2 py-1 text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors tooltip" title="Delete Section">
                                            <x-heroicon-o-trash class="w-3.5 h-3.5" />
                                        </button>
                                    </div>

                                    <div class="mb-6">
                                        <h3 class="text-xl font-bold text-gray-900" x-text="section.title || 'Untitled Section'"></h3>
                                        <p x-show="section.description" class="text-sm text-gray-500 mt-1" x-text="section.description"></p>
                                    </div>

                                    <!-- Fields Container -->
                                    <div class="fields-container space-y-4 min-h-[100px] p-2 -mx-2 rounded-lg" :id="'section-' + section.id" :data-section-id="section.id">
                                        <template x-for="(field, fIndex) in section.fields" :key="field.id">
                                            <div class="group relative p-5 bg-white rounded-xl border-2 transition-all cursor-pointer"
                                                :class="selectedFieldId === field.id && !isPreview ? 'border-indigo-500 shadow-md ring-4 ring-indigo-500/10' : 'border-gray-100 hover:border-gray-300 hover:shadow-sm'"
                                                @click.stop="!isPreview && selectField(field.id, section.id)"
                                                :data-id="field.id">
                                                
                                                <div x-show="!isPreview" class="absolute -left-3 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 cursor-move p-1.5 text-gray-400 hover:text-indigo-600 transition-all drag-handle bg-white rounded-full shadow-md border border-gray-200 z-10">
                                                    <x-heroicon-s-bars-2 class="w-4 h-4"/>
                                                </div>

                                                <button x-show="!isPreview" @click.stop="deleteField(field.id, section.id)" class="absolute right-3 top-3 opacity-0 group-hover:opacity-100 text-gray-400 hover:text-red-600 transition-opacity p-1.5 hover:bg-red-50 rounded-lg z-10">
                                                    <x-heroicon-o-trash class="w-4 h-4" />
                                                </button>

                                                <div :class="isPreview ? '' : 'pointer-events-none'">
                                                    <label class="block text-sm font-bold text-gray-900 mb-1">
                                                        <span x-text="field.label || 'Untitled Field'"></span>
                                                        <span x-show="field.validation?.required" class="text-red-500 ml-1">*</span>
                                                    </label>
                                                    <p x-show="field.help_text" class="text-xs text-gray-500 mb-3" x-text="field.help_text"></p>
                                                    
                                                    <!-- Mock/Preview Inputs -->
                                                    <div class="mt-2">
                                                        <template x-if="field.type === 'text'">
                                                            <input type="text" :placeholder="field.properties?.placeholder" :disabled="!isPreview" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm bg-white disabled:bg-gray-50 disabled:text-gray-500">
                                                        </template>
                                                        <template x-if="field.type === 'textarea'">
                                                            <textarea :rows="field.properties?.rows || 3" :placeholder="field.properties?.placeholder" :disabled="!isPreview" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm bg-white disabled:bg-gray-50 disabled:text-gray-500"></textarea>
                                                        </template>
                                                        <template x-if="field.type === 'select'">
                                                            <select :disabled="!isPreview" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm bg-white disabled:bg-gray-50 disabled:text-gray-500">
                                                                <option value="" disabled selected>Select an option...</option>
                                                                <template x-for="opt in field.options">
                                                                    <option :value="opt.value" x-text="opt.label"></option>
                                                                </template>
                                                            </select>
                                                        </template>
                                                        <template x-if="['radio', 'checkbox'].includes(field.type)">
                                                            <div class="space-y-3 mt-3">
                                                                <template x-for="opt in field.options">
                                                                    <label class="flex items-start gap-3 cursor-pointer">
                                                                        <div class="flex items-center h-5">
                                                                            <input :type="field.type" :name="field.id" :disabled="!isPreview" class="border-gray-300 text-indigo-600 focus:ring-indigo-500" :class="field.type === 'radio' ? 'rounded-full h-4.5 w-4.5 mt-0.5' : 'rounded h-4.5 w-4.5 mt-0.5'">
                                                                        </div>
                                                                        <div class="text-sm font-medium text-gray-700 leading-snug" x-text="opt.label"></div>
                                                                    </label>
                                                                </template>
                                                            </div>
                                                        </template>
                                                        <template x-if="field.type === 'file'">
                                                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg" :class="isPreview ? 'bg-white hover:border-indigo-400' : 'bg-gray-50'">
                                                                <div class="space-y-1 text-center">
                                                                    <x-heroicon-o-arrow-up-tray class="mx-auto h-12 w-12 text-gray-400" />
                                                                    <div class="flex text-sm text-gray-600 justify-center">
                                                                        <span class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                                            <span>Upload a file</span>
                                                                        </span>
                                                                        <p class="pl-1">or drag and drop</p>
                                                                    </div>
                                                                    <p class="text-xs text-gray-500">PDF, PNG, JPG, DOCX up to 10MB</p>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Empty State for Section -->
                                        <div x-show="section.fields.length === 0 && !isPreview" class="text-center py-10 border-2 border-dashed border-indigo-100 rounded-xl bg-indigo-50/30 text-indigo-400">
                                            <p class="text-sm font-medium">Drag and drop fields here</p>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div x-show="schema.sections.length === 0 && !isPreview" class="text-center py-20 border-2 border-dashed border-gray-200 rounded-2xl bg-white">
                                <div class="text-gray-300 mb-4 flex justify-center"><x-heroicon-o-rectangle-group class="w-12 h-12" /></div>
                                <h3 class="text-base font-bold text-gray-900">Start building your form</h3>
                                <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">Drag a Section from the left panel, then add fields inside it to collect information.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- RIGHT PANEL: Properties (Hidden in Preview) -->
            <aside class="w-80 bg-white border-l border-gray-200 flex flex-col z-10 shrink-0 transition-transform duration-300" :class="isPreview ? 'translate-x-full absolute right-0 h-full' : 'translate-x-0 relative'">
                
                <!-- Tabs -->
                <div class="flex border-b border-gray-200 bg-gray-50">
                    <button class="flex-1 py-3 text-xs font-bold uppercase tracking-wider text-center border-b-2 transition-colors"
                            :class="activeTab === 'field' ? 'border-indigo-500 text-indigo-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            @click="activeTab = 'field'">
                        Field
                    </button>
                    <button class="flex-1 py-3 text-xs font-bold uppercase tracking-wider text-center border-b-2 transition-colors"
                            :class="activeTab === 'section' ? 'border-indigo-500 text-indigo-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'"
                            @click="activeTab = 'section'">
                        Section
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto">
                    <!-- Field Properties Tab -->
                    <div x-show="activeTab === 'field'" class="p-5 space-y-6">
                        <template x-if="selectedField">
                            <div class="space-y-6 animate-fade-in">
                                <!-- Basic Properties -->
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Basic Settings</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Field Label <span class="text-red-500">*</span></label>
                                            <input type="text" x-model="selectedField.label" @input="debouncedSave" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm" />
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Help Text (Optional)</label>
                                            <textarea x-model="selectedField.help_text" @input="debouncedSave" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm text-gray-600" placeholder="E.g., Please provide your full legal name."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Validation Settings -->
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Validation</h4>
                                    <div class="space-y-3">
                                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg bg-gray-50 hover:bg-gray-100 cursor-pointer transition-colors">
                                            <input type="checkbox" x-model="selectedField.validation.required" @change="saveDraft" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                            <span class="text-sm font-semibold text-gray-900">Required Field</span>
                                        </label>

                                        <template x-if="['text', 'textarea'].includes(selectedField.type)">
                                            <div class="grid grid-cols-2 gap-3 mt-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 mb-1">Min Length</label>
                                                    <input type="number" x-model.number="selectedField.validation.min" @input="debouncedSave" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm" placeholder="0">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 mb-1">Max Length</label>
                                                    <input type="number" x-model.number="selectedField.validation.max" @input="debouncedSave" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm" placeholder="255">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <!-- Options Editor -->
                                <template x-if="['select', 'radio', 'checkbox'].includes(selectedField.type)">
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Options</h4>
                                        <div class="space-y-2" id="options-list">
                                            <template x-for="(opt, optIndex) in selectedField.options" :key="optIndex">
                                                <div class="flex items-center gap-2 group">
                                                    <div class="cursor-move text-gray-400 hover:text-gray-600 p-1">
                                                        <x-heroicon-s-bars-2 class="w-3 h-3"/>
                                                    </div>
                                                    <input type="text" x-model="opt.label" @input="debouncedSave" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm py-1.5" />
                                                    <button @click="removeOption(optIndex)" class="text-gray-400 hover:text-red-500 p-1.5 hover:bg-red-50 rounded transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100">
                                                        <x-heroicon-o-trash class="w-4 h-4" />
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                        <button type="button" @click="addOption" class="mt-3 w-full flex items-center justify-center gap-2 px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                            <x-heroicon-o-plus class="w-4 h-4 text-gray-400"/> Add Option
                                        </button>
                                    </div>
                                </template>
                                
                                <!-- Placeholder Properties -->
                                <template x-if="['text', 'textarea'].includes(selectedField.type)">
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Properties</h4>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Placeholder</label>
                                            <input type="text" x-model="selectedField.properties.placeholder" @input="debouncedSave" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm" placeholder="Enter placeholder..." />
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- Conditional Logic (UI stub) -->
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2 mb-4">Conditional Logic</h4>
                                    <div class="bg-indigo-50/50 p-4 rounded-lg border border-indigo-100 flex flex-col items-center text-center">
                                        <x-heroicon-o-bolt class="w-6 h-6 text-indigo-400 mb-2"/>
                                        <p class="text-xs text-gray-600 font-medium">Show or hide this field based on other responses.</p>
                                        <button type="button" class="mt-3 text-xs font-bold text-indigo-600 bg-white px-3 py-1.5 rounded border border-indigo-200 shadow-sm hover:bg-indigo-50 transition-colors">Add Rule</button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="!selectedField">
                            <div class="text-center py-20 text-gray-400 px-4">
                                <x-heroicon-o-hand-raised class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                                <h3 class="text-sm font-bold text-gray-900 mb-1">No Field Selected</h3>
                                <p class="text-xs">Click on any field in the canvas to edit its properties, validation rules, and logic.</p>
                            </div>
                        </template>
                    </div>

                    <!-- Section Properties Tab -->
                    <div x-show="activeTab === 'section'" class="p-5 space-y-6">
                        <template x-if="selectedSection">
                            <div class="space-y-6 animate-fade-in">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Section Title <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="selectedSection.title" @input="debouncedSave" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm font-bold" />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Section Description (Optional)</label>
                                    <textarea x-model="selectedSection.description" @input="debouncedSave" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 sm:text-sm text-gray-600" placeholder="Provide context or instructions for this section..."></textarea>
                                </div>
                            </div>
                        </template>

                        <template x-if="!selectedSection">
                            <div class="text-center py-20 text-gray-400 px-4">
                                <x-heroicon-o-rectangle-group class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                                <h3 class="text-sm font-bold text-gray-900 mb-1">No Section Selected</h3>
                                <p class="text-xs">Click on the background of a section in the canvas to edit its title and description.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
        /* Sortable Ghost Styles */
        .sortable-ghost { opacity: 0.4; background-color: #eef2ff !important; border: 2px dashed #6366f1 !important; border-radius: 0.75rem; }
        .section-ghost { opacity: 0.5; border: 2px dashed #6366f1 !important; }
    </style>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formBuilder', (formId, initialSchema) => ({
                formId: formId,
                schema: initialSchema?.sections ? initialSchema : { sections: [] },
                
                // State
                selectedFieldId: null,
                selectedSectionId: null,
                activeTab: 'field', // 'field' or 'section'
                isPreview: false,
                
                // History (Undo/Redo)
                history: [],
                historyIndex: -1,
                isUndoRedoAction: false,

                // Saving
                isSaving: false,
                saveStatus: 'Saved',
                saveTimeout: null,

                basicComponents: [
                    { type: 'text', label: 'Short Text', icon: '<x-heroicon-o-bars-3-bottom-left class="w-6 h-6"/>' },
                    { type: 'textarea', label: 'Paragraph', icon: '<x-heroicon-o-bars-4 class="w-6 h-6"/>' },
                    { type: 'select', label: 'Dropdown', icon: '<x-heroicon-o-chevron-up-down class="w-6 h-6"/>' },
                    { type: 'radio', label: 'Single Choice', icon: '<x-heroicon-o-check-circle class="w-6 h-6"/>' },
                    { type: 'checkbox', label: 'Multi Choice', icon: '<x-heroicon-o-queue-list class="w-6 h-6"/>' },
                ],

                advancedComponents: [
                    { type: 'file', label: 'File Upload', icon: '<x-heroicon-o-arrow-up-tray class="w-6 h-6"/>' },
                    { type: 'date', label: 'Date Picker', icon: '<x-heroicon-o-calendar-days class="w-6 h-6"/>' },
                ],

                init() {
                    // Initialize empty state if needed
                    if (!this.schema.sections || this.schema.sections.length === 0) {
                        this.schema.sections = [];
                    } else if (this.schema.sections.length > 0 && !this.selectedSectionId) {
                        this.selectedSectionId = this.schema.sections[0].id;
                        this.activeTab = 'section';
                    }

                    this.saveStateToHistory();

                    this.$nextTick(() => {
                        this.initSidebarDrag();
                        this.initSectionsSortable();
                        this.initFieldsSortable();
                    });
                },

                get selectedSection() {
                    if (!this.selectedSectionId) return null;
                    return this.schema.sections.find(s => s.id === this.selectedSectionId);
                },

                get selectedField() {
                    if (!this.selectedFieldId || !this.selectedSectionId) return null;
                    const section = this.schema.sections.find(s => s.id === this.selectedSectionId);
                    return section ? section.fields.find(f => f.id === this.selectedFieldId) : null;
                },

                get canUndo() { return this.historyIndex > 0; },
                get canRedo() { return this.historyIndex < this.history.length - 1; },

                selectSection(id) {
                    if (this.isPreview) return;
                    this.selectedSectionId = id;
                    this.selectedFieldId = null;
                    this.activeTab = 'section';
                },

                selectField(fieldId, sectionId) {
                    if (this.isPreview) return;
                    this.selectedSectionId = sectionId;
                    this.selectedFieldId = fieldId;
                    this.activeTab = 'field';
                },

                generateId() {
                    return 'id_' + Math.random().toString(36).substr(2, 9);
                },

                // --- History Management ---
                saveStateToHistory() {
                    if (this.isUndoRedoAction) return;
                    
                    // Remove future history if we made a new change after undoing
                    if (this.historyIndex < this.history.length - 1) {
                        this.history = this.history.slice(0, this.historyIndex + 1);
                    }
                    
                    this.history.push(JSON.stringify(this.schema));
                    // Keep history limited to 30 items
                    if (this.history.length > 30) this.history.shift();
                    this.historyIndex = this.history.length - 1;
                },

                undo() {
                    if (this.canUndo) {
                        this.isUndoRedoAction = true;
                        this.historyIndex--;
                        this.schema = JSON.parse(this.history[this.historyIndex]);
                        this.saveDraft(false); // Save without history push
                        this.$nextTick(() => { this.isUndoRedoAction = false; this.reinitSortables(); });
                    }
                },

                redo() {
                    if (this.canRedo) {
                        this.isUndoRedoAction = true;
                        this.historyIndex++;
                        this.schema = JSON.parse(this.history[this.historyIndex]);
                        this.saveDraft(false);
                        this.$nextTick(() => { this.isUndoRedoAction = false; this.reinitSortables(); });
                    }
                },

                // --- Drag and Drop Logic ---
                initSidebarDrag() {
                    new Sortable(document.getElementById('components-list'), {
                        group: { name: 'components', pull: 'clone', put: false },
                        sort: false,
                        animation: 150
                    });
                },

                initSectionsSortable() {
                    const canvas = document.getElementById('form-canvas');
                    if (this.sectionsSortable) this.sectionsSortable.destroy();
                    
                    this.sectionsSortable = new Sortable(canvas, {
                        group: 'sections',
                        handle: '.section-drag-handle',
                        animation: 250,
                        ghostClass: 'section-ghost',
                        onAdd: (evt) => {
                            const type = evt.item.getAttribute('data-type');
                            evt.item.remove();
                            
                            if (type === 'section') {
                                const newSection = {
                                    id: this.generateId(),
                                    title: 'New Section',
                                    description: '',
                                    fields: []
                                };
                                this.schema.sections.splice(evt.newIndex, 0, newSection);
                                this.selectSection(newSection.id);
                                this.saveDraft();
                                this.$nextTick(() => { this.initFieldsSortable(); });
                            }
                        },
                        onUpdate: (evt) => {
                            const item = this.schema.sections.splice(evt.oldIndex, 1)[0];
                            this.schema.sections.splice(evt.newIndex, 0, item);
                            this.saveDraft();
                        }
                    });
                },

                initFieldsSortable() {
                    const containers = document.querySelectorAll('.fields-container');
                    
                    if(this.fieldSortables) {
                        this.fieldSortables.forEach(s => s.destroy());
                    }
                    this.fieldSortables = [];

                    containers.forEach(container => {
                        const s = new Sortable(container, {
                            group: 'fields',
                            handle: '.drag-handle',
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            onAdd: (evt) => {
                                // If dropped from sidebar
                                if (evt.from.id === 'components-list') {
                                    const type = evt.item.getAttribute('data-type');
                                    evt.item.remove();
                                    
                                    if (type === 'section') return; // Handled by sections sortable
                                    
                                    const toSectionId = evt.to.getAttribute('data-section-id');
                                    const section = this.schema.sections.find(s => s.id === toSectionId);
                                    
                                    const newField = {
                                        id: this.generateId(),
                                        type: type,
                                        label: 'New ' + type,
                                        help_text: '',
                                        validation: { required: false },
                                        properties: { placeholder: '' },
                                        logic: []
                                    };

                                    if (['select', 'radio', 'checkbox'].includes(type)) {
                                        newField.options = [
                                            { label: 'Option 1', value: 'Option 1' },
                                            { label: 'Option 2', value: 'Option 2' },
                                        ];
                                    }

                                    section.fields.splice(evt.newIndex, 0, newField);
                                    this.selectField(newField.id, toSectionId);
                                    this.saveDraft();
                                } 
                                // Moving between sections is complex in Alpine with nested sortables
                                // We handle state updates by listening to end event and manual DOM syncing if needed.
                                // For simplicity here, we trust Alpine array binding.
                            },
                            onEnd: (evt) => {
                                if (evt.from.id !== 'components-list') {
                                    // Move within or across sections
                                    const fromSectionId = evt.from.getAttribute('data-section-id');
                                    const toSectionId = evt.to.getAttribute('data-section-id');
                                    
                                    const fromSection = this.schema.sections.find(s => s.id === fromSectionId);
                                    const toSection = this.schema.sections.find(s => s.id === toSectionId);
                                    
                                    if(fromSection && toSection) {
                                        const item = fromSection.fields.splice(evt.oldIndex, 1)[0];
                                        toSection.fields.splice(evt.newIndex, 0, item);
                                        this.selectField(item.id, toSectionId);
                                        this.saveDraft();
                                    }
                                }
                            }
                        });
                        this.fieldSortables.push(s);
                    });
                },

                reinitSortables() {
                    this.initSectionsSortable();
                    this.initFieldsSortable();
                },

                deleteSection(id) {
                    if(confirm('Delete this section and all its fields?')) {
                        this.schema.sections = this.schema.sections.filter(s => s.id !== id);
                        if(this.selectedSectionId === id) {
                            this.selectedSectionId = null;
                            this.selectedFieldId = null;
                        }
                        this.saveDraft();
                        this.$nextTick(() => { this.reinitSortables(); });
                    }
                },

                deleteField(fieldId, sectionId) {
                    const section = this.schema.sections.find(s => s.id === sectionId);
                    if (section) {
                        section.fields = section.fields.filter(f => f.id !== fieldId);
                        if (this.selectedFieldId === fieldId) {
                            this.selectedFieldId = null;
                            this.activeTab = 'section';
                        }
                        this.saveDraft();
                    }
                },

                addOption() {
                    if (this.selectedField) {
                        if (!this.selectedField.options) this.selectedField.options = [];
                        const num = this.selectedField.options.length + 1;
                        this.selectedField.options.push({ label: 'Option ' + num, value: 'Option ' + num });
                        this.saveDraft();
                    }
                },

                removeOption(index) {
                    if (this.selectedField) {
                        this.selectedField.options.splice(index, 1);
                        this.saveDraft();
                    }
                },

                togglePreview() {
                    this.isPreview = !this.isPreview;
                    if (this.isPreview) {
                        this.selectedFieldId = null;
                        this.selectedSectionId = null;
                    }
                },

                debouncedSave() {
                    clearTimeout(this.saveTimeout);
                    this.saveStatus = 'Editing...';
                    this.saveTimeout = setTimeout(() => {
                        this.saveDraft();
                    }, 800);
                },

                saveDraft(pushToHistory = true) {
                    this.isSaving = true;
                    this.saveStatus = 'Saving...';
                    
                    if (pushToHistory) {
                        this.saveStateToHistory();
                    }

                    fetch(`/form-studio/${this.formId}/draft`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ schema: this.schema })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isSaving = false;
                        const now = new Date();
                        this.saveStatus = 'Saved ' + now.getHours() + ':' + now.getMinutes().toString().padStart(2, '0');
                    })
                    .catch(err => {
                        this.isSaving = false;
                        this.saveStatus = 'Error saving';
                        console.error(err);
                    });
                },

                publish() {
                    if (confirm('Ready to publish? This will update the live form for all new applicants.')) {
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

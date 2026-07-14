<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Form') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-[#f0ebf8] min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden relative">
                <div class="h-3 w-full bg-indigo-600"></div>
                <div class="p-8">
                    <form method="POST" action="{{ route('forms.store') }}" class="space-y-6">
                        @csrf
                        
                        <div>
                            <input type="text" name="title" value="{{ old('title') }}" class="w-full text-3xl font-normal text-gray-900 border-b border-transparent hover:border-gray-300 focus:border-indigo-500 focus:ring-0 px-0 pb-2 transition-colors placeholder-gray-400" placeholder="Untitled form" required>
                            @error('title') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <textarea name="description" rows="2" class="w-full text-sm text-gray-600 border-b border-transparent hover:border-gray-300 focus:border-indigo-500 focus:ring-0 px-0 pb-1 resize-none placeholder-gray-400" placeholder="Form description">{{ old('description') }}</textarea>
                            @error('description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md transition-colors">Start Building Form</button>
                            <a href="{{ route('forms.index') }}" class="text-gray-500 hover:text-gray-800 font-medium">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<section class="space-y-6">
    <header class="mb-6">
        <h2 class="text-lg font-bold text-red-600 tracking-tight flex items-center gap-2">
            <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-500"/>
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 font-medium max-w-xl">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-ui.button type="button" variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="shadow-sm">
        <x-heroicon-o-trash class="w-4 h-4 mr-2"/>
        {{ __('Delete Account') }}
    </x-ui.button>

    <x-ui.modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <x-ui.form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @method('delete')

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600 border border-red-100 shrink-0">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5"/>
                </div>
                <h2 class="text-lg font-bold text-gray-900">
                    {{ __('Delete Account') }}
                </h2>
            </div>

            <p class="text-sm text-gray-600 mb-6 font-medium">
                {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Password') }}</label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full rounded-lg border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-red-500 focus:ring-1 focus:ring-red-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3 {{ $errors->userDeletion->has('password') ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : '' }}"
                        placeholder="••••••••"
                    />
                </div>
                @if($errors->userDeletion->has('password'))
                    <p class="mt-1.5 text-sm font-medium text-red-600">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-gray-100">
                <x-ui.button type="button" variant="secondary" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-ui.button>

                <x-ui.button type="submit" variant="danger" x-bind:disabled="isSubmitting" class="shadow-sm">
                    <span x-show="!isSubmitting">{{ __('Permanently Delete') }}</span>
                    <span x-show="isSubmitting" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        {{ __('Deleting...') }}
                    </span>
                </x-ui.button>
            </div>
        </x-ui.form>
    </x-ui.modal>
</section>

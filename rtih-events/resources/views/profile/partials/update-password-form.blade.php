<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 tracking-tight flex items-center gap-2">
            <x-heroicon-o-lock-closed class="w-5 h-5 text-indigo-500"/>
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 font-medium">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <x-ui.form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-xl">
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Current Password</label>
            <div class="relative">
                <input id="update_password_current_password" type="password" name="current_password" autocomplete="current-password" class="block w-full rounded-lg border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3 {{ $errors->updatePassword->has('current_password') ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : '' }}" placeholder="••••••••">
            </div>
            @if($errors->updatePassword->has('current_password'))
                <p class="mt-1.5 text-sm font-medium text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
            <div class="relative">
                <input id="update_password_password" type="password" name="password" autocomplete="new-password" class="block w-full rounded-lg border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3 {{ $errors->updatePassword->has('password') ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : '' }}" placeholder="••••••••">
            </div>
            @if($errors->updatePassword->has('password'))
                <p class="mt-1.5 text-sm font-medium text-red-600">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
            <div class="relative">
                <input id="update_password_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" class="block w-full rounded-lg border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3" placeholder="••••••••">
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-ui.button type="submit" variant="primary" x-bind:disabled="isSubmitting" class="shadow-sm">
                <span x-show="!isSubmitting"><x-heroicon-o-check class="w-4 h-4 mr-2 inline-block"/>{{ __('Update Password') }}</span>
                <span x-show="isSubmitting" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    {{ __('Saving...') }}
                </span>
            </x-ui.button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm text-emerald-600 font-bold flex items-center gap-1.5 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100"
                ><x-heroicon-s-check-circle class="w-4 h-4"/> {{ __('Saved.') }}</p>
            @endif
        </div>
    </x-ui.form>
</section>

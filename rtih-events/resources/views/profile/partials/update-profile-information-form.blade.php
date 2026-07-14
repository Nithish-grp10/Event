<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-900 tracking-tight flex items-center gap-2">
            <x-heroicon-o-user-circle class="w-5 h-5 text-indigo-500"/>
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500 font-medium">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <x-ui.form method="post" action="{{ route('profile.update') }}" class="space-y-6 max-w-xl">
        @method('patch')

        <div>
            <x-ui.input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" label="Name" placeholder="John Doe" />
        </div>

        <div>
            <x-ui.input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" label="Email" placeholder="name@example.com" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 bg-amber-50 p-4 rounded-xl border border-amber-200 shadow-sm flex items-start gap-3">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" />
                    <div>
                        <p class="text-sm font-semibold text-amber-800">
                            {{ __('Your email address is unverified.') }}
                        </p>
                        <button form="send-verification" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 mt-1 transition-colors">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-ui.button type="submit" variant="primary" x-bind:disabled="isSubmitting" class="shadow-sm">
                <span x-show="!isSubmitting"><x-heroicon-o-check class="w-4 h-4 mr-2 inline-block"/>{{ __('Save Changes') }}</span>
                <span x-show="isSubmitting" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    {{ __('Saving...') }}
                </span>
            </x-ui.button>

            @if (session('status') === 'profile-updated')
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

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
        @csrf
    </form>
</section>

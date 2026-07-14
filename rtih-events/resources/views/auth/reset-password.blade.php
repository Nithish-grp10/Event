<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2 tracking-tight">Reset password</h2>
        <p class="text-sm text-gray-500 font-medium">Enter your new password below.</p>
    </div>

    <x-ui.form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-ui.input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" label="Email address" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
            <div class="relative">
                <input id="password" type="password" name="password" required autocomplete="new-password" class="block w-full rounded-lg border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3 {{ $errors->has('password') ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : '' }}" placeholder="••••••••">
            </div>
            @error('password')
                <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="block w-full rounded-lg border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3" placeholder="••••••••">
            </div>
        </div>

        <div class="pt-2">
            <x-ui.button type="submit" variant="primary" class="w-full py-3 text-base shadow-sm" x-bind:disabled="isSubmitting">
                <span x-show="!isSubmitting">Reset Password</span>
                <span x-show="isSubmitting" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Resetting...
                </span>
            </x-ui.button>
        </div>
    </x-ui.form>
</x-guest-layout>

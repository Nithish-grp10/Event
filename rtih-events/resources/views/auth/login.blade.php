<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2 tracking-tight">Welcome back</h2>
        <p class="text-sm text-gray-500 font-medium">Enter your credentials to access your account.</p>
    </div>

    <x-ui.form method="POST" action="{{ route('login') }}" class="space-y-6">
        <!-- Email Address -->
        <div>
            <x-ui.input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" label="Email address" placeholder="name@example.com" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <input id="password" type="password" name="password" required autocomplete="current-password" class="block w-full rounded-lg border-gray-200 shadow-[0_1px_2px_rgba(0,0,0,0.02)] focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all bg-white hover:border-gray-300 px-4 py-3 {{ $errors->has('password') ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : '' }}" placeholder="••••••••">
                @if($errors->has('password'))
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <x-heroicon-s-exclamation-circle class="h-5 w-5 text-red-500" />
                    </div>
                @endif
            </div>
            @error('password')
                <p class="mt-1.5 text-sm font-medium text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded transition-colors cursor-pointer">
            <label for="remember_me" class="ml-2 block text-sm font-medium text-gray-700 cursor-pointer select-none">
                Remember me for 30 days
            </label>
        </div>

        <div class="pt-2">
            <x-ui.button type="submit" variant="primary" class="w-full py-3 text-base shadow-sm" x-bind:disabled="isSubmitting">
                <span x-show="!isSubmitting">Sign in</span>
                <span x-show="isSubmitting" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Signing in...
                </span>
            </x-ui.button>
        </div>
        
        <div class="text-center text-sm font-medium text-gray-600 mt-6 border-t border-gray-100 pt-6">
            Don't have an account? 
            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">Create one now</a>
        </div>
    </x-ui.form>
</x-guest-layout>

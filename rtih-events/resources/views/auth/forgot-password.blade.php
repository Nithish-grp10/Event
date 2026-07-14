<x-guest-layout>
    <div class="mb-8">
        <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 mb-6 border border-indigo-100">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2 tracking-tight">Forgot your password?</h2>
        <p class="text-sm text-gray-500 font-medium leading-relaxed">No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <x-ui.form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        <!-- Email Address -->
        <div>
            <x-ui.input id="email" type="email" name="email" :value="old('email')" required autofocus label="Email address" placeholder="name@example.com" />
        </div>

        <div class="pt-2">
            <x-ui.button type="submit" variant="primary" class="w-full py-3 text-base shadow-sm" x-bind:disabled="isSubmitting">
                <span x-show="!isSubmitting">Email Password Reset Link</span>
                <span x-show="isSubmitting" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Sending link...
                </span>
            </x-ui.button>
        </div>
        
        <div class="text-center text-sm font-medium text-gray-600 mt-6 border-t border-gray-100 pt-6">
            Remember your password? 
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">Back to login</a>
        </div>
    </x-ui.form>
</x-guest-layout>

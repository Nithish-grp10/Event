<x-app-layout>
    <x-slot name="header">
        Scan Attendance: {{ $event->title }}
    </x-slot>
    <x-slot name="actions">
        <x-ui.button href="{{ route('events.show', $event) }}" variant="secondary">
            <x-heroicon-o-arrow-left class="w-4 h-4 mr-2"/>
            Back to Event
        </x-ui.button>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8">
        <x-ui.card>
            <div class="p-8 text-center" x-data="scannerApp()">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 text-white mb-6 shadow-sm">
                    <x-heroicon-o-qr-code class="w-12 h-12"/>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2 tracking-tight">Scan Ticket</h2>
                <p class="text-sm font-medium text-gray-500 mb-8">Ensure your cursor is in the input field below, then scan the barcode.</p>

                <form @submit.prevent="submitScan" class="max-w-md mx-auto relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none mb-4">
                        <x-heroicon-o-viewfinder-circle class="h-6 w-6 text-indigo-400" />
                    </div>
                    <input type="text" x-model="token" x-ref="tokenInput" placeholder="Enter barcode token..." class="w-full text-center text-xl font-medium p-4 pl-12 border-2 border-indigo-100 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20 transition-all shadow-sm mb-4" autofocus autocomplete="off">
                    
                    <x-ui.button type="submit" variant="primary" class="w-full py-4 text-lg bg-gray-900 hover:bg-black focus-visible:ring-gray-900 border-gray-900" x-bind:disabled="loading">
                        <span x-show="!loading" class="flex items-center justify-center">
                            <x-heroicon-o-bolt class="w-5 h-5 mr-2 text-yellow-400"/>
                            Process Scan
                        </span>
                        <span x-show="loading" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Processing...
                        </span>
                    </x-ui.button>
                </form>

                <!-- Feedback Message -->
                <div x-show="message" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="mt-8 p-5 rounded-xl text-left border shadow-sm" :class="status === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : (status === 'duplicate' ? 'bg-amber-50 border-amber-200 text-amber-800' : 'bg-red-50 border-red-200 text-red-800')" style="display: none;">
                    <div class="flex items-start gap-4">
                        <div class="mt-0.5 flex-shrink-0">
                            <x-heroicon-s-check-circle class="w-8 h-8 text-emerald-500" x-show="status === 'success'" />
                            <x-heroicon-s-exclamation-triangle class="w-8 h-8 text-amber-500" x-show="status === 'duplicate'" />
                            <x-heroicon-s-x-circle class="w-8 h-8 text-red-500" x-show="status === 'error'" />
                        </div>
                        <div>
                            <p class="font-bold text-lg mb-1" x-text="messageTitle"></p>
                            <p class="font-medium opacity-90" x-text="messageBody"></p>
                        </div>
                    </div>
                </div>
            </div>
        </x-ui.card>
    </div>

    <script>
        function scannerApp() {
            return {
                token: '',
                loading: false,
                message: false,
                status: '',
                messageTitle: '',
                messageBody: '',
                
                init() {
                    this.$watch('token', () => {
                        this.message = false;
                    });
                },

                async submitScan() {
                    if (!this.token.trim()) return;
                    
                    this.loading = true;
                    this.message = false;

                    try {
                        const response = await fetch("{{ route('events.attendance.store', $event) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ barcode_token: this.token })
                        });

                        const data = await response.json();

                        this.message = true;
                        if (response.ok) {
                            if (data.was_already_present) {
                                this.status = 'duplicate';
                                this.messageTitle = 'Already Scanned';
                                this.messageBody = `${data.applicant} has already been marked as present.`;
                            } else {
                                this.status = 'success';
                                this.messageTitle = 'Access Granted';
                                this.messageBody = `${data.applicant} marked as present.`;
                            }
                        } else {
                            this.status = 'error';
                            this.messageTitle = 'Scan Failed';
                            this.messageBody = data.message || 'Unknown error occurred.';
                        }
                    } catch (error) {
                        this.message = true;
                        this.status = 'error';
                        this.messageTitle = 'Network Error';
                        this.messageBody = 'Could not connect to the server.';
                    } finally {
                        this.loading = false;
                        this.token = ''; // Clear for next scan
                        this.$nextTick(() => {
                            this.$refs.tokenInput.focus();
                        });
                    }
                }
            }
        }
    </script>
</x-app-layout>

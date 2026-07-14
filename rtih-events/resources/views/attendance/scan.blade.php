<x-app-layout>
    <x-slot name="header">
        Scan Attendance: {{ $event->title }}
    </x-slot>
    <x-slot name="actions">
        <a href="{{ route('events.show', $event) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
            Back to Event
        </a>
    </x-slot>

    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-8 text-center" x-data="scannerApp()">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-indigo-50 text-indigo-600 mb-6">
                    <x-heroicon-o-qr-code class="w-10 h-10"/>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Scan Ticket</h2>
                <p class="text-gray-500 mb-8">Ensure your cursor is in the input field below, then scan the barcode.</p>

                <form @submit.prevent="submitScan" class="max-w-md mx-auto">
                    <input type="text" x-model="token" x-ref="tokenInput" placeholder="Enter barcode token..." class="w-full text-center text-lg p-4 border-2 border-indigo-200 rounded-xl focus:border-indigo-600 focus:ring focus:ring-indigo-200 transition-colors mb-4" autofocus autocomplete="off">
                    
                    <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors flex justify-center items-center gap-2" :disabled="loading">
                        <span x-show="!loading">Process Scan</span>
                        <span x-show="loading">Processing...</span>
                    </button>
                </form>

                <!-- Feedback Message -->
                <div x-show="message" x-transition.opacity class="mt-8 p-4 rounded-xl text-left border" :class="status === 'success' ? 'bg-green-50 border-green-200 text-green-800' : (status === 'duplicate' ? 'bg-yellow-50 border-yellow-200 text-yellow-800' : 'bg-red-50 border-red-200 text-red-800')" style="display: none;">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5">
                            <x-heroicon-o-check-circle class="w-6 h-6 text-green-500" x-show="status === 'success'" />
                            <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-500" x-show="status === 'duplicate'" />
                            <x-heroicon-o-x-circle class="w-6 h-6 text-red-500" x-show="status === 'error'" />
                        </div>
                        <div>
                            <p class="font-bold text-lg" x-text="messageTitle"></p>
                            <p x-text="messageBody"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        this.$refs.tokenInput.focus();
                    }
                }
            }
        }
    </script>
</x-app-layout>

<!-- Global Toast Notifications -->
<div x-data="{ toasts: [] }" 
        @toast.window="toasts.push({ id: Date.now(), message: $event.detail.message, type: $event.detail.type || 'success' }); setTimeout(() => { toasts = toasts.filter(t => t.id !== toasts[toasts.length-1].id) }, 3000)"
        class="fixed bottom-4 right-4 z-toast flex flex-col gap-2 pointer-events-none">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                class="px-4 py-3 rounded-xl shadow-floating flex items-center gap-3 text-white min-w-[250px] pointer-events-auto"
                :class="{
                    'bg-success-600': toast.type === 'success',
                    'bg-danger-600': toast.type === 'error',
                    'bg-primary-600': toast.type === 'info',
                    'bg-warning-600': toast.type === 'warning'
                }">
            <template x-if="toast.type === 'success'">
                <x-heroicon-o-check-circle class="w-5 h-5"/>
            </template>
            <template x-if="toast.type === 'error'">
                <x-heroicon-o-x-circle class="w-5 h-5"/>
            </template>
            <template x-if="toast.type === 'info'">
                <x-heroicon-o-information-circle class="w-5 h-5"/>
            </template>
            <template x-if="toast.type === 'warning'">
                <x-heroicon-o-exclamation-triangle class="w-5 h-5"/>
            </template>
            <span x-text="toast.message" class="text-sm font-medium"></span>
        </div>
    </template>
</div>

<!-- Dispatch session messages to Toast -->
@if(session('success'))
    <script>
        document.addEventListener('alpine:init', () => {
            setTimeout(() => window.dispatchEvent(new CustomEvent('toast', { detail: { message: "{{ session('success') }}", type: 'success' } })), 500);
        });
    </script>
@endif
@if(session('error'))
    <script>
        document.addEventListener('alpine:init', () => {
            setTimeout(() => window.dispatchEvent(new CustomEvent('toast', { detail: { message: "{{ session('error') }}", type: 'error' } })), 500);
        });
    </script>
@endif

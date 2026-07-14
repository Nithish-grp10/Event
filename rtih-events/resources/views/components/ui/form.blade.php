<form 
    {{ $attributes->merge(['method' => 'POST']) }}
    x-data="{
        isSubmitting: false,
        isDirty: false,
        
        init() {
            // Track if form is dirty for cancel confirmation
            const inputs = this.$el.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', () => {
                    this.isDirty = true;
                });
            });

            @if(isset($autosave) && $autosave)
                // Autosave logic (Debounced)
                let timeout = null;
                inputs.forEach(input => {
                    input.addEventListener('input', () => {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            this.triggerAutosave();
                        }, 2000);
                    });
                });
            @endif
        },

        async triggerAutosave() {
            // Placeholder for autosave dispatch/fetch
            this.$dispatch('notify', { message: 'Draft autosaved', type: 'success' });
        },

        confirmCancel(e) {
            if (this.isDirty) {
                if (!confirm('You have unsaved changes. Are you sure you want to cancel?')) {
                    e.preventDefault();
                }
            }
        },

        handleSubmit(e) {
            this.isSubmitting = true;
            // The actual form submission will proceed. 
            // isSubmitting state can be used by child components (like buttons) via $parent.isSubmitting or similar
        }
    }"
    @submit="handleSubmit"
    x-ref="form"
>
    @if($attributes->get('method') !== 'GET')
        @csrf
    @endif
    
    @if(in_array(strtoupper($attributes->get('method')), ['PUT', 'PATCH', 'DELETE']))
        @method(strtoupper($attributes->get('method')))
    @endif

    {{ $slot }}
</form>

export function denseConfirmModal() {
    return {
        open: false,
        message: 'Are you sure?',
        danger: false,
        settled: false,
        onOpen: null,

        init() {
            this.onOpen = (event) => {
                this.message = event.detail?.message ?? 'Are you sure?';
                this.danger = Boolean(event.detail?.danger);
                this.settled = false;
                this.open = true;

                this.$nextTick(() => this.$refs.confirmButton?.focus?.());
            };

            window.addEventListener('dense:confirm:open', this.onOpen);
        },

        destroy() {
            if (this.onOpen) {
                window.removeEventListener('dense:confirm:open', this.onOpen);
            }
        },

        confirm() {
            this.resolve(true);
        },

        cancel() {
            this.resolve(false);
        },

        resolve(confirmed) {
            if (this.settled) {
                return;
            }

            this.settled = true;
            this.open = false;
            window.dispatchEvent(new CustomEvent('dense:confirm:resolved', {
                detail: { confirmed },
            }));
        },
    };
}

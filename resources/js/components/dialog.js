import { focusFirst, focusable, restoreFocus } from '../utilities/focus';

export function denseDialog(config = {}) {
    return {
        open: false,
        trigger: null,
        closeOnEscape: config.closeOnEscape !== false,

        init() {
            this.trigger = this.$refs.trigger ?? null;
        },

        show() {
            this.open = true;
            this.$nextTick(() => focusFirst(this.$refs.dialog ?? this.$root));
        },

        hide() {
            this.open = false;
            this.$nextTick(() => restoreFocus(this.trigger));
        },

        onKeydown(event) {
            if (event.key === 'Escape' && this.closeOnEscape) {
                event.preventDefault();
                this.hide();
                return;
            }

            if (event.key !== 'Tab' || !this.open) return;

            const elements = focusable(this.$refs.dialog ?? this.$root);
            if (!elements.length) return;

            const first = elements[0];
            const last = elements.at(-1);
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },
    };
}

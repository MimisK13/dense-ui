import { focusFirst, restoreFocus } from '../utilities/focus';

export function denseDropdown(config = {}) {
    return {
        open: false,
        returnFocus: config.returnFocus !== false,
        trigger: null,

        init() {
            this.trigger = this.$refs.trigger ?? null;
        },

        toggle() {
            this.open ? this.close() : this.openMenu();
        },

        openMenu() {
            this.open = true;
            this.$nextTick(() => focusFirst(this.$refs.menu ?? this.$root));
        },

        close() {
            this.open = false;
            if (this.returnFocus) this.$nextTick(() => restoreFocus(this.trigger));
        },

        onKeydown(event) {
            if (event.key === 'Escape') {
                event.preventDefault();
                this.close();
            }
        },
    };
}

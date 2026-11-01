import { focusFirst, restoreFocus } from '../utilities/focus';

export function denseDropdown(config = {}) {
    return {
        open: false,
        returnFocus: config.returnFocus !== false,
        floating: config.floating === true,
        trigger: null,

        init() {
            this.trigger = this.$refs.trigger ?? null;
        },

        toggle() {
            this.open ? this.close() : this.openMenu();
        },

        openMenu() {
            this.open = true;
            this.$nextTick(() => {
                this.positionMenu();
                focusFirst(this.$refs.menu ?? this.$root);
            });
        },

        close(focusAfter = null) {
            this.open = false;
            const target = focusAfter ?? (this.returnFocus ? this.trigger : null);
            if (target) this.$nextTick(() => restoreFocus(target));
        },

        positionMenu() {
            if (!this.floating || !this.trigger || !this.$refs.menu) return;

            const rect = this.trigger.getBoundingClientRect();
            const menu = this.$refs.menu;

            menu.style.position = 'fixed';
            menu.style.zIndex = '9999';
            menu.style.top = `${rect.bottom + 4}px`;
            menu.style.right = `${window.innerWidth - rect.right}px`;
            menu.style.left = 'auto';
        },

        onKeydown(event) {
            if (event.key === 'Escape') {
                event.preventDefault();
                this.close(this.trigger);
                return;
            }

            if (!['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) return;
            event.preventDefault();

            const items = [...(this.$refs.menu ?? this.$root).querySelectorAll('[role="menuitem"]:not([disabled])')];
            if (items.length === 0) return;

            const currentIndex = Math.max(items.indexOf(document.activeElement), 0);
            let nextIndex = currentIndex;

            if (event.key === 'Home') nextIndex = 0;
            if (event.key === 'End') nextIndex = items.length - 1;
            if (event.key === 'ArrowDown') nextIndex = (currentIndex + 1) % items.length;
            if (event.key === 'ArrowUp') nextIndex = (currentIndex - 1 + items.length) % items.length;

            items[nextIndex]?.focus();
        },
    };
}

export function denseTabs(config = {}) {
    return {
        active: config.active ?? 0,
        count: config.count ?? 0,

        activate(index) {
            if (index >= 0 && index < this.count) this.active = index;
        },

        onKeydown(event) {
            if (!['ArrowRight', 'ArrowLeft', 'Home', 'End'].includes(event.key)) return;
            event.preventDefault();

            const tabs = [...event.currentTarget.querySelectorAll('[role="tab"]:not([disabled])')];
            if (tabs.length === 0) return;

            const currentIndex = Math.max(tabs.indexOf(document.activeElement), 0);
            let nextIndex = currentIndex;

            if (event.key === 'Home') nextIndex = 0;
            if (event.key === 'End') nextIndex = tabs.length - 1;
            if (event.key === 'ArrowRight') nextIndex = (currentIndex + 1) % tabs.length;
            if (event.key === 'ArrowLeft') nextIndex = (currentIndex - 1 + tabs.length) % tabs.length;

            this.active = nextIndex;
            tabs[nextIndex]?.focus();
        },
    };
}

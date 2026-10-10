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

            if (event.key === 'Home') this.active = 0;
            if (event.key === 'End') this.active = this.count - 1;
            if (event.key === 'ArrowRight') this.active = (this.active + 1) % this.count;
            if (event.key === 'ArrowLeft') this.active = (this.active - 1 + this.count) % this.count;
        },
    };
}

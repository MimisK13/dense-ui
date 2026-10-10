import { nextIndex } from '../utilities/keyboard';

export function denseCombobox(config = {}) {
    return {
        query: '',
        selected: config.multiple ? [...(config.selected ?? [])] : config.selected ?? null,
        options: config.options ?? [],
        multiple: Boolean(config.multiple),
        limit: config.limit ?? null,
        model: config.model ?? null,
        open: false,
        activeIndex: -1,

        get filteredOptions() {
            const normalizedQuery = this.query.trim().toLowerCase();
            const results = normalizedQuery === ''
                ? this.options
                : this.options.filter((option) => option.name.toLowerCase().includes(normalizedQuery));

            return this.limit ? results.slice(0, this.limit) : results;
        },

        init() {
            if (!this.multiple && this.selected !== null) {
                this.query = this.selectedLabel();
            }
        },

        isSelected(option) {
            return this.multiple
                ? this.selected.some((value) => String(value) === String(option.id))
                : String(this.selected ?? '') === String(option.id);
        },

        selectedLabel() {
            return this.options.find((option) => String(option.id) === String(this.selected))?.name ?? '';
        },

        sync() {
            if (this.model && this.$wire) {
                this.$wire.set(this.model, this.selected);
            }
        },

        select(option) {
            if (option.disabled) return;

            if (this.multiple) {
                if (!this.isSelected(option)) this.selected = [...this.selected, option.id];
                this.query = '';
                this.open = true;
            } else {
                this.selected = option.id;
                this.query = option.name;
                this.open = false;
            }

            this.activeIndex = -1;
            this.sync();
        },

        remove(value) {
            this.selected = this.selected.filter((selected) => String(selected) !== String(value));
            this.sync();
        },

        clear() {
            this.selected = this.multiple ? [] : null;
            this.query = '';
            this.open = false;
            this.activeIndex = -1;
            this.sync();
        },

        onKeydown(event) {
            if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                event.preventDefault();
                this.open = true;
                this.activeIndex = nextIndex(this.activeIndex, this.filteredOptions.length, event.key === 'ArrowDown' ? 1 : -1);
            } else if (event.key === 'Enter' && this.open && this.filteredOptions[this.activeIndex]) {
                event.preventDefault();
                this.select(this.filteredOptions[this.activeIndex]);
            } else if (event.key === 'Escape') {
                this.open = false;
            } else if (event.key === 'Backspace' && this.multiple && this.query === '' && this.selected.length) {
                this.remove(this.selected[this.selected.length - 1]);
            }
        },

        reset() {
            this.selected = this.multiple ? [] : null;
            this.query = '';
            this.open = false;
            this.activeIndex = -1;
        },
    };
}

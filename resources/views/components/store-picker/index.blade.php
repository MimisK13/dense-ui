@props([
    'id' => null,
    'name' => '',
    'value' => [],
    'suggestions' => [],
    'placeholder' => 'All stores',
    'floatSuggestions' => false,
    'single' => false,
])

<div
    {{ $attributes->merge(['class' => 'erp-store-picker'.($single ? ' erp-store-picker-single' : '')]) }}
    x-data="{
        tags: @js($value),
        input: '',
        suggestions: @js($suggestions),
        floatSuggestions: @js($floatSuggestions),
        single: @js($single),
        filtered: [],
        showSuggestions: false,
        activeIndex: -1,
        availableSuggestions() {
            return this.suggestions.filter(s => !this.tags.includes(s));
        },
        positionSuggestions() {
            if (!this.floatSuggestions) return;
            const el = this.$refs.suggestions;
            if (!el) return;
            const rect = this.$el.getBoundingClientRect();
            el.style.top = (rect.bottom + 4) + 'px';
            el.style.left = rect.left + 'px';
            el.style.width = rect.width + 'px';
        },
        openSuggestions() {
            const pool = this.availableSuggestions();
            const query = this.input.toLowerCase();
            this.filtered = query ? pool.filter(s => s.toLowerCase().includes(query)) : pool;
            this.showSuggestions = this.filtered.length > 0;
            this.activeIndex = -1;
            if (this.showSuggestions && this.floatSuggestions) {
                this.$nextTick(() => this.positionSuggestions());
            }
        },
        addTag(tag) {
            tag = tag.trim();
            if (tag && !this.tags.includes(tag)) {
                this.tags = this.single ? [tag] : [...this.tags, tag];
                $dispatch('tags-changed', this.tags);
            }
            this.input = '';
            this.openSuggestions();
        },
        removeTag(index) {
            this.tags.splice(index, 1);
            $dispatch('tags-changed', this.tags);
            this.openSuggestions();
        },
        onKeydown(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.activeIndex >= 0 && this.filtered[this.activeIndex]) {
                    this.addTag(this.filtered[this.activeIndex]);
                } else if (this.input) {
                    this.addTag(this.input);
                }
            } else if (e.key === 'Backspace' && !this.input && this.tags.length) {
                this.removeTag(this.tags.length - 1);
            } else if (e.key === 'ArrowDown' && this.showSuggestions) {
                e.preventDefault();
                this.activeIndex = Math.min(this.activeIndex + 1, this.filtered.length - 1);
            } else if (e.key === 'ArrowUp' && this.showSuggestions) {
                e.preventDefault();
                this.activeIndex = Math.max(this.activeIndex - 1, 0);
            } else if (e.key === 'Escape') {
                this.showSuggestions = false;
            }
        },
        init() {
            this.filtered = this.availableSuggestions();
        },
    }"
    x-on:click="$refs.field.focus(); openSuggestions()"
    x-on:resize.window="if (showSuggestions && floatSuggestions) positionSuggestions()"
    x-on:scroll.window="if (showSuggestions && floatSuggestions) positionSuggestions()"
>
    @if($name)
        <template x-for="(tag, i) in tags" :key="i">
            <input type="hidden" :name="@js($name) + '[]'" :value="tag">
        </template>
    @endif

    <template x-for="(tag, i) in tags" :key="'tag-' + i">
        <span class="erp-store-picker-tag">
            <span x-text="tag"></span>
            <button type="button" x-on:click.stop="removeTag(i)" aria-label="Remove store">
                <x-dense::icons.x-mark size="5" />
            </button>
        </span>
    </template>

    <input
        @if($id) id="{{ $id }}" @endif
        x-ref="field"
        class="erp-store-picker-field"
        type="text"
        x-model="input"
        x-on:input="openSuggestions()"
        x-on:focus="openSuggestions()"
        x-on:keydown="onKeydown($event)"
        x-on:blur="setTimeout(() => showSuggestions = false, 150)"
        placeholder="{{ $placeholder }}"
    >

    <div
        x-ref="suggestions"
        x-show="showSuggestions"
        x-cloak
        x-bind:class="floatSuggestions ? 'erp-store-picker-suggestions erp-store-picker-suggestions-floating' : 'erp-store-picker-suggestions'"
    >
        <template x-for="(suggestion, i) in filtered" :key="suggestion">
            <button type="button" x-text="suggestion" x-on:mousedown.prevent="addTag(suggestion)" x-bind:class="{ 'active': i === activeIndex }"></button>
        </template>
    </div>
</div>

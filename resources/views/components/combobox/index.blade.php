@props([
    'options' => [],
    'model' => null,
    'selected' => null,
    'multiple' => false,
    'limit' => null,
    'placeholder' => 'Choose...',
    'id' => null,
    'invalid' => false,
    'ariaDescribedby' => null,
])

@php
    $normalizedOptions = collect($options)->map(function ($label, $key): array {
        if (is_array($label)) {
            return [
                'id' => $label['id'] ?? $key,
                'name' => $label['name'] ?? $label['label'] ?? (string) $key,
                'disabled' => (bool) ($label['disabled'] ?? false),
            ];
        }

        return [
            'id' => $key,
            'name' => (string) $label,
            'disabled' => false,
        ];
    })->values()->all();

    $initialSelection = $multiple
        ? array_values((array) ($selected ?? []))
        : $selected;
@endphp

<div
    {{ $attributes->class('dense-combobox') }}
    x-data="denseCombobox({
        options: @js($normalizedOptions),
        selected: @js($initialSelection),
        multiple: @js($multiple),
        limit: @js($limit),
        model: @js($model),
    })"
    x-on:click.outside="open = false"
    x-on:dense-combobox-reset.window="reset()"
>
    @if($multiple)
        <div x-show="selected.length" class="dense-combobox-tags">
            <template x-for="value in selected" :key="value">
                <span class="dense-combobox-tag">
                    <span x-text="options.find(option => String(option.id) === String(value))?.name"></span>
                    <button type="button" x-on:click.prevent="remove(value)" aria-label="Remove selection">
                        <x-dense::icons.x-mark size="4" />
                    </button>
                </span>
            </template>
        </div>
    @endif

    <div class="dense-combobox-control">
        <input
            @if($id) id="{{ $id }}" @endif
            type="text"
            x-model="query"
            x-on:focus="open = true"
            x-on:input="open = true; activeIndex = -1; if (! multiple) { selected = null; sync(); }"
            x-on:keydown="onKeydown($event)"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            @if($invalid) aria-invalid="true" @endif
            @if($ariaDescribedby) aria-describedby="{{ $ariaDescribedby }}" @endif
            class="input dense-combobox-input @if($invalid) is-invalid @endif"
            role="combobox"
            aria-autocomplete="list"
            :aria-expanded="open"
        />
        <button type="button" class="dense-combobox-button" x-on:click="open = !open" aria-label="Toggle options">
            <x-dense::icons.chevron-down size="5" />
        </button>
    </div>

    <div x-show="open" x-cloak class="dense-combobox-options" role="listbox">
        <template x-for="(option, index) in filteredOptions" :key="option.id">
            <button
                type="button"
                class="dense-combobox-option"
                x-on:click="select(option)"
                x-bind:class="{ 'is-active': activeIndex === index, 'is-disabled': option.disabled }"
                :disabled="option.disabled"
                role="option"
                :aria-selected="isSelected(option)"
            >
                <span class="dense-combobox-check" x-show="isSelected(option)"><x-dense::icons.check size="4" /></span>
                <span x-text="option.name"></span>
            </button>
        </template>

        <p x-show="filteredOptions.length === 0" class="dense-combobox-empty">No results found.</p>
    </div>
</div>

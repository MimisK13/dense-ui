@props([
    'index' => null,
    'selected' => false,
    'disabled' => false,
    'controls' => null,
    'interactive' => false,
])

<button
    type="button"
    role="tab"
    {{ $attributes->class('dense-tabs-trigger') }}
    @if($controls) aria-controls="{{ $controls }}" @endif
    @if($disabled) aria-disabled="true" disabled @endif
    @if($interactive && $index !== null)
        x-on:click="activate({{ $index }})"
        x-bind:aria-selected="active === {{ $index }}"
        x-bind:tabindex="active === {{ $index }} ? 0 : -1"
    @else
        aria-selected="{{ $selected ? 'true' : 'false' }}"
    @endif
>
    {{ $slot }}
</button>

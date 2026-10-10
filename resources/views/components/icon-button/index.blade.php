@props([
    'icon',
    'variant' => 'ghost',
    'size' => 'sm',
    'label' => null,
])

<x-dense::button
    :variant="$variant"
    :size="$size"
    :aria-label="$label"
    {{ $attributes->class('button-icon') }}
>
    <x-dense::icon :name="$icon" aria-hidden="true" />
</x-dense::button>

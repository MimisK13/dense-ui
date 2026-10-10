@props([
    'name',
    'variant' => 'outline',
])

<span
    data-dense-icon="{{ $name }}"
    data-dense-icon-variant="{{ $variant }}"
    {{ $attributes->class('icon') }}
>
    {{ $slot }}
</span>

@props([
    'disabled' => false,
    'menu' => false,
])

<button
    type="button"
    @if($disabled) disabled aria-disabled="true" @endif
    @if($menu) role="menuitem" @endif
    {{ $attributes->class('dropdown-item w-full text-left') }}
>
    {{ $slot }}
</button>

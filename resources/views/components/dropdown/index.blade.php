@props([
    'menu' => false,
    'floating' => false,
    'minWidth' => null,
])

<div
    x-data="denseDropdown({ floating: @js($floating) })"
    x-id="['dense-dropdown']"
    {{ $attributes->class('relative inline-flex') }}
>
    {{ $trigger }}

    <div
        x-ref="menu"
        x-show="open"
        x-cloak
        x-on:click.outside="close($refs.trigger)"
        x-on:keydown="onKeydown($event)"
        :id="$id('dense-dropdown')"
        @if($menu) role="menu" @endif
        @if($minWidth) style="min-width: {{ $minWidth }};" @endif
        class="dropdown{{ $menu ? ' dropdown-menu' : '' }}"
    >
        {{ $slot }}
    </div>
</div>

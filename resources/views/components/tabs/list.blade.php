@props(['interactive' => false])

<div
    role="tablist"
    {{ $attributes->class('dense-tabs-list') }}
    @if($interactive) x-on:keydown="onKeydown($event)" @endif
>
    {{ $slot }}
</div>

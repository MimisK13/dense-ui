@props([
    'variant' => 'underline',
    'interactive' => false,
    'active' => 0,
    'count' => 0,
])

@php
    $allowedVariants = ['underline', 'plain'];
    $variant = in_array($variant, $allowedVariants, true) ? $variant : 'underline';
@endphp

<div
    {{ $attributes->class(['dense-tabs', 'dense-tabs--'.$variant]) }}

    @if($interactive)
        x-data="denseTabs({ active: @js($active), count: @js($count) })"
    @endif
>
    {{ $slot }}
</div>

@props([
    'variant' => 'primary',
    'size' => 'base',
    'type' => 'button',
])

@php
    $variantClass = match ($variant) {
        'danger' => 'button-danger',
        'warning' => 'button-warning',
        'secondary' => 'button-secondary',
        'ghost' => 'button-ghost',
        'accent' => 'button-accent',
        default => 'button-primary',
    };
    $sizeClass = $size === 'base' ? '' : "button-{$size}";
@endphp

@if($attributes->has('href'))
    <a {{ $attributes->class("button {$variantClass} {$sizeClass}")->merge(['role' => 'button']) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class("button {$variantClass} {$sizeClass}") }}>{{ $slot }}</button>
@endif

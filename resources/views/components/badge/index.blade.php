@props([
    'color' => null,
    'variant' => null,
    'size' => null,
])

@php
    $colorClass = match ($color) {
        'green', 'active' => 'badge-green',
        'amber', 'pending' => 'badge-amber',
        'red', 'error' => 'badge-red',
        'blue', 'info' => 'badge-blue',
        'violet' => 'badge-violet',
        'cyan' => 'badge-cyan',
        'navy' => 'badge-navy',
        default => 'badge-zinc',
    };
    $variantClass = $variant === 'outline' ? 'badge-outline' : '';
@endphp

<span {{ $attributes->class("badge {$colorClass} {$variantClass}") }}>
    {{ $slot }}
</span>

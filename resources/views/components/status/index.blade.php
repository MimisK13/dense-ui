@props([
    'color' => 'zinc',
    'variant' => 'pill',
    'label' => null,
])

@php
    $colorClass = match ($color) {
        'green', 'active' => 'status-green',
        'amber', 'pending' => 'status-amber',
        'red', 'error' => 'status-red',
        'blue', 'info' => 'status-blue',
        'violet' => 'status-violet',
        'cyan' => 'status-cyan',
        'navy' => 'status-navy',
        default => 'status-zinc',
    };
    $variantClass = $variant === 'outline' ? 'status-outline' : '';
@endphp

<span {{ $attributes->class("status {$colorClass} {$variantClass}") }}>
    <span class="status-dot"></span>
    {{ $label ?? $slot }}
</span>

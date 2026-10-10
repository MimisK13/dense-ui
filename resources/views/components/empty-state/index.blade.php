@props([
    'icon' => null,
    'title' => null,
    'description' => null,
])

<div {{ $attributes->class('empty-state') }}>
    @if($icon)
        <div class="empty-state-icon">{{ $icon }}</div>
    @endif
    @if($title)
        <div class="empty-state-title">{{ $title }}</div>
    @endif
    @if($description)
        <div class="empty-state-description">{{ $description }}</div>
    @endif
</div>

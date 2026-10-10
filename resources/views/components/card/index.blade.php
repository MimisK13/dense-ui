@props([
    'title' => null,
    'collapsible' => false,
    'open' => true,
])

@php
    $cardAttributes = $attributes->class('card');

    if ($collapsible) {
        $cardAttributes = $cardAttributes->merge([
            'x-data' => '{ open: '.($open ? 'true' : 'false').' }',
        ]);
    }
@endphp

<div {{ $cardAttributes }}>
    @if($title || isset($headerActions) || $collapsible)
        <div class="card-header">
            @if($title)
                <span class="card-title">{{ $title }}</span>
            @endif

            {{ $headerActions ?? '' }}

            @if($collapsible)
                <button
                    type="button"
                    class="button button-ghost button-xs button-icon ml-auto"
                    x-on:click="open = ! open"
                    :aria-expanded="open"
                    aria-label="{{ __('Toggle card') }}"
                    title="{{ __('Toggle card') }}"
                >
                    <x-dense::icons.chevron-down size="4" class="transition-transform" ::class="{ 'rotate-180': ! open }" />
                </button>
            @endif
        </div>
    @endif

    <div class="card-body" @if($collapsible) x-cloak x-show="open" @endif>
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="card-footer">{{ $footer }}</div>
    @endisset
</div>

@props([
    'title' => null,
    'count' => null,
    'countClass' => 'badge-zinc',
    'collapsible' => false,
    'open' => true,
])

@php
    $cardAttributes = $attributes->class('card table-card');

    if ($collapsible) {
        $cardAttributes = $cardAttributes->merge([
            'x-data' => '{ open: '.($open ? 'true' : 'false').' }',
        ]);
    }
@endphp

<div {{ $cardAttributes }}>
    @if($title || $count !== null || isset($headerActions) || $collapsible)
        <div class="card-header">
            <div class="flex items-center gap-2">
                @if($title)
                    <span class="card-title">{{ $title }}</span>
                @endif

                @if($count !== null)
                    <span class="badge {{ $countClass }}">{{ $count }}</span>
                @endif
            </div>

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

    <div class="table-card-content" @if($collapsible) x-cloak x-show="open" @endif>
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="card-footer">{{ $footer }}</div>
    @endisset
</div>

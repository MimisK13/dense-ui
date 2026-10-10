@props([
    'title' => null,
    'close' => null,
    'mode' => 'flow',
])

<aside
    @if($close) x-on:keydown.escape.window="if ($el.offsetWidth > 0 && $el.offsetHeight > 0) { {{ $close }} }" @endif
    {{ $attributes->class(['dense-side-panel', "dense-side-panel--{$mode}"]) }}
    role="dialog"
    aria-modal="false"
>
    @if($title)
        <div class="dense-side-panel-header">
            <span class="dense-side-panel-title">
                {{ $title }}
            </span>

            @if($close)
                <button
                    type="button"
                    class="dense-side-panel-close"
                    x-on:click="{{ $close }}"
                    aria-label="{{ __('Close') }}"
                >
                    <x-dense::icons.x-mark size="5" />
                </button>
            @endif
        </div>
    @endif

    <div class="dense-side-panel-body">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="dense-side-panel-footer">
            {{ $footer }}
        </div>
    @endisset
</aside>

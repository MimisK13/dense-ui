<div {{ $attributes->class('toolbar') }}>
    {{ $slot }}
    @isset($right)
        <div class="toolbar-right">{{ $right }}</div>
    @endisset
</div>

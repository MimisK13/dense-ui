@props(['checked' => false])

<div {{ $attributes->class(['checkbox', 'checked' => $checked]) }}>
    <x-dense::icons.check size="5" />
</div>

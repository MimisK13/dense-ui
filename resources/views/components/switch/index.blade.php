@props(['on' => false])

<div {{ $attributes->class(['toggle', 'on' => $on]) }}>
    <div class="toggle-knob"></div>
</div>

@props(['checked' => false])

<div {{ $attributes->class(['radio', 'checked' => $checked]) }}></div>

@props([
    'compact' => false,
    'striped' => false,
    'bordered' => false,
])

<table {{ $attributes->class([
    'table',
    'table-compact' => $compact,
    'table-striped' => $striped,
    'table-bordered' => $bordered,
]) }}>
    {{ $slot }}
</table>

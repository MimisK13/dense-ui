@props([
    'message' => null,
    'role' => 'alert',
])

@if(filled($message))
    <p {{ $attributes->merge(['class' => 'dense-field-error', 'role' => $role]) }}>{{ $message }}</p>
@endif

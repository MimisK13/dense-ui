@props([
    'label' => null,
    'for' => null,
    'help' => null,
    'error' => null,
    'errorId' => null,
    'required' => false,
])

<div {{ $attributes->class(['field', 'has-error' => $error]) }}>
    @if($label)
        <x-dense::field-label :for="$for" :required="$required">
            {{ $label }}
        </x-dense::field-label>
    @endif
    {{ $slot }}
    @if($error)
        <x-dense::field-error :id="$errorId" :message="$error" />
    @elseif($help)
        <div class="field-help">{{ $help }}</div>
    @endif
</div>

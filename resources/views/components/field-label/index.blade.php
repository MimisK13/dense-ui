@props([
    'for' => null,
    'required' => false,
])

<label @if($for) for="{{ $for }}" @endif {{ $attributes->merge(['class' => 'erp-field-label']) }}>
    {{ $slot }}
    @if($required)
        <span class="erp-field-label-required" aria-hidden="true">*</span>
    @endif
</label>

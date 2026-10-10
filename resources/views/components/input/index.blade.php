@props([
    'id' => null,
    'name' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'autocomplete' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'invalid' => false,
    'ariaLabel' => null,
    'ariaDescribedby' => null,
])

<input
    @if($id) id="{{ $id }}" @endif
    @if($name) name="{{ $name }}" @endif
    type="{{ $type }}"
    @if($value !== null) value="{{ $value }}" @endif
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
    @required($required)
    @disabled($disabled)
    @readonly($readonly)
    @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    @if($ariaDescribedby) aria-describedby="{{ $ariaDescribedby }}" @endif
    @if($invalid) aria-invalid="true" @endif
    @if($required) aria-required="true" @endif
    {{ $attributes->class(['input', 'is-invalid' => $invalid]) }}
 />

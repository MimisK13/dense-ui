@props([
    'id' => null,
    'name' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'invalid' => false,
    'ariaLabel' => null,
    'ariaDescribedby' => null,
])

<select
    @if($id) id="{{ $id }}" @endif
    @if($name) name="{{ $name }}" @endif
    @required($required)
    @disabled($disabled)
    @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
    @if($ariaDescribedby) aria-describedby="{{ $ariaDescribedby }}" @endif
    @if($invalid) aria-invalid="true" @endif
    @if($required) aria-required="true" @endif
    {{ $attributes->class(['input', 'is-invalid' => $invalid]) }}
>
    @if($placeholder)
        <option value="" disabled selected>{{ $placeholder }}</option>
    @endif
    {{ $slot }}
</select>

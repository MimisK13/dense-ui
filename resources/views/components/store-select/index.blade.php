@props([
    'model' => null,
    'options' => [],
    'placeholder' => 'All stores',
    'id' => null,
])

<select
    @if($id) id="{{ $id }}" @endif
    {{ $attributes->merge(['class' => 'erp-store-select']) }}
    @if($model) wire:model.live="{{ $model }}" @endif
>
    <option value="">{{ $placeholder }}</option>
    @foreach($options as $option)
        <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
    @endforeach
</select>

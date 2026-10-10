@props([
    'model' => null,
    'placeholder' => 'Search...',
    'id' => null,
])

<div class="relative">
    <div {{ $attributes->merge(['class' => 'erp-search-input']) }}>
        <x-dense::icons.search size="5" />
        <input
            @if($id) id="{{ $id }}" @endif
            type="search"
            placeholder="{{ $placeholder }}"
            @if($model) wire:model.live.debounce.300ms="{{ $model }}" @endif
            {{ $attributes->except(['class'])->whereStartsWith('wire:') }}
        >
    </div>
    {{ $slot }}
</div>

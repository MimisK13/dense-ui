@props([
    'id' => null,
    'model' => null,
    'clearMethod' => null,
    'accept' => '*',
    'multiple' => false,
    'preview' => null,
    'resetMethod' => null,
    'placeholder' => 'Drag files here or click to browse.',
])

<div
    {{ $attributes->class('dense-dropzone') }}
    x-data="{
        fileNames: [],
        previewUrl: @js($preview),
        dragging: false,
        setFiles(files) {
            this.fileNames = Array.from(files).map(file => file.name);
        },
        clearFile() {
            this.fileNames = [];
            this.previewUrl = null;
            this.$refs.file.value = '';

            @if($clearMethod)
                $wire.call(@js($clearMethod));
            @elseif($model)
                $wire.set(@js($model), null);
            @endif
        },
        reset() {
            this.fileNames = [];
            this.previewUrl = null;
            this.$refs.file.value = '';

            @if($resetMethod)
                $wire.call(@js($resetMethod));
            @elseif($model)
                $wire.set(@js($model), null);
            @endif
        },
    }"
    x-effect="
        const temporaryPreview = @js($preview);

        if (temporaryPreview) {
            previewUrl = temporaryPreview;
        } else if (fileNames.length === 0) {
            previewUrl = null;
        }
    "
    x-on:dense-dropzone-reset.window="reset()"
>
    <div
        x-show="! previewUrl"
        class="dense-dropzone-target"
        x-bind:class="{ 'is-dragging': dragging }"
        x-on:dragover.prevent="dragging = true"
        x-on:dragleave.prevent="dragging = false"
        x-on:drop.prevent="dragging = false"
    >
        <input
            @if($id) id="{{ $id }}" @endif
            type="file"
            accept="{{ $accept }}"
            @if($multiple) multiple @endif
            @if($model) wire:model="{{ $model }}" @endif
            x-on:change="setFiles($event.target.files)"
            x-ref="file"
            class="dense-dropzone-input"
        />

        <div class="dense-dropzone-content">
            <x-dense::icons.upload size="6" />
            <p>{{ $placeholder }}</p>
            <template x-for="fileName in fileNames" :key="fileName">
                <span class="dense-dropzone-file" x-text="fileName"></span>
            </template>
        </div>
    </div>

    <div x-show="previewUrl" x-cloak class="dense-dropzone-preview-state">
        <img x-bind:src="previewUrl" alt="" class="dense-dropzone-preview" />
        <div class="dense-dropzone-preview-details">
            <span class="dense-dropzone-preview-label">Image selected</span>
            <template x-for="fileName in fileNames" :key="fileName">
                <span class="dense-dropzone-file" x-text="fileName"></span>
            </template>
        </div>
        <button
            type="button"
            class="dense-dropzone-delete"
            x-on:click="clearFile()"
            title="Remove image"
            aria-label="Remove image"
        >
            <x-dense::icons.trash size="5" />
        </button>
    </div>
</div>
